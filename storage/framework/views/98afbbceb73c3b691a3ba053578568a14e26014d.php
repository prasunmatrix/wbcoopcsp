<?php $__env->startSection('content'); ?>
<?php
$logginUser = AdminHelper::loggedUser()

?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo e($data['page_title']); ?>

    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo e($data['page_title']); ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <?php echo $__env->make('admin.elements.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                
                <?php echo e(Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.complain.editSubmit', $details["id"]],
                                    'id'    => 'replyComplainForm',
                                    'files' => true,
		                            'novalidate' => true))); ?>

                    <div class="box-body cus-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Submitted On : </label>
                                    <?php echo e(Helper::formattedDateTime(strtotime($details->created_at))); ?>

                                </div>
                             </div>
                            </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Reported By : </label>
                                    <?php echo e($details->userDetail->full_name); ?>

                                </div>
                             </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Reported To : </label>
                                    <?php if($details->user_type == 5): ?> 
                                        <?php if(isset($details->userServiceProvider->full_name)): ?>
                                            <?php if($details->userServiceProvider->full_name != NULL || $details->userServiceProvider->full_name !=''): ?>
                                                <?php echo e($details->userServiceProvider->full_name); ?>

                                            <?php else: ?>
                                                NA     
                                            <?php endif; ?>
                                        <?php else: ?>
                                            NA
                                        <?php endif; ?>
                                    <?php else: ?>
                                    
                                    <?php if(isset($details->userDetails->full_name)): ?>
                                            <?php if($details->userDetails->full_name != NULL || $details->userDetails->full_name !=''): ?>
                                                <?php echo e($details->userDetails->full_name); ?>

                                            <?php else: ?>
                                                NA     
                                            <?php endif; ?>
                                        <?php else: ?>
                                            NA
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                             </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Ticket No : </label>
                                    <?php echo e($details->ticket_no); ?>

                                </div>
                             </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Description : </label>
                                    <?php echo e($details->report_details); ?>

                                </div>
                             </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Disposing Note<span class="red_star">*</span></label>
                                    <?php echo e(Form::textarea('disposing_note', $details->disposing_note, array(
                                                                'id' => 'disposing_note',
                                                                'placeholder' => 'Disposing Note',
                                                                'class' => 'form-control',
                                                                'rows'  => 3,
                                                                'required' => 'required'
                                                                 ))); ?>

                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" title="Submit">Reply</button>
                            <a onclick="window.history.back()" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                            <!-- <a href="<?php echo e(route('admin.complain.alllreply').'?page='.$data['pageNo']); ?>" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a> -->
                        </div>
                    </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', ['title' => $data['panel_title']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/complain/edit.blade.php ENDPATH**/ ?>