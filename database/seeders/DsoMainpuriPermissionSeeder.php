<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DsoMainpuriPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Find the Hostel Management module
        $hostelModule = DB::table('urm_module_manager')
            ->where('module_name', 'like', '%Hostel%')
            ->first();

        if (!$hostelModule) {
            $this->command->error('Hostel Management module not found in urm_module_manager.');
            return;
        }

        $this->command->info("Found module: {$hostelModule->module_name} (ID: {$hostelModule->id})");

        // Ensure Coaching Camp Trial List page exists
        $coachingTrialPage = DB::table('urm_page_manager')
            ->where('page_url', 'hosteladmin/coaching_camp_trialList')
            ->first();

        if (!$coachingTrialPage) {
            $maxOrder = DB::table('urm_page_manager')->where('module_id', $hostelModule->id)->max('page_order') ?? 0;
            $coachingTrialPageId = DB::table('urm_page_manager')->insertGetId([
                'page_name'   => 'Coaching Camp Trial List',
                'page_url'    => 'hosteladmin/coaching_camp_trialList',
                'module_id'   => $hostelModule->id,
                'is_menu'     => 1,
                'menu_icon'   => 'icon icon-list',
                'page_order'  => $maxOrder + 1,
                'page_status' => 1,
                'page_parent' => null,
            ]);
            $this->command->info("Created page: Coaching Camp Trial List (ID: {$coachingTrialPageId})");
        } else {
            $coachingTrialPageId = $coachingTrialPage->id;
            $this->command->info("Found page: Coaching Camp Trial List (ID: {$coachingTrialPageId})");
        }

        // Ensure Final Approved List page exists
        $finalApprovedPage = DB::table('urm_page_manager')
            ->where('page_url', 'hosteladmin/final_approved_list')
            ->first();

        if (!$finalApprovedPage) {
            $maxOrder = DB::table('urm_page_manager')->where('module_id', $hostelModule->id)->max('page_order') ?? 0;
            $finalApprovedPageId = DB::table('urm_page_manager')->insertGetId([
                'page_name'   => 'Final Approved List',
                'page_url'    => 'hosteladmin/final_approved_list',
                'module_id'   => $hostelModule->id,
                'is_menu'     => 1,
                'menu_icon'   => 'icon icon-check',
                'page_order'  => $maxOrder + 1,
                'page_status' => 1,
                'page_parent' => null,
            ]);
            $this->command->info("Created page: Final Approved List (ID: {$finalApprovedPageId})");
        } else {
            $finalApprovedPageId = $finalApprovedPage->id;
            $this->command->info("Found page: Final Approved List (ID: {$finalApprovedPageId})");
        }

        // Find DSO Mainpuri user (search by username or name)
        $dsoUser = DB::table('admin')
            ->where('username', 'like', '%mainpuri%')
            ->orWhere('username', 'like', '%dso_mainpuri%')
            ->orWhere('name', 'like', '%mainpuri%')
            ->first();

        if (!$dsoUser) {
            $this->command->error('DSO Mainpuri user not found in admin table. Please check the username.');
            $this->command->line('Available admin users with similar names:');
            $users = DB::table('admin')->where('username', 'like', '%dso%')->orWhere('username', 'like', '%district%')->get(['id', 'username', 'name', 'admin_role']);
            foreach ($users as $u) {
                $this->command->line("  ID: {$u->id}, Username: {$u->username}, Name: {$u->name}, Role: {$u->admin_role}");
            }
            return;
        }

        $this->command->info("Found DSO Mainpuri user: {$dsoUser->username} (ID: {$dsoUser->id})");

        $pageIds = [$coachingTrialPageId, $finalApprovedPageId];

        foreach ($pageIds as $pageId) {
            $exists = DB::table('urm_role_module_mapping')
                ->where('user_id', $dsoUser->id)
                ->where('page_id', $pageId)
                ->where('module_id', $hostelModule->id)
                ->exists();

            if (!$exists) {
                DB::table('urm_role_module_mapping')->insert([
                    'module_id' => $hostelModule->id,
                    'user_id'   => $dsoUser->id,
                    'page_id'   => $pageId,
                    'role_id'   => $dsoUser->admin_role,
                ]);
                $pageName = ($pageId === $coachingTrialPageId) ? 'Coaching Camp Trial List' : 'Final Approved List';
                $this->command->info("Permission added: {$pageName} for {$dsoUser->username}");
            } else {
                $pageName = ($pageId === $coachingTrialPageId) ? 'Coaching Camp Trial List' : 'Final Approved List';
                $this->command->warn("Permission already exists: {$pageName} for {$dsoUser->username}");
            }
        }

        $this->command->info('Done.');
    }
}
