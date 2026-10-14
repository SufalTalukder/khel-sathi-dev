
<?php $__env->startSection('content'); ?>


<div class="pageheader" id="menu-margin">
	<h4 class="mb-0">Module List
		<a href="<?php echo e(route('moduleForm')); ?>" class="btn btn-primary btn-sm float-end">
			<i class="fas fa-plus"></i>
			&nbsp;&nbsp;Add Module
		</a>
		<a title="Module List" class="btn btn-sm btn-success float-end" onclick="ExportToExcel('xlsx')">
			<i class="fa fa-file-excel"></i> Export to Excel
		</a>
	</h4>
</div>
<div class="card">
	<div class="card-body">
		<div class="table-responsive">
			<table id="dataTable" class="table table-bordred table-hover bg-white datatable">
				<thead>
					<tr>
						<th>S.No.</th>
						<th>Module Name</th>
						<th>Module URL</th>
						<th>Menu Order</th>
						<th>Is Menu</th>
						<!-- <th>Status</th> -->
						<th class="text-center">Action</th>
						<th class="text-center">Edit</th>
					</tr>
				</thead>
				<tbody>
					<?php $__currentLoopData = $module; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>

						<td><?php echo e($key+1); ?></td>
						<td><?php echo e($item->module_name); ?></td>
						<td><?php echo e($item->module_url); ?></td>
						<td><?php echo e($item->menu_order); ?></td>
						<td><?php echo e($item->is_menu==1?'Yes':'No'); ?></td>
						<!-- <td><?php echo e($item->module_status==0?'Active':'Active'); ?></td> -->

						<td class="text-center" width="20%">
							<div class="btn-group">
								<?php if($item->module_status==0): ?>
								<div class="form-control2">
									<label class="switch">
										<input type="checkbox" id="themeskin<?php echo e($key+1); ?>">
										<div class="slider round" onclick="moduleStatus('<?php echo e($item->id); ?>')">
											<span class="off">Inactive</span>
											<span class="on">Active</span>
										</div>
									</label>
								</div>
								<?php else: ?>
								<div class="form-control2">
									<label class="switch">
										<input type="checkbox" id="themeskin<?php echo e($key+1); ?>" checked>
										<div class="slider round" onclick="moduleStatus('<?php echo e($item->id); ?>')">
											<span class="off">Inactive</span>
											<span class="on">Active</span>
										</div>
									</label>
								</div>
								<?php endif; ?>


							</div>
						</td>
						<td>
							<a class="btn btn-sm btn-dark pointer bt" href="<?php echo e(route('editModule' , $item->id)); ?>">
								<i class="fa fa-edit"></i>
							</a>
						</td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>



	</div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('custom-scripts'); ?>
<script type="text/javascript" src="<?php echo e(asset('js/xlsx.full.min.js')); ?>"></script>
<script>
	function ExportToExcel(type, fn, dl) {
		var elt = document.getElementById('dataTable');
		var wb = XLSX.utils.table_to_book(elt, {
			sheet: "sheet1"
		});
		return dl ?
			XLSX.write(wb, {
				bookType: type,
				bookSST: true,
				type: 'base64'
			}) :
			XLSX.writeFile(wb, fn || ('UserList.' + (type || 'xlsx')));
	}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts/admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\khel-sathi-dev\resources\views/admin/modulemanage/index.blade.php ENDPATH**/ ?>