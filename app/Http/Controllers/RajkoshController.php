<?php

namespace App\Http\Controllers;

use App\Models\CoachingCamp;
use App\Models\HostelRegister;
use App\Models\OnlineAdmissionModel;
use App\Models\FacilityRegister;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;
class RajkoshController extends Controller
{
    public function payment_request_bkp_20_01_25(Request $request)
    {
        $userDetail = Auth::guard('hostel')->user();
        $division_id = rajkosh_division_treasury($userDetail->applicationCommunicationDetails->pdistrict->id);

        $DEPCODE = 'EDU';
        $enc_DEPCODE = base64_encode('EDU');
        $PMODE = 'NETPAY';
        $enc_PMODE = base64_encode('NETPAY');
        $PTYPE = "";
        $CHQDDNO = '';
        $CHQDT = "";
        $ASMTYEAR = (config('app.session_year') - 1) . '-' . config('app.session_year');
        $enc_ASMTYEAR = base64_encode((config('app.session_year') - 1) . '-' . config('app.session_year'));
        $TAXPERIOD = "A";
        $enc_TAXPERIOD = base64_encode("A");
        $PERIOD = "";
        $enc_BANK = base64_encode("0001");
        $UIDNO = "";
        $enc_NAME = base64_encode($userDetail->name);
        $enc_DepositorADDRESS = "";
        $enc_DIVCODE = base64_encode($division_id->DIVCODE);
        $TCODE = $division_id->TCODE;
        $enc_TCODE = base64_encode($TCODE);
        $enc_LOCATION = base64_encode($userDetail->applicationCommunicationDetails->pdistrict->city);
        $REMARK = "";
        $TOTAL = '2500';
        $enc_TOTAL = base64_encode('2500');
        $enc_REFERENCEID = base64_encode("S05");
        $enc_ServiceID = base64_encode("05");
        $SNO = "40";
        $enc_SNO = base64_encode("40");
        $HEAD = "020203101010000";
        $enc_HEAD = base64_encode("020203101010000");
        $AMOUNT = '2500';
        $enc_AMOUNT = base64_encode('2500');

        $OTHER1 = $DEPCODE . $PMODE . $ASMTYEAR . $TAXPERIOD . $TCODE . $TOTAL;
        $md5OTHER1 = strtoupper(md5($OTHER1));
        $sha512other1 = strtoupper(hash("sha512", $md5OTHER1));

        $OTHER2 = $DEPCODE . $SNO . $HEAD . $AMOUNT;
        $md5OTHER2 = strtoupper(md5($OTHER2));
        $sha512other2 = strtoupper(hash("sha512", $md5OTHER2));


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://rajkosh.up.nic.in/rkapi/rajkoshapi/Rajkosh',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"DEPCODE":"' . $enc_DEPCODE . '","PMODE":"' . $enc_PMODE . '","PTYPE":"","CHQDDNO":"","CHQDT":"","ASMTYEAR":"' . $enc_ASMTYEAR . '","TAXPERIOD":"' . $enc_TAXPERIOD . '","PERIOD":"","BANK":"' . $enc_BANK . '","UIDNO":"","NAME":"' . $enc_NAME . '","DepositorADDRESS":"' . $enc_DepositorADDRESS . '","DIVCODE":"' . $enc_DIVCODE . '","TCODE":"' . $enc_TCODE . '","LOCATION":"' . $enc_LOCATION . '","REMARK":"","TOTAL":"' . $enc_TOTAL . '","REFERENCEID":"' . $enc_REFERENCEID . '","ServiceID":"' . $enc_ServiceID . '","OTHER1":"' . $sha512other1 . '","rajkoshHeadInfo":[{"SNO":"' . $enc_SNO . '","HEAD":"' . $enc_HEAD . '","AMOUNT":"' . $enc_AMOUNT . '","OTHER2":"' . $sha512other2 . '"}]}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cache-Control: no-cache',
                'secret: x$p#9cbJu',
                'key: Khel',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if ($response) {
            $paymenturl = json_decode($response)[0]->Output2;

            $paymentchallan = explode('|', json_decode($response)[0]->Output1)[0];

            $DEPCODE = 'EDU';
            $enc_DEPCODE = base64_encode('EDU');
            $PMODE = 'NETPAY';
            $enc_PMODE = base64_encode('NETPAY');
            $PTYPE = "";
            $CHQDDNO = '';
            $CHQDT = "";
            $ASMTYEAR = (config('app.session_year') - 1) . '-' . config('app.session_year');
            $enc_ASMTYEAR = base64_encode((config('app.session_year') - 1) . '-' . config('app.session_year'));
            $TAXPERIOD = "A";
            $enc_TAXPERIOD = base64_encode("A");
            $PERIOD = "";
            $BANK = "0001";
            $enc_BANK = base64_encode("0001");
            $UIDNO = "";
            $NAME = $userDetail->name;
            $enc_NAME = base64_encode($userDetail->name);
            $enc_DepositorADDRESS = "";
            $DepositorADDRESS = "";
            $DIVCODE = $division_id->DIVCODE;
            $enc_DIVCODE = base64_encode($division_id->DIVCODE);
            $TCODE = $division_id->TCODE;
            $enc_TCODE = base64_encode($TCODE);
            $LOCATION = $userDetail->applicationCommunicationDetails->pdistrict->city;
            $enc_LOCATION = base64_encode($userDetail->applicationCommunicationDetails->pdistrict->city);
            $REMARK = "";
            $TOTAL = '10';
            $enc_TOTAL = base64_encode('10');
            $REFERENCEID = "S05";
            $enc_REFERENCEID = base64_encode("S05");
            $ServiceID = "05";
            $enc_ServiceID = base64_encode("05");
            $SNO = "40";
            $enc_SNO = base64_encode("40");
            $HEAD = "020203101010000";
            $enc_HEAD = base64_encode("020203101010000");
            $AMOUNT = '10';
            $enc_AMOUNT = base64_encode('10');

            $OTHER1 = $DEPCODE . $PMODE . $ASMTYEAR . $TAXPERIOD . $TCODE . $TOTAL;
            $md5OTHER1 = strtoupper(md5($OTHER1));
            $sha512other1 = strtoupper(hash("sha512", $md5OTHER1));

            $OTHER2 = $DEPCODE . $SNO . $HEAD . $AMOUNT;
            $md5OTHER2 = strtoupper(md5($OTHER2));
            $sha512other2 = strtoupper(hash("sha512", $md5OTHER2));

            $Depchallan = $DEPCODE . $paymentchallan;

            $data = [
                'application_no' => $userDetail->application_no,
                'application_id' => $userDetail->id,
                'Depchallan' => $Depchallan,
                'challanNumber' => $paymentchallan,
                'DEPCODE' => $DEPCODE,
                'PMODE' => $PMODE,
                'PTYPE' => $PTYPE,
                'CHQDDNO' => $CHQDDNO,
                'CHQDT' => $CHQDT,
                'ASMTYEAR' => $ASMTYEAR,
                'TAXPERIOD' => $TAXPERIOD,
                'PERIOD' => $PERIOD,
                'BANK' => $BANK,
                'UIDNO' => $UIDNO,
                'NAME' => $NAME,
                'DepositorADDRESS' => $DepositorADDRESS,
                'DIVCODE' => $DIVCODE,
                'TCODE' => $TCODE,
                'LOCATION' => $LOCATION,
                'TOTAL' => $TOTAL,
                'REFERENCEID' => $REFERENCEID,
                'ServiceID' => $ServiceID,
                'SNO' => $SNO,
                'HEAD' => $HEAD,
                'AMOUNT' => $AMOUNT,
                'OTHER1' => $OTHER1,
                'OTHER2' => $OTHER2,
                'module' => 1,

            ];

            $id = DB::table('rajkosh_payment_request')->insertGetId($data);

            return redirect($paymenturl);
        }


        return redirect()->back()->with('error', 'Internal Server Error.');
    }

