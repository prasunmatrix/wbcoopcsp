<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo e($page_title); ?>

    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo e($page_title); ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!-- <div class="box-header with-border">
                    <h3 class="box-title">Password</h3>
                </div> -->

                <div class="box-header">
                    <?php $__currentLoopData = ['danger', 'warning', 'success', 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(Session::has('alert-' . $msg)): ?>
                            <div class="alert alert-dismissable alert-<?php echo e($msg); ?>">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <span><?php echo e(Session::get('alert-' . $msg)); ?></span><br/>
                            </div>
                            <script>
                            $(document).ready(function(){
                                setTimeout(function () {
                                    <?php
                                    if($msg == 'success') {
                                        $url = route('admin.logout');
                                    ?>
                                        window.location.href = '<?php echo $url;?>';
                                    <?php
                                    }
                                    ?>
                                }, 2000);
                            });                            
                            </script>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span><?php echo e($error); ?></span><br/>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php echo e(Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.change-password'],
                                    'name'  => 'updateAdminPassword',
                                    'id'    => 'updateAdminPassword',
                                    'files' => true,
		                            'novalidate' => true))); ?>

                    <div class="box-body cus-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="OldPassword">Current Password<span class="red_star">*</span></label>
                                    <?php echo e(Form::password('current_password', array(
                                                                'id' => 'current_password',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Current Password',
                                                                'required' => 'required' ))); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="NewPassword">New Password<span class="red_star">*</span></label>
                                    <?php echo e(Form::password('password', array(
                                                                'id' => 'password',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'New Password',
                                                                'required' => 'required' ))); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ConfirmPassword">Confirm Password<span class="red_star">*</span></label>
                                    <?php echo e(Form::password('confirm_password', array(
                                                                'id' => 'confirm_password',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Confirm Password',
                                                                'required' => 'required' ))); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                            </div>
                        </div>
                    </div>
                <?php echo Form::close(); ?>


            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', ['title' => $panel_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/account/change_password.blade.php ENDPATH**/ ?>