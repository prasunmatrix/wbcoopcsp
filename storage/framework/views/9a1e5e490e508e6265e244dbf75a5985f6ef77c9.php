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
                    <h3 class="box-title"><?php echo e($panel_title); ?></h3>
                </div> -->

                <div class="box-header">
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
                    </div>

                <?php echo e(Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.serviceprovider.addSubmit'],
                                    'name'  => 'addProjectTypeForm',
                                    'id'    => 'addProjectTypeForm',
                                    'files' => true,
		                            'novalidate' => true))); ?>

                    <div class="box-body cus-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Name">Name<span class="red_star">*</span></label>
                                    <?php echo e(Form::text('full_name', null, array(
                                                                'id' => 'full_name',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Name',
                                                                'required' => 'required' ))); ?>

                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="<?php echo e(route('admin.serviceprovider.list')); ?>" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', ['title' =>$panel_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/software/add.blade.php ENDPATH**/ ?>