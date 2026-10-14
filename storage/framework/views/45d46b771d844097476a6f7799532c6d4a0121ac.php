<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title inertia>Official Web Portal of Department of Sports, Government of Uttar Pradesh, India</title>

    <!-- Theme & Portal Styles -->
    <link href="<?php echo e(asset('admin/css/bootstrap.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('admin/css/font.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('admin/css/all.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('admin/css/custom_theme.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('admin/css/default.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('admin/css/responsive.css')); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Vite & Asset Management -->
    <?php if(file_exists(public_path('hot'))): ?>
    <?php
    $viteUrl = trim(file_get_contents(public_path('hot')));
    ?>
    <script type="module">
        import RefreshRuntime from "<?php echo e($viteUrl); ?>/@react-refresh";
        RefreshRuntime.injectIntoGlobalHook(window);
        window.$RefreshReg$ = () => {};
        window.$RefreshSig$ = () => (type) => type;
        window.__vite_plugin_react_preamble_installed__ = true;
    </script>
    <script type="module" src="<?php echo e($viteUrl); ?>/@vite/client"></script>
    <link rel="stylesheet" href="<?php echo e($viteUrl); ?>/resources/css/app.css" />
    <script type="module" src="<?php echo e($viteUrl); ?>/resources/js/app.jsx"></script>
    <?php elseif(file_exists(public_path('build/manifest.json'))): ?>
    <?php
    $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
    ?>
    <?php if(isset($manifest['resources/css/app.css']['file'])): ?>
    <link rel="stylesheet" href="<?php echo e(asset('build/' . $manifest['resources/css/app.css']['file'])); ?>" />
    <?php endif; ?>
    <?php if(isset($manifest['resources/js/app.jsx']['css'])): ?>
    <?php $__currentLoopData = $manifest['resources/js/app.jsx']['css']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cssFile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <link rel="stylesheet" href="<?php echo e(asset('build/' . $cssFile)); ?>" />
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <?php if(isset($manifest['resources/js/app.jsx']['file'])): ?>
    <script type="module" src="<?php echo e(asset('build/' . $manifest['resources/js/app.jsx']['file'])); ?>"></script>
    <?php endif; ?>
    <?php elseif(file_exists(public_path('mix-manifest.json'))): ?>
    <link rel="stylesheet" href="<?php echo e(mix('css/app.css')); ?>">
    <script src="<?php echo e(mix('js/app.js')); ?>" defer></script>
    <?php else: ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
    <?php endif; ?>

    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
</head>

<body class="font-sans antialiased">
    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
</body>

</html><?php /**PATH D:\khel-sathi-dev\resources\views/app.blade.php ENDPATH**/ ?>