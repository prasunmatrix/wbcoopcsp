<?php $__env->startSection('content'); ?>
<?php
$logginUser = AdminHelper::loggedUser()

?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo e($page_title); ?>

    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo e(route('admin.complain.alllreply')); ?>"><i class="fa fa-cubes"></i>Complain Reply</a></li>
        <li class="active"><?php echo e($page_title); ?></li>
    </ol>
</section>

<section class="content cus-listing">
<div class="row">
    <div class="col-sm-5" ><strong>Submitted On :</strong></div>
    <div class="col-sm-7" ><?php echo e(Helper::formattedDateTime(strtotime($replyData->created_at))); ?></div>
	</div>
<div class="row">
    <div class="col-sm-5" ><strong>Reported By :</strong></div>
    <div class="col-sm-7" ><?php echo e($replyData->userDetail->full_name); ?></div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Reported To :</strong></div>
    <div class="col-sm-7" >
      <?php if($replyData->user_type == 5): ?> 
            <?php if(isset($replyData->userServiceProvider->full_name)): ?>
                <?php if($replyData->userServiceProvider->full_name != NULL || $replyData->userServiceProvider->full_name !=''): ?>
                    <?php echo e($replyData->userServiceProvider->full_name); ?>

                <?php else: ?>
                    NA     
                <?php endif; ?>
            <?php else: ?>
                NA
            <?php endif; ?>
        <?php else: ?>
        
        <?php if(isset($replyData->userDetails->full_name)): ?>
                <?php if($replyData->userDetails->full_name != NULL || $replyData->userDetails->full_name !=''): ?>
                    <?php echo e($replyData->userDetails->full_name); ?>

                <?php else: ?>
                    NA     
                <?php endif; ?>
            <?php else: ?>
                NA
            <?php endif; ?>
      <?php endif; ?>
    </div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Ticket No :</strong></div>
    <div class="col-sm-7" ><?php echo e($replyData->ticket_no); ?></div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Description :</strong></div>
    <div class="col-sm-7" ><?php echo e($replyData->report_details); ?></div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Disposing Note :</strong></div>
    <div class="col-sm-7" ><?php if(isset($replyData->disposing_note)): ?><?php echo e($replyData->disposing_note); ?><?php else: ?> NA <?php endif; ?></div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Disposed On :</strong></div>
    <div class="col-sm-7" ><?php if(isset($replyData->updated_at)): ?><?php echo e(Helper::formattedDateTime(strtotime($replyData->updated_at))); ?><?php else: ?> NA <?php endif; ?></div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Disposed By :</strong></div>
    <div class="col-sm-7" ><?php if(isset($replyData->userDetailReply->full_name)): ?><?php echo e($replyData->userDetailReply->full_name); ?><?php else: ?> NA <?php endif; ?></div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Status :</strong></div>
    <div class="col-sm-7" >
      <?php if(isset($replyData->status)): ?>
            <?php if($replyData->status!=null || $replyData->status!=''): ?>
              <?php if($replyData->status == '1'): ?>
              <span class="yellowBt">Ongoing</span>
                <?php elseif($replyData->status == '2'): ?>
                <span class="rdBt">Closed</span>
              <?php else: ?>
                <span class="greenBt">Open</span>
              <?php endif; ?>  
            <?php else: ?>
                NA
            <?php endif; ?>
        <?php else: ?>
        NA
    <?php endif; ?>
    </div>
	</div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <a href="<?php echo e(route('admin.complain.alllreply')); ?>" class="btn btn-info">Back</a>
      </div>
    </div>
    <?php if($replyData->status != '1' && $replyData->status != '2'): ?>
    <?php if(\Auth::guard('admin')->user()->id == $replyData->reported_to): ?>
    <div class="col-md-6 text-right">
    
    <a class="btn btn-info" href="<?php echo e(route('admin.complain.edit', [$replyData->id])); ?>" title="Reply to Complain">
        <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
    </a>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', ['title' => $panel_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/complain/viewreply.blade.php ENDPATH**/ ?>