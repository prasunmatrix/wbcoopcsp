<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo e($data['page_title']); ?>

    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo e(route('admin.pacs.list')); ?>"><i class="fa fa-home" aria-hidden="true"></i>Pacs List</a></li>
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
                                    'route' => ['admin.pacs.editSubmit', $details["id"]],
                                    'title'  => 'editCityForm',
                                    'id'    => 'editCityForm',
                                    'files' => true,
		                            'novalidate' => true))); ?>

                    <div class="box-body cus-body">
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Full Name<span class="red_star">*</span></label>
                                <?php echo e(Form::text('full_name', $details->full_name, array(
                                                                'id' => 'full_name',
                                                                'placeholder' => 'Name',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 ))); ?> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Email<span class="red_star">*</span></label>
                                <?php echo e(Form::text('email', $details->email, array(
                                                                'id' => 'email',
                                                                'placeholder' => 'Email',
                                                                'class' => 'form-control'
                                                                
                                                                 ))); ?>

                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Phone Number<span class="red_star">*</span></label>
                                <?php echo e(Form::text('phone_no', $details->phone_no, array(
                                                                'id' => 'phone_no',
                                                                'placeholder' => 'Phone Number',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 ))); ?> 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Unique ID</label>
                                <?php echo e(Form::text('unique_id', $details->userProfile['unique_id'], array(
                                                                'id' => 'unique_id',
                                                                'placeholder' => 'Unique ID',
                                                                'class' => 'form-control'
                                                                 ))); ?> 
                                </div>
                            </div>
                            
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Bank<span class="red_star">*</span></label>
                                    <select name="bank_id" id="bank_id" class="form-control" value="<?php echo e(old('bank_id')); ?>" required>
                                        <option value="">-Select-</option>
                                <?php if(count($bankList)): ?>
                                    <?php $__currentLoopData = $bankList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>" <?php if($state->id == $details->userProfile['bank_id'] ): ?> selected="selected" <?php endif; ?>><?php echo e($state->full_name); ?> (<?php echo e($state->userProfile->ifsc_code); ?>)</option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Zone<span class="red_star">*</span></label>
                                    <select name="zone_id" id="zone_id" class="form-control" value="<?php echo e(old('zone_id')); ?>" required>
                                        <option value="">-Select-</option>
                                <?php if(count($zoneList)): ?>
                                    <?php $__currentLoopData = $zoneList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>" <?php if($state->id == $details->userProfile['zone_id'] ): ?> selected="selected" <?php endif; ?>><?php echo e($state->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Range<span class="red_star">*</span></label>
                                    <select name="range_id" id="range_id" class="form-control" value="<?php echo e(old('range_id')); ?>">
                                        <option value="">-Select-</option>
                                <?php if(count($rangeList)): ?>
                                    <?php $__currentLoopData = $rangeList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>" <?php if($state->id == $details->userProfile['range_id'] ): ?> selected="selected" <?php endif; ?>><?php echo e($state->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">District<span class="red_star">*</span></label>
                                    <select name="district_id" id="district_id" class="form-control" value="<?php echo e(old('district_id')); ?>"  required>
                                        <option value="">-Select-</option>
                                <?php if(count($districtList)): ?>
                                    <?php $__currentLoopData = $districtList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>" <?php if($state->id == $details->userProfile['district_id'] ): ?> selected="selected" <?php endif; ?>><?php echo e($state->district_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Block</label>
                                <?php echo e(Form::text('block', $details->userProfile['block'], array(
                                                                'id' => 'block',
                                                                'placeholder' => 'Block',
                                                                'class' => 'form-control'
                                                                 ))); ?> 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Service Provider</label>
                                    <select name="software_using" id="software_using" class="form-control" value="<?php echo e(old('software_using')); ?>">
                                        <option value="">-Select-</option>
                                <?php if(count($softwareList)): ?>
                                    <?php $__currentLoopData = $softwareList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>" <?php if($state->id == $details->userProfile['software_using'] ): ?> selected="selected" <?php endif; ?>><?php echo e($state->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="class row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Address<span class="red_star">*</span></label>
                                    <?php echo e(Form::textarea('address', $details->userProfile->address, array(
                                                                'id' => 'address',
                                                                'placeholder' => 'Address',
                                                                'class' => 'form-control',
                                                                'rows'  => 3,
                                                                'required' => 'required'
                                                                 ))); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Society Type<span class="red_star">*</span></label>
                                        <select name="socity_type" id="socity_type" class="form-control" value="<?php echo e(old('socity_type')); ?>" required>
                                            <option value="">-Select-</option>
                                                <?php if(count($societiesList)): ?>
                                                    <?php $__currentLoopData = $societiesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($state->id); ?>" <?php if($state->id == $details->userProfile['socity_type'] ): ?> selected="selected" <?php endif; ?>><?php echo e($state->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="class row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Society Registration No</label>
                                    <?php echo e(Form::text('socity_registration_no', $details->userProfile->socity_registration_no, array(
                                                                'id' => 'socity_registration_no',
                                                                'placeholder' => 'Society Registration No',
                                                                'class' => 'form-control'
                                                                 ))); ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">E District Registration No</label>
                                    <?php echo e(Form::text('district_registration_no', $details->userProfile->district_registration_no, array(
                                                                'id' => 'district_registration_no',
                                                                'placeholder' => 'District Registration No',
                                                                'class' => 'form-control'
                                                                 ))); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="UploadBannerWeb">Profile Image</label>                                        
                                        <?php echo e(Form::file('profile_image', array(
                                                                'id' => 'profile_image',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Image' 
                                                                 ))); ?>

                                        </div>
                                        <div class="form-group">
                                <?php
                                $imgPath = \URL:: asset('images').'/admin/'.Helper::NO_IMAGE;
                                if ($details->userProfile->profile_image != null) {
                                    if(file_exists(public_path('/uploads/member'.'/'.$details->userProfile->profile_image))) {
                                    $imgPath = \URL::asset('uploads/member').'/'.$details->userProfile->profile_image;
                                    }
                                }
                                ?>
                                <img src="<?php echo e($imgPath); ?>" alt="" height="50px">
					            </div>
                                   
                            	    </div>
                                </div>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo e($details->id); ?>">
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" title="Submit">Update</button>
                            <a href="<?php echo e(route('admin.pacs.list').'?page='.$data['pageNo']); ?>" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<?php $__env->stopSection(); ?>