    public function payment_request(Request $request)
    {
        $userDetail = Auth::guard('hostel')->user();
        $division_id = rajkosh_division_treasury($userDetail->applicationCommunicationDetails->pdistrict->id);
        $TOTAL = 10;

        $DEPCODE = 'EDU';
        $PMODE = 'NETPAY';
        $PTYPE = '';
        $CHQDDNO = '';
        $CHQDT = '';
        $ASMTYEAR = config('app.session_year') . '-' . (config('app.session_year') + 1);
        $TAXPERIOD = 'A';
        $PERIOD = '';
        $BANK = '0001';
        $UIDNO = '';
        $NAME = $userDetail->name;
        $DepositorADDRESS = '';
        $DIVCODE = $division_id->DIVCODE;
        $TCODE = $division_id->TCODE;
        $LOCATION = $userDetail->applicationCommunicationDetails->pdistrict->city;
        $REMARK = '';
        $REFERENCEID = 'S05';
        $ServiceID = '05';
        $SNO = '40';
        $HEAD = '020203101010000';
        $AMOUNT = $TOTAL;

        $OTHER1 = $DEPCODE . $PMODE . $ASMTYEAR . $TAXPERIOD . $TCODE . $TOTAL;
        $sha512other1 = strtoupper(hash('sha512', strtoupper(md5($OTHER1))));

        $OTHER2 = $DEPCODE . $SNO . $HEAD . $AMOUNT;
        $sha512other2 = strtoupper(hash('sha512', strtoupper(md5($OTHER2))));

        $postFields = json_encode([
            'DEPCODE' => base64_encode($DEPCODE),
            'PMODE' => base64_encode($PMODE),
            'PTYPE' => '',
            'CHQDDNO' => '',
            'CHQDT' => '',
            'ASMTYEAR' => base64_encode($ASMTYEAR),
            'TAXPERIOD' => base64_encode($TAXPERIOD),
            'PERIOD' => '',
            'BANK' => base64_encode($BANK),
            'UIDNO' => '',
            'NAME' => base64_encode($NAME),
            'DepositorADDRESS' => '',
            'DIVCODE' => base64_encode($DIVCODE),
            'TCODE' => base64_encode($TCODE),
            'LOCATION' => base64_encode($LOCATION),
            'REMARK' => '',
            'TOTAL' => base64_encode((string)$TOTAL),
            'REFERENCEID' => base64_encode($REFERENCEID),
            'ServiceID' => base64_encode($ServiceID),
            'OTHER1' => $sha512other1,
            'rajkoshHeadInfo' => [[
                'SNO' => base64_encode($SNO),
                'HEAD' => base64_encode($HEAD),
                'AMOUNT' => base64_encode((string)$AMOUNT),
                'OTHER2' => $sha512other2,
            ]],
        ]);

        // rajkosh.up.nic.in uses legacy TLS - enable UnsafeLegacyRenegotiation
        $prevOpenSslConf = getenv('OPENSSL_CONF');
        putenv('OPENSSL_CONF=' . base_path('openssl_legacy.cnf'));

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://rajkosh.up.nic.in/rkapi/rajkoshapi/Rajkosh',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_OPTIONS => CURLSSLOPT_ALLOW_BEAST,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Cache-Control: no-cache',
                'secret: x$p#9cbJu',
                'key: Khel',
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        putenv('OPENSSL_CONF=' . ($prevOpenSslConf ?: ''));

        if ($response) {
            $decoded = json_decode($response);
            if (is_string($decoded)) {
                return redirect()->back()->with('error', 'Payment gateway returned an invalid response: ' . $decoded);
            }
            $paymenturl = $decoded[0]->Output2;
            $paymentchallan = explode('|', $decoded[0]->Output1)[0];
            $Depchallan = $DEPCODE . $paymentchallan;

            DB::table('rajkosh_payment_request')->insertGetId([
                'application_no' => $userDetail->application_no,
                'application_id' => $userDetail->id,
                'Depchallan' => $Depchallan,
                'challanNumber' => $paymentchallan,
                'DEPCODE' => $DEPCODE,
                'PMODE' => $PMODE,
                'PTYPE' => $PTYPE,
                'CHQDDNO' => $CHQDDNO,
                'CHQDT' => $CHQDT,
                'ASMTYEAR' => $ASMTYEAR,
                'TAXPERIOD' => $TAXPERIOD,
                'PERIOD' => $PERIOD,
                'BANK' => $BANK,
                'UIDNO' => $UIDNO,
                'NAME' => $NAME,
                'DepositorADDRESS' => $DepositorADDRESS,
                'DIVCODE' => $DIVCODE,
                'TCODE' => $TCODE,
                'LOCATION' => $LOCATION,
                'TOTAL' => $TOTAL,
                'REFERENCEID' => $REFERENCEID,
                'ServiceID' => $ServiceID,
                'SNO' => $SNO,
                'HEAD' => $HEAD,
                'AMOUNT' => $AMOUNT,
                'OTHER1' => $OTHER1,
                'OTHER2' => $OTHER2,
                'type' => 0,
                'module' => 1,
            ]);

            return redirect($paymenturl);
        }

        return redirect()->back()->with('error', 'Payment gateway connection failed. Please try again.');
    }

    // Hostel Allotment
    public function payment_request_for_allotment(Request $request)
    {
        try {
            $user = Auth::guard('hostel')->user();
            $div = rajkosh_division_treasury($user->applicationCommunicationDetails->pdistrict->id);

            $total     = 2500;
            $dep       = 'EDU';
            $pmode     = 'NETPAY';
            $sno       = '40';
            $head      = '020203101010000';
            $ref       = 'S05';
            $service   = '05';
            $bank      = '0001';
            $asmt      = config('app.session_year') . '-' . (config('app.session_year') + 1);
            $tax       = 'A';

            $other1 = strtoupper(hash('sha512', strtoupper(md5($dep . $pmode . $asmt . $tax . $div->TCODE . $total))));
            $other2 = strtoupper(hash('sha512', strtoupper(md5($dep . $sno . $head . $total))));

            $payload = json_encode([
                'DEPCODE' => base64_encode($dep),
                'PMODE' => base64_encode($pmode),
                'PTYPE' => '',
                'CHQDDNO' => '',
                'CHQDT' => '',
                'ASMTYEAR' => base64_encode($asmt),
                'TAXPERIOD' => base64_encode($tax),
                'PERIOD' => '',
                'BANK' => base64_encode($bank),
                'UIDNO' => '',
                'NAME' => base64_encode($user->name),
                'DepositorADDRESS' => '',
                'DIVCODE' => base64_encode($div->DIVCODE),
                'TCODE' => base64_encode($div->TCODE),
                'LOCATION' => base64_encode($user->applicationCommunicationDetails->pdistrict->city),
                'REMARK' => '',
                'TOTAL' => base64_encode((string)$total),
                'REFERENCEID' => base64_encode($ref),
                'ServiceID' => base64_encode($service),
                'OTHER1' => $other1,
                'rajkoshHeadInfo' => [[
                    'SNO' => base64_encode($sno),
                    'HEAD' => base64_encode($head),
                    'AMOUNT' => base64_encode((string)$total),
                    'OTHER2' => $other2,
                ]]
            ], JSON_UNESCAPED_SLASHES);

            $file = storage_path('app/rajkosh.json');
            file_put_contents($file, $payload);

            $curlExe = 'C:\\Windows\\System32\\curl.exe';
            $cmd = '"' . $curlExe . '" -s -X POST '
                . '"https://rajkosh.up.nic.in/rkapi/rajkoshapi/Rajkosh" '
                . '-H "Content-Type: application/json" '
                . '-H "secret: x$p#9cbJu" '
                . '-H "key: Khel" '
                . '--data @"' . str_replace('\\', '/', $file) . '"';

            $response = shell_exec($cmd);
            @unlink($file);

            $data = json_decode($response);

            if (!isset($data[0]->Output2)) {
                Log::error('Invalid Rajkosh Response', ['response' => $response]);
                return back()->with('error', 'Payment gateway connection failed.');
            }

            $paymentUrl = $data[0]->Output2;
            $paymentChallan = explode('|', $data[0]->Output1)[0];
            $depChallan = $dep . $paymentChallan;

            DB::table('rajkosh_payment_request')->insertGetId([
                'application_no'   => $user->application_no,
                'application_id'   => $user->id,
                'Depchallan'       => $depChallan,
                'challanNumber'    => $paymentChallan,
                'DEPCODE'          => $dep,
                'PMODE'            => $pmode,
                'PTYPE'            => '',
                'CHQDDNO'          => '',
                'CHQDT'            => '',
                'ASMTYEAR'         => $asmt,
                'TAXPERIOD'        => $tax,
                'PERIOD'           => '',
                'BANK'             => $bank,
                'UIDNO'            => '',
                'NAME'             => $user->name,
                'DepositorADDRESS' => '',
                'DIVCODE'          => $div->DIVCODE,
                'TCODE'            => $div->TCODE,
                'LOCATION'         => $user->applicationCommunicationDetails->pdistrict->city,
                'TOTAL'            => $total,
                'REFERENCEID'      => $ref,
                'ServiceID'        => $service,
                'SNO'              => $sno,
                'HEAD'             => $head,
                'AMOUNT'           => $total,
                'OTHER1'           => $other1,
                'OTHER2'           => $other2,
                'type'             => 0,
                'module'           => 3
            ]);

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Rajkosh Exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Something went wrong while initiating payment.');
        }
    }

    public function payment_response_test(Request $request)
    {
        Log::info('Rajkosh TEST payment_response', $request->all());

        return response('<pre style="font-family:monospace;padding:30px;">'
            . '<h2>Rajkosh Test Return URL - Response Received</h2>'
            . '<b>challan_no:</b> '   . base64_decode($request->challan_no)   . "\n"
            . '<b>Status:</b> '       . base64_decode($request->Status)       . "\n"
            . '<b>status_desc:</b> '  . base64_decode($request->status_desc)  . "\n"
            . '<b>amount:</b> '       . base64_decode($request->amount)       . "\n"
            . '<b>ref_no:</b> '       . base64_decode($request->ref_no)       . "\n"
            . '<b>Bank_Id:</b> '      . base64_decode($request->Bank_Id)      . "\n"
            . '<b>Other:</b> '        . base64_decode($request->Other)        . "\n"
            . "\n<hr>Raw data:\n"     . print_r($request->all(), true)
            . '</pre>');
    }

