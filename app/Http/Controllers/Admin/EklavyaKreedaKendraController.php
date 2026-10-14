<?php

namespace App\Http\Controllers\Admin;

use App\Events\SmsMail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EklavyaKreedaKosh;
use Illuminate\Support\Facades\Hash;

use App\Models\GymnasiumSwimming;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EklavyaKreedaKendraController extends Controller
{

    public function eklavya_kreeda_kendra_list()
    {
        $applicationview = DB::table('eklavya_krida_kosh_basic_detail')->orderBy('id', 'DESC')->get();

        $districts = DB::table('cities')->select('city', 'id')->where('state_id', '23')->get();

        return view('admin.eklavya_kreeda_kendra.eklavya_kreeda_list', compact('applicationview', 'districts'));
    }

    // SQL Injection Vulnerability Fixes
    public function eklavya_kreeda_view_details($id)
    {
        $applicationview = DB::table('eklavya_krida_kosh_registration as r')
            ->join(
                'eklavya_krida_kosh_basic_detail as d',
                'd.user_id',
                '=',
                'r.id'
            )
            ->select(
                'r.id',
                'r.application_no',
                'r.name',
                'r.mobile',
                'r.email',
                'r.status_preview',
                'r.final_submit_date',
                'r.application_status',
                'd.*'
            )
            ->where('d.user_id', $id)
            ->get();
        // dd($applicationview);
        $award = DB::table('eklavya_krida_kosh_award')->where('user_id', $id)->get();
        return view('admin.eklavya_kreeda_kendra.eklavya_kreeda_details', compact('applicationview', 'award'));
    }

    public function eklavya_kreeda_list_search(Request $req)
    {

        $applicant_detailss = DB::table('eklavya_krida_kosh_basic_detail')
            ->join('eklavya_krida_kosh_registration', 'eklavya_krida_kosh_registration.id', '=', 'eklavya_krida_kosh_basic_detail.user_id')
            ->select('eklavya_krida_kosh_registration.*', 'eklavya_krida_kosh_registration.id as play_id', 'eklavya_krida_kosh_basic_detail.*')
            ->where('eklavya_krida_kosh_registration.status_preview', 2);

        if ($req->city_filter) {
            $applicant_detailss->where('eklavya_krida_kosh_basic_detail.permanent_district', $req->city_filter);
        }

        $applicationview = $applicant_detailss->orderBy('eklavya_krida_kosh_registration.name')->get();
        $districts = DB::table('cities')->select('city', 'id')->where('state_id', '23')->get();
        $sports = DB::table('sport_type')->orderBy('name')->get();
        return view('admin.eklavya_kreeda_kendra.eklavya_kreeda_list', compact('applicationview', 'sports', 'districts'));
    }
}
