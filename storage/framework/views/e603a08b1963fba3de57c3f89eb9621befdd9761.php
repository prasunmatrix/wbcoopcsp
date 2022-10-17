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
                <div class="box-header with-border">
                    
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
                <form method="POST" name="edit_bank_details" id="edit_bank_details" action="<?php echo e(route('admin.bank.editSubmit')); ?>" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?> 
                        <div class="card-body">
                        <div class="row">  
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Name<span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="full_name" id="full_name" value="<?php if(isset($bankUserDetails->full_name)): ?><?php echo e($bankUserDetails->full_name); ?><?php endif; ?>">

                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="point">Email<span class="red_star">*</span></label>
                                    <input type="text" class="form-control" name="email" id="email" readonly="" value="<?php if(isset($bankUserDetails->email)): ?><?php echo e($bankUserDetails->email); ?><?php endif; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="point">Phone No<span class="red_star">*</span></label>
                                    <input type="text" class="form-control" name="phone_no" id="phone_no" value="<?php if(isset($bankUserDetails->phone_no)): ?><?php echo e($bankUserDetails->phone_no); ?><?php endif; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">  
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">IFSC Code(Head Office)<span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" value="<?php if(isset($bankUserDetails->userProfile->ifsc_code)): ?><?php echo e($bankUserDetails->userProfile->ifsc_code); ?><?php endif; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" id="profile_image">
                                </div>
                                <?php
                                $imgPath = \URL:: asset('images').'/admin/'.Helper::NO_IMAGE;
                                if (@$bankUserDetails->userProfile->profile_image != null) {
                                    if(file_exists(public_path('/uploads/member'.'/'.@$bankUserDetails->userProfile->profile_image))) {
                                    $imgPath = \URL::asset('uploads/member').'/'.@$bankUserDetails->userProfile->profile_image;
                                    }
                                }
                                ?>
                                <img src="<?php echo e($imgPath); ?>" alt="" height="50px">
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Office address<span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="address" id="address" value="<?php if(isset($bankUserDetails->userProfile->address)): ?><?php echo e($bankUserDetails->userProfile->address); ?><?php endif; ?>">
                                </div>
                            </div>
                        </div>
                       
                            
                           
                        </div>
                                        
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo e($bankUserDetails->id); ?>">
                        <!-- /.card-body -->
                        <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Edit Details</button>
                        <a href="<?php echo e(route('admin.bank.list')); ?>" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', ['title' =>$data['panel_title']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/bank_user/edit.blade.php ENDPATH**/ ?>