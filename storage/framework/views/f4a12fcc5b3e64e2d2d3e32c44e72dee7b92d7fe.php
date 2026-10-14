<?php
$user_id = Auth::guard('admin')->user()->id;

$modules = DB::table('urm_module_manager')->where('is_menu', '=', 1)->where('module_status', '=', 1)->orderBy('menu_order', 'ASC')->get();
//  dd($modules);
foreach ($modules as $module) {
	// dd( $module->id);

	$role_modules = DB::table('urm_role_module_mapping')->join('urm_page_manager', 'urm_role_module_mapping.page_id', '=', 'urm_page_manager.id')->where('urm_role_module_mapping.user_id', $user_id)->where('urm_page_manager.module_id', '=', $module->id)->orderBy('urm_page_manager.page_order', 'ASC')->groupBy('urm_page_manager.id')->get();
	//  dd($role_modules);
	if ($role_modules) {
		//    dd($role_modules);
		$i = 0;
		// $adminData[$user->name][$module->id] = [];
		foreach ($role_modules as $role_module) {
			//  $menu[$user->name][$module->id]['module_name']= $module->module_name;
			$menu[$module->module_name]['id'] = $module->id;
			$menu[$module->module_name]['module_url'] = $module->module_url;
			$menu[$module->module_name]['menu_icon'] = $module->menu_icon;
			$menu[$module->module_name]['dor'] = $role_module->dor;
			$menu[$module->module_name]['pages'][] = DB::table('urm_page_manager')->where('page_status', '=', 1)->where('is_menu', '=', 1)->where('id', $role_module->page_id)->orderBy('page_order', 'ASC')->first();
			$i++;
		}
	}
}

?>
<span style="color: #d64d92;">
	<?php
	$str  = Auth::guard('admin')->user()->username;
	$res = str_replace('_', ' ', $str); ?>
	<?php echo e(ucfirst($res)); ?>

</span>
<ul class="navsidebar" id="accordionExample">
	
	<li class=" menu_item nav-item">
		<a href="<?php echo e(url('admin/dashboard')); ?>">
			<i class="icon icon-home"></i><span>Dashboard </span>
		</a>
	</li>
	
	<?php if(isset($menu)): ?> <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value_arr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<li data-bs-toggle="collapse" data-bs-target="#abc<?php echo e($value_arr['id']); ?>" class="collapsed menu_item">
		<a href="javascript:void(0)"><i class="<?php echo e($value_arr['menu_icon']); ?>"></i><span><?php echo e(repairHindi($key)); ?></span> <span class="fa fa-caret-down"></span></a>
	</li>
	<ul class="sub-menu collapse" id="abc<?php echo e($value_arr['id']); ?>" data-bs-parent="#accordionExample">
		<?php $__currentLoopData = $value_arr['pages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if(isset($page) && $page != ""): ?>
		<li><a href="<?php echo e(url($page->page_url)); ?>"><i class="<?php echo e($page->menu_icon); ?>"></i><span><?php echo e(repairHindi($page->page_name)); ?></span></a>
		</li>
		<?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>
</ul>



<?php /**PATH D:\khel-sathi-dev\resources\views/components/sidebar_left_menu.blade.php ENDPATH**/ ?>