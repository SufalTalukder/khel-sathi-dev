<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator as FacadesValidator;




class NoticeController extends Controller
{
    public function notices()
    {
        $notices=DB::table('notices')->get();
        return view('admin/notices/view-notices', compact('notices'));
    }
    public function add_notice(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'file_date' => 'required|date',
            'notice_type' => 'required',
            'notice' => 'required|file|mimes:pdf,zip',
            'thumbnail' => 'nullable|file|mimes:png,jpeg,gif',
        ], msg());

        // Server-side validation: each file is required on first upload
        if (!$request->hasFile('notice'))
            return response()->json(['error' => true, 'msg' => 'Please upload the Notice']);
        if($request->notice_type==4 || $request->notice_type==5  ){
            if (!$request->hasFile('thumbnail')) {
                return response()->json(['error' => true, 'msg' => 'Please upload the thumbnail image']);
            }

        }

        if ($validation->fails())
            return redirect()->back()->withInput($request->all())->with('error', $validation->errors()->first());

        if ($request->hasFile('notice')) {

            $file_name = date('His') . '_' . $request->file('notice')->getClientOriginalName();

            $request->file('notice')->move(
                public_path('notices'),
                $file_name
            );

            $pdfPath = public_path('notices/' . $file_name);

            if($request->notice_type==4 || $request->notice_type==5){
                // Generate thumbnail
                if ($request->hasFile('thumbnail')) {
                    $thumbnail_name = date('His') . '_' . $request->file('thumbnail')->getClientOriginalName();
                    $request->file('thumbnail')->move(
                        public_path('notices/thumbnails'),
                        $thumbnail_name
                    );
                }
            }
        }

        $status = [
            'title'              => $request->title,
            'description'        => $request->description,
            'file_date'          => $request->file_date,
            'notice_type'        => $request->notice_type,
            'file_name'          => $file_name,
            'thumbnail'          => $thumbnail_name ?? null,
        ];
        DB::table('notices')->insert($status);

        return response()->json(['error' => false, 'msg' => 'Notice Added Successfully.', 'url' => url('admin/notices') ]);

        //   return response()->json(['error' => true, 'msg' => $validation->errors()->first()]);

    }
    public function change_status(Request $request){
        $id     = $request->id;
        $status = $request->status;
        $data   = [
            'status'                  => $status,
        ];
        DB::table('notices')->where('id', $id)->update($data);

        return response()->json([
            'error' => false,
            'msg'   => 'Notice status updated successfully.',
        ]);
    }
}

