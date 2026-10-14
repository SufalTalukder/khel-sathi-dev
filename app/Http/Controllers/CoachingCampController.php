<?php

namespace App\Http\Controllers;

use App\Events\SmsMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CoachingCamp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CoachingCampController extends Controller
{
    public function coaching_camp_register()
    {
        $Code1 = rand(11, 99);
        $Code2 = rand(11, 99);
        $capchaCode = $Code1 +  $Code2;
        session()->put('capchaCode', $capchaCode);
        return view('coaching_camp.register',  compact('capchaCode', 'Code1', 'Code2'));
    }

    public function coaching_camp_otp()
    {
        return view('coaching_camp.otp');
    }

    public function coaching_camp_login()
    {

        $Code1 = rand(11, 99);
        $Code2 = rand(11, 99);
        $capchaCode = $Code1 +  $Code2;
        session()->put('capchaCode', $capchaCode);

        return view('coaching_camp.login', compact('capchaCode', 'Code1', 'Code2'));
    }

    public function coaching_camp_forget_password()
    {
        return view('coaching_camp.forget_password');
    }

    public function coaching_camp_change_password()
    {
        return view('coaching_camp.change_password');
    }

    public function coaching_camp_application_form()
    {
        $applicationview        =   DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->where('final_submit','=',1)->first();
        if ($applicationview) {
            return redirect()->route('coaching_camp_dashboard');
        }
        $sport                  =   DB::table('coaching_camp_sports')->orderBy('name')->get();
        $applicationview        =   DB::table('coaching_camp_basic_details')
                                    ->join('coaching_camp_sports','coaching_camp_sports.id','=','coaching_camp_basic_details.sport')
                                    ->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();
        $states                 =   DB::table('states')->orderBy('name')->get();

        $districts              =   DB::table('cities')->where('state_id', 23)->orderBy('city')->get();
        $stadium                =   DB::table('studium_master')->get();
        $cities                 =   DB::table('cities')->where('state_id', 23)->where('isp_state_code','!=',0)->orderBy('city')->get();
        $districtall            =   DB::table('cities')->orderBy('city')->get();
        $allSubSports           =   DB::table('sub_sport_type')->get();

        $dob                    =   Carbon::parse(Auth::guard('CoachingCamp')->user()->dob);

        $age                    =   $dob->age;
        return view('coaching_camp.application_form', compact('stadium', 'sport', 'applicationview', 'states', 'districts', 'districtall','age','allSubSports','cities'));
    }

    public function coaching_camp_application_preview()
    {
        $applicationview = DB::table('coaching_camp_basic_details as a')
                            ->join('coaching_camp_sports','coaching_camp_sports.id','=','a.sport')
                            ->leftJoin('studium_master as b', 'a.stadium', 'b.id')
                            ->select('a.*', 'b.studium_name as stadium_name','coaching_camp_sports.name as sports_name')
                            ->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();

        return view('coaching_camp.application_preview', compact('applicationview'));
    }


    public function coaching_camp_dashboard()
    {


        // if (Auth::guard('CoachingCamp')->user()->change_password  != 1) {
        //     return redirect()->route('coaching_camp_change_password');
        // }
        $repay=0;
        $applicationview = DB::table('coaching_camp_basic_details')
                            ->join('coaching_camp_sports','coaching_camp_sports.id','=','coaching_camp_basic_details.sport')
                            ->where('user_id', Auth::guard('CoachingCamp')->user()->id)
                            ->select('coaching_camp_basic_details.*','coaching_camp_basic_details.id as basic_id','coaching_camp_sports.name as sports_name')
                            ->where('final_submit',1)->first();
        if (!$applicationview) {
            return redirect()->route('coaching_camp_application_form');
        }
        $payment_requests = DB::table('rajkosh_payment_request')->where('application_no',$applicationview->application_no)->where('module',5)->get();
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
                if ($monthly_payments->count() < 1) {
                    $repay=1;
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
                if ($monthly_payments->count() < 1) {
                    $repay=1;
                }
            }
        }
        if($payment_requests){
            $challanNumbers = DB::table('rajkosh_payment_request')
            ->where('application_no', $applicationview->application_no)
            ->where('module', 5)
            ->pluck('Depchallan')
            ->toArray();
            $payment_responses = DB::table('rajkosh_payment_response')->whereIn('challan_no', $challanNumbers)->where('Status','=','Success')->get();
            return view('coaching_camp.dashboard',compact('applicationview','payment_responses','repay'));
        }
        return view('coaching_camp.dashboard', compact('applicationview'));
    }


    public function coaching_camp_regsiter_store(Request $request)
    {
        $user = CoachingCamp::where('email', $request->email)->where('verified', 1)->first();
        if ($user) {
            return response()->json(["error" => true, "msg" => "Already Registered with this Email."]);
        }
        $dob = Carbon::parse($request->dob);
        $age = $dob->age;
        $userType = $age < 18 ? 1 : 2;

        $checkEmail_for_registered = DB::table('coaching_camp_register')->where('email', $request->email)->first();
        if ($checkEmail_for_registered  && $checkEmail_for_registered->verified == 0) {
            $validation = Validator::make($request->all(), [
                'name' => 'required|max:100',
                'email' => 'required|email',
                'captchacode' => 'required',
                'captcha' => 'required',
                'aadhar' => 'required',
                'gender' => 'required',
                'dob' => 'required|date',
                'mobile' => 'required|numeric|digits:10',
                'new_password' => [
                    'required',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,15}$/'
                ],
                'confirm_password' => 'required|min:8|same:new_password',]
                ,[
                    'new_password.required' => 'Password is required.',
                    'new_password.regex' => 'Password must be between 8 and 15 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
                ]);
            if ($validation->fails())
                return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
            if ($request->captcha != $request->captchacode) {
                return response()->json(["error" => true, "msg" => "Oops! Invalid Captcha Code."]);
            }

            //$otp = 123456;
            $otp = rand(111111, 999999);
            session()->put('email', $request->email);
            session()->put('mobile', $request->mobile);

            SmsMail::dispatch([
                "otp" => $otp,
                "email" => $request->email,
                "mobile" => $request->mobile
            ], 1);

            $data = [
                'name'                  => $request->name,
                'decoded_password'      => $request->new_password,
                'password'              => Hash::make($request->new_password),
                'email'                 => $request->email,
                'gender'                => $request->gender,
                'mobile'                => $request->mobile,
                'aadhar'                => $request->aadhar,
                'dob'                   => $request->dob,
                'otp'                   => $otp,
                'user_type'             => $userType,
            ];

            DB::table('coaching_camp_register')->where('id', $checkEmail_for_registered->id)->update($data);

            return response()->json(["error" => false, "msg" => "OTP Sent Successfully./ओटीपी सफलतापूर्वक अग्रेषित कर दिया गया है।",  "url" => route('coaching_camp_otp')]);

            //return redirect('CoachingCampotp'.base64_encode($checkEmail_for_registered->id))->with('error', 'Oops! Varification is pending for this email id, Please varify the OTP.');
        }
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:100',

            'email' => 'required|email',
            'captchacode' => 'required',
            'gender' => 'required',
            'captcha' => 'required',
            'dob' => 'required|date',
            'aadhar' => 'required|unique:coaching_camp_register',
            'mobile' => 'required|numeric|digits:10',
            'new_password' => [
                'required',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,15}$/'
            ],
            'confirm_password' => 'required|min:8|same:new_password',]
            ,[
                'new_password.required' => 'Password is required.',
                'new_password.regex' => 'Password must be between 8 and 15 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&#).',
            ]);
        if ($validation->fails())
            return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);

        if ($request->captcha != $request->captchacode) {
            return response()->json(["error" => true, "msg" => "Oops! Invalid Captcha Code."]);
        }
        $otp = rand(111111, 999999);
        //$otp = 123456;
        // $randomPassword =   rand(11111111, 99999999);

        $data = [
            'name' => $request->name,
            'otp' => $otp,
            'email' => $request->email,
            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'aadhar' => $request->aadhar,
            'dob' => $request->dob,
            'decoded_password' => $request->new_password,
            'password' => Hash::make($request->new_password),
            'user_type'             => $userType,
        ];

        $registerUser = CoachingCamp::create($data);

        session()->put('email', $request->email);
        session()->put('mobile', $request->mobile);
        session()->put('form_type', 1);
        SmsMail::dispatch([
            "otp" => $otp,
            "email" => $request->email,
            "mobile" => $request->mobile
        ], 1);
        return response()->json(["error" => false, "msg" => "OTP Sent Successfully./ओटीपी सफलतापूर्वक अग्रेषित कर दिया गया है।", "url" => route('coaching_camp_otp')]);
    }

    public function cp_refresh()
    {
        $Code1 = rand(11, 99);
        $Code2 = rand(11, 99);
        $capchaCode = $Code1 +  $Code2;
        session()->put('capchaCode', $capchaCode);
        $data['capchaCode'] = $capchaCode;
        $data['Code1'] = $Code1;
        $data['Code2'] = $Code2;
        return response()->json($data);
    }

    public function coaching_camp_otpStore(Request $req)
    {
        $otp = $req->otp1 . $req->otp2 . $req->otp3 . $req->otp4 . $req->otp5 . $req->otp6;
        $email = session()->get('email');

        $user = CoachingCamp::where('email', $email)->where('otp', $otp)->first();

        if ($user) {
            $user->verified = 1;
            $user->created_on = now();

            $user->save();
            SmsMail::dispatch(["id" => $user->id],  20);
            return response()->json(["error" => false, "msg" => "Successfully Registered on the Portal./पोर्टल पर सफलतापूर्वक पंजीकृत हो गए हैं।", "url" => route('coaching_camp_login')]);
        }
        return response()->json(["error" => true, "msg" => "Oops! Invalid OTP  Please try again ."]);
    }




    public function coaching_camp_resendotp()
    {
        // $otp = 123456;
        $otp = rand(111111, 999999);

        CoachingCamp::where('email', session()->get('email'))->update(
            ["otp" => $otp]
        );

        SmsMail::dispatch([
            "otp" => $otp,
            "email" => session()->get('email'),
            "mobile" => session()->get('mobile')
        ], 1);
        return response()->json(["error" => false, "msg" => "Otp Resend Successfully."]);
    }

    public function loginStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
        $user = CoachingCamp::where('email', $request->email)->where('verified', 0)->first();
        if ($user) {
            return response()->json(["error" => true, "msg" => "Please Register First."]);
        }

        if ($request->captcha != $request->captchacode) {

            return response()->json(["error" => true, "msg" => "Oops! Invalid Captcha Code."]);
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',

        ]);
        Auth::guard('CoachingCamp')->attempt($credentials);
        if (Auth::guard('CoachingCamp')->attempt($credentials)) {
            CoachingCamp::where('email', $request->email)->first()->update(['last_login' => date('Y-m-d H:i:s')]);
            return response()->json(["error" => false, "msg" => "Logged In Successfully./सफलतापूर्वक लॉगिन हो गए हैं।", "url" => route('coaching_camp_dashboard')]);

            // if (Auth::guard('CoachingCamp')->user()->change_password == 1) {
            //     return response()->json(["error" => false, "msg" => "Logged In Successfully./सफलतापूर्वक लॉगिन हो गए हैं।", "url" => route('coaching_camp_dashboard')]);
            // } else {

            //     return response()->json(["error" => false, "msg" => "Logged In Successfully./सफलतापूर्वक लॉगिन हो गए हैं।", "url" => route('coaching_camp_change_password')]);
            // }
        }
        return response()->json(["error" => true, "msg" => "The provided credentials does not match our records"]);
    }




    public function changepasswordStore(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8|different:old_password',
            'confirm_password' => 'required|min:8|same:new_password',
        ]);
        if ($validation->fails())
            //   return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
            return redirect()->route('coaching_camp_login')->with('error',  $validation->errors()->first());

        $user = Auth::guard('CoachingCamp')->user();
        //    if ($user->decoded_password != $request->old_password) {
        //     return response()->json(['error' => true, 'msg' => 'Old Password Does Not Match Our Record.']);
        //    }

        $userUpdate = CoachingCamp::find($user->id);



        $data = [
            'decoded_password' => $request->new_password,
            'password' => Hash::make($request->new_password),
            'change_password' => 1
        ];

        $userUpdate->update($data);
        Auth::guard('CoachingCamp')->logout();
        // return response()->json(['error' => false, 'msg' => 'Password Changed Successfully./पासवर्ड सफलतापूर्वक बदल दिया गया है।', 'url'=> route('coaching_camp_login')]);
        return redirect()->route('coaching_camp_login')->with('success', 'Password Changed Successfully./पासवर्ड सफलतापूर्वक बदल दिया गया है।');
    }



    public function forgotStore(Request $req)
    {
        $user = CoachingCamp::where('email', $req->email)->first();


        if ($user) {

            SmsMail::dispatch(["id" => $user->id],  20);
            return response()->json(["error" => false, "msg" => "Password has been sent on the registered Mobile No./Email ID./पासवर्ड पंजीकृत मोबाइल नंबर/ईमेल आईडी पर भेज दिया गया है।", "url" => route('coaching_camp_login')]);
        }
        return response()->json(["error" => true, "msg" => "Email does not exists"]);
    }


    public function application_form_store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'sport' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'religion' => 'required',

            'category' => 'required',
            'permanent_address' => 'required',
            'permanent_state' => 'required',
            'permanent_district' => 'required',
            'permanent_pin' => 'required',
            'correspondence_address' => 'required',
            'correspondence_state' => 'required',
            'correspondence_district' => 'required',
            'correspondence_pin' => 'required',
            'stad_district' => 'required',
            'stadium' => 'required',
            'is_applicant_suffering_disease' => 'nullable',
            'dob' => 'required|date',

            'profile_picture' => 'nullable|mimes:png,jpg|max:2000',
            'signature' => 'nullable|mimes:png,jpg|max:2000',
            'aadhaar_card_photo' => 'nullable|mimes:pdf|max:2000',
            'medical_certificate' => 'nullable|mimes:pdf|max:2000',

            'height' => 'required',
            'weight' => 'required',
            'visible_identification_mark' => 'required',
            'blood_group' => 'nullable',
        ],
        [
            'profile_picture.mimes' => 'Profile Picture must be a PNG or JPG file. / प्रोफाइल फोटो केवल PNG या JPG फ़ॉर्मेट में होनी चाहिए।',
            'profile_picture.max'   => 'Profile Picture size must not exceed 2 MB. / प्रोफाइल फोटो का आकार 2 MB से अधिक नहीं होना चाहिए।',

            'signature.mimes'       => 'Signature must be a PNG or JPG file. / हस्ताक्षर केवल PNG या JPG फ़ॉर्मेट में होने चाहिए।',
            'signature.max'         => 'Signature size must not exceed 2 MB. / हस्ताक्षर का आकार 2 MB से अधिक नहीं होना चाहिए।',

            'aadhaar_card_photo.mimes' => 'Aadhaar Card file must be in PDF format. / आधार कार्ड केवल PDF फ़ॉर्मेट में होना चाहिए।',
            'aadhaar_card_photo.max'   => 'Aadhaar Card file size must not exceed 2 MB. / आधार कार्ड फ़ाइल का आकार 2 MB से अधिक नहीं होना चाहिए।',

            'medical_certificate.mimes' => 'Medical Certificate file must be in PDF format. / मेडिकल सर्टिफिकेट केवल PDF फ़ॉर्मेट में होना चाहिए।',
            'medical_certificate.max'   => 'Medical Certificate file size must not exceed 2 MB. / मेडिकल सर्टिफिकेट फ़ाइल का आकार 2 MB से अधिक नहीं होना चाहिए।',
        ]);
        if ($validation->fails())
            return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);

        if ($request->sport) {
            $hasSubSports = DB::table('sub_sport_type')->where('sport_id', $request->sport)->exists();
            if ($hasSubSports && empty($request->sub_type)) {
                return response()->json(['error' => true, 'msg' => 'Please select Sub-Sport Type./कृपया उप-खेल प्रकार का चयन करें।']);
            }
        }
        if(Auth::guard('CoachingCamp')->user()->user_type==1){
            $validation = Validator::make($request->all(), [
                'parent_sign' => 'required|mimes:png,jpg|max:2000',
            ],
            [
                'parent_sign.mimes' => 'Parent Sign must be a PNG or JPG file. / अभिभावक के हस्ताक्षर की फ़ाइल केवल PNG या JPG प्रारूप में होनी चाहिए।',
                'parent_sign.max'   => 'Parent Sign size must not exceed 2 MB. / अभिभावक के हस्ताक्षर का आकार 2 MB से अधिक नहीं होना चाहिए।',
            ]);
            if ($validation->fails())
                return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
        }
        if(Auth::guard('CoachingCamp')->user()->user_type==2){
            $validation = Validator::make($request->all(), [
                'time_slot_pref' => 'required',
            ]);
            if ($validation->fails())
                return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
        }
        if(Auth::guard('CoachingCamp')->user()->user_type==2){
            $validation = Validator::make($request->all(), [
                'time_slot_pref' => 'required',
                'mem_type_pref' => 'required',
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
        if(Auth::guard('CoachingCamp')->user()->user_type==2){
            $data['time_slot_pref'] = $request->time_slot_pref;
            $data['mem_type_pref'] = $request->mem_type_pref;
        }

        if ($request->hasFile('profile_picture')) {
            $profile_picture = moveFile('coaching_camp/profile_picture', $request->profile_picture);
            $data['profile_picture'] = $profile_picture;
        }

        if ($request->is_applicant_suffering_disease == 1 && $request->hasFile('medical_certificate')) {
            $medical_certificate = moveFile('coaching_camp/medical_certificate', $request->medical_certificate);
            $data['medical_certificate'] = $medical_certificate;
        } elseif ($request->is_applicant_suffering_disease == 0) {
            $data['medical_certificate'] = '';
        }

        if ($request->hasFile('aadhar_card_photo')) {
            $aadhar_card_photo = moveFile('coaching_camp/aadhar_card_photo', $request->aadhar_card_photo);
            $data['aadhar_card_photo'] = $aadhar_card_photo;
        }

        if ($request->hasFile('signature')) {
            $signature = moveFile('coaching_camp/signature', $request->signature);
            $data['signature'] = $signature;
        }
        if ($request->hasFile('parent_sign')) {
            $parent_sign = moveFile('coaching_camp/parent_sign', $request->parent_sign);
            $data['parent_sign'] = $parent_sign;
        }
        $applicationview = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();

        if ($applicationview) {

            DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->update($data);
        } else {

            DB::table('coaching_camp_basic_details')->insert($data);
        }
        return response()->json(['error' => false, 'msg' => 'Application Basic Detail Submitted Successfully.', "url" => route('coaching_camp_application_preview')]);
    }



    public function applicationfinalSubmit()
    {

        $basic = DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->first();
        $basicId = $basic->id;
        if($basic->application_no == null){
            DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->update([
                'final_submit' => 1,
                'application_no' => $this->appNumberGenerate($basicId),
                'created' => now()
            ]);
        }else{
            DB::table('coaching_camp_basic_details')->where('user_id', Auth::guard('CoachingCamp')->user()->id)->update([
                'final_submit' => 1,
            ]);
        }

        return response()->json(['error' => false, 'msg' => 'Final Submission of Application is completed.', "url" => route('coaching_camp_dashboard')]);

        //    return redirect()->route('coaching_camp_dashboard')->with('success', 'Final Submission of Application is completed.');

    }
    public function coaching_camp_challan(Request $request)
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

    public function logout()
    {
        Auth::guard('CoachingCamp')->logout();
        return redirect()->route('coaching_camp_login')->with('success', 'Logout Successfully.');
    }

    public function appNumberGenerate($basicId)
    {
        return date('Y') . sprintf("%06d", $basicId);
    }
    public function reregister(Request $request){
        $id=Auth::guard('CoachingCamp')->user()->id;
        $data = [
            'validity'                      =>  null,
            'payment_status_coaching_fee'   =>  null,
            'final_submit'                  =>  null,
            'status'                        =>  null,
            'payment_amount_coaching'       =>  null,
            'payment_status'                =>  null,
            'payment_date'                  =>  null,
            'payment_date_coaching_fee'     =>  null,
        ];
        DB::table('coaching_camp_basic_details')->where('user_id',$id)->update($data);
        return redirect()->route('coaching_camp_application_form');
    }
    public function getStadiums($district_id)
    {
        $stadiums = DB::table('studium_master')
            ->where('district_id', $district_id)
            ->get();

        return response()->json($stadiums);
    }
    public function id_card($id)
    {
        $idcard = DB::table('coaching_camp_basic_details as a')
                ->join('coaching_camp_register as c','c.id','=','a.user_id')
                ->join('coaching_camp_sports','coaching_camp_sports.id','=','a.sport')
                ->join('studium_master as b', 'a.stadium','=', 'b.id')
                ->select('a.*', 'b.studium_name as stadium_name','c.*','coaching_camp_sports.name as sports_name')
                ->where('a.id', $id)->first();

        return view('coaching_camp.idcard', compact('idcard'));
    }
}
