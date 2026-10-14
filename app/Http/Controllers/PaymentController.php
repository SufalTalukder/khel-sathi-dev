<?php

namespace App\Http\Controllers;

use App\Models\OnlineAdmissionModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{


    private const OPENSSL_CIPHER_NAME = "aes-128-cbc";
    private const CIPHER_KEY_LEN = 16;
    private static function fixKey($key)
    {

        if (strlen($key) < PaymentController::CIPHER_KEY_LEN) {

            return str_pad("$key", PaymentController::CIPHER_KEY_LEN, "0");
        }

        if (strlen($key) > PaymentController::CIPHER_KEY_LEN) {

            return substr($key, 0, PaymentController::CIPHER_KEY_LEN);
        }
        return $key;
    }

    static function encrypt($key, $iv, $data)
    {


        //echo 'Data value is :' .$data;
        //echo "<br>";
        $encodedEncryptedData = base64_encode(openssl_encrypt($data, PaymentController::OPENSSL_CIPHER_NAME, PaymentController::fixKey($key), OPENSSL_RAW_DATA, $iv));

        $encodedIV = base64_encode($iv);
        $encryptedPayload = $encodedEncryptedData . ":" . $encodedIV;
        //echo '$encryptedPayload value is :' .$encryptedPayload;

        return $encryptedPayload;
    }


    public function gateway()
    {




        $data = DB::table('admission_registration_login as rg')
            ->join('online_admission_basic_details as basic', 'rg.id', '=', 'basic.user_id')
            ->join('online_admission_communication_details as communication', 'rg.id', '=', 'communication.user_id')
            ->join('online_admission_education_document_details as education', 'rg.id', '=', 'education.user_id')
            ->join('sport_type', 'basic.sport_type', '=', 'sport_type.id')
            ->join('cities', 'cities.id', '=', 'communication.p_district')
            ->select('rg.*', 'basic.*', 'communication.*', 'education.*', 'sport_type.name', 'cities.trial_from_date')
            ->where('rg.id', Auth::guard('OnlineAdmission')->user()->id)->first();

        $encData = null;


        $clientCode = 'GGSSC';
        $username = 'nishant.jha_8637';
        $password = 'GGSSC_SP8637';
        $authKey = 'voeXpkMY5WTg63sl';
        $authIV = 'wE9gkr5BKoTXf8Mh';

        $payerName = $data->fullname;
        $payerEmail = $data->email;
        $payerMobile = $data->mobile;
        $payerAddress = '';

        $clientTxnId = date('Y') . time();
        $amount = 200;
        $amountType = 'INR';
        $mcc = 5137;
        $channelId = 'W';
        $callbackUrl = route('gatewayResponse');
        $challan_no = $data->challan_no;


        $encData = "?clientCode=" . $clientCode . "&transUserName=" . $username . "&transUserPassword=" . $password . "&payerName=" . $payerName .
            "&payerMobile=" . $payerMobile . "&payerEmail=" . $payerEmail . "&payerAddress=" . $payerAddress . "&clientTxnId=" . $clientTxnId .
            "&amount=" . $amount . "&amountType=" . $amountType . "&mcc=" . $mcc . "&channelId=" . $channelId . "&callbackUrl=" . $callbackUrl . "&udf6=" . $challan_no;


        $paymentdata = $this->encrypt($authKey, $authIV, $encData);




        return view('onlineAdmission.payment')->with('data', $data)->with('clientCode', $clientCode)->with('paymentdata', $paymentdata)->with('clientTxnId', $clientTxnId);

        return view('onlineAdmissionTest.payment')->with('data', $data)->with('clientCode', $clientCode)->with('paymentdata', $paymentdata)->with('clientTxnId', $clientTxnId);
    }

    // Updated Payment Method
    public function gatewayResponse(Request $request)
    {
        $authKey = 'voeXpkMY5WTg63sl';
        $authIV  = 'wE9gkr5BKoTXf8Mh';

        $query   = $request->encResponse;
        $decText = $this->decrypt($authKey, $authIV, $query);

        Log::info('SabPaisa RAW decrypted: ' . $decText);

        $params = [];
        foreach (explode('&', $decText) as $pair) {
            [$k, $v]    = array_pad(explode('=', $pair, 2), 2, '');
            $params[strtolower(trim($k))] = urldecode(trim($v));
        }

        Log::info('SabPaisa parsed params: ', $params);

        $payerName         = $params['payername']         ?? '';
        $payerEmail        = $params['payeremail']        ?? '';
        $payerMobile       = $params['payermobile']       ?? '';
        $clientTxnId       = $params['clienttxnid']       ?? '';
        $amount            = $params['amount']            ?? '0';
        $clientCode        = $params['clientcode']        ?? '';
        $paidAmount        = $params['paidamount']        ?? '0';
        $paymentMode       = $params['paymentmode']       ?? '';
        $bankName          = $params['bankname']          ?? '';
        $amountType        = $params['amounttype']        ?? '';
        $challanNumber     = $params['udf6']              ?? '';
        $uniquechallan     = $challanNumber;
        $status            = strtoupper(trim($params['status']      ?? ''));
        $statusCode        = $params['statuscode']        ?? '';
        $SabPaisaTxnId     = $params['sabpaisatxnid']     ?? '';
        $SabPaisaMessage   = $params['sabpaisamessage']   ?? '';
        $bankMessage       = $params['bankmessage']       ?? '';
        $bankErrorCode     = $params['bankerrorcode']     ?? '';
        $SabPaisaErrorCode = $params['sabpaisaerrorcode'] ?? '';
        $bankTxnId         = $params['banktxnid']         ?? '';
        $transDate         = $params['transdate']         ?? '';
        $amountFloat = floatval($amount);

        // Authenticate student by email
        if ($payerEmail) {
            $student = OnlineAdmissionModel::where('email', $payerEmail)->first();
            if ($student) {
                Auth::guard('OnlineAdmission')->login($student);
            }
        }

        // Look up user by challan_no
        $user = null;
        if ($challanNumber) {
            $user = DB::table('admission_registration_login')
                ->where('challan_no', $challanNumber)
                ->first();
        }

        // Fallback: find by email if challan lookup failed
        if (!$user && $payerEmail) {
            $user = DB::table('admission_registration_login')
                ->where('email', $payerEmail)
                ->first();
            Log::warning('SabPaisa: user found by email fallback. challan=' . $challanNumber . ', email=' . $payerEmail);
        }

        // Step 5: Build shared payment record
        $paymentData = [
            'user_id'           => $user->id ?? null,
            'payerName'         => $payerName,
            'payerEmail'        => $payerEmail,
            'payerMobile'       => $payerMobile,
            'clientTxnId'       => $clientTxnId,
            'amount'            => $amount,
            'clientCode'        => $clientCode,
            'paidAmount'        => $paidAmount,
            'paymentMode'       => $paymentMode,
            'bankName'          => $bankName,
            'amountType'        => $amountType,
            'uniquechallan'     => $uniquechallan,
            'status'            => $status,
            'statusCode'        => $statusCode,
            'challanNumber'     => $challanNumber,
            'SabPaisaTxnId'     => $SabPaisaTxnId,
            'SabPaisaMessage'   => $SabPaisaMessage,
            'bankMessage'       => $bankMessage,
            'bankErrorCode'     => $bankErrorCode,
            'SabPaisaErrorCode' => $SabPaisaErrorCode,
            'bankTxnId'         => $bankTxnId,
            'transDate'         => $transDate,
        ];

        // Handle SUCCESS
        if ($status === 'SUCCESS' && $amountFloat >= 200) {

            if (!$user) {
                // Cannot match payment to any user — log and show fallback
                Log::error('SabPaisa SUCCESS but no user found. challan=' . $challanNumber . ', email=' . $payerEmail . ', txn=' . $clientTxnId);
                DB::table('online_admission_payment_response_details')->insert($paymentData);
                return redirect()->route('onlineAdmission.dashboard')
                    ->with('error', 'Payment received but your record could not be updated. Please contact support with Transaction ID: ' . $SabPaisaTxnId);
            }

            // Update payment_status (idempotent — skip if already paid)
            if ($user->payment_status != 1) {
                DB::table('admission_registration_login')
                    ->where('id', $user->id)
                    ->update([
                        'payment_status' => 1,
                        'enroll_no'      => date('Y') . sprintf('%06d', $user->id),
                    ]);
            }

            // Insert payment record only once per successful transaction
            $alreadyRecorded = DB::table('online_admission_payment_response_details')
                ->where('clientTxnId', $clientTxnId)
                ->where('status', 'SUCCESS')
                ->exists();

            if (!$alreadyRecorded) {
                $paymentData['user_id'] = $user->id;
                DB::table('online_admission_payment_response_details')->insert($paymentData);
            }

            // Secondary verification — audit only, never blocks the flow
            try {
                $statusTrans        = 'clientCode=GGSSC&clientTxnId=' . $clientTxnId;
                $statusTransEncData = $this->encrypt($authKey, $authIV, $statusTrans);
                $parts              = explode(':', $statusTransEncData);

                $ch = curl_init('https://txnenquiry.sabpaisa.in/SPTxtnEnquiry/getTxnStatusByClientxnId');
                curl_setopt($ch, CURLOPT_POST,           1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,     json_encode([
                    'clientCode'         => 'GGSSC',
                    'statusTransEncData' => $parts[0],
                ]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_TIMEOUT,        10);
                $verifyResult = curl_exec($ch);
                curl_close($ch);

                Log::info('SabPaisa verify response: ' . $verifyResult);

                // 6d: SOAP callback for registered_from == 2 users
                $freshUser = DB::table('admission_registration_login')->where('id', $user->id)->first();
                if ($verifyResult && $freshUser && $freshUser->registered_from == 2) {
                    $soapUrl      = 'http://164.100.181.28/DeptWebIntService/Service.asmx?op=SendResponse';
                    $main_xml_str = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope
                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
                        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                        <soap:Body>
                            <SendResponse xmlns="http://tempuri.org/">
                                <RequestKey>'        . $freshUser->RequestKey     . '</RequestKey>
                                <DeptRegistraionID>5EA45F3FA6BD786D7E1024431E03961D</DeptRegistraionID>
                                <ApplicationNo>'     . $freshUser->application_no . '</ApplicationNo>
                                <serviceCode>'       . $freshUser->serviceCode    . '</serviceCode>
                            </SendResponse>
                        </soap:Body>
                    </soap:Envelope>';
                    call_curlApi($main_xml_str, $soapUrl, 'Response');
                }
            } catch (\Exception $e) {
                // Verification failure must never roll back a confirmed payment
                Log::error('SabPaisa secondary verification failed: ' . $e->getMessage());
            }

            // 6e: Redirect to receipt or dashboard
            $route = ($user->registered_from == 1)
                ? 'onlineAdmissionTest.paymentReceipt'
                : 'onlineAdmission.dashboard';

            return redirect()->route($route)
                ->with('success', 'Payment Successful. Your application has been submitted successfully.');
        }

        // ── Step 7: Handle FAILED / PENDING ──────────────────────────────────
        if ($user) {
            $paymentData['user_id'] = $user->id;
        }

        // Only insert if not already recorded (gateway may retry)
        $alreadyRecorded = DB::table('online_admission_payment_response_details')
            ->where('clientTxnId', $clientTxnId)
            ->where('status', $status)
            ->exists();

        if (!$alreadyRecorded) {
            DB::table('online_admission_payment_response_details')->insert($paymentData);
        }

        Log::warning('SabPaisa payment not successful. status=' . $status . ', amount=' . $amount . ', challan=' . $challanNumber);

        $dashRoute = ($user && $user->registered_from == 1)
            ? 'onlineAdmissionTest.dashboard'
            : 'onlineAdmission.dashboard';

        return redirect()->route($dashRoute)
            ->with('error', 'Payment Failed. Please try again. Status: ' . $status);
    }

    public function transactionenquirystore(Request $request)
    {
        $request->validate([
            'clientTxnID' => 'required|string|max:50',
        ]);

        $authKey = 'voeXpkMY5WTg63sl';
        $authIV  = 'wE9gkr5BKoTXf8Mh';

        // Encrypt request & call Transaction Enquiry API
        $statusTrans        = 'clientCode=GGSSC&clientTxnId=' . trim($request->clientTxnID);
        $statusTransEncData = $this->encrypt($authKey, $authIV, $statusTrans);
        $parts              = explode(':', $statusTransEncData);

        $url  = 'https://txnenquiry.sabpaisa.in/SPTxtnEnquiry/getTxnStatusByClientxnId';
        $post = json_encode([
            'clientCode'         => 'GGSSC',
            'statusTransEncData' => $parts[0],
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST,           1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,     $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT,        15);
        $result = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if (!$result || $curlError) {
            Log::error('SabPaisa TxnEnquiry cURL error: ' . $curlError);
            return redirect()->back()->with('error', 'Could not reach SabPaisa. Please try again.');
        }

        // Decode & decrypt the API response
        $responseJson = json_decode($result);

        if (!$responseJson || empty($responseJson->statusResponseData)) {
            Log::error('SabPaisa TxnEnquiry bad response: ' . $result);
            return redirect()->back()->with('error', 'Invalid response from SabPaisa. Please try again.');
        }

        // The IV used for decryption is the one we sent (parts[1])
        $decryptedData = openssl_decrypt(
            base64_decode($responseJson->statusResponseData),
            PaymentController::OPENSSL_CIPHER_NAME,
            PaymentController::fixKey($authKey),
            OPENSSL_RAW_DATA,
            base64_decode($parts[1])
        );

        if (!$decryptedData) {
            Log::error('SabPaisa TxnEnquiry decryption failed. Raw: ' . $result);
            return redirect()->back()->with('error', 'Decryption failed. Please contact support.');
        }

        $params = [];
        foreach (explode('&', $decryptedData) as $pair) {
            [$k, $v]    = array_pad(explode('=', $pair, 2), 2, '');
            $params[strtolower(trim($k))] = trim($v);
        }

        $payerName         = $params['payername']         ?? '';
        $payerEmail        = $params['payeremail']        ?? '';
        $payerMobile       = $params['payermobile']       ?? '';
        $clientTxnId       = $params['clienttxnid']       ?? '';
        $amount            = $params['amount']            ?? '0';
        $clientCode        = $params['clientcode']        ?? '';
        $paidAmount        = $params['paidamount']        ?? '0';
        $paymentMode       = $params['paymentmode']       ?? '';
        $bankName          = $params['bankname']          ?? '';
        $amountType        = $params['amounttype']        ?? '';
        $status            = strtoupper(trim($params['status']         ?? ''));
        $statusCode        = $params['statuscode']        ?? '';
        $challanNumber     = $params['challannumber']     ?? '';
        $SabPaisaTxnId     = $params['sabpaisatxnid']     ?? '';
        $SabPaisaMessage   = $params['sabpaisamessage']   ?? '';
        $bankMessage       = $params['bankmessage']       ?? '';
        $bankErrorCode     = $params['bankerrorcode']     ?? '';
        $SabPaisaErrorCode = $params['sabpaisaerrorcode'] ?? '';
        $bankTxnId         = $params['banktxnid']         ?? '';
        $transDate         = $params['transdate']         ?? '';

        $udf1 = $params['udf1'] ?? '';
        $udf6 = $params['udf6'] ?? '';

        $isBlank = fn($v) => in_array(strtolower(trim($v)), ['na', 'null', ''], true);

        if (!$isBlank($udf6)) {
            $uniquechallan = $udf6;
        } elseif (!$isBlank($udf1)) {
            $uniquechallan = $udf1;
        } else {
            $uniquechallan = $challanNumber;
        }

        $amountFloat = floatval($amount);

        $paymentData = [
            'payerName'         => $payerName,
            'payerEmail'        => $payerEmail,
            'payerMobile'       => $payerMobile,
            'clientTxnId'       => $clientTxnId,
            'amount'            => $amount,
            'clientCode'        => $clientCode,
            'paidAmount'        => $paidAmount,
            'paymentMode'       => $paymentMode,
            'bankName'          => $bankName,
            'amountType'        => $amountType,
            'uniquechallan'     => $uniquechallan,
            'status'            => $status,
            'statusCode'        => $statusCode,
            'challanNumber'     => $challanNumber,
            'SabPaisaTxnId'     => $SabPaisaTxnId,
            'SabPaisaMessage'   => $SabPaisaMessage,
            'bankMessage'       => $bankMessage,
            'bankErrorCode'     => $bankErrorCode,
            'SabPaisaErrorCode' => $SabPaisaErrorCode,
            'bankTxnId'         => $bankTxnId,
            'transDate'         => $transDate,
        ];

        if ($status === 'SUCCESS' && $amountFloat >= 200 && !$isBlank($uniquechallan)) {

            $user = DB::table('admission_registration_login')
                ->where('challan_no', $uniquechallan)
                ->where('email', $payerEmail)
                ->first();

            // Fallback: match by challan only (email might be "NA" in response)
            if (!$user) {
                $user = DB::table('admission_registration_login')
                    ->where('challan_no', $uniquechallan)
                    ->first();
                Log::warning('TxnEnquiry: matched user by challan only. challan=' . $uniquechallan);
            }

            if (!$user) {
                Log::error('TxnEnquiry: no user found. challan=' . $uniquechallan . ', email=' . $payerEmail);
                return redirect()->route('transactionenquiryupdate')
                    ->with('error', 'User not found for challan: ' . $uniquechallan);
            }

            $paymentData['user_id'] = $user->id;

            // 7a: Update payment_status (idempotent)
            if ($user->payment_status != 1) {
                DB::table('admission_registration_login')
                    ->where('id', $user->id)
                    ->update([
                        'payment_status' => 1,
                        'enroll_no'      => date('Y') . sprintf('%06d', $user->id),
                    ]);
            }

            // 7b: Insert payment record only once per successful transaction
            $alreadyRecorded = DB::table('online_admission_payment_response_details')
                ->where('clientTxnId', $clientTxnId)
                ->where('status', 'SUCCESS')
                ->exists();

            if (!$alreadyRecorded) {
                DB::table('online_admission_payment_response_details')->insert($paymentData);
            }

            // 7c: SOAP callback — only for registered_from == 2
            if ($user->registered_from == 2) {
                try {
                    $freshUser    = DB::table('admission_registration_login')->where('id', $user->id)->first();
                    $soapUrl      = 'http://164.100.181.28/DeptWebIntService/Service.asmx?op=SendResponse';
                    $main_xml_str = '<?xml version="1.0" encoding="utf-8"?>
                    <soap:Envelope
                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
                        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
                        <soap:Body>
                            <SendResponse xmlns="http://tempuri.org/">
                                <RequestKey>'      . $freshUser->RequestKey     . '</RequestKey>
                                <DeptRegistraionID>5EA45F3FA6BD786D7E1024431E03961D</DeptRegistraionID>
                                <ApplicationNo>'   . $freshUser->application_no . '</ApplicationNo>
                                <serviceCode>'     . $freshUser->serviceCode    . '</serviceCode>
                            </SendResponse>
                        </soap:Body>
                    </soap:Envelope>';

                    $call_api = call_curlApi($main_xml_str, $soapUrl, 'Response');

                    // Parse SOAP response to check ReturnType
                    $xmlStr   = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $call_api);
                    $xml      = simplexml_load_string($xmlStr);
                    $array    = json_decode(json_encode($xml), true);
                    $innerXml = $array['soapBody']['SendResponseResponse']['SendResponseResult'] ?? '';

                    if ($innerXml) {
                        $xmlStr2  = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $innerXml);
                        $xml2     = simplexml_load_string($xmlStr2);
                        $result2  = json_decode(json_encode($xml2), true);

                        if (($result2['ReturnType'] ?? '') != 1) {
                            Log::warning('TxnEnquiry SOAP ReturnType != 1 for user_id=' . $user->id);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('TxnEnquiry SOAP callback failed: ' . $e->getMessage());
                }
            }

            return redirect()->route('transactionenquiryupdate')
                ->with('success', 'Payment verified and updated successfully. Enroll No: ' . date('Y') . sprintf('%06d', $user->id));
        }

        // Still log the failed/pending attempt if user exists
        $user = DB::table('admission_registration_login')
            ->where('challan_no', $uniquechallan)
            ->first();

        if ($user) {
            $paymentData['user_id'] = $user->id;

            $alreadyRecorded = DB::table('online_admission_payment_response_details')
                ->where('clientTxnId', $clientTxnId)
                ->where('status', $status)
                ->exists();

            if (!$alreadyRecorded) {
                DB::table('online_admission_payment_response_details')->insert($paymentData);
            }
        }

        Log::warning('TxnEnquiry non-success. status=' . $status . ', amount=' . $amount . ', challan=' . $uniquechallan);

        $errorMsg = match (true) {
            $status !== 'SUCCESS'    => 'Payment status is: ' . ($status ?: 'UNKNOWN') . '. Not marked as paid.',
            $amountFloat < 200       => 'Paid amount (' . $amount . ') is less than required (200).',
            $isBlank($uniquechallan) => 'Challan number not found in transaction response.',
            default                  => 'Payment verification failed.',
        };

        return redirect()->route('transactionenquiryupdate')->with('error', $errorMsg);
    }

    public function transactionenquirystorebyemail(Request $request)
    {
        $statusTrans = 'clientCode=GGSSC&clientTxnId=' . $request->clientTxnID;

        $authKey = 'voeXpkMY5WTg63sl';
        $authIV = 'wE9gkr5BKoTXf8Mh';

        $statusTransEncData = $this->encrypt($authKey, $authIV,  $statusTrans);

        $parts = explode(':', $statusTransEncData);

        $url = "https://txnenquiry.sabpaisa.in/SPTxtnEnquiry/getTxnStatusByClientxnId";
        $Data = [

            'clientCode' => 'GGSSC',
            'statusTransEncData' => $parts[0],

        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($Data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        die($result);
        $decryptedData = openssl_decrypt(
            base64_decode(json_decode($result)->statusResponseData),
            PaymentController::OPENSSL_CIPHER_NAME,
            PaymentController::fixKey($authKey),
            OPENSSL_RAW_DATA,
            base64_decode($parts[1])
        );

        $array  = explode("&", $decryptedData);
        // dd($array);

        // $udf1=   $uniquechallan= Str::after($array[11], '=') ;
        // $udf6=   $uniquechallan= Str::after($array[16], '=') ;

        // if($udf1=="NA" || $udf1==null){
        //     $uniquechallan=   $uniquechallan= Str::after($array[16], '=') ;
        // }
        // else if($udf6=="NA" || $udf6==null){
        //     $uniquechallan=   $uniquechallan= Str::after($array[11], '=') ;
        // }else{
        //     $uniquechallan = false;
        // }
        // $udf6=   $uniquechallan= Str::after($array[16], '=') ;

        $payerName = Str::after($array[0], '=');
        $payerEmail = Str::after($array[1], '=');
        $payerMobile = Str::after($array[2], '=');
        $clientTxnId = Str::after($array[3], '=');
        $amount = Str::after($array[5], '=');
        $clientCode = Str::after($array[6], '=');
        $paidAmount = Str::after($array[7], '=');
        $paymentMode = Str::after($array[8], '=');
        $bankName = Str::after($array[9], '=');
        $amountType = Str::after($array[10], '=');
        //    $uniquechallan= Str::after($array[11], '=') ;
        $status = Str::after($array[31], '=');
        $statusCode = Str::after($array[33], '=');
        $challanNumber = Str::after($array[26], '=');
        $SabPaisaTxnId = Str::after($array[35], '=');
        $SabPaisaMessage = Str::after($array[36], '=');
        $bankMessage = Str::after($array[37], '=');
        $bankErrorCode = Str::after($array[38], '=');
        $SabPaisaErrorCode = Str::after($array[39], '=');
        $bankTxnId = Str::after($array[40], '=');
        $transDate = Str::after($array[43], '=');


        if ($status == 'SUCCESS' &&   $amount == '200.0') {

            $user =  DB::table('admission_registration_login')->where('fullname',  $payerName)->where('email', $payerEmail)->where('mobile', $payerMobile)->first();

            if ($user) {
                DB::table('admission_registration_login')->where('fullname',  $payerName)->where('email', $payerEmail)->where('mobile', $payerMobile)->update([
                    'payment_status' => 1,
                    'enroll_no' => date('Y') . sprintf("%06d", $user->id)
                ]);


                if (Auth::guard('OnlineAdmission')->user()->registered_from == 2) {

                    $check = DB::table('admission_registration_login')->where('id', Auth::guard('OnlineAdmission')->user()->id)->first();

                    $url = 'http://164.100.181.28/DeptWebIntService/Service.asmx?op=SendResponse';
                    $rKey = $check->RequestKey;

                    $depId = '5EA45F3FA6BD786D7E1024431E03961D';
                    $serviceCode = $check->serviceCode;
                    $application = $check->application_no;
                    $main_xml_str = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><SendResponse xmlns="http://tempuri.org/"><RequestKey>' . $rKey . '</RequestKey><DeptRegistraionID>' . $depId . '</DeptRegistraionID><ApplicationNo>' . $application . '</ApplicationNo><serviceCode>' . $serviceCode . '</serviceCode></SendResponse></soap:Body></soap:Envelope>';

                    $call_api = call_curlApi($main_xml_str, $url, 'Response');

                    $xmlStr = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $call_api);
                    $xml = simplexml_load_string($xmlStr);
                    $json = json_encode($xml);

                    $array = json_decode($json, TRUE);

                    $check = $array['soapBody']['SendResponseResponse']['SendResponseResult'];

                    $xmlStr = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $check);
                    $xml = simplexml_load_string($xmlStr);
                    $json = json_encode($xml);

                    $array = json_decode($json, TRUE);

                    if ($array['ReturnType'] != 1) {
                        session()->flash('error', 'Technical Issue.');
                        return;
                    }
                };


                $paymentData = [

                    'user_id' => $user->id,
                    'payerName' => $payerName,
                    'payerEmail' => $payerEmail,
                    'payerMobile' => $payerMobile,
                    'clientTxnId' => $clientTxnId,
                    'amount' => $amount,
                    'clientCode' =>  $clientCode,
                    'paidAmount' => $paidAmount,
                    'paymentMode' => $paymentMode,
                    'bankName' =>  $bankName,
                    'amountType' => $amountType,
                    'uniquechallan' => $user->challan_no,
                    'status' => $status,
                    'statusCode' =>   $statusCode,
                    'challanNumber' =>   $challanNumber,
                    'SabPaisaTxnId' => $SabPaisaTxnId,
                    'SabPaisaMessage' =>  $SabPaisaMessage,
                    'bankMessage' => $bankMessage,
                    'bankErrorCode' =>  $bankErrorCode,
                    'SabPaisaErrorCode' =>   $SabPaisaErrorCode,
                    'bankTxnId' =>   $bankTxnId,
                    'transDate' =>  $transDate,
                ];


                $data = DB::table('online_admission_payment_response_details')->insert($paymentData);

                return redirect()->route('transactionenquiryupdatebyemail')->with('success', 'Payment Successfull.');;
            } else {
                return redirect()->route('transactionenquiryupdatebyemail')->with('error', 'user not found.');;
            }
        } else {
            return redirect()->route('transactionenquiryupdatebyemail')->with('error', 'Payment Failed.');;
        }
    }
    static function decrypt($key, $iv, $data)
    {

        $parts = explode(':', $data);
        //print_r($parts);                     //Separate Encrypted data from iv.
        $encrypted = $parts[0];
        $iv = $parts[1];
        $decryptedData = openssl_decrypt(
            base64_decode($encrypted),
            PaymentController::OPENSSL_CIPHER_NAME,
            PaymentController::fixKey($key),
            OPENSSL_RAW_DATA,
            base64_decode($iv)
        );
        return $decryptedData;
    }


    static function respdecrypt($key, $iv, $data)
    {

        $parts = explode(':', $data);
        //print_r($parts);                     //Separate Encrypted data from iv.
        $encrypted = $parts[0];
        $iv = $parts[1];
        $decryptedData = openssl_decrypt(
            base64_decode($encrypted),
            PaymentController::OPENSSL_CIPHER_NAME,
            PaymentController::fixKey($key),
            OPENSSL_RAW_DATA,
            base64_decode($iv)
        );
        return $decryptedData;
    }




    //     public function gatewayRefund(){

    //         $encData=null;

    //         $clientCode='NITE5';


    //         $authKey = 'zvMzY0UZLxkiE6ad';
    //         $authIV = 'iFwrtsCSw3j7HG15';
    //         $clientCode='NITE5';
    //         $amount=100;
    //         $spTxnId ='018720805230913035';
    //         $clientTxnId='1814';
    //         $message ='return my payment'
    //       ;
    //         $encData= "amount=".$amount."&spTxnId=".$spTxnId."&clientTxnId=".$clientTxnId."&message=".$message ;


    //         $refundQuery = $this->encrypt($authKey, $authIV, $encData);

    //          $ch = curl_init();

    //          curl_setopt($ch,CURLOPT_URL, `https://stage-securepay.sabpaisa.in/SabPaisaRefundApi/refund?clientcode=$clientCode&refundQuery=$refundQuery`);

    //             curl_setopt($ch,CURLOPT_HTTPGET, true);

    //             //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    //             curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
    //             $result = curl_exec($ch);
    //             dd($result)
    // ;

    //     }



    public function transactiondetail(Request $request)
    {

        $data = [
            'clientTxnId' => $request->transaction,
            'user_id' =>  Auth::guard('OnlineAdmission')->user()->id,
            'uniquechallan' => Auth::guard('OnlineAdmission')->user()->challan_no,
            'date' => date("Y-m-d h:i:sa")
        ];
        $data = DB::table('online_admission_transaction')->insert($data);

        return response()->json(["error" => false, "msg" => "Transaction detail Submit Successfully"]);
    }


    public function transactionenquiryupdate()
    {
        return view('collegeAdmin.transactionenquiryupdate');
    }

    public function transactionenquiryupdatebyemail()
    {
        return view('collegeAdmin.transactionenquiryupdatebyemail');
    }

    public function reconciliationStatus()
    {
        $stats = [
            'initiated' => DB::table('online_admission_payment_response_details')->where('status', 'INITIATED')->count(),
            'success'   => DB::table('online_admission_payment_response_details')->where('status', 'SUCCESS')->count(),
            'failed'    => DB::table('online_admission_payment_response_details')->where('status', 'FAILED')->count(),
            'paid'      => DB::table('admission_registration_login')->where('payment_status', 1)->count(),
        ];

        $recent = DB::table('online_admission_payment_response_details as p')
            ->join('admission_registration_login as r', 'r.id', '=', 'p.user_id')
            ->select(
                'p.id',
                'p.clientTxnId',
                'p.status',
                'p.amount',
                'p.paidAmount',
                'p.paymentMode',
                'p.bankName',
                'p.transDate',
                'p.SabPaisaMessage',
                'r.fullname',
                'r.mobile',
                'r.application_no',
                'r.payment_status as reg_payment_status'
            )
            ->orderByDesc('p.id')
            ->limit(50)
            ->get();

        return view('collegeAdmin.reconciliation', compact('stats', 'recent'));
    }

    public function runReconciliation(Request $request)
    {
        $limit = (int) ($request->input('limit', 50));
        $limit = max(1, min($limit, 200));

        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        \Illuminate\Support\Facades\Artisan::call('payments:reconcile', ['--limit' => $limit], $output);

        return response()->json([
            'error'  => false,
            'output' => nl2br(htmlspecialchars($output->fetch())),
        ]);
    }
}
