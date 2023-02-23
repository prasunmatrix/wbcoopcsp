<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Administrator - <?php if($title): ?><?php echo e($title); ?> <?php else: ?> <?php echo e(Helper::getAppName()); ?> <?php endif; ?></title>

    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('images/site/favicon.png')); ?>"/>
    <!-- Styles -->
    <link href="<?php echo e(asset('css/admin/bower_components/bootstrap/dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo e(asset('css/admin/bower_components/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet">
    <!-- Ionicons -->
    <link href="<?php echo e(asset('css/admin/bower_components/Ionicons/css/ionicons.min.css')); ?>" rel="stylesheet">
    <!-- Theme style -->
    <link href="<?php echo e(asset('css/admin/dist/css/AdminLTE.min.css')); ?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo e(asset('css/admin/plugins/iCheck/square/blue.css')); ?>" rel="stylesheet">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- Scripts -->
    <!-- jQuery 3 -->
    <script src="<?php echo e(asset('js/admin/bower_components/jquery/dist/jquery.min.js')); ?>"></script>
    
    <!-- Bootstrap 3.3.7 -->
    <script src="<?php echo e(asset('js/admin/bower_components/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
    <!-- iCheck -->
    <!-- <script src="<?php echo e(asset('js/admin/plugins/iCheck/icheck.min.js')); ?>"></script>
    <script>
    $(function () {
        $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
        });
    });
    </script> -->

    <script src="<?php echo e(asset('js/admin/jquery.validate.min.js')); ?>"></script>

    <link href="<?php echo e(asset('css/admin/development_admin.css')); ?>" rel="stylesheet">

</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="logo-area">
            <a href="#"><img src="<?php echo e(asset('images/admin/logo.gif')); ?>" alt="" style="
    max-width: 100%;
"></a>
        </div>
        <div class="login-form-content">
            
            <!-- /.login-logo -->
            <div class="login-box-body">
                <?php echo $__env->yieldContent('content'); ?>            
            </div>
            <!-- /.login-box-body -->
        </div>

    </div>

    <script src="<?php echo e(asset('js/admin/development_admin.js')); ?>"></script>
</body>
</html><?php /**PATH C:\xampp\htdocs\matrixmedia\wbcoopcsp\resources\views/admin/layouts/login.blade.php ENDPATH**/ ?>