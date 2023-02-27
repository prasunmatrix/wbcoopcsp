<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php echo e($rangeUserDetails['page_title']); ?>

    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo e($rangeUserDetails['page_title']); ?></li>
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
                <form method="POST" name="add_range_details" id="add_range_details" action="<?php echo e(route('admin.range.rangeSubmit')); ?>" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?> 
                        <div class="card-body">
                        <div class="row">  
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Name<span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="full_name" id="full_name" value="<?php echo e(old('full_name')); ?>">
                                
                                <!-- <?php if($errors->has('full_name')): ?>
                                   <span class="error"><?php echo e($errors->first('full_name')); ?></span>
                                <?php endif; ?> -->
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="point">Email <span class="red_star">*</span></label>
                                    <input type="text" class="form-control" name="email" id="email" value="<?php echo e(old('email')); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="point">Phone No <span class="red_star">*</span></label>
                                    <input type="text" class="form-control" name="phone_no" id="phone_no" value="<?php echo e(old('phone_no')); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">  
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="name">Zone Name <span class="red_star">*</span></label>
                                <select name="zone_id" id="zone_id" class="form-control">
                                    <option value=""> Select Zone</option>
                                    <?php $__currentLoopData = @$zoneList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bankValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($bankValue->id); ?>"><?php echo e(@$bankValue->full_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="name">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" id="profile_image">
                                </div>
                                
                            </div>
                           
                        </div>
                        
                        <div class="row">  
                           
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Office address <span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="address" id="address" value="<?php echo e(old('address')); ?>">
                                </div>
                            </div>
                        </div>

                                        
                        <input type="hidden" name="user_id" id="user_id" value="<?php echo e(@$rangeUserDetails->id); ?>">
                        <!-- /.card-body -->
                        <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Add Details</button>
                        <a href="<?php echo e(route('admin.range.list')); ?>" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>

<script type="text/javascript">
    function getZone(bank_id){

     $.ajax({
       
        url: "<?php echo e(route('admin.range.getZone')); ?>",
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
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', ['title' =>$rangeUserDetails['panel_title']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/range_user/add.blade.php ENDPATH**/ ?>