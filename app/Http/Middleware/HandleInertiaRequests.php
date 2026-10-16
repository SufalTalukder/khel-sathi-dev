<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $admin = \Illuminate\Support\Facades\Auth::guard('admin')->user();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name ?? $request->user()->applicant_name ?? '',
                    'email' => $request->user()->email ?? '',
                ] : null,
                'admin' => $admin ? [
                    'id' => $admin->id,
                    'name' => $admin->name ?? '',
                    'username' => $admin->username ?? '',
                    'email' => $admin->email ?? '',
                    'admin_role' => $admin->admin_role ?? '',
                    'last_login' => $admin->last_login ?? '',
                    'district_id' => $admin->district_id ?? '',
                    'division_id' => $admin->division_id ?? '',
                    'sport_type' => $admin->sport_type ?? '',
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'message' => fn () => $request->session()->get('message'),
            ],
            'menu' => function () use ($admin) {
                if (!$admin) return [];
                $userId = $admin->id;
                $modules = \Illuminate\Support\Facades\DB::table('urm_module_manager')
                    ->where('is_menu', '=', 1)
                    ->where('module_status', '=', 1)
                    ->orderBy('menu_order', 'ASC')
                    ->get();
                $menu = [];
                foreach ($modules as $module) {
                    $roleModules = \Illuminate\Support\Facades\DB::table('urm_role_module_mapping')
                        ->join('urm_page_manager', 'urm_role_module_mapping.page_id', '=', 'urm_page_manager.id')
                        ->where('urm_role_module_mapping.user_id', $userId)
                        ->where('urm_page_manager.module_id', '=', $module->id)
                        ->orderBy('urm_page_manager.page_order', 'ASC')
                        ->groupBy('urm_page_manager.id')
                        ->get();
                    if ($roleModules && count($roleModules) > 0) {
                        $pages = [];
                        foreach ($roleModules as $roleModule) {
                            $p = \Illuminate\Support\Facades\DB::table('urm_page_manager')
                                ->where('page_status', '=', 1)
                                ->where('is_menu', '=', 1)
                                ->where('id', $roleModule->page_id)
                                ->orderBy('page_order', 'ASC')
                                ->first();
                            if ($p) {
                                $pageUrl = $p->page_url;
                                if ($p->page_name === 'Module Manager' || strpos($pageUrl, 'module_manager') !== false || strpos($pageUrl, 'module-manager') !== false) {
                                    $pageUrl = '/khelsathi-department/module-manager';
                                } else {
                                    $pageUrl = str_starts_with($pageUrl, 'http') ? $pageUrl : ('/' . ltrim($pageUrl, '/'));
                                }
                                $pages[] = [
                                    'id' => $p->id,
                                    'page_name' => function_exists('repairHindi') ? repairHindi($p->page_name) : $p->page_name,
                                    'page_url' => $pageUrl,
                                    'menu_icon' => $p->menu_icon,
                                ];
                            }
                        }
                        if (!empty($pages)) {
                            $menu[] = [
                                'id' => $module->id,
                                'module_name' => function_exists('repairHindi') ? repairHindi($module->module_name) : $module->module_name,
                                'module_url' => $module->module_url,
                                'menu_icon' => $module->menu_icon,
                                'pages' => $pages,
                            ];
                        }
                    }
                }
                return $menu;
            },
        ]);
    }
}