    public function payment_response_allotment_test(Request $request)
    {
        Log::info('Rajkosh TEST hostel allotment payment_response', $request->all());

        return response('<pre style="font-family:monospace;padding:30px;">'
            . '<h2>Rajkosh Test Return URL (Hostel Allotment Fee) - Response Received</h2>'
            . '<b>challan_no:</b> '   . base64_decode($request->challan_no)   . "\n"
            . '<b>Status:</b> '       . base64_decode($request->Status)       . "\n"
            . '<b>status_desc:</b> '  . base64_decode($request->status_desc)  . "\n"
            . '<b>amount:</b> '       . base64_decode($request->amount)       . "\n"
            . '<b>ref_no:</b> '       . base64_decode($request->ref_no)       . "\n"
            . '<b>Bank_Id:</b> '      . base64_decode($request->Bank_Id)      . "\n"
            . '<b>Other:</b> '        . base64_decode($request->Other)        . "\n"
            . "\n<hr>Raw data:\n"     . print_r($request->all(), true)
            . '</pre>');
    }

    public function coaching_camp_payment_response_registration_fee_test(Request $request)
    {
        Log::info('Rajkosh TEST coaching camp registration fee payment_response', $request->all());

        return response('<pre style="font-family:monospace;padding:30px;">'
            . '<h2>Rajkosh Test Return URL (Coaching Camp Registration Fee) - Response Received</h2>'
            . '<b>challan_no:</b> '   . base64_decode($request->challan_no)   . "\n"
            . '<b>Status:</b> '       . base64_decode($request->Status)       . "\n"
            . '<b>status_desc:</b> '  . base64_decode($request->status_desc)  . "\n"
            . '<b>amount:</b> '       . base64_decode($request->amount)       . "\n"
            . '<b>ref_no:</b> '       . base64_decode($request->ref_no)       . "\n"
            . '<b>Bank_Id:</b> '      . base64_decode($request->Bank_Id)      . "\n"
            . '<b>Other:</b> '        . base64_decode($request->Other)        . "\n"
            . "\n<hr>Raw data:\n"     . print_r($request->all(), true)
            . '</pre>');
    }

    public function coaching_camp_payment_response_coaching_fee_test(Request $request)
    {
        Log::info('Rajkosh TEST coaching camp coaching fee payment_response', $request->all());

        return response('<pre style="font-family:monospace;padding:30px;">'
            . '<h2>Rajkosh Test Return URL (Coaching Camp Coaching Fee) - Response Received</h2>'
            . '<b>challan_no:</b> '   . base64_decode($request->challan_no)   . "\n"
            . '<b>Status:</b> '       . base64_decode($request->Status)       . "\n"
            . '<b>status_desc:</b> '  . base64_decode($request->status_desc)  . "\n"
            . '<b>amount:</b> '       . base64_decode($request->amount)       . "\n"
            . '<b>ref_no:</b> '       . base64_decode($request->ref_no)       . "\n"
            . '<b>Bank_Id:</b> '      . base64_decode($request->Bank_Id)      . "\n"
            . '<b>Other:</b> '        . base64_decode($request->Other)        . "\n"
            . "\n<hr>Raw data:\n"     . print_r($request->all(), true)
            . '</pre>');
    }

    public function facility_booking_payment_response_test(Request $request)
    {
        Log::info('Rajkosh TEST facility_booking payment_response', $request->all());

        return response('<pre style="font-family:monospace;padding:30px;">'
            . '<h2>Rajkosh Test Return URL (Facility Booking) - Response Received</h2>'
            . '<b>challan_no:</b> '   . base64_decode($request->challan_no)   . "\n"
            . '<b>Status:</b> '       . base64_decode($request->Status)       . "\n"
            . '<b>status_desc:</b> '  . base64_decode($request->status_desc)  . "\n"
            . '<b>amount:</b> '       . base64_decode($request->amount)       . "\n"
            . '<b>ref_no:</b> '       . base64_decode($request->ref_no)       . "\n"
            . '<b>Bank_Id:</b> '      . base64_decode($request->Bank_Id)      . "\n"
            . '<b>Other:</b> '        . base64_decode($request->Other)        . "\n"
            . "\n<hr>Raw data:\n"     . print_r($request->all(), true)
            . '</pre>');
    }

    public function payment_response(Request $request)
    {

        $userDetail = DB::table('rajkosh_payment_request')->where('Depchallan', base64_decode($request->challan_no))->first();

        if (!$userDetail || !$request->challan_no) {
            return response('No response received from Rajkosh.', 200);
        }

        $data = [

            'challan_no' => base64_decode($request->challan_no),
            'Bank_Id' => base64_decode($request->Bank_Id),
            'ref_no' => base64_decode($request->ref_no),
            'amount' => base64_decode($request->amount),
            'Status' => base64_decode($request->Status),
            'status_desc' => base64_decode($request->status_desc),
            'Other' => base64_decode($request->Other),
        ];

        $id = DB::table('rajkosh_payment_response')->insertGetId($data);



        if ($userDetail->module == 2) {
            if (base64_decode($request->Status) == 'Success') {

                $application = DB::table('facility_booking_type_detail')->where('id', $userDetail->application_id)->first();

                $users = FacilityRegister::where('id', $application->user_id)->first();

                Auth::guard('facility_booking')->login($users);

                DB::table('facility_booking_type_detail')->where('id', $userDetail->application_id)->update([
                    'payment_status' => 1,
                    'payment_date' => date('Y-m-d'),
                ]);



                return redirect()->route('facility_booking_dashboard')->with('success', 'Payment Successful.');
            }

            return redirect()->route('facility_booking_dashboard')->with('error', 'Payment Fail.');
        } elseif ($userDetail->module == 1) {

            $student = HostelRegister::where('application_no', $userDetail->application_no)->first();
            Auth::guard('hostel')->login($student);
            if (base64_decode($request->Status) == 'Success') {




                $hostelUser = HostelRegister::find($userDetail->application_id);

                $hostelUser->update([

                    'level' => 4,
                    'payment_status' => 2,
                    'payment_date' => date('Y-m-d'),
                ]);



                return redirect()->route('hostel.dashboard')->with('success', 'Payment Successful.');
            }

            return redirect()->route('hostel.dashboard')->with('error', 'Payment Fail.');
        } elseif ($userDetail->module == 3) {

            $student = HostelRegister::where('application_no', $userDetail->application_no)->first();
            Auth::guard('hostel')->login($student);
            if (base64_decode($request->Status) == 'Success') {

                $hostelUser = HostelRegister::find($userDetail->application_id);

                $hostelUser->update([
                    'payment_allotment_fee_status' => 1,
                    'payment_allotment_fee_date' => date('Y-m-d'),
                ]);



                return redirect()->route('hostel.dashboard')->with('success', 'Payment Successful.');
            }

            return redirect()->route('hostel.dashboard')->with('error', 'Payment Fail.');
        } elseif ($userDetail->module == 4) {



            if (base64_decode($request->Status) == 'Success') {

                $application = DB::table('coaching_camp_basic_details')->where('id', $userDetail->application_id)->first();

                $users = CoachingCamp::where('id', $application->user_id)->first();

                Auth::guard('CoachingCamp')->login($users);

                DB::table('coaching_camp_basic_details')->where('id', $userDetail->application_id)->update([
                    'payment_status_coaching_fee' => 1,
                    'payment_date_coaching_fee' => date('Y-m-d'),
                    'validity' => null,
                    
                ]);



                return redirect()->route('coaching_camp_dashboard')->with('success', 'Payment Successful.');
            }

            return redirect()->route('coaching_camp_dashboard')->with('error', 'Payment Fail.');
        } elseif ($userDetail->module == 5) {



            if (base64_decode($request->Status) == 'Success') {

                $application = DB::table('coaching_camp_basic_details')->where('user_id', $userDetail->application_id)->first();

                $users = CoachingCamp::where('id', $userDetail->application_id)->first();

                Auth::guard('CoachingCamp')->login($users);

                DB::table('coaching_camp_basic_details')->where('user_id', $userDetail->application_id)->update([
                    'payment_status_coaching_fee' => 1,
                    'payment_date_coaching_fee' => date('Y-m-d'),
                ]);


                return redirect()->route('coaching_camp_dashboard')->with('success', 'Payment Successful.');
            }

            return redirect()->route('coaching_camp_dashboard')->with('error', 'Payment Fail.');
        }
    }

