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
        <li><a href="<?php echo e(route('admin.complain.list')); ?>"><i class="fa fa-cubes"></i>Complain List</a></li>
        <li class="active"><?php echo e($page_title); ?></li>
    </ol>
</section>

<section class="content cus-listing">
<div class="row">
    <div class="col-sm-5" ><strong>Submitted On :</strong></div>
    <div class="col-sm-7" ><?php echo e(Helper::formattedDateTime(strtotime($saleData->created_at))); ?></div>
	</div>
<div class="row">
    <div class="col-sm-5" ><strong>Reported By :</strong></div>
    <div class="col-sm-7" ><?php echo e($saleData->userDetail->full_name); ?></div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Reported To :</strong></div>
    <div class="col-sm-7" >
        <?php if($saleData->user_type == 5): ?> 
            <?php if(isset($saleData->userServiceProvider->full_name)): ?>
                <?php if($saleData->userServiceProvider->full_name != NULL || $saleData->userServiceProvider->full_name !=''): ?>
                    <?php echo e($saleData->userServiceProvider->full_name); ?>

                <?php else: ?>
                    NA     
                <?php endif; ?>
            <?php else: ?>
                NA
            <?php endif; ?>
        <?php else: ?>
        
        <?php if(isset($saleData->userDetails->full_name)): ?>
                <?php if($saleData->userDetails->full_name != NULL || $saleData->userDetails->full_name !=''): ?>
                    <?php echo e($saleData->userDetails->full_name); ?>

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
    <div class="col-sm-7" ><?php echo e($saleData->ticket_no); ?></div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Description :</strong></div>
    <div class="col-sm-7" ><?php echo e($saleData->report_details); ?></div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Disposing Note :</strong></div>
    <div class="col-sm-7" ><?php if(isset($saleData->disposing_note)): ?><?php echo e($saleData->disposing_note); ?><?php else: ?> NA <?php endif; ?></div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Disposed On :</strong></div>
    <div class="col-sm-7" ><?php if(isset($saleData->updated_at)): ?><?php echo e(Helper::formattedDateTime(strtotime($saleData->updated_at))); ?><?php else: ?> NA <?php endif; ?></div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Disposed By :</strong></div>
    <div class="col-sm-7" ><?php if(isset($saleData->userDetailReply->full_name)): ?><?php echo e($saleData->userDetailReply->full_name); ?><?php else: ?> NA <?php endif; ?></div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Status :</strong></div>
    <div class="col-sm-7" >
      <?php if(isset($saleData->status)): ?>
            <?php if($saleData->status!=null || $saleData->status!=''): ?>
              <?php if($saleData->status == '1'): ?>
              <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="<?php echo e(route('admin.complain.changestatus', [$saleData->id])); ?>" title=""><span class="yellowBt">Ongoing</span></a>
              <?php elseif($saleData->status == '2'): ?>

                  <span class="rdBt">Closed</span>
              <?php else: ?>
                <span class="greenBt" style="color:red">Open</span>
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
        <a href="<?php echo e(route('admin.complain.list')); ?>" class="btn btn-info">Back</a>
      </div>
    </div>
    <?php if($saleData->status != '1'): ?>
    <?php if(\Auth::guard('admin')->user()->id == $saleData->reported_to): ?>
    <div class="col-md-6 text-right">
    
    <a class="btn btn-info" href="<?php echo e(route('admin.complain.edit', [$saleData->id])); ?>" title="Reply to Complain ">
        <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
    </a>
    </div>
    <?php endif; ?>
    <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', ['title' => $panel_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/complain/view.blade.php ENDPATH**/ ?>