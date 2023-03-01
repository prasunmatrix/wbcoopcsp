<?php $__env->startSection('content'); ?>

    <!-- <p class="login-box-msg">Sign In</p> -->

    <?php $__currentLoopData = ['danger', 'warning', 'success', 'info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(Session::has('alert-' . $msg)): ?>
            <div class="alert alert-dismissable alert-<?php echo e($msg); ?>">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <span><?php echo e(Session::get('alert-' . $msg)); ?></span><br/>
            </div>
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

    <?php echo Form::open(array('name'=>'adminLoginForm','route' =>  ['admin.login'], 'id' => 'adminLoginForm')); ?>

        <div class="form-group has-feedback">
            <?php echo e(Form::text('email', null, array('required','class' => 'form-control','id' => 'email', 'placeholder' => 'Email'))); ?>

            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?php echo e(Form::password('password', array('required','class' => 'form-control','id' => 'password', 'placeholder' => 'Password'))); ?>

            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn _btn-primary _btn-block _btn-flat btn-submit">Login</button>
            </div>
        </div>
    <?php echo Form::close(); ?>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.login', ['title' => $page_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>