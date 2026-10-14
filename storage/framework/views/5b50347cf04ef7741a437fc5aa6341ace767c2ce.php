<style>
    .profileicon {
        color: #c68686 !important
    }
</style>
<header class="header menuheader-width">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <h4 class="moudlename">
                    <div class="list-control">
                        <a class="sidemenutoggle">
                            <span class="icons icon-menu"></span>
                        </a>
                        <div class="menusidebar menusidebar-width">
                            <div class="profiletitle">
                                <img src="<?php echo e(asset('')); ?>/assets_admin/images/logo.png" alt=""
                                    class="dash-logo" />
                                <span class="logotxt">Department of Sports Government of Uttar Pradesh</span>
                            </div>
                            <div class="menuscrollwrap">
                                <div class="nano-content f">
                                    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.sidebar_left_menu','data' => []]); ?>
<?php $component->withName('sidebar_left_menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                </div>
                            </div>
                            <?php if(Auth::guard('admin')->user()): ?>
                                <div class="logoutbutton"><a href="<?php echo e(route('signOuts')); ?>"><span
                                            class="fas fa-power-off"></span>&nbsp;&nbsp;Logout</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    Department of Sports Government of Uttar Pradesh</span>
                </h4>
            </div>
            <div class="col-md-auto">
                <a href="javascript:void()" class="profileicon">
                    <span class="icons icon-user"></span>
                </a>
                <?php //echo '<pre>'; print_r(Auth::guard('admin')->user()); die;
                ?>
                <div class="sidebar">
                    <div class="profiletitle">
                        <?php if(Auth::guard('admin')->user()): ?>
                            <?php echo e(Auth::guard('admin')->user()->username); ?>

                        <?php else: ?>
                            Darpan
                        <?php endif; ?>
                        <span class="text-uppercase">
                            Last Login/पिछली बार लॉगिन का समय :
                            <?php echo e(\Carbon\Carbon::parse(Auth::guard('admin')->user()->last_login)->format('d M Y h:i A')); ?>

                        </span>
                    </div>
                    <div class="scrollwrap">
                        <div class="nano-content">
                            <ul class="navsidebar">
                                <li class="nav-item"><a href="<?php echo e(route('adminprofile')); ?>"><span
                                            class="icons icon-user"></span> Profile</a> </li>
                                <li class="nav-item"><a href="<?php echo e(route('adminChangePassword')); ?>"><span
                                            class="icons icon-lock"></span> Change Password</a> </li>
                                <!-- <li class="nav-item"><a href="<?php echo e(route('changePassword')); ?>"><span class="icons icon-lock"></span>  Change Password</a> </li> -->
                            </ul>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH D:\khel-sathi-dev\resources\views/components/adminnav.blade.php ENDPATH**/ ?>