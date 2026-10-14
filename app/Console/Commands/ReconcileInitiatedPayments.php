<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReconcileInitiatedPayments extends Command
{
    protected $signature   = 'payments:reconcile {--limit=50 : Max records to process per run}';
    protected $description = 'Query SabPaisa for INITIATED admission payments and update records that succeeded';

    private const OPENSSL_CIPHER = 'aes-128-cbc';
    private const KEY_LEN        = 16;
    private const AUTH_KEY       = 'voeXpkMY5WTg63sl';
    private const AUTH_IV        = 'wE9gkr5BKoTXf8Mh';
    private const CLIENT_CODE    = 'GGSSC';
    private const ENQUIRY_URL    = 'https://txnenquiry.sabpaisa.in/SPTxtnEnquiry/getTxnStatusByClientxnId';
    private const EXPECTED_AMOUNT = '200.0';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $initiated = DB::table('online_admission_payment_response_details')
            ->where('status', 'INITIATED')
            ->whereNotNull('clientTxnId')
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($initiated->isEmpty()) {
            $this->info('No INITIATED transactions found.');
            return 0;
        }

        $this->info("Processing {$initiated->count()} INITIATED transaction(s)...");

        foreach ($initiated as $record) {
            $this->line("  → clientTxnId: {$record->clientTxnId}");
            $this->processRecord($record);
        }

        $this->info('Done.');
        return 0;
    }

    private function processRecord(object $record): void
    {
        $statusTrans     = 'clientCode=' . self::CLIENT_CODE . '&clientTxnId=' . $record->clientTxnId;
        $encPayload      = $this->encrypt($statusTrans);
        $parts           = explode(':', $encPayload);
        $encData         = $parts[0];
        $ivPart          = $parts[1];

        $result = $this->callEnquiryApi($encData);
        if ($result === null) {
            $this->warn("    API call failed for clientTxnId {$record->clientTxnId}");
            return;
        }

        $decoded = json_decode($result);
        if (!$decoded || empty($decoded->statusResponseData)) {
            $this->warn("    Empty/invalid API response for clientTxnId {$record->clientTxnId}");
            return;
        }

        $decrypted = openssl_decrypt(
            base64_decode($decoded->statusResponseData),
            self::OPENSSL_CIPHER,
            $this->fixKey(self::AUTH_KEY),
            OPENSSL_RAW_DATA,
            base64_decode($ivPart)
        );

        if ($decrypted === false) {
            $this->warn("    Decryption failed for clientTxnId {$record->clientTxnId}");
            return;
        }

        $fields = explode('&', $decrypted);

        $udf1 = Str::after($fields[11] ?? '', '=');
        $udf6 = Str::after($fields[16] ?? '', '=');

        if ($udf1 && $udf1 !== 'NA') {
            $uniquechallan = $udf1;
        } elseif ($udf6 && $udf6 !== 'NA') {
            $uniquechallan = $udf6;
        } else {
            $uniquechallan = Str::after($fields[26] ?? '', '=');
        }

        $payerName         = Str::after($fields[0]  ?? '', '=');
        $payerEmail        = Str::after($fields[1]  ?? '', '=');
        $payerMobile       = Str::after($fields[2]  ?? '', '=');
        $clientTxnId       = Str::after($fields[3]  ?? '', '=');
        $amount            = Str::after($fields[5]  ?? '', '=');
        $clientCode        = Str::after($fields[6]  ?? '', '=');
        $paidAmount        = Str::after($fields[7]  ?? '', '=');
        $paymentMode       = Str::after($fields[8]  ?? '', '=');
        $bankName          = Str::after($fields[9]  ?? '', '=');
        $amountType        = Str::after($fields[10] ?? '', '=');
        $status            = Str::after($fields[31] ?? '', '=');
        $statusCode        = Str::after($fields[33] ?? '', '=');
        $challanNumber     = Str::after($fields[26] ?? '', '=');
        $SabPaisaTxnId     = Str::after($fields[35] ?? '', '=');
        $SabPaisaMessage   = Str::after($fields[36] ?? '', '=');
        $bankMessage       = Str::after($fields[37] ?? '', '=');
        $bankErrorCode     = Str::after($fields[38] ?? '', '=');
        $SabPaisaErrorCode = Str::after($fields[39] ?? '', '=');
        $bankTxnId         = Str::after($fields[40] ?? '', '=');
        $transDate         = Str::after($fields[43] ?? '', '=');

        $this->line("    Status from gateway: {$status}");

        // Always update the response record with latest gateway data
        DB::table('online_admission_payment_response_details')
            ->where('id', $record->id)
            ->update([
                'payerName'        => $payerName,
                'payerEmail'       => $payerEmail,
                'payerMobile'      => $payerMobile,
                'amount'           => $amount,
                'clientCode'       => $clientCode,
                'paidAmount'       => $paidAmount,
                'paymentMode'      => $paymentMode,
                'bankName'         => $bankName,
                'amountType'       => $amountType,
                'uniquechallan'    => $uniquechallan,
                'status'           => $status,
                'statusCode'       => $statusCode,
                'challanNumber'    => $challanNumber,
                'SabPaisaTxnId'    => $SabPaisaTxnId,
                'SabPaisaMessage'  => $SabPaisaMessage,
                'bankMessage'      => $bankMessage,
                'bankErrorCode'    => $bankErrorCode,
                'SabPaisaErrorCode'=> $SabPaisaErrorCode,
                'bankTxnId'        => $bankTxnId,
                'transDate'        => $transDate,
            ]);

        if ($status !== 'SUCCESS' || $amount !== self::EXPECTED_AMOUNT) {
            $this->line("    Skipped (status={$status}, amount={$amount})");
            return;
        }

        // Find the registration record via challan
        $user = DB::table('admission_registration_login')
            ->where('challan_no', $uniquechallan)
            ->first();

        if (!$user) {
            $this->warn("    No registration found for challan {$uniquechallan}");
            return;
        }

        if ($user->payment_status == 1) {
            $this->line("    Already marked paid (user_id={$user->id})");
            return;
        }

        $enroll_no = date('Y') . sprintf('%06d', $user->id);

        DB::table('admission_registration_login')
            ->where('id', $user->id)
            ->update([
                'payment_status' => 1,
                'enroll_no'      => $enroll_no,
            ]);

        // Notify e-district portal for registrations sourced from there
        if ($user->registered_from == 2) {
            $this->notifyEDistrict($user);
        }

        $this->info("    ✓ Marked paid — user_id={$user->id}, enroll_no={$enroll_no}");
    }

    private function notifyEDistrict(object $user): void
    {
        try {
            $soapUrl = 'http://164.100.181.28/DeptWebIntService/Service.asmx?op=SendResponse';
            $xml = '<?xml version="1.0" encoding="utf-8"?>'
                . '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
                . 'xmlns:xsd="http://www.w3.org/2001/XMLSchema" '
                . 'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'
                . '<soap:Body><SendResponse xmlns="http://tempuri.org/">'
                . '<RequestKey>' . $user->RequestKey . '</RequestKey>'
                . '<DeptRegistraionID>5EA45F3FA6BD786D7E1024431E03961D</DeptRegistraionID>'
                . '<ApplicationNo>' . $user->application_no . '</ApplicationNo>'
                . '<serviceCode>' . $user->serviceCode . '</serviceCode>'
                . '</SendResponse></soap:Body></soap:Envelope>';

            call_curlApi($xml, $soapUrl, 'Response');
        } catch (\Exception $e) {
            $this->warn("    e-district notify failed: " . $e->getMessage());
        }
    }

    private function callEnquiryApi(string $encData): ?string
    {
        $ch = curl_init(self::ENQUIRY_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'clientCode'        => self::CLIENT_CODE,
            'statusTransEncData'=> $encData,
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $result = curl_exec($ch);
        $error  = curl_error($ch);
        curl_close($ch);

        return ($result === false) ? null : $result;
    }

    private function encrypt(string $data): string
    {
        $key     = $this->fixKey(self::AUTH_KEY);
        $iv      = self::AUTH_IV;
        $enc     = base64_encode(openssl_encrypt($data, self::OPENSSL_CIPHER, $key, OPENSSL_RAW_DATA, $iv));
        $encodedIv = base64_encode($iv);
        return $enc . ':' . $encodedIv;
    }

    private function fixKey(string $key): string
    {
        if (strlen($key) < self::KEY_LEN) {
            return str_pad($key, self::KEY_LEN, '0');
        }
        return substr($key, 0, self::KEY_LEN);
    }
}