    public function update_payment_status($application)
    {


        $rajkoskdata = DB::table('rajkosh_payment_request')->where('application_no', $application)->where('module', 1)->get();
        if (count($rajkoskdata) > 0) {
            foreach ($rajkoskdata as $item) {


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://nicupws.up.nic.in/rajkoshdoubleverification.asmx/DoubleVerifyParticular?TransNo=' . $item->Depchallan . '&Amount=2500',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $data_response = explode("|", simplexml_load_string($response));


                if (explode("=", $data_response[1])[1] == 'Success') {


                    $data = [

                        'challan_no' => explode("=", $data_response[0])[1],
                        'Bank_Id' => '',
                        'ref_no' => explode("=", $data_response[3])[1],
                        'amount' => explode("=", $data_response[4])[1],
                        'Status' => explode("=", $data_response[1])[1],
                        'status_desc' => explode("=", $data_response[2])[1],
                        'Other' => '',
                    ];

                    $id = DB::table('rajkosh_payment_response')->insertGetId($data);

                    $student = HostelRegister::where('application_no', $application)->first();
                    Auth::guard('hostel')->login($student);

                    $hostelUser = HostelRegister::find($student->id);

                    $hostelUser->update([

                        'level' => 4,
                        'payment_status' => 2,
                    ]);



                    return redirect()->back()->with('success', 'Payment Status updated Successful.');
                }
            }
        }

        return redirect()->back();
    }

    public function update_payment_status_allotment(Request $request)
    {


        $rajkoskdata = DB::table('rajkosh_payment_request')->where('application_no', $request->application)->where('module', 3)->get();
        if (count($rajkoskdata) > 0) {
            foreach ($rajkoskdata as $item) {


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://nicupws.up.nic.in/rajkoshdoubleverification.asmx/DoubleVerifyParticular?TransNo=' . $item->Depchallan . '&Amount=2500',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $data_response = explode("|", simplexml_load_string($response));;


                if (explode("=", $data_response[1])[1] == 'Success') {


                    $data = [

                        'challan_no' => explode("=", $data_response[0])[1],
                        'Bank_Id' => '',
                        'ref_no' => explode("=", $data_response[3])[1],
                        'amount' => explode("=", $data_response[4])[1],
                        'Status' => explode("=", $data_response[1])[1],
                        'status_desc' => explode("=", $data_response[2])[1],
                        'Other' => '',
                    ];

                    $id = DB::table('rajkosh_payment_response')->insertGetId($data);

                    $student = HostelRegister::where('application_no', $request->application)->first();
                    Auth::guard('hostel')->login($student);

                    $hostelUser = HostelRegister::find($student->id);

                    $hostelUser->update([

                        'payment_allotment_fee_status' => 1,
                        'payment_allotment_fee_date' => date('Y-m-d'),
                    ]);



                    return redirect()->back()->with('success', 'Payment Status updated Successful.');
                }
            }
        }

        return redirect()->back();
    }

