<?php $__env->startSection('content'); ?>

<section class="content-header">
    <h1>
        <?php echo e($page_title); ?>

    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo e(route('admin.complain.list')); ?>"><i class="fa fa-inbox" aria-hidden="true"></i> Complain List</a></li>
        <li class="active"><?php echo e($page_title); ?></li>
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
                                    'route' => ['admin.complain.addSubmit'],
                                    'name'  => 'addMessageForm',
                                    'id'    => 'addMessageForm',
                                    'files' => true,
                                    'novalidate' => true))); ?>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Enter Details<span class="red_star">*</span></label>
                                    <?php echo e(Form::textarea('report_details', null, array(
                                                                'id' => 'report_details',
                                                                'placeholder' => 'Text',
                                                                'class' => 'form-control',
                                                                'rows'  => 4,
                                                                'required' => 'required'
                                                                 ))); ?>

                                </div>
                            </div>
                              
                        </div>
                        <div class="row">
                            <div class="col-md-6">            
                                <div class="form-group">
                                    <label for="title">Addressed To<span class="red_star">*</span></label>
                                    <select id="user_type" name="user_type"  class="form-control">
                                        <option value="">Select</option>
                                        <option value="1">Bank</option>
                                        <option value="2">Zone</option>
                                        <option value="3">Range</option>
                                        <option value="4">Pacs</option>
                                        <option value="5">Service Provider</option>
                                        
                                            
                                    </select>
                                </div>
                                                            
                            </div>
                            <div class="col-md-6" id="bankuser" style="display:none">
                                <div class="form-group">
                                <label for="title">Bank<span class="red_star">*</span></label>
                                    <select name="bank_id" id="bank_id" class="form-control common" required>
                                        <option value="">-Select-</option>
                                <?php if(count($bankList)): ?>
                                    <?php $__currentLoopData = $bankList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>"><?php echo e($state->full_name); ?> (<?php echo e(@$state->UserProfile->ifsc_code); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="zoneuser" style="display:none">
                                <div class="form-group">
                                <label for="title">Zone<span class="red_star">*</span></label>
                                    <select name="zone_id" id="zone_id" class="form-control common"required>
                                        <option value="">-Select-</option>
                                <?php if(count($zoneList)): ?>
                                    <?php $__currentLoopData = $zoneList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>"><?php echo e($state->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="rangeuser" style="display:none">
                                <div class="form-group">
                                <label for="title">Range<span class="red_star">*</span></label>
                                    <select name="range_id" id="range_id" class="form-control common" required>
                                        <option value="">-Select-</option>
                                <?php if(count($rangeList)): ?>
                                    <?php $__currentLoopData = $rangeList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>"><?php echo e($state->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="paceuser" style="display:none">
                                <div class="form-group">
                                <label for="title">PACS<span class="red_star">*</span></label>
                                    <select name="pacs_id" id="pacs_id" class="form-control common"  required>
                                        <option value="">-Select-</option>
                                <?php if(count($paceList)): ?>
                                    <?php $__currentLoopData = $paceList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>"><?php echo e($state->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="serviceuser" style="display:none">
                                <div class="form-group">
                                <label for="title">Service Provider<span class="red_star">*</span></label>
                                    <select name="service_provider_id" id="service_provider_id" class="form-control common"  required>
                                        <option value="">-Select-</option>
                                <?php if(count($serviceProviderList)): ?>
                                    <?php $__currentLoopData = $serviceProviderList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>"><?php echo e($state->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                        <input type="hidden" name="reported_to" id="reported_to">    
                        </div>
                       
                    </div>
                                            
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button onclick="return confirm('Are you sure ? Want to confirm ticket!!')" type="submit" class="btn btn-primary">Submit</button>
                            <a href="<?php echo e(route('admin.complain.list')); ?>" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</section>


<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
<script>
$(document).ready(function(){
 
 
 $('#user_type').on('change', function() {
       var select_user_type = $(this).val();
       
       if(select_user_type == '1'){
           $('#bankuser').show();
           $('#zoneuser').hide();
           $('#rangeuser').hide();
           $('#paceuser').hide();
           $('#serviceuser').hide();
       } else if (select_user_type == '2') {
            $('#bankuser').hide();
            $('#zoneuser').show();
            $('#rangeuser').hide();
            $('#paceuser').hide();
            $('#serviceuser').hide();
       } else if (select_user_type == '3') {
            $('#bankuser').hide();
            $('#zoneuser').hide();
            $('#rangeuser').show();
            $('#paceuser').hide();
            $('#serviceuser').hide();
       } else if (select_user_type == '4') {
            $('#bankuser').hide();
            $('#zoneuser').hide();
            $('#rangeuser').hide();
            $('#paceuser').show();
            $('#serviceuser').hide();
       }
       else if (select_user_type == '5') {
            $('#bankuser').hide();
            $('#zoneuser').hide();
            $('#rangeuser').hide();
            $('#paceuser').hide();
            $('#serviceuser').show();
       }
       
   });

   $('.common').on('change', function() {
       var reported_to = $(this).val();
       
       $('#reported_to').val(reported_to);
   });
 
 
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', ['title' => $panel_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/complain/add.blade.php ENDPATH**/ ?>