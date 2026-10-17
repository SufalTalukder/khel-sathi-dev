
<?php $__env->startSection('content'); ?>


<div class="pageheader" id="menu-margin">
    <h4 class="mb-0">Role Manager <a title="Role Manager List" class="btn btn-sm btn-success float-end" onclick="ExportToExcel('xlsx')">
            <i class="fa fa-file-excel"></i> Export to Excel
        </a>
    </h4>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form action="<?php echo e(route('createRole')); ?>" class="needs-validation" id="reload" novalidate method="post" autocomplete="off">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control alphanumeric" id="role_name" name="role_name" required>
                                <div class="invalid-feedback">
                                    Please provide a Role Manager.
                                </div>
                                <?php if($errors->has('name')): ?>
                                <span class="error_mess"><?php echo e($errors->first('name')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mt-3 col-md-2 d-grid">
                            <label for="name" style="margin: 0;line-height: 13px;">&nbsp;</label>
                            <button class="btn btn-primary form-group" type="submit">Submit/दर्ज करे</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordred table-hover bg-white datatable">
                            <thead>
                                <tr>
                                    <th width="6%">S.No.</th>
                                    <th>Role Name</th>
                                    <th style="width: 10%;" class="text-center">Status</th>
                                    <th style="width: 8%;" class="text-center">Edit</th>
                                    <th style="width: 8%;" class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $role; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>

                                    <td><?php echo e($key+1); ?></td>
                                    <td><?php echo e($item->role_name); ?></td>
                                    <td class="text-center">
                                        <div class="form-control2">
                                            <label class="switch">
                                                <input type="checkbox" id="themeskin<?php echo e($key+1); ?>" <?php if ($item->role_status == 1) {
                                                                                                        echo "checked";
                                                                                                    } elseif ($item->role_status == 0) {
                                                                                                        echo "";
                                                                                                    } else {
                                                                                                        echo "checked";
                                                                                                    } ?>>
                                                <div class="slider round" onclick="roleStatus('<?php echo e($item->id); ?>')">
                                                    <span class="off">Disable</span>
                                                    <span class="on">Enable</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-dark pointer bt role_manager_id" href="javascript:void(0)" data-toggle="modal" data-target="#exampleModal" data-id="<?php echo e($item->id); ?>" data-name="<?php echo e($item->role_name); ?>">
                                            <i class="far fa-edit"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-danger pointer bt" href="<?php echo e(url('/admin/deleteRole')); ?>/<?php echo e($item->id); ?>" onclick="return confirm('Are you sure you want to delete ?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>




<?php $__env->stopSection(); ?>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Role Manager</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('updateRole')); ?>" class="needs-validation" id="reload" novalidate method="post" autocomplete="off">
                <?php echo csrf_field(); ?>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="hidden" class="form-control" id="role_id" name="id">
                        <input type="text" class="form-control" id="role_name" name="name" required>
                        <div class="invalid-feedback">
                            Please provide a Role Manager.
                        </div>
                        <?php if($errors->has('name')): ?>
                        <span class="error_mess"><?php echo e($errors->first('name')); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="modal-footer">
                    <!--button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button-->
                    <button class="btn btn-primary" type="submit">Submit/दर्ज करे</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
            XLSX.writeFile(wb, fn || ('Role Manager List.' + (type || 'xlsx')));
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts/admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\khel-sathi-dev\resources\views/admin/role/index.blade.php ENDPATH**/ ?>