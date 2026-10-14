<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminCoachingCampController extends Controller
{

    public function coaching_camp_list(Request $request)
    {

        $applicant_details = DB::table('coaching_camp_register')
            ->join('coaching_camp_basic_details', 'coaching_camp_register.id', '=', 'coaching_camp_basic_details.user_id')
            ->join('coaching_camp_sports','coaching_camp_sports.id','=','coaching_camp_basic_details.sport')
            ->join('studium_master','coaching_camp_basic_details.stadium','=','studium_master.id')
            ->join('cities','studium_master.district_id','=','cities.id')
            ->join('hostel_div_district_mapping','cities.id','=','hostel_div_district_mapping.district_id')
            ->join('hostel_division_master','hostel_div_district_mapping.division_id','=','hostel_division_master.id')
            ->select('coaching_camp_register.*', 'coaching_camp_basic_details.id as basic_id', 'coaching_camp_basic_details.*', 'coaching_camp_register.dob as regdob','studium_master.*','cities.*','hostel_div_district_mapping.*','hostel_division_master.*','coaching_camp_basic_details.status as form_status','coaching_camp_sports.name as sports_name')
            ->where('coaching_camp_basic_details.final_submit', 1);

        if ($request->post()){
            if($request->division_id){
                $applicant_details->where('hostel_div_district_mapping.division_id', $request->division_id);
            }
            if($request->district_id){
                $applicant_details->where('hostel_div_district_mapping.district_id', $request->district_id);
            }
            if($request->stadium){
                $applicant_details->where('coaching_camp_basic_details.stadium', $request->stadium);
            }
            if($request->sport_id){
                $applicant_details->where('coaching_camp_basic_details.sport', $request->sport_id);
            }
            if($request->subsport){
                $applicant_details->where('coaching_camp_basic_details.sub_type', $request->subsport);
            }
            if($request->gender){
                $applicant_details->where('coaching_camp_register.gender', $request->gender);
            }
        }
        $districts          =   DB::table('cities')->select('city', 'id')->where('state_id', '23')->get();
        $divisions          =   DivisionModel::select('division_name', 'id')->get();
        $stadiums           =   DB::table('studium_master')->join('cities','studium_master.district_id','=','cities.id')->where('cities.state_id','23')->select('studium_master.*')->get();
        $sports             =   DB::table('coaching_camp_sports')->orderBy('name')->get();
        if(Auth::guard('admin')->user()->admin_role==2){
            $applicant_details->where('hostel_div_district_mapping.division_id', Auth::guard('admin')->user()->division_id);
            $districts      =   DB::table('cities')
                                ->join('hostel_div_district_mapping','cities.id','=','hostel_div_district_mapping.district_id')
                                ->where('hostel_div_district_mapping.division_id', Auth::guard('admin')->user()->division_id)
                                ->select('cities.city as city' , 'cities.id as id')
                                ->where('cities.state_id', '23')
                                ->get();
            $stadiums       =   DB::table('studium_master')
                                ->join('cities','studium_master.district_id','=','cities.id')
                                ->join('hostel_div_district_mapping','cities.id','=','hostel_div_district_mapping.district_id')
                                ->where('hostel_div_district_mapping.division_id', Auth::guard('admin')->user()->division_id)
                                ->where('cities.state_id','23')
                                ->select('studium_master.*')
                                ->get();
        }
        if(Auth::guard('admin')->user()->admin_role==9){
            $applicant_details->where('hostel_div_district_mapping.district_id', Auth::guard('admin')->user()->district_id);
            $stadiums       =   DB::table('studium_master')->join('cities','studium_master.district_id','=','cities.id')->join('hostel_div_district_mapping','cities.id','=','hostel_div_district_mapping.district_id')->where('hostel_div_district_mapping.district_id', Auth::guard('admin')->user()->district_id)->where('cities.state_id','23')->get();
        }
        $applicationview    =   $applicant_details->orderByDesc('coaching_camp_register.id')->get();
        return view('admin.coaching_camp.index', compact('sports', 'districts', 'applicationview','divisions','stadiums'));
    }
    public function id_card($id)
    {
        $idcard = DB::table('coaching_camp_basic_details as a')
                    ->join('coaching_camp_sports','coaching_camp_sports.id','=','a.sport')
                    ->join('coaching_camp_register as c','c.id','=','a.user_id')
                    ->join('studium_master as b', 'a.stadium','=', 'b.id')
            ->select('a.*', 'b.studium_name as stadium_name','c.*','coaching_camp_sports.name as sports_name')
            ->where('a.user_id', $id)->first();

        return view('admin.coaching_camp.idcard', compact('idcard'));
    }
    public function stadium_sports_mapping(Request $request){
        $divisions              =   DivisionModel::select('division_name', 'id')->get();
        $districts              =   DB::table('cities')->select('city', 'id')->where('state_id', '23')->get();
        $stadiums               =   DB::table('studium_master')->get();
        $stadium                =   DB::table('studium_master')->limit(1)->first();
        $sports                 =   DB::table('coaching_camp_sports')->get();
        if(Auth::guard('admin')->user()->admin_role==2){
            $stadiums           =   DB::table('studium_master')->where('studium_master.division_id','=',Auth::guard('admin')->user()->division_id)->get();
            $stadium            =   DB::table('studium_master')->where('studium_master.division_id','=',Auth::guard('admin')->user()->division_id)->limit(1)->first();
        }elseif(Auth::guard('admin')->user()->admin_role==9){
            $stadiums           =   DB::table('studium_master')->where('studium_master.district_id','=',Auth::guard('admin')->user()->district_id)->get();
            $stadium            =   DB::table('studium_master')->where('studium_master.district_id','=',Auth::guard('admin')->user()->district_id)->limit(1)->first();
        }
        if ($request->post()){

            if($request->division_id){
                $division          =   DivisionModel::select('division_name', 'id')->where('id',$request->division_id)->first();
            }
            if($request->district_id){
                $district           =   DB::table('cities')->select('city','id')->where('id',$request->district_id)->first();
            }
            if($request->stadium){
                $stadium           =   DB::table('studium_master')->where('id', $request->stadium)->first();
            }
        }

        return view('admin.coaching_camp.stadium_sports_mapping',compact('sports','stadiums','divisions','districts','stadium'));
    }
    public function admin_coaching_camp_challan(Request $request)
    {
        // $challan=DB::table('rajkosh_payment');
        $districts = DB::table('cities')->where('state_id', '23')->get();
        // $userDetails = Auth::guard('CoachingCamp')->user();
        // dd( $userDetails);
        $sportList = array();


        // $downloadpdfname = $userDetails->name . "_Receipt";
        // $name  = $downloadpdfname . "_" . date('d-m-Y') . '.pdf';
        $userDetails =  DB::table('rajkosh_payment_response')
                       ->join('rajkosh_payment_request', 'rajkosh_payment_request.Depchallan', '=', 'rajkosh_payment_response.challan_no')
                       ->join('coaching_camp_basic_details', 'rajkosh_payment_request.application_no', '=', 'coaching_camp_basic_details.application_no')
                       ->join('coaching_camp_register', 'coaching_camp_basic_details.user_id', '=', 'coaching_camp_register.id')
                       ->where('rajkosh_payment_response.challan_no',(string)$request->challan)
                       ->select('coaching_camp_basic_details.*','coaching_camp_register.*','rajkosh_payment_response.*','rajkosh_payment_request.Depchallan','rajkosh_payment_response.amount as paid_amount')
                       ->first();
        $pdf = Pdf::loadView('coaching_camp.challan', compact('districts','sportList','userDetails'));
        $name = $userDetails->name . "_Coaching_Camp_Receipt_" . date('d-m-Y') . '.pdf';

        return $pdf->stream($name);
        // return view('coaching_camp.challan',compact('districts','sportList','userDetails'));
    }

    public function coaching_camp_view_details($id)
    {
        //$applicationview = DB::table('gymnasium_swimming_basic_detail')->where('user_id',$id)->first();
        $applicationview = DB::table('coaching_camp_basic_details')
            ->join('coaching_camp_sports','coaching_camp_sports.id','=','coaching_camp_basic_details.sport')
            ->join('coaching_camp_register', 'coaching_camp_register.id', '=', 'coaching_camp_basic_details.user_id')
            ->join('studium_master as b', 'coaching_camp_basic_details.stadium', '=', 'b.id')
            ->select('coaching_camp_register.*', 'coaching_camp_basic_details.id as basic_id', 'coaching_camp_basic_details.*', 'b.studium_name as stadium_name', 'coaching_camp_register.dob as regdob','b.district_id as stadium_district','coaching_camp_basic_details.status as form_status','coaching_camp_sports.name as sports_name')
            ->where('coaching_camp_basic_details.final_submit', 1)->where('coaching_camp_basic_details.id', $id)->first();
        // $dob   = Carbon::parse($applicationview->regdob);

        // // End of current financial year
        // $fyEndYear        = $today->month > 3 ? $today->year + 1 : $today->year;
        // $financialYearEnd = Carbon::create($fyEndYear, 3, 31);

        // $ageToday   = $dob->age;
        // $ageAtFyEnd = $dob->diffInYears($financialYearEnd);

        // if ($ageToday >= 18) {
        //     // Already above 18
        //     $validity = $today->copy()->addMonth()->format('Y-m-d');

        // } elseif ($ageAtFyEnd < 18) {
        //     // Will still be under 18 at FY end
        //     $validity = $financialYearEnd->format('Y-m-d');

        // } else {
        //     // Turns 18 before/on FY end
        //     $validity = $dob->copy()->addYears(18)->format('Y-m-d');
        // }
        if(in_array($applicationview->sport, [9, 23, 24, 29, 43, 53, 54, 55])){
            $validity = Carbon::now()->endOfMonth()->format('Y-m-d');
        }
        else{
            if($applicationview->user_type==1){
                $today = Carbon::today();
                $fyEndYear              = $today->month > 3 ? $today->year + 1 : $today->year;
                $financialYearEnd       = Carbon::create($fyEndYear, 3, 31);
                $validity               = $financialYearEnd->format('Y-m-d');
            }
            elseif($applicationview->user_type==2){
                $validity = Carbon::now()->endOfMonth()->format('Y-m-d');
            }
        }
        $payment_requests = DB::table('rajkosh_payment_request')->where('application_no',$applicationview->application_no)->where('module',5)->get();
        if($payment_requests){
            $challanNumbers = DB::table('rajkosh_payment_request')
            ->where('application_no', $applicationview->application_no)
            ->where('module', 5)
            ->pluck('Depchallan')
            ->toArray();
            $payment_responses = DB::table('rajkosh_payment_response')->whereIn('challan_no', $challanNumbers)->where('Status','=','Success')->get();
            return view('admin.coaching_camp.basic_detail',compact('applicationview','payment_responses','validity'));
        }
        return view('admin.coaching_camp.basic_detail', compact('applicationview', 'validity'));
    }

    public function coaching_camp_report(Request $request){
        // 1. Initialize query with Conditional Aggregation & New Columns
        $query = DB::table('coaching_camp_register as cr')
            ->join('coaching_camp_basic_details as cb', 'cr.id', '=', 'cb.user_id')
            ->join('rajkosh_payment_request as rr', 'rr.application_no', '=', 'cb.application_no')
            ->join('rajkosh_payment_response as r', 'rr.Depchallan', '=', 'r.challan_no')
            ->join('cities as c', 'cb.stad_district', '=', 'c.id')
            ->join('hostel_div_district_mapping as hm', 'c.id', '=', 'hm.district_id')
            ->join('hostel_division_master as hdm', 'hm.division_id', '=', 'hdm.id')
            ->select(
                'hdm.division_name',
                'c.city',
                DB::raw('MONTH(rr.date_of_payment_attempt) as month'),
                DB::raw('YEAR(rr.date_of_payment_attempt) as year'),

                // Sum 1: sport is 9
                DB::raw('SUM(CASE WHEN cb.sport = 9 THEN rr.AMOUNT ELSE 0 END) as tarantal'),

                // Sum 2: user_type is 1 AND sport is not 9, 54, 55, or 56
                DB::raw('SUM(CASE WHEN cr.user_type = 1 AND cb.sport NOT IN (9, 54, 55, 56) THEN rr.AMOUNT ELSE 0 END) as prashiksharthi'),

                // Sum 3: user_type is 2 AND sport is not 9
                DB::raw('SUM(CASE WHEN cr.user_type = 2 AND cb.sport != 9 THEN rr.AMOUNT ELSE 0 END) as shaukiya')
            )
            ->where('r.Status', 'Success');

        if ($request->isMethod('post')) {
            if ($request->filled('division_id')) {
                $query->where('hm.division_id', $request->division_id);
            }

            if ($request->filled('district_id')) {
                $query->where('hm.district_id', $request->district_id);
            }

            if ($request->filled('sport_id')) {
                $query->where('cb.sport', $request->sport_id);
            }

            if ($request->filled('gender')) {
                $query->where('cb.gender', $request->gender);
            }

            // Independent Date Logic
            $fromDate = $request->filled('from_date') ? $request->from_date : '2026-09-01';
            $toDate = $request->filled('to_date') ? $request->to_date : now()->toDateString();

            $query->whereBetween('rr.date_of_payment_attempt', [$fromDate, $toDate]);
        }

        // 3. Group by Division, District, Year, and Month
        $results = $query
            ->groupBy(
                'hdm.division_name',
                'c.city',
                DB::raw('YEAR(rr.date_of_payment_attempt)'),
                DB::raw('MONTH(rr.date_of_payment_attempt)')
            )
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('hm.division_id', 'asc')
            ->orderBy('hm.district_id', 'asc')
            ->get();
        $districts          =   DB::table('cities')->select('city', 'id')->where('state_id', '23')->get();
        $divisions          =   DivisionModel::select('division_name', 'id')->get();
        $stadiums           =   DB::table('studium_master')->join('cities','studium_master.district_id','=','cities.id')->where('cities.state_id','23')->select('studium_master.*')->get();
        $sports             =   DB::table('coaching_camp_sports')->orderBy('name')->get();
        return view('admin.coaching_camp.report',compact('districts','divisions','stadiums','sports','results'));
    }

    public function applicationUpdateStatus(Request $request)
    {
        $id     = $request->id;
        $status = $request->status;
        $data   = [
            'status'                  => $status,
            'payment_amount_coaching' => $request->payment_amount,
            'protsahan_amount'        => $request->protsahan_amount,
            'remark'                  => $request->remark,
            'status_on'               => date('Y-m-d H:i:s'),
            'status_updated_by'       => Auth::guard('admin')->user()->id,
        ];
        DB::table('coaching_camp_basic_details')->where('id', $id)->update($data);

        return response()->json([
            'error' => false,
            'msg'   => 'Application status updated successfully.',
            'url'   => route('coaching_camp_view_details', $id),
        ]);

    }
    public function cancelRegistration(Request $request)
    {
        $id     = $request->id;
        $status = 2;
        $data   = [
            'validity'          => date('Y-m-d'),
            'active'            => 0,
            'status'            => $status,
            'cancel_reason'     => $request->cancel_reason,
            'status_on'         => date('Y-m-d H:i:s'),
            'status_updated_by' => Auth::guard('admin')->user()->id,
        ];
        DB::table('coaching_camp_basic_details')->where('id', $id)->update($data);

        return response()->json([
            'error' => false,
            'msg'   => 'Application status updated successfully.',
            'url'   => route('coaching_camp_view_details', $id),
        ]);
    }
    public function activateMembership(Request $request)
    {
        $id     = $request->id;
        $data   = [
            'validity'          => $request->validity,
            'payment_date'      => $request->payment_date,
            'payment_status'    => 1,
            'active'            =>  1,
            'status_on'         => date('Y-m-d H:i:s'),
            'status_updated_by' => Auth::guard('admin')->user()->id,
        ];
        DB::table('coaching_camp_basic_details')->where('id', $id)->update($data);
        $new_data = [
            'user_id'           =>  $id,
            'membership_type'   =>  $request->membership_type,
            'protsahan_fees'    =>  $request->protsahan_fees,
            'coaching_fees'     =>  $request->coaching_fees,
            'validity_from'     =>  date('Y-m-d'),
            'validity_to'       =>  $request->validity,
            'created_by'        =>  Auth::guard('admin')->user()->id,
            'stadium_id'        =>  $request->stadium_id,
            'district_id'       =>  $request->district_id,
        ];
        DB::table('coaching_camp_membership')->insert($new_data);

        return response()->json([
            'error' => false,
            'msg'   => 'Membership Activated Successfully.',
            'url'   => route('coaching_camp_view_details', $id),
        ]);
    }
    public function application_form_update(Request $request){
        if($request->post()){
            $id                             =   $request->id;
            $user_type                      =   $request->user_type;
            $validation                     =   Validator::make($request->all(), [
                'sport'                             => 'required',
                'father_name'                       => 'required',
                'mother_name'                       => 'required',
                'religion'                          => 'required',

                'category'                          => 'required',
                'permanent_address'                 => 'required',
                'permanent_state'                   => 'required',
                'permanent_district'                => 'required',
                'permanent_pin'                     => 'required',
                'correspondence_address'            => 'required',
                'correspondence_state'              => 'required',
                'correspondence_district'           => 'required',
                'correspondence_pin'                => 'required',
                'stad_district'                     => 'required',
                'stadium'                           => 'required',
                'is_applicant_suffering_disease'    => 'nullable',
                'dob'                               => 'required|date',

                'profile_picture'                   => 'nullable|mimes:png,jpg|max:2000',
                'signature'                         => 'nullable|mimes:png,jpg|max:2000',
                'aadhaar_card_photo'                => 'nullable|mimes:pdf|max:2000',
                'medical_certificate'               => 'nullable|mimes:pdf|max:2000',

                'height'                            => 'required',
                'weight'                            => 'required',
                'visible_identification_mark'       => 'required',
                'blood_group'                       => 'nullable',
            ],
            [
                'profile_picture.mimes'             => 'Profile Picture must be a PNG or JPG file. / प्रोफाइल फोटो केवल PNG या JPG फ़ॉर्मेट में होनी चाहिए।',
                'profile_picture.max'               => 'Profile Picture size must not exceed 2 MB. / प्रोफाइल फोटो का आकार 2 MB से अधिक नहीं होना चाहिए।',

                'signature.mimes'                   => 'Signature must be a PNG or JPG file. / हस्ताक्षर केवल PNG या JPG फ़ॉर्मेट में होने चाहिए।',
                'signature.max'                     => 'Signature size must not exceed 2 MB. / हस्ताक्षर का आकार 2 MB से अधिक नहीं होना चाहिए।',

                'aadhaar_card_photo.mimes'          => 'Aadhaar Card file must be in PDF format. / आधार कार्ड केवल PDF फ़ॉर्मेट में होना चाहिए।',
                'aadhaar_card_photo.max'            => 'Aadhaar Card file size must not exceed 2 MB. / आधार कार्ड फ़ाइल का आकार 2 MB से अधिक नहीं होना चाहिए।',

                'medical_certificate.mimes'         => 'Medical Certificate file must be in PDF format. / मेडिकल सर्टिफिकेट केवल PDF फ़ॉर्मेट में होना चाहिए।',
                'medical_certificate.max'           => 'Medical Certificate file size must not exceed 2 MB. / मेडिकल सर्टिफिकेट फ़ाइल का आकार 2 MB से अधिक नहीं होना चाहिए।',
            ]);
            if ($validation->fails())
                return response()->json(['error'    => true, 'msg' => $validation->errors()->first()]);

            if ($request->sport) {
                $hasSubSports                       = DB::table('sub_sport_type')->where('sport_id', $request->sport)->exists();
                if ($hasSubSports && empty($request->sub_type)) {
                    return response()->json(['error' => true, 'msg' => 'Please select Sub-Sport Type./कृपया उप-खेल प्रकार का चयन करें।']);
                }
            }
            if($user_type==1){
                $validation                         = Validator::make($request->all(), [
                    'parent_sign'                   => 'required|mimes:png,jpg|max:2000',
                ],
                [
                    'parent_sign.mimes'             => 'Parent Sign must be a PNG or JPG file. / अभिभावक के हस्ताक्षर की फ़ाइल केवल PNG या JPG प्रारूप में होनी चाहिए।',
                    'parent_sign.max'               => 'Parent Sign size must not exceed 2 MB. / अभिभावक के हस्ताक्षर का आकार 2 MB से अधिक नहीं होना चाहिए।',
                ]);
                if ($validation->fails())
                    return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
            }
            if($user_type==2){
                $validation                         = Validator::make($request->all(), [
                    'time_slot_pref'                => 'required',
                ]);
                if ($validation->fails())
                    return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
            }
            $data = [
                'user_id'                           => Auth::guard('CoachingCamp')->user()->id,
                'sport'                             => $request->sport,
                'sub_type'                          => $request->sub_type ?? null,
                'father_name'                       => $request->father_name,
                'mother_name'                       => $request->mother_name,
                'religion'                          => $request->religion,
                'dob'                               => $request->dob,

                'category'                          => $request->category,
                'permanent_address'                 => $request->permanent_address,
                'permanent_state'                   => $request->permanent_state,
                'permanent_district'                => $request->permanent_district,
                'permanent_pin'                     => $request->permanent_pin,
                'correspondence_address'            => $request->correspondence_address,
                'correspondence_state'              => $request->correspondence_state,
                'correspondence_district'           => $request->correspondence_district,
                'correspondence_pin'                => $request->correspondence_pin,
                'stadium'                           => $request->stadium,
                'stad_district'                     => $request->stad_district,
                'blood_group'                       => $request->blood_group,
                'is_applicant_suffering_disease'    => $request->is_applicant_suffering_disease,
                'height'                            => $request->height,
                'weight'                            => $request->weight,
                'visible_identification_mark'       => $request->visible_identification_mark,
            ];
            if($user_type==2){
                $data['time_slot_pref']             = $request->time_slot_pref;
            }

            if ($request->hasFile('profile_picture')) {
                $profile_picture                    = moveFile('coaching_camp/profile_picture', $request->profile_picture);
                $data['profile_picture']            = $profile_picture;
            }

            if ($request->is_applicant_suffering_disease == 1 && $request->hasFile('medical_certificate')) {
                $medical_certificate                = moveFile('coaching_camp/medical_certificate', $request->medical_certificate);
                $data['medical_certificate']        = $medical_certificate;
            } elseif ($request->is_applicant_suffering_disease == 0) {
                $data['medical_certificate']        = '';
            }

            if ($request->hasFile('aadhar_card_photo')) {
                $aadhar_card_photo                  = moveFile('coaching_camp/aadhar_card_photo', $request->aadhar_card_photo);
                $data['aadhar_card_photo']          = $aadhar_card_photo;
            }

            if ($request->hasFile('signature')) {
                $signature                          = moveFile('coaching_camp/signature', $request->signature);
                $data['signature']                  = $signature;
            }
            if ($request->hasFile('parent_sign')) {
                $parent_sign                        = moveFile('coaching_camp/parent_sign', $request->parent_sign);
                $data['parent_sign']                = $parent_sign;
            }

            $applicationview = DB::table('coaching_camp_basic_details')->where('user_id', $id)->first();
            $update                                 = DB::table('coaching_camp_basic_details')->where('user_id', $id)->update($data);
            return response()->json(['error' => false, 'msg' => 'Application Basic Detail Submitted Successfully.', "url" => route('coaching_camp_application_preview')]);
        }else{
            $id                                     =   $request->id;
            $applicationview                        =   DB::table('coaching_camp_basic_details')
                                                        ->join('coaching_camp_sports','coaching_camp_sports.id','=','coaching_camp_basic_details.sport')
                                                        ->join('coaching_camp_register', 'coaching_camp_register.id', '=', 'coaching_camp_basic_details.user_id')
                                                        ->join('studium_master as b', 'coaching_camp_basic_details.stadium', '=', 'b.id')
                                                        ->select('coaching_camp_register.*', 'coaching_camp_basic_details.id as basic_id', 'coaching_camp_basic_details.*', 'b.studium_name as stadium_name', 'coaching_camp_register.dob as regdob','b.district_id as stadium_district','coaching_camp_basic_details.status as form_status','coaching_camp_sports.name as sports_name')
                                                        ->where('coaching_camp_basic_details.final_submit', 1)->where('coaching_camp_basic_details.id', $id)->first();

            $sport                                  =   DB::table('coaching_camp_sports')->orderBy('name')->get();
            $states                                 =   DB::table('states')->orderBy('name')->get();

            $districts                              =   DB::table('cities')->where('state_id', 23)->orderBy('city')->get();
            $stadium                                =   DB::table('studium_master')->get();
            $cities                                 =   DB::table('cities')->where('state_id', 23)->where('isp_state_code','!=',0)->orderBy('city')->get();
            $districtall                            =   DB::table('cities')->orderBy('city')->get();
            $allSubSports                           =   DB::table('sub_sport_type')->get();

            $dob                                    =   Carbon::parse($applicationview->dob);
            $age                                    =   $dob->age;
            return view('admin.coaching_camp.edit_application_form', compact('stadium', 'sport', 'applicationview', 'states', 'districts', 'districtall','age','allSubSports','cities'));

        }
    }

}
