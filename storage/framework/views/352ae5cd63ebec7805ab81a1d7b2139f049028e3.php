<?php if($message = session()->get('success')): ?>
<script type="text/javascript">
    success("<?php echo e($message); ?>");
</script>
<?php endif; ?>
<?php if($message = session()->get('error')): ?>
<script type="text/javascript">
    error("<?php echo e($message); ?>");
</script>
<?php endif; ?>
<?php if($message = session()->get('warning')): ?>
<script type="text/javascript">
    warning("<?php echo e($message); ?>");
</script>
<?php endif; ?>
<?php if($message = session()->get('info')): ?>
<script type="text/javascript">
    info("<?php echo e($message); ?>");
</script>
<?php endif; ?>


<?php if(session()->has('success_marked')): ?>
<script type="text/javascript">
Swal.fire({
  position: 'top-end',
//   icon: 'success',
  title: "<?php echo e(session()->get('success_marked')); ?>",
  showConfirmButton: false,
  timer: 1500
})
</script>
 <?php endif; ?>

<div class="modal fade" id="query_form_reply" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="prodiv">
        <div class="modal-content" id="query_form_reply_append">
            
        </div>
    </div>
</div>


<div class="modal fade" id="query_form_iamge" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="query_form_image_append">
             
        </div>
    </div>
</div>
<?php /**PATH D:\khel-sathi-dev\resources\views/components/message.blade.php ENDPATH**/ ?>