<script type="text/javascript">
    function getPacsZone(bank_id){

     $.ajax({
       
        url: "<?php echo e(route('admin.pacs.getPacsZone')); ?>",
        type:'get',
        dataType: "json",
        data:{bank_id:bank_id,_token:"<?php echo e(csrf_token()); ?>"}
       // data:{bank_id:bank_id}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allZone);
             var stringified = JSON.stringify(response.allZone);
            var zonedata = JSON.parse(stringified);
             var zone_list = '<option value=""> Select Zone</option>';
             $.each(zonedata,function(index, zone_id){
                    zone_list += '<option value="'+zone_id.id+'">'+ zone_id.full_name +'</option>';
             });
                $("#zone_id").html(zone_list);
            }
        });
    }

    function getPacsRange(zone_id){

$.ajax({
  
   url: "<?php echo e(route('admin.pacs.getPacsRange')); ?>",
   type:'get',
   dataType: "json",
   data:{zone_id:zone_id,_token:"<?php echo e(csrf_token()); ?>"}
  // data:{bank_id:bank_id}
   }).done(function(response) {
      
      console.log(response.status);
       if(response.status){
        console.log(response.allRange);
        var stringified = JSON.stringify(response.allRange);
       var zonedata = JSON.parse(stringified);
        var zone_list = '<option value=""> Select Range</option>';
        $.each(zonedata,function(index, range_id){
               zone_list += '<option value="'+range_id.id+'">'+ range_id.full_name +'</option>';
        });
           $("#range_id").html(zone_list);
       }
   });
}

function getPacsBlock(district_id){
    

$.ajax({
  
   url: "<?php echo e(route('admin.pacs.getPacsBlock')); ?>",
   type:'get',
   dataType: "json",
   data:{district_id:district_id,_token:"<?php echo e(csrf_token()); ?>"}
  // data:{bank_id:bank_id}
   }).done(function(response) {
      
      console.log(response.status);
       if(response.status){
        console.log(response.allBlock);
        var stringified = JSON.stringify(response.allBlock);
       var zonedata = JSON.parse(stringified);
        var zone_list = '<option value=""> Select Block</option>';
        $.each(zonedata,function(index, block_id){
               zone_list += '<option value="'+block_id.id+'">'+ block_id.block_name +'</option>';
        });
           $("#block_id").html(zone_list);
       }
   });
}
</script>
<?php echo $__env->make('admin.layouts.app', ['title' => $data['panel_title']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matrixmedia\wbcoopcsp\resources\views/admin/pacs/edit.blade.php ENDPATH**/ ?>