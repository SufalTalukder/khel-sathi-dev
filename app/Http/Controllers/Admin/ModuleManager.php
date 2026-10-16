<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ModuleManager extends Controller
{
    /**
     * Display a listing of modules.
     */
    public function index(Request $request)
    {
        $modules = DB::table('urm_module_manager')
            ->orderBy('menu_order', 'ASC')
            ->get();

        $icons = DB::table('module_icon')->get();

        if ($request->is('khelsathi-department/*') || $request->is('inertia/*') || $request->header('X-Inertia') || $request->is('*module-manager*')) {
            return Inertia::render('admin/ManageModule', [
                'modules' => $modules,
                'icons'   => $icons,
            ]);
        }

        // Backward compatibility for legacy blade views
        $module = $modules;
        return view('admin.modulemanage.index', compact('module', 'icons'));
    }

    /**
     * Show form for creating a new module.
     */
    public function moduleForm()
    {
        if (request()->is('khelsathi-department/*') || request()->header('X-Inertia')) {
            return redirect()->route('moduleCreate')->with('openCreateModal', true);
        }
        return view('admin.modulemanage.create');
    }

    /**
     * Create a new module.
     */
    public function createModule(Request $req)
    {
        $modulename = $req->input('module_name') ?? $req->input('modulename');
        $moduleurl  = $req->input('module_url') ?? $req->input('moduleurl');
        $menuorder  = $req->input('menu_order') ?? $req->input('menuorder');
        $ismenu     = $req->input('is_menu') ?? $req->input('ismenu');
        $status     = $req->input('module_status') ?? $req->input('status');
        $icon       = $req->input('menu_icon') ?? $req->input('options');

        $validation = Validator::make([
            'module_name'   => $modulename,
            'module_url'    => $moduleurl,
            'menu_order'    => $menuorder,
            'is_menu'       => $ismenu,
            'module_status' => $status,
            'menu_icon'     => $icon,
        ], [
            'module_name'   => 'required|max:150',
            'module_url'    => 'required|max:255',
            'menu_order'    => 'required|numeric',
            'is_menu'       => 'required',
            'module_status' => 'required',
            'menu_icon'     => 'required',
        ], function_exists('msg') ? msg() : []);

        if ($validation->fails()) {
            if ($req->header('X-Inertia')) {
                return back()->withErrors($validation)->with('error', $validation->errors()->first());
            }
            return response()->json(['error' => true, 'msg' => $validation->errors()->first()], 422);
        }

        $id = DB::table('urm_module_manager')->insertGetId([
            "module_name"   => $modulename,
            "module_url"    => $moduleurl,
            "menu_order"    => $menuorder,
            "is_menu"       => $ismenu,
            "module_status" => $status,
            "menu_icon"     => $icon,
        ]);

        if ($req->header('X-Inertia') || $req->is('khelsathi-department/*')) {
            return redirect()->back()->with("success", "Module '{$modulename}' successfully created.");
        }

        if ($req->wantsJson()) {
            return response()->json(['error' => false, 'msg' => 'Module successfully created.', 'id' => $id]);
        }

        return redirect('/admin/module_manager')->with("success", "Module successfully created.");
    }

    /**
     * Toggle module active/inactive status.
     */
    public function moduleStatus($id)
    {
        $module = DB::table('urm_module_manager')->where('id', $id)->first();
        if (!$module) {
            if (request()->wantsJson() || request()->header('X-Inertia')) {
                return response()->json(['error' => true, 'msg' => 'Module not found.'], 404);
            }
            return back()->with('error', 'Module not found.');
        }

        $newStatus = $module->module_status == 1 ? 0 : 1;
        DB::table('urm_module_manager')->where('id', $id)->update(["module_status" => $newStatus]);
        $msg = $newStatus == 1 ? "Enabled" : "Disabled";

        if (request()->header('X-Inertia')) {
            return back()->with('success', "Module successfully {$msg}.");
        }

        return response()->json([
            'error'  => false,
            'status' => $newStatus,
            'msg'    => "Module successfully {$msg}."
        ]);
    }

    /**
     * Show edit form / fetch module data for editing.
     */
    public function editModule($id)
    {
        $module = DB::table('urm_module_manager')->where('id', $id)->first();
        $icon = DB::table('module_icon')->get();

        if (request()->wantsJson() || request()->header('X-Inertia')) {
            return response()->json(['error' => false, 'module' => $module, 'icons' => $icon]);
        }

        return view('admin.modulemanage.update', compact('module', 'icon'));
    }

    /**
     * Update an existing module.
     */
    public function updateModule(Request $req, $id)
    {
        $modulename = $req->input('module_name') ?? $req->input('modulename');
        $moduleurl  = $req->input('module_url') ?? $req->input('moduleurl');
        $menuorder  = $req->input('menu_order') ?? $req->input('menuorder');
        $ismenu     = $req->input('is_menu') ?? $req->input('ismenu');
        $status     = $req->input('module_status') ?? $req->input('status');
        $icon       = $req->input('menu_icon') ?? $req->input('options');

        $validation = Validator::make([
            'module_name'   => $modulename,
            'module_url'    => $moduleurl,
            'menu_order'    => $menuorder,
            'is_menu'       => $ismenu,
            'module_status' => $status,
            'menu_icon'     => $icon,
        ], [
            'module_name'   => 'required|max:150',
            'module_url'    => 'required|max:255',
            'menu_order'    => 'required|numeric',
            'is_menu'       => 'required',
            'module_status' => 'required',
            'menu_icon'     => 'required',
        ], function_exists('msg') ? msg() : []);

        if ($validation->fails()) {
            if ($req->header('X-Inertia')) {
                return back()->withErrors($validation)->with('error', $validation->errors()->first());
            }
            return response()->json(['error' => true, 'msg' => $validation->errors()->first()], 422);
        }

        DB::table('urm_module_manager')->where('id', $id)->update([
            "module_name"   => $modulename,
            "module_url"    => $moduleurl,
            "menu_order"    => $menuorder,
            "is_menu"       => $ismenu,
            "module_status" => $status,
            "menu_icon"     => $icon,
        ]);

        if ($req->header('X-Inertia') || $req->is('khelsathi-department/*')) {
            return redirect()->back()->with("success", "Module '{$modulename}' successfully updated.");
        }

        if ($req->wantsJson()) {
            return response()->json(['error' => false, 'msg' => 'Module successfully updated.']);
        }

        return redirect('/admin/module_manager')->with("success", "Module successfully updated.");
    }

    /**
     * Delete module.
     */
    public function deleteModule($id)
    {
        $pagesCount = DB::table('urm_page_manager')->where('module_id', $id)->count();
        if ($pagesCount > 0) {
            return back()->with('error', "Cannot delete: {$pagesCount} pages are currently assigned to this module.");
        }

        $module = DB::table('urm_module_manager')->where('id', $id)->first();
        DB::table('urm_module_manager')->where('id', $id)->delete();

        return back()->with('success', "Module '" . ($module->module_name ?? $id) . "' successfully deleted.");
    }
}
