
<?php $__env->startSection( 'content' ); ?>

<div class="pageheader" id="menu-margin">
	<h4 class="mb-0">User Access Details <a title="User Access Details" class="btn btn-sm btn-success float-end" onclick="ExportToExcel('xlsx')">
			<i class="fa fa-file-excel"></i> Export to Excel
		</a>
	</h4>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="dataTable" class="table table-bordered datatable v-align">
						<thead class="bg-info text-white">
							<tr>
								<th valign="top">S.No.</th>
								<th valign="top">User</th>
								<th valign="top">Modules</th>
								<th valign="top">Pages</th>
								<th valign="top">Updated By</th>
								<th valign="top">Updated Date</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1; ?> <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value_arr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr>
								<td><?php echo e($i); ?></td>
								<td><?php echo e($key); ?></td>
								<td>
									<?php $__currentLoopData = $value_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<p style="height:<?php echo (count($modules['pages']) * 30); ?>px;display: block;"> <?php echo e($modules['module_name']); ?> </p>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</td>
								<td>
									<ul class="tablisting">
										<?php $__currentLoopData = $value_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $__currentLoopData = $modules['pages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

										<li> <span><?php if(isset($page)): ?><?php echo e($page->page_name); ?> <?php endif; ?></span></li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</ul>
								</td>
								<td>
									<?php $__currentLoopData = $value_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<span style="height:<?php echo (count($modules['pages']) * 30); ?>px;display: block;"> <?php echo e($modules['updated_by']); ?> </span><br> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</td>
								<td>
									<?php $__currentLoopData = $value_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<span style="height:<?php echo (count($modules['pages']) * 30); ?>px;display: block;"> <?php echo e(date('d-m-Y h:s A',strtotime($modules['dor']))); ?> </span><br> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</td>
							</tr>
							<?php $i++;  ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


</div>


<?php $__env->stopSection(); ?>


<?php $__env->startPush( 'custom-scripts' ); ?>
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
			XLSX.writeFile(wb, fn || ('User Access Details.' + (type || 'xlsx')));
	}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make( 'layouts/admin_layout' , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\khel-sathi-dev\resources\views/admin/user/user_access_details.blade.php ENDPATH**/ ?>