    public function update_payment_status_facility_booking($application)
    {


        $rajkoskdata = DB::table('rajkosh_payment_request')->where('application_no', $application)->where('module', 2)->get();

        if (count($rajkoskdata) > 0) {
            foreach ($rajkoskdata as $item) {


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://nicupws.up.nic.in/rajkoshdoubleverification.asmx/DoubleVerifyParticular?TransNo=' . $item->Depchallan . '&Amount=' . $item->AMOUNT,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Accept: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                $data_response = explode("|", simplexml_load_string($response));;


                if (explode("=", $data_response[1])[1] == 'Success') {


                    $data = [

                        'challan_no' => explode("=", $data_response[0])[1],
                        'Bank_Id' => '',
                        'ref_no' => explode("=", $data_response[3])[1],
                        'amount' => explode("=", $data_response[4])[1],
                        'Status' => explode("=", $data_response[1])[1],
                        'status_desc' => explode("=", $data_response[2])[1],
                        'Other' => '',
                    ];

                    $id = DB::table('rajkosh_payment_response')->insertGetId($data);





                    DB::table('facility_booking_type_detail')->where('application_no', $application)->update([
                        'payment_status' => 1,
                        'payment_date' => date('Y-m-d'),
                    ]);

                    return redirect()->route('facility_booking_dashboard')->with('success', 'Payment Status updated Successful.');
                }
            }
        }

        return redirect()->back();
    }
    public function update_payment_status_coaching_camp(Request $request)
    {
        try {

            $rajkoskdata = DB::table('rajkosh_payment_request')->where('application_id', Auth::guard('CoachingCamp')->id())->where('module', 5)->get();
            if (count($rajkoskdata) > 0) {
                foreach ($rajkoskdata as $item) {


                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://nicupws.up.nic.in/rajkoshdoubleverification.asmx/DoubleVerifyParticular?TransNo=' . $item->Depchallan . '&Amount='.$item->AMOUNT,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Accept: application/json'
                        ),
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    $xml = simplexml_load_string($response);

                    if ($xml === false) {
                        Log::error('Invalid Rajkosh XML Response', [
                            'response' => $response
                        ]);

                        continue;
                    }
                    $data_response = explode("|", (string) $xml);
                    $statusData = explode('=', $data_response[1] ?? '', 2);

                    if (($statusData[1] ?? '') == 'Success') {

                        $date = DateTime::createFromFormat('d/m/Y H:i:s', explode('=', $data_response[6], 2)[1]);
                        $timestamp = $date->format('Y-m-d H:i:s');
                        $data = [
                            'challan_no'  => explode('=', $data_response[0], 2)[1],
                            'Bank_Id'     => '001',
                            'ref_no'      => explode('=', $data_response[3], 2)[1],
                            'amount'      => explode('=', $data_response[4], 2)[1],
                            'Status'      => explode('=', $data_response[1], 2)[1],
                            'status_desc' => explode('=', $data_response[2], 2)[1],
                            'date'        => $timestamp,
                            'Other'       => '',
                        ];

                        $exists = DB::table('rajkosh_payment_response')
                            ->where('challan_no', $data['challan_no'])
                            ->where('Status', $data['Status'])
                            ->exists();

                        if (!$exists) {
                            $id = DB::table('rajkosh_payment_response')->insertGetId($data);
                        }
                        $student = CoachingCamp::where('id', $item->application_id)->first();
                        Auth::guard('CoachingCamp')->login($student);

                        $coachingCampUser = DB::table('coaching_camp_basic_details')->where('user_id', $student->id)->update([

                            'payment_status_coaching_fee' => 1,
                            'payment_date_coaching_fee' => date('Y-m-d'),
                        ]);

                    }
                }
                return redirect()->back()->with('success', 'Payment Status updated Successful.');
            }

            return redirect()->back();
        } catch (\Throwable $e) {

            Log::error('Rajkosh Payment Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Payment verification failed.');
        }
    }


    // Facility Booking
    public function facility_booking_payment_request(Request $request, $id)
    {
            $application = DB::table('facility_booking_type_detail')->where('id', $id)->first();
            if (!$application) return back()->with('error', 'Application not found.');

            $user = DB::table('facility_booking_register')->where('id', $application->user_id)->first();
            if (!$user) return back()->with('error', 'User details not found.');

            $div = rajkosh_division_treasury($application->city);
            if (!$div) return back()->with('error', 'Division treasury details not found.');

            $total   = $application->amount_to_be_paid;
            $dep     = 'EDU';
            $pmode   = 'NETPAY';
            $sno     = '40';
            $head    = '020203101010000';
            $ref     = 'S05';
            $service = '05';
            $bank    = '0001';
            $asmt    = config('app.session_year') . '-' . (config('app.session_year') + 1);
            $tax     = 'A';

            $other1 = strtoupper(hash('sha512', strtoupper(md5($dep . $pmode . $asmt . $tax . $div->TCODE . $total))));
            $other2 = strtoupper(hash('sha512', strtoupper(md5($dep . $sno . $head . $total))));

            $payload = json_encode([
                'DEPCODE' => base64_encode($dep),
                'PMODE' => base64_encode($pmode),
                'PTYPE' => '',
                'CHQDDNO' => '',
                'CHQDT' => '',
                'ASMTYEAR' => base64_encode($asmt),
                'TAXPERIOD' => base64_encode($tax),
                'PERIOD' => '',
                'BANK' => base64_encode($bank),
                'UIDNO' => '',
                'NAME' => base64_encode($user->name),
                'DepositorADDRESS' => base64_encode($application->address),
                'DIVCODE' => base64_encode($div->DIVCODE),
                'TCODE' => base64_encode($div->TCODE),
                'LOCATION' => base64_encode($application->city),
                'REMARK' => '',
                'TOTAL' => base64_encode((string)$total),
                'REFERENCEID' => base64_encode($ref),
                'ServiceID' => base64_encode($service),
                'OTHER1' => $other1,
                'rajkoshHeadInfo' => [[
                    'SNO' => base64_encode($sno),
                    'HEAD' => base64_encode($head),
                    'AMOUNT' => base64_encode($total),
                    'OTHER2' => $other2,
                ]]
            ], JSON_UNESCAPED_SLASHES);
            Log::error('Rajkosh invalid response', ['response' => $payload]);

            $file = storage_path('app/rajkosh_facility.json');
            file_put_contents($file, $payload);

            $curlExe = 'C:\\Windows\\System32\\curl.exe';
            $cmd = '"' . $curlExe . '" -s -X POST '
                . '"https://rajkosh.up.nic.in/rkapi/rajkoshapi/Rajkosh" '
                . '-H "Content-Type: application/json" '
                . '-H "secret: x$p#9cbJu" '
                . '-H "key: Khel" '
                . '--data @"' . str_replace('\\', '/', $file) . '"';

            $response = shell_exec($cmd);
            @unlink($file);

            $data = json_decode($response);

            if (!isset($data[0]->Output2)) {
                Log::error('Rajkosh invalid response', ['response' => $response]);
                return back()->with('error', 'Payment gateway connection failed.');
            }

            $paymentUrl     = $data[0]->Output2;
            $paymentChallan = explode('|', $data[0]->Output1)[0];
            $depChallan     = $dep . $paymentChallan;

            DB::table('rajkosh_payment_request')->insertGetId([
                'application_no'   => $application->application_no,
                'application_id'   => $application->id,
                'Depchallan'       => $depChallan,
                'challanNumber'    => $paymentChallan,
                'DEPCODE'          => $dep,
                'PMODE'            => $pmode,
                'ASMTYEAR'         => $asmt,
                'TAXPERIOD'        => $tax,
                'DIVCODE'          => $div->DIVCODE,
                'TCODE'            => $div->TCODE,
                'LOCATION'         => $application->city,
                'TOTAL'            => $total,
                'REFERENCEID'      => $ref,
                'ServiceID'        => $service,
                'SNO'              => $sno,
                'HEAD'             => $head,
                'AMOUNT'           => $total,
                'OTHER1'           => $other1,
                'OTHER2'           => $other2,
                'type'             => 2,
                'module'           => 2
            ]);

            return redirect($paymentUrl);

    }
      public function coaching_camp_application_registration_fee(Request $request)
    {
        $month = date('n'); // Numeric month without leading zero (1–12)
        $year = date('Y'); // Current year in 4 digits

        if ($month >= 4) {
        // April to December: Assessment year is current year – next year
        $DynamicASMTYEAR = $year . '-' . ($year + 1);
        }
        else {
        // January to March: Assessment year is previous year – current year
        $DynamicASMTYEAR = ($year - 1) . '-' . $year;
        }

        $userDetail = Auth::guard('CoachingCamp')->user();
        $permanent_district_id = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first()->permanent_district;
        $basic = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();
        $division_id = rajkosh_division_treasury($permanent_district_id);


        $sportIds = explode(',', optional($basic)->sport ?? '');


        $TOTAL = count($sportIds) * 10;
        $DEPCODE = 'EDU';
        $PMODE = 'NETPAY';
        $PTYPE = '';
        $CHQDDNO = '';
        $CHQDT = '';
        $ASMTYEAR = $DynamicASMTYEAR;
        $enc_ASMTYEAR = base64_encode($DynamicASMTYEAR);
        $TAXPERIOD = 'O';
        $PERIOD = '';
        $BANK = '0001';
        $UIDNO = '';
        $NAME = $userDetail->name;
        $DepositorADDRESS = '';
        $DIVCODE = $division_id->DIVCODE;
        $TCODE = $division_id->TCODE;
        $LOCATION = districtName($permanent_district_id);
        $REMARK = '';
        $REFERENCEID = 'S05';
        $ServiceID = '05';
        $SNO = '40';
        $HEAD = '020203101010000';
        $AMOUNT = $TOTAL;

        $OTHER1 = $DEPCODE . $PMODE . $ASMTYEAR . $TAXPERIOD . $TCODE . $TOTAL;
        $sha512other1 = strtoupper(hash('sha512', strtoupper(md5($OTHER1))));

        $OTHER2 = $DEPCODE . $SNO . $HEAD . $AMOUNT;
        $sha512other2 = strtoupper(hash('sha512', strtoupper(md5($OTHER2))));

        $postFields = json_encode([
        'DEPCODE' => base64_encode($DEPCODE),
        'PMODE' => base64_encode($PMODE),
        'PTYPE' => '',
        'CHQDDNO' => '',
        'CHQDT' => '',
        'ASMTYEAR' => base64_encode($ASMTYEAR),
        'TAXPERIOD' => base64_encode($TAXPERIOD),
        'PERIOD' => '',
        'BANK' => base64_encode($BANK),
        'UIDNO' => '',
        'NAME' => base64_encode($NAME),
        'DepositorADDRESS' => '',
        'DIVCODE' => base64_encode($DIVCODE),
        'TCODE' => base64_encode($TCODE),
        'LOCATION' => base64_encode($LOCATION),
        'REMARK' => '',
        'TOTAL' => base64_encode((string)$TOTAL),
        'REFERENCEID' => base64_encode($REFERENCEID),
        'ServiceID' => base64_encode($ServiceID),
        'OTHER1' => $sha512other1,
        'rajkoshHeadInfo' => [[
            'SNO' => base64_encode($SNO),
            'HEAD' => base64_encode($HEAD),
            'AMOUNT' => base64_encode((string)$AMOUNT),
            'OTHER2' => $sha512other2,
            ]],
        ]);

        // rajkosh.up.nic.in uses legacy TLS - enable UnsafeLegacyRenegotiation
        $prevOpenSslConf2 = getenv('OPENSSL_CONF');
        putenv('OPENSSL_CONF=' . base_path('openssl_legacy.cnf'));

        $curl = curl_init();
        curl_setopt_array($curl, [
        CURLOPT_URL => 'https://rajkosh.up.nic.in/rkapi/rajkoshapi/Rajkosh',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_OPTIONS => CURLSSLOPT_ALLOW_BEAST,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Cache-Control: no-cache',
            'secret: x$p#9cbJu',
            'key: Khel',
        ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        putenv('OPENSSL_CONF=' . ($prevOpenSslConf2 ?: ''));

        if ($response) {
        $decoded = json_decode($response);
        $paymenturl = $decoded[0]->Output2;
        $paymentchallan = explode('|', $decoded[0]->Output1)[0];
        $Depchallan = $DEPCODE . $paymentchallan;

        DB::table('rajkosh_payment_request')->insertGetId([
            'application_no' => $userDetail->application_no,
            'application_id' => $userDetail->id,
            'Depchallan' => $Depchallan,
            'challanNumber' => $paymentchallan,
            'DEPCODE' => $DEPCODE,
            'PMODE' => $PMODE,
            'PTYPE' => $PTYPE,
            'CHQDDNO' => $CHQDDNO,
            'CHQDT' => $CHQDT,
            'ASMTYEAR' => $ASMTYEAR,
            'TAXPERIOD' => $TAXPERIOD,
            'PERIOD' => $PERIOD,
            'BANK' => $BANK,
            'UIDNO' => $UIDNO,
            'NAME' => $NAME,
            'DepositorADDRESS' => $DepositorADDRESS,
            'DIVCODE' => $DIVCODE,
            'TCODE' => $TCODE,
            'LOCATION' => $LOCATION,
            'TOTAL' => $TOTAL,
            'REFERENCEID' => $REFERENCEID,
            'ServiceID' => $ServiceID,
            'SNO' => $SNO,
            'HEAD' => $HEAD,
            'AMOUNT' => $AMOUNT,
            'OTHER1' => $OTHER1,
            'OTHER2' => $OTHER2,
            'type' => 0,
            'module' => 4,
        ]);

        return redirect($paymenturl);
        }

        return redirect()->back()->with('error', 'Payment gateway connection failed. Please try again.');
    }

    public function coaching_camp_application_registration_fee_bkp_17_06_2026(Request $request)
    {
        $month = date('n'); // Numeric month without leading zero (1–12)
        $year = date('Y'); // Current year in 4 digits

        if ($month >= 4) {
            // April to December: Assessment year is current year – next year
            $DynamicASMTYEAR = $year . '-' . ($year + 1);
        } else {
            // January to March: Assessment year is previous year – current year
            $DynamicASMTYEAR = ($year - 1) . '-' . $year;
        }




        $userDetail = Auth::guard('CoachingCamp')->user();
        $permanent_district_id = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first()->permanent_district;
        $basic = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();
        $division_id = rajkosh_division_treasury($permanent_district_id);


        $sportIds = explode(',', optional($basic)->sport ?? '');


        $TOTAL = count($sportIds) * 10;


        $DEPCODE = 'EDU';
        $enc_DEPCODE = base64_encode('EDU');
        $PMODE = 'NETPAY';
        $enc_PMODE = base64_encode('NETPAY');
        $PTYPE = "";
        $CHQDDNO = '';
        $CHQDT = "";
        $ASMTYEAR = $DynamicASMTYEAR;
        $enc_ASMTYEAR = base64_encode($DynamicASMTYEAR);
        $TAXPERIOD = "A";
        $enc_TAXPERIOD = base64_encode("A");
        $PERIOD = "";
        $enc_BANK = base64_encode("0001");
        $UIDNO = "";
        $enc_NAME = base64_encode($userDetail->name);
        $enc_DepositorADDRESS = "";
        $enc_DIVCODE = base64_encode($division_id->DIVCODE);
        $TCODE = $division_id->TCODE;
        $enc_TCODE = base64_encode($TCODE);
        $enc_LOCATION = base64_encode(districtName($permanent_district_id));
        $REMARK = "";
        // $TOTAL = $application->amount_to_be_paid;
        $enc_TOTAL = base64_encode($TOTAL);
        $enc_REFERENCEID = base64_encode("S05");
        $enc_ServiceID = base64_encode("05");
        $SNO = "40";
        $enc_SNO = base64_encode("40");
        $HEAD = "020203101010000";
        $enc_HEAD = base64_encode("020203101010000");
        $AMOUNT = $TOTAL;
        $enc_AMOUNT = base64_encode($AMOUNT);

        $OTHER1 = $DEPCODE . $PMODE . $ASMTYEAR . $TAXPERIOD . $TCODE . $TOTAL;
        $md5OTHER1 = strtoupper(md5($OTHER1));
        $sha512other1 = strtoupper(hash("sha512", $md5OTHER1));

        $OTHER2 = $DEPCODE . $SNO . $HEAD . $AMOUNT;
        $md5OTHER2 = strtoupper(md5($OTHER2));
        $sha512other2 = strtoupper(hash("sha512", $md5OTHER2));


        $data_response = '{"DEPCODE":"' . $enc_DEPCODE . '","PMODE":"' . $enc_PMODE . '","PTYPE":"","CHQDDNO":"","CHQDT":"","ASMTYEAR":"' . $enc_ASMTYEAR . '","TAXPERIOD":"' . $enc_TAXPERIOD . '","PERIOD":"","BANK":"' . $enc_BANK . '","UIDNO":"","NAME":"' . $enc_NAME . '","DepositorADDRESS":"' . $enc_DepositorADDRESS . '","DIVCODE":"' . $enc_DIVCODE . '","TCODE":"' . $enc_TCODE . '","LOCATION":"' . $enc_LOCATION . '","REMARK":"","TOTAL":"' . $enc_TOTAL . '","REFERENCEID":"' . $enc_REFERENCEID . '","ServiceID":"' . $enc_ServiceID . '","OTHER1":"' . $sha512other1 . '","rajkoshHeadInfo":[{"SNO":"' . $enc_SNO . '","HEAD":"' . $enc_HEAD . '","AMOUNT":"' . $enc_AMOUNT . '","OTHER2":"' . $sha512other2 . '"}]}';



        $DEPCODE = 'EDU';
        $enc_DEPCODE = base64_encode('EDU');
        $PMODE = 'NETPAY';
        $enc_PMODE = base64_encode('NETPAY');
        $PTYPE = "";
        $CHQDDNO = '';
        $CHQDT = "";
        $ASMTYEAR = $DynamicASMTYEAR;
        $enc_ASMTYEAR = base64_encode($DynamicASMTYEAR);
        $TAXPERIOD = "A";
        $enc_TAXPERIOD = base64_encode("A");
        $PERIOD = "";
        $BANK = "0001";
        $enc_BANK = base64_encode("0001");
        $UIDNO = "";
        $NAME = $userDetail->name;
        $enc_NAME = base64_encode($userDetail->name);
        $enc_DepositorADDRESS = "";
        $DepositorADDRESS = "";
        $DIVCODE = $division_id->DIVCODE;
        $enc_DIVCODE = base64_encode($division_id->DIVCODE);
        $TCODE = $division_id->TCODE;
        $enc_TCODE = base64_encode($TCODE);
        $LOCATION = districtName($permanent_district_id);
        $enc_LOCATION = base64_encode(districtName($permanent_district_id));
        $REMARK = "Coaching Camp Registration";

        $enc_TOTAL = base64_encode($TOTAL);
        $REFERENCEID = "S05";
        $enc_REFERENCEID = base64_encode("S05");
        $ServiceID = "05";
        $enc_ServiceID = base64_encode("05");
        $SNO = "40";
        $enc_SNO = base64_encode("40");
        $HEAD = "020203101010000";
        $enc_HEAD = base64_encode("020203101010000");
        $AMOUNT = $TOTAL;
        $enc_AMOUNT = base64_encode($TOTAL);

        $OTHER1 = $DEPCODE . $PMODE . $ASMTYEAR . $TAXPERIOD . $TCODE . $TOTAL;
        $md5OTHER1 = strtoupper(md5($OTHER1));
        $sha512other1 = strtoupper(hash("sha512", $md5OTHER1));

        $OTHER2 = $DEPCODE . $SNO . $HEAD . $AMOUNT;
        $md5OTHER2 = strtoupper(md5($OTHER2));
        $sha512other2 = strtoupper(hash("sha512", $md5OTHER2));

        $data_set = [

            'application_no' => $basic->application_no,
            'application_id' => $basic->id,


            'DEPCODE' => $DEPCODE,
            'PMODE' => $PMODE,
            'PTYPE' => $PTYPE,
            'CHQDDNO' => $CHQDDNO,
            'CHQDT' => $CHQDT,
            'ASMTYEAR' => $ASMTYEAR,
            'TAXPERIOD' => $TAXPERIOD,
            'PERIOD' => $PERIOD,
            'BANK' => $BANK,
            'UIDNO' => $UIDNO,
            'NAME' => $NAME,
            'DepositorADDRESS' => $DepositorADDRESS,
            'DIVCODE' => $DIVCODE,
            'TCODE' => $TCODE,
            'LOCATION' => $LOCATION,
            'TOTAL' => $TOTAL,
            'REFERENCEID' => $REFERENCEID,
            'ServiceID' => $ServiceID,
            'SNO' => $SNO,
            'HEAD' => $HEAD,
            'AMOUNT' => $AMOUNT,
            'OTHER1' => $OTHER1,
            'OTHER2' => $OTHER2,
            'type' => 0,
            'module' => 4,

        ];



        $data = [
            'data_response' => $data_response,
            'data_set' => $data_set,


        ];




        return redirect('https://www.startinup.up.gov.in/demo/PaymentController/payment_gateway/' . base64_encode(json_encode($data)));
    }
    public function coaching_camp_fees(Request $request){
        $applicationview = DB::table('coaching_camp_basic_details')
                            ->join('coaching_camp_sports','coaching_camp_sports.id','=','coaching_camp_basic_details.sport')
                            ->where('user_id', Auth::guard('CoachingCamp')->user()->id)
                            ->select('coaching_camp_basic_details.*','coaching_camp_basic_details.id as basic_id','coaching_camp_sports.name as sports_name')
                            ->where('final_submit',1)->first();
        if (!$applicationview) {
            return redirect()->route('coaching_camp_application_form');
        }
        if($applicationview->payment_amount_coaching==null){
            return redirect()->route('coaching_camp_dashboard');
        }
        $form_fee=10;
        if($applicationview->payment_amount_coaching!=null && $applicationview->payment_status_coaching_fee==1){
            if(Auth::guard('CoachingCamp')->user()->user_type==2){
                $monthly_payments = DB::table('rajkosh_payment_request as r')
                                    ->join('rajkosh_payment_response as rr','rr.challan_no','=','r.Depchallan')
                                    ->where('r.application_no',$applicationview->application_no)
                                    ->where('r.module',5)
                                    ->whereYear('r.date_of_payment_attempt', now()->year)
                                    ->whereMonth('r.date_of_payment_attempt', now()->month)
                                    ->where('rr.Status','=','Success')
                                    ->where('rr.amount','>=',$applicationview->payment_amount_coaching)
                                    ->select('rr.challan_no', 'rr.amount','r.date_of_payment_attempt')
                                    ->get();
                if ($monthly_payments->count() == 0) {
                    $monthly_payment = DB::table('rajkosh_payment_request as r')
                                    ->join('rajkosh_payment_response as rr','rr.challan_no','=','r.Depchallan')
                                    ->where('r.application_no',$applicationview->application_no)
                                    ->where('r.module',5)
                                    ->whereYear('r.date_of_payment_attempt', now()->year)
                                    ->whereMonth('r.date_of_payment_attempt', now()->month)
                                    ->where('rr.Status','=','Success')
                                    ->where('rr.amount','=',10)
                                    ->select('rr.challan_no', 'rr.amount','r.date_of_payment_attempt')
                                    ->get();
                    if($monthly_payment->count()>0){
                        $form_fee=0;
                    }
                }
            }
            elseif(Auth::guard('CoachingCamp')->user()->user_type==1){
                $monthly_payments = DB::table('rajkosh_payment_request as r')
                                    ->join('rajkosh_payment_response as rr','rr.challan_no','=','r.Depchallan')
                                    ->where('r.application_no',$applicationview->application_no)
                                    ->where('r.module',5)
                                    ->whereBetween('r.date_of_payment_attempt', [Carbon::parse('2026-09-01')->startOfDay(),Carbon::parse('2027-03-31')->endOfDay()])
                                    ->where('rr.Status','=','Success')
                                    ->where('rr.amount','>=',$applicationview->payment_amount_coaching)
                                    ->select('rr.challan_no', 'rr.amount','r.date_of_payment_attempt')
                                    ->get();
                if ($monthly_payments->count() == 0) {
                    $monthly_payment = DB::table('rajkosh_payment_request as r')
                                    ->join('rajkosh_payment_response as rr','rr.challan_no','=','r.Depchallan')
                                    ->where('r.application_no',$applicationview->application_no)
                                    ->where('r.module',5)
                                    ->whereBetween('r.date_of_payment_attempt', [Carbon::parse('2026-09-01')->startOfDay(),Carbon::parse('2027-03-31')->endOfDay()])
                                    ->where('rr.Status','=','Success')
                                    ->where('rr.amount','=','10')
                                    ->select('rr.challan_no', 'rr.amount','r.date_of_payment_attempt')
                                    ->get();
                    if($monthly_payment->count()>0){
                        $form_fee=0;
                    }
                }
            }
        }

        $month = date('n'); // Numeric month without leading zero (1–12)
        $year = date('Y'); // Current year in 4 digits

        if ($month >= 4) {
            // April to December: Assessment year is current year – next year
            $DynamicASMTYEAR = $year . '-' . ($year + 1);
        } else {
            // January to March: Assessment year is previous year – current year
            $DynamicASMTYEAR = ($year - 1) . '-' . $year;
        }
        $permanent_district_id = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first()->permanent_district;
        $basic = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();
        $division_id = rajkosh_division_treasury($permanent_district_id);
        $TOTAL = $basic->payment_amount_coaching+$form_fee;
        try {

            $user = Auth::guard('CoachingCamp')->user();
            $div = $division_id;

            $total     = $TOTAL;
            $dep       = 'EDU';
            $pmode     = 'NETPAY';
            $sno       = '40';
            $head      = '020203101010000';
            $ref       = 'S05';
            $service   = '05';
            $bank      = '0001';
            $asmt      = $DynamicASMTYEAR;
            $tax       = 'A';
            $LOCATION = districtName($permanent_district_id);

            $other1 = strtoupper(hash('sha512', strtoupper(md5($dep . $pmode . $asmt . $tax . $div->TCODE . $total))));
            $other2 = strtoupper(hash('sha512', strtoupper(md5($dep . $sno . $head . $total))));

            $payload = json_encode([
                'DEPCODE' => base64_encode($dep),
                'PMODE' => base64_encode($pmode),
                'PTYPE' => '',
                'CHQDDNO' => '',
                'CHQDT' => '',
                'ASMTYEAR' => base64_encode($asmt),
                'TAXPERIOD' => base64_encode($tax),
                'PERIOD' => '',
                'BANK' => base64_encode($bank),
                'UIDNO' => '',
                'NAME' => base64_encode($user->name),
                'DepositorADDRESS' => base64_encode($basic->permanent_address),
                'DIVCODE' => base64_encode($div->DIVCODE),
                'TCODE' => base64_encode($div->TCODE),
                'LOCATION' => base64_encode($LOCATION),
                'REMARK' => '',
                'TOTAL' => base64_encode((string)$total),
                'REFERENCEID' => base64_encode($ref),
                'ServiceID' => base64_encode($service),
                'OTHER1' => $other1,
                'rajkoshHeadInfo' => [[
                    'SNO' => base64_encode($sno),
                    'HEAD' => base64_encode($head),
                    'AMOUNT' => base64_encode((string)$total),
                    'OTHER2' => $other2,
                ]]
            ], JSON_UNESCAPED_SLASHES);

            $file = storage_path('app/rajkosh.json');
            file_put_contents($file, $payload);

            $curlExe = 'C:\\Windows\\System32\\curl.exe';
            $cmd = '"' . $curlExe . '" -s -X POST '
                . '"https://rajkosh.up.nic.in/rkapi/rajkoshapi/Rajkosh" '
                . '-H "Content-Type: application/json" '
                . '-H "secret: x$p#9cbJu" '
                . '-H "key: Khel" '
                . '--data @"' . str_replace('\\', '/', $file) . '"';

            $response = shell_exec($cmd);
            @unlink($file);

            $data = json_decode($response);

            if (!isset($data[0]->Output2)) {
                Log::error('Invalid Rajkosh Response', ['response' => $response]);
                return back()->with('error', 'Payment gateway connection failed.');
            }

            $paymentUrl = $data[0]->Output2;
            $paymentChallan = explode('|', $data[0]->Output1)[0];
            $depChallan = $dep . $paymentChallan;

            DB::table('rajkosh_payment_request')->insertGetId([
                'application_no'   => $basic->application_no,
                'application_id'   => $user->id,
                'Depchallan'       => $depChallan,
                'challanNumber'    => $paymentChallan,
                'DEPCODE'          => $dep,
                'PMODE'            => $pmode,
                'PTYPE'            => '',
                'CHQDDNO'          => '',
                'CHQDT'            => '',
                'ASMTYEAR'         => $asmt,
                'TAXPERIOD'        => $tax,
                'PERIOD'           => '',
                'BANK'             => $bank,
                'UIDNO'            => '',
                'NAME'             => $user->name,
                'DepositorADDRESS' => '',
                'DIVCODE'          => $div->DIVCODE,
                'TCODE'            => $div->TCODE,
                'LOCATION'         => $LOCATION,
                'TOTAL'            => $total,
                'REFERENCEID'      => $ref,
                'ServiceID'        => $service,
                'SNO'              => $sno,
                'HEAD'             => $head,
                'AMOUNT'           => $total,
                'OTHER1'           => $other1,
                'OTHER2'           => $other2,
                'type'             => 0,
                'module'           => 5
            ]);

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Rajkosh Exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Something went wrong while initiating payment.');
        }
    }
    public function renew_coaching_camp_fees(Request $request){
        $applicationview = DB::table('coaching_camp_basic_details')
                            ->join('coaching_camp_sports','coaching_camp_sports.id','=','coaching_camp_basic_details.sport')
                            ->where('user_id', Auth::guard('CoachingCamp')->user()->id)
                            ->select('coaching_camp_basic_details.*','coaching_camp_basic_details.id as basic_id','coaching_camp_sports.name as sports_name')
                            ->where('final_submit',1)->first();
        if (!$applicationview) {
            return redirect()->route('coaching_camp_application_form');
        }
        if($applicationview->payment_amount_coaching==null){
            return redirect()->route('coaching_camp_dashboard');
        }

        $month = date('n'); // Numeric month without leading zero (1–12)
        $year = date('Y'); // Current year in 4 digits

        if ($month >= 4) {
            // April to December: Assessment year is current year – next year
            $DynamicASMTYEAR = $year . '-' . ($year + 1);
        } else {
            // January to March: Assessment year is previous year – current year
            $DynamicASMTYEAR = ($year - 1) . '-' . $year;
        }
        $permanent_district_id = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first()->permanent_district;
        $basic = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();
        $division_id = rajkosh_division_treasury($permanent_district_id);
        $TOTAL = $basic->payment_amount_coaching;
        try {

            $user = Auth::guard('CoachingCamp')->user();
            $div = $division_id;

            $total     = $TOTAL;
            $dep       = 'EDU';
            $pmode     = 'NETPAY';
            $sno       = '40';
            $head      = '020203101010000';
            $ref       = 'S05';
            $service   = '05';
            $bank      = '0001';
            $asmt      = $DynamicASMTYEAR;
            $tax       = 'A';
            $LOCATION = districtName($permanent_district_id);

            $other1 = strtoupper(hash('sha512', strtoupper(md5($dep . $pmode . $asmt . $tax . $div->TCODE . $total))));
            $other2 = strtoupper(hash('sha512', strtoupper(md5($dep . $sno . $head . $total))));

            $payload = json_encode([
                'DEPCODE' => base64_encode($dep),
                'PMODE' => base64_encode($pmode),
                'PTYPE' => '',
                'CHQDDNO' => '',
                'CHQDT' => '',
                'ASMTYEAR' => base64_encode($asmt),
                'TAXPERIOD' => base64_encode($tax),
                'PERIOD' => '',
                'BANK' => base64_encode($bank),
                'UIDNO' => '',
                'NAME' => base64_encode($user->name),
                'DepositorADDRESS' => base64_encode($basic->permanent_address),
                'DIVCODE' => base64_encode($div->DIVCODE),
                'TCODE' => base64_encode($div->TCODE),
                'LOCATION' => base64_encode($LOCATION),
                'REMARK' => '',
                'TOTAL' => base64_encode((string)$total),
                'REFERENCEID' => base64_encode($ref),
                'ServiceID' => base64_encode($service),
                'OTHER1' => $other1,
                'rajkoshHeadInfo' => [[
                    'SNO' => base64_encode($sno),
                    'HEAD' => base64_encode($head),
                    'AMOUNT' => base64_encode((string)$total),
                    'OTHER2' => $other2,
                ]]
            ], JSON_UNESCAPED_SLASHES);

            $file = storage_path('app/rajkosh.json');
            file_put_contents($file, $payload);

            $curlExe = 'C:\\Windows\\System32\\curl.exe';
            $cmd = '"' . $curlExe . '" -s -X POST '
                . '"https://rajkosh.up.nic.in/rkapi/rajkoshapi/Rajkosh" '
                . '-H "Content-Type: application/json" '
                . '-H "secret: x$p#9cbJu" '
                . '-H "key: Khel" '
                . '--data @"' . str_replace('\\', '/', $file) . '"';

            $response = shell_exec($cmd);
            @unlink($file);

            $data = json_decode($response);

            if (!isset($data[0]->Output2)) {
                Log::error('Invalid Rajkosh Response', ['response' => $response]);
                return back()->with('error', 'Payment gateway connection failed.');
            }

            $paymentUrl = $data[0]->Output2;
            $paymentChallan = explode('|', $data[0]->Output1)[0];
            $depChallan = $dep . $paymentChallan;

            DB::table('rajkosh_payment_request')->insertGetId([
                'application_no'   => $basic->application_no,
                'application_id'   => $user->id,
                'Depchallan'       => $depChallan,
                'challanNumber'    => $paymentChallan,
                'DEPCODE'          => $dep,
                'PMODE'            => $pmode,
                'PTYPE'            => '',
                'CHQDDNO'          => '',
                'CHQDT'            => '',
                'ASMTYEAR'         => $asmt,
                'TAXPERIOD'        => $tax,
                'PERIOD'           => '',
                'BANK'             => $bank,
                'UIDNO'            => '',
                'NAME'             => $user->name,
                'DepositorADDRESS' => '',
                'DIVCODE'          => $div->DIVCODE,
                'TCODE'            => $div->TCODE,
                'LOCATION'         => $LOCATION,
                'TOTAL'            => $total,
                'REFERENCEID'      => $ref,
                'ServiceID'        => $service,
                'SNO'              => $sno,
                'HEAD'             => $head,
                'AMOUNT'           => $total,
                'OTHER1'           => $other1,
                'OTHER2'           => $other2,
                'type'             => 0,
                'module'           => 4
            ]);

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            Log::error('Rajkosh Exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Something went wrong while initiating payment.');
        }
    }

    public function coaching_camp_application_coaching_fee_bkp_19_06_26(Request $request)
    {
        $month = date('n'); // Numeric month without leading zero (1–12)
        $year = date('Y'); // Current year in 4 digits

        if ($month >= 4) {
            // April to December: Assessment year is current year – next year
            $DynamicASMTYEAR = $year . '-' . ($year + 1);
        } else {
            // January to March: Assessment year is previous year – current year
            $DynamicASMTYEAR = ($year - 1) . '-' . $year;
        }




        $userDetail = Auth::guard('CoachingCamp')->user();
        $permanent_district_id = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first()->permanent_district;
        $basic = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();
        $division_id = rajkosh_division_treasury($permanent_district_id);
        $TOTAL = $basic->payment_amount_coaching;



        $DEPCODE = 'EDU';
        $enc_DEPCODE = base64_encode('EDU');
        $PMODE = 'NETPAY';
        $enc_PMODE = base64_encode('NETPAY');
        $PTYPE = "";
        $CHQDDNO = '';
        $CHQDT = "";
        $ASMTYEAR = $DynamicASMTYEAR;
        $enc_ASMTYEAR = base64_encode($DynamicASMTYEAR);
        $TAXPERIOD = "A";
        $enc_TAXPERIOD = base64_encode("A");
        $PERIOD = "";
        $enc_BANK = base64_encode("0001");
        $UIDNO = "";
        $enc_NAME = base64_encode($userDetail->name);
        $enc_DepositorADDRESS = "";
        $enc_DIVCODE = base64_encode($division_id->DIVCODE);
        $TCODE = $division_id->TCODE;
        $enc_TCODE = base64_encode($TCODE);
        $enc_LOCATION = base64_encode(districtName($permanent_district_id));
        $REMARK = "";
        // $TOTAL = $application->amount_to_be_paid;
        $enc_TOTAL = base64_encode($TOTAL);
        $enc_REFERENCEID = base64_encode("S05");
        $enc_ServiceID = base64_encode("05");
        $SNO = "40";
        $enc_SNO = base64_encode("40");
        $HEAD = "020203101010000";
        $enc_HEAD = base64_encode("020203101010000");
        $AMOUNT = $TOTAL;
        $enc_AMOUNT = base64_encode($AMOUNT);

        $OTHER1 = $DEPCODE . $PMODE . $ASMTYEAR . $TAXPERIOD . $TCODE . $TOTAL;
        $md5OTHER1 = strtoupper(md5($OTHER1));
        $sha512other1 = strtoupper(hash("sha512", $md5OTHER1));

        $OTHER2 = $DEPCODE . $SNO . $HEAD . $AMOUNT;
        $md5OTHER2 = strtoupper(md5($OTHER2));
        $sha512other2 = strtoupper(hash("sha512", $md5OTHER2));


        $data_response = '{"DEPCODE":"' . $enc_DEPCODE . '","PMODE":"' . $enc_PMODE . '","PTYPE":"","CHQDDNO":"","CHQDT":"","ASMTYEAR":"' . $enc_ASMTYEAR . '","TAXPERIOD":"' . $enc_TAXPERIOD . '","PERIOD":"","BANK":"' . $enc_BANK . '","UIDNO":"","NAME":"' . $enc_NAME . '","DepositorADDRESS":"' . $enc_DepositorADDRESS . '","DIVCODE":"' . $enc_DIVCODE . '","TCODE":"' . $enc_TCODE . '","LOCATION":"' . $enc_LOCATION . '","REMARK":"","TOTAL":"' . $enc_TOTAL . '","REFERENCEID":"' . $enc_REFERENCEID . '","ServiceID":"' . $enc_ServiceID . '","OTHER1":"' . $sha512other1 . '","rajkoshHeadInfo":[{"SNO":"' . $enc_SNO . '","HEAD":"' . $enc_HEAD . '","AMOUNT":"' . $enc_AMOUNT . '","OTHER2":"' . $sha512other2 . '"}]}';



        $DEPCODE = 'EDU';
        $enc_DEPCODE = base64_encode('EDU');
        $PMODE = 'NETPAY';
        $enc_PMODE = base64_encode('NETPAY');
        $PTYPE = "";
        $CHQDDNO = '';
        $CHQDT = "";
        $ASMTYEAR = $DynamicASMTYEAR;
        $enc_ASMTYEAR = base64_encode($DynamicASMTYEAR);
        $TAXPERIOD = "A";
        $enc_TAXPERIOD = base64_encode("A");
        $PERIOD = "";
        $BANK = "0001";
        $enc_BANK = base64_encode("0001");
        $UIDNO = "";
        $NAME = $userDetail->name;
        $enc_NAME = base64_encode($userDetail->name);
        $enc_DepositorADDRESS = "";
        $DepositorADDRESS = "";
        $DIVCODE = $division_id->DIVCODE;
        $enc_DIVCODE = base64_encode($division_id->DIVCODE);
        $TCODE = $division_id->TCODE;
        $enc_TCODE = base64_encode($TCODE);
        $LOCATION = districtName($permanent_district_id);
        $enc_LOCATION = base64_encode(districtName($permanent_district_id));
        $REMARK = "Coaching Camp Fees";

        $enc_TOTAL = base64_encode($TOTAL);
        $REFERENCEID = "S05";
        $enc_REFERENCEID = base64_encode("S05");
        $ServiceID = "05";
        $enc_ServiceID = base64_encode("05");
        $SNO = "40";
        $enc_SNO = base64_encode("40");
        $HEAD = "020203101010000";
        $enc_HEAD = base64_encode("020203101010000");
        $AMOUNT = $TOTAL;
        $enc_AMOUNT = base64_encode($TOTAL);

        $OTHER1 = $DEPCODE . $PMODE . $ASMTYEAR . $TAXPERIOD . $TCODE . $TOTAL;
        $md5OTHER1 = strtoupper(md5($OTHER1));
        $sha512other1 = strtoupper(hash("sha512", $md5OTHER1));

        $OTHER2 = $DEPCODE . $SNO . $HEAD . $AMOUNT;
        $md5OTHER2 = strtoupper(md5($OTHER2));
        $sha512other2 = strtoupper(hash("sha512", $md5OTHER2));

        $data_set = [

            'application_no' => $basic->application_no,
            'application_id' => $basic->id,


            'DEPCODE' => $DEPCODE,
            'PMODE' => $PMODE,
            'PTYPE' => $PTYPE,
            'CHQDDNO' => $CHQDDNO,
            'CHQDT' => $CHQDT,
            'ASMTYEAR' => $ASMTYEAR,
            'TAXPERIOD' => $TAXPERIOD,
            'PERIOD' => $PERIOD,
            'BANK' => $BANK,
            'UIDNO' => $UIDNO,
            'NAME' => $NAME,
            'DepositorADDRESS' => $DepositorADDRESS,
            'DIVCODE' => $DIVCODE,
            'TCODE' => $TCODE,
            'LOCATION' => $LOCATION,
            'TOTAL' => $TOTAL,
            'REFERENCEID' => $REFERENCEID,
            'ServiceID' => $ServiceID,
            'SNO' => $SNO,
            'HEAD' => $HEAD,
            'AMOUNT' => $AMOUNT,
            'OTHER1' => $OTHER1,
            'OTHER2' => $OTHER2,
            'type' => 0,
            'module' => 5,

        ];



        $data = [
            'data_response' => $data_response,
            'data_set' => $data_set,


        ];




        return redirect('https://www.startinup.up.gov.in/demo/PaymentController/payment_gateway/' . base64_encode(json_encode($data)));
    }
}
