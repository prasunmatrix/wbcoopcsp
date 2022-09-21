<?php $__env->startSection('content'); ?>
<?php
$arr = ['0' => 'No', '1'=>'Yes'	]

?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Dashboard of <strong> <?php echo e(Helper::getAppName()); ?> </strong></h1>
  <ol class="breadcrumb">
      <li><a><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Dashboard</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                
                
                    <div class="respons-table">
                        
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                            Name of PACS
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1">
                                            <?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('userDistrict.district_name', 'District'));?>
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >
                                            <?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('userBlock.block_name', 'Block'));?>
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                            Type of Society
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                            Unique Id of PACS
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                            Confirmation Done By PACS
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                            Service Provider of PACS
                                        </th>
                                        
                                        
                                        
                                    </tr>

                                    </thead>
                                    <?php if(isset($list)): ?>
                                    <?php if(count($list)>0): ?>
                                    <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <tbody>
                                        <tr role="row" class="odd">
                                            <td class="sorting_1">
                                                <?php echo e($row->userDetail->full_name); ?>

                                            </td>   
                                            
                                            <td class="sorting_1">
                                                    <?php if(isset($row->district_id)): ?>
                                                        <?php if($row->district_id != NULL || $row->district_id !=''): ?>
                                                            <?php echo e($row->userDistrict->district_name); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                <?php endif; ?> 
                                            </td>
                                            <td class="sorting_1">
                                                    <?php if(isset($row->block_id)): ?>
                                                        <?php if($row->block_id != NULL || $row->userProfile->block_id !=''): ?>
                                                            <?php echo e($row->userBlock->block_name); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                <?php endif; ?> 
                                            </td>
                                            <td class="sorting_1">
                                                    <?php if(isset($row->socity_type)): ?>
                                                        <?php if($row->socity_type != NULL || $row->socity_type !=''): ?>
                                                            <?php echo e($row->userSocietie->name); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                <?php endif; ?> 
                                            </td>
                                            <td class="sorting_1">
                                                    <?php if(isset($row->unique_id)): ?>
                                                        <?php if($row->unique_id != NULL || $row->unique_id !=''): ?>
                                                            <?php echo e($row->unique_id); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                <?php endif; ?> 
                                            </td>
                                            <td class="sorting_1">
                                                    <?php if(isset($row->pacs_using_software)): ?>
                                                        <?php if($row->pacs_using_software != NULL || $row->pacs_using_software !=''): ?>
                                                            <?php echo e($arr[$row->pacs_using_software]); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                <?php endif; ?> 
                                            </td>

                                            <td class="sorting_1">
                                                    <?php if(isset($row->software_using)): ?>
                                                        <?php if($row->software_using != NULL || $row->software_using !=''): ?>
                                                            <?php echo e($row->userSoftware->full_name); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                <?php endif; ?> 
                                            </td>
                                           
                                           
                                           
                                            
                                           
                                            
                                            
                                            
                                            
                                        </tr>
                                        
                                       
                                    </tbody>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                            <?php else: ?>
                            <tr><td colspan="8" style="text-align: center;">No Records Found</td></tr>   
                            <?php endif; ?>
                            </table>
                        <?php else: ?>
                            <p>No User Input found</p>        
                        <?php endif; ?>            
                    </div>
                <?php if(count($list) > 0): ?>
                <div class="box-footer">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                            <strong>Show Records:</strong>
                            <strong style="color:green"><?php echo e($list->firstItem()); ?></strong>
                                To
                            <strong style="color:green"><?php echo e($list->lastItem()); ?></strong>
                                Total
                            <strong style="color:green"><?php echo e($list->total()); ?></strong>
                                Entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            <?php echo e($list->appends(request()->input())->links("pagination::bootstrap-4")); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>
<!-- /.content -->
<?php if(count($list)>0): ?>
<section class="content cus-listing">
<?php if(\Auth::guard('admin')->user()->user_type != '4'): ?>                                              
  <div class="row">
  <div class="col-sm-12">
    <div class="col-sm-3"><strong>Service Provider</strong></div>
    <div class="col-sm-9"><strong>Cumulative Number of PACS</strong></div>
    </div>
    </div>
   
    <?php $__currentLoopData = $softwareDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
    <div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
        <?php echo e($row->userSoftware->full_name); ?> 
        </div>
        <div class="col-md-9">
         <?php echo e($row->total); ?>

        </div>
    </div>
    </div>
    
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
            Total 
        </div>
        <div class="col-md-9">
        <?php echo e($test); ?>

        </div>
    </div>
    </div>
   
    <?php endif; ?>
    
  
  
</section>
<?php endif; ?>


<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.layouts.app', ['title' => $panel_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/account/dashboard.blade.php ENDPATH**/ ?>