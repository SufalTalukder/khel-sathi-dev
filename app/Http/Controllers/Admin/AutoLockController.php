<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AutoLockController extends Controller
{
    public function auto_lock_list()
    {
        $auto_lock_master_data = DB::table('auto_lock_master')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.auto_lock_master.list', compact('auto_lock_master_data'));
    }

    public function auto_lock_add(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'auto_lock_type'          => 'required',
                'schedule_auto_lock_date' => 'required|date',
                'auto_lock_status'        => 'required|in:0,1',
            ],
            [
                'auto_lock_type.required'          => 'Please select the sub module.',
                'schedule_auto_lock_date.required' => 'Please enter the schedule date.',
                'schedule_auto_lock_date.date'     => 'The auto lock date must be a valid date.',
                'auto_lock_status.required'        => 'Please select the status.',
                'auto_lock_status.in'              => 'Auto lock status must be either Active or Inactive.',
            ]
        );

        if ($validation->fails()) {
            return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
        }

        $isAutoLockTypeExists = DB::table('auto_lock_master')
            ->where('auto_lock_type', $request->auto_lock_type)
            ->count();

        if ($isAutoLockTypeExists) {
            return response()->json(['error' => true, 'msg' => 'Data already exists.']);
        }

        $isSaved = DB::table('auto_lock_master')->insert([
            'auto_lock_type'          => $request->auto_lock_type,
            'schedule_auto_lock_date' => $request->schedule_auto_lock_date,
            'auto_lock_status'        => $request->auto_lock_status,
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        if (!$isSaved) {
            return response()->json(['error' => true, 'msg' => 'Error! While Data Creating.']);
        }

        return response()->json(['error' => false, 'msg' => 'Data Created Successfully.']);
    }

    public function auto_lock_update(Request $request)
    {
        $validation = Validator::make(
            $request->all(),
            [
                'auto_lock_id'            => 'required',
                'auto_lock_type'          => 'required',
                'schedule_auto_lock_date' => 'required|date',
                'auto_lock_status'        => 'required|in:0,1',
            ],
            [
                'auto_lock_id.required'            => 'ID is required.',
                'auto_lock_type.required'          => 'Please select the sub module.',
                'schedule_auto_lock_date.required' => 'Please enter the schedule date.',
                'schedule_auto_lock_date.date'     => 'The auto lock date must be a valid date.',
                'auto_lock_status.required'        => 'Please select the status.',
                'auto_lock_status.in'              => 'Auto lock status must be either Active or Inactive.',
            ]
        );

        if ($validation->fails()) {
            return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);
        }

        $recordExists = DB::table('auto_lock_master')
            ->where('auto_lock_id', $request->auto_lock_id)
            ->count();

        if (!$recordExists) {
            return response()->json(['error' => true, 'msg' => 'Data not found.']);
        }

        $isDuplicate = DB::table('auto_lock_master')
            ->where('auto_lock_type', $request->auto_lock_type)
            ->where('auto_lock_id', '!=', $request->auto_lock_id)
            ->count();

        if ($isDuplicate) {
            return response()->json(['error' => true, 'msg' => 'Data already exists.']);
        }

        $isUpdated = DB::table('auto_lock_master')
            ->where('auto_lock_id', $request->auto_lock_id)
            ->update([
                'auto_lock_type'          => $request->auto_lock_type,
                'schedule_auto_lock_date' => $request->schedule_auto_lock_date,
                'auto_lock_status'        => $request->auto_lock_status,
                'updated_at'              => now(),
            ]);

        if ($isUpdated === false) {
            return response()->json(['error' => true, 'msg' => 'Error! While Data Updating.']);
        }

        return response()->json(['error' => false, 'msg' => 'Data Updated Successfully.']);
    }

    public function auto_lock_delete(Request $request, $id)
    {
        $isExists = DB::table('auto_lock_master')
            ->where('auto_lock_id', $id)
            ->count();

        if (!$isExists) {
            return response()->json(['error' => true, 'msg' => 'Data not found.']);
        }

        DB::table('auto_lock_master')->where('auto_lock_id', $id)->delete();

        return response()->json(['error' => false, 'msg' => 'Data Deleted Successfully.']);
    }
}
