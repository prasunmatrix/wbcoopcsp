<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo e($data['page_title']); ?>

    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo e($data['page_title']); ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!-- <div class="box-header with-border">
                    
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
                                    'route' => ['admin.site-settings'],
                                    'name'  => 'updateSiteSettingsForm',
                                    'id'    => 'updateSiteSettingsForm',
                                    'files' => true,
		                            'novalidate' => true))); ?>

                    <div class="box-body cus-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="FirstName">From Email<span class="red_star">*</span></label>
                                    <?php echo e(Form::text('from_email', $data['from_email'], array(
                                                                'id' => 'from_email',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'From Email',
                                                                'required' => 'required' ))); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="LastName">To Email<span class="red_star">*</span></label>
                                    <?php echo e(Form::text('to_email', $data['to_email'], array(
                                                                'id' => 'to_email',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'To Email',
                                                                'required' => 'required' ))); ?>

                                </div>
                            </div>
                        </div>
                        
                        

                        

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Address</label>
                                    <?php echo e(Form::textarea('address', $data['address'], array(
                                                                'id' => 'address',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'placeholder' => 'Address' ))); ?>

                                </div>
                            </div>     
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Website Title For Email<span class="red_star">*</span></label>
                                    <?php echo e(Form::text('website_title', $data['website_title'], array(
                                                                'id' => 'website_title',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Website Title',
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
<?php echo $__env->make('admin.layouts.app', ['title' => $data['panel_title']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matrixmedia\wbcoopcsp\resources\views/admin/account/site_settings.blade.php ENDPATH**/ ?>