<?php $__env->startSection('content'); ?>


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo e($page_title); ?></h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo e($page_title); ?></li>
    </ol>
</section>
<section>
    <div class="row-mb-2">
        <div class="col-sm-8">
            <?php echo $__env->make('admin.elements.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</section>
<!-- Content Header (Page header) -->

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo e($panel_title); ?></h3>
                    <div class="row">
                        <form name="filter" id="filter" method="GET" action="<?php echo e(route('admin.serviceprovider.list')); ?>">
                           
                            <div class="col-sm-6">
                                <label class="w-100">Name 
                                    <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1" name="searchText" id="searchText" value="<?php if(isset($searchText)): ?> <?php echo e($searchText); ?><?php endif; ?>" style="margin-top:3px;">
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="submit" name="filter" value="Filter" class="btn btn-primary modal-action-btn" style="margin-top:20px;">   
                                <a class="btn btn-primary modal-action-btn" href="<?php echo e(route('admin.serviceprovider.list')); ?>" style="margin-top:20px;">Reset</a>
                                
                            </div>
                            <div class="col-sm-2">   
                                <a class="btn btn-primary modal-action-btn" href="<?php echo e(route('admin.serviceprovider.add')); ?>" style="margin-top:20px;">Add Service Provider</a>
                                
                            </div>
                        </form>
                    </div>
                </div>
                
                    <div class="respons-table">
                        
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                            Name
                                        </th>
                                        
                                        
                                        
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Status
                                        </th>
                                        
                                        
                                        
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Action
                                        </th>
                                        
                                        
                                    </tr>
                                    </thead>
                                    <?php if(isset($list)): ?>
                                     <?php if(count($list)>0): ?>
                                    <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tbody>
                                        <tr role="row" class="odd">
                                            <td class="sorting_1">
                                                <?php if(isset($row->full_name)): ?>
                                                    <?php if($row->full_name != NULL || $row->full_name !=''): ?>
                                                        <?php echo e($row->full_name); ?>

                                                    <?php else: ?>
                                                        No records found     
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    No records found
                                                <?php endif; ?>
                                            </td>
                                           
                                           
                                           
                                            <td>
                                                <?php if(isset($row->status)): ?>
                                                    <?php if($row->status!=null || $row->status!=''): ?>
                                                    <?php if($row->status == '1'): ?>
                                                        <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="<?php echo e(route('admin.serviceprovider.status', [$row->id])); ?>" title="Change Status" style="color:green"><i class="fa fa-check" aria-hidden="true"></i></a>
                                                    <?php else: ?>
                                                        <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="<?php echo e(route('admin.serviceprovider.status', [$row->id])); ?>"  title="Change Status" style="color:red">X</a>
                                                    <?php endif; ?>  
                                                    <?php else: ?>
                                                        No records found
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    No records found
                                                <?php endif; ?>
                                            </td>
                                           
                                            
                                            
                                            <td>
                                                <button>
                                                
                                                <a href="<?php echo e(route('admin.serviceprovider.edit', [$row->id])); ?>" title="Edit">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                                
                                                </button>
                                               
                                           
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


<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.layouts.app', ['title' => $panel_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/software/list.blade.php ENDPATH**/ ?>