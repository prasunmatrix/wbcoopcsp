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
            <form name="filter" id="filter" method="GET" action="<?php echo e(route('admin.bank.pacslist')); ?>">

              <div class="col-sm-6">
                <label class="w-100">[Name/Email/Phone No]
                  <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1" name="searchText" id="searchText" value="<?php if(isset($searchText)): ?> <?php echo e($searchText); ?><?php endif; ?>" style="margin-top:3px;">
                </label>
              </div>
              <div class="col-sm-4">
                <input type="submit" name="filter" value="Filter" class="btn btn-primary modal-action-btn" style="margin-top:20px;">
                <a class="btn btn-primary modal-action-btn" href="<?php echo e(route('admin.bank.pacslist')); ?>" style="margin-top:20px;">Reset</a>

              </div>
              <!-- <div class="col-sm-2">
                <a class="btn btn-primary modal-action-btn" href="<?php echo e(route('admin.range.pacsadd')); ?>" style="margin-top:20px;">Add PACS</a>
              </div> -->
            </form>
          </div>
        </div>

        <div class="respons-table">

          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Unique Id of PACS
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Name
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Email
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Phone No
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Bank
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Zone
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Range
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  District
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Block
                </th>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                  Service Provider
                </th>
                <!-- <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                  Status
                </th>
                <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                  Action
                </th> -->


              </tr>
            </thead>
            <?php if(isset($list)): ?>
            <?php if(count($list)>0): ?>
            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tbody>
              <tr role="row" class="odd">
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
                  <?php if(isset($row->userDetail->full_name)): ?>
                  <?php if($row->userDetail->full_name != NULL || $row->userDetail->full_name !=''): ?>
                  <?php echo e($row->userDetail->full_name); ?>

                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                </td>
                <td class="sorting_1">
                  <?php if(isset($row->userDetail->email)): ?>
                  <?php if($row->userDetail->email != NULL || $row->userDetail->email !=''): ?>
                  <?php echo e($row->userDetail->email); ?>

                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                </td>
                <td class="sorting_1">
                  <?php if(isset($row->userDetail->phone_no)): ?>
                  <?php if($row->userDetail->phone_no != NULL || $row->userDetail->phone_no !=''): ?>
                  <?php echo e($row->userDetail->phone_no); ?>

                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                </td>
                <td class="sorting_1">
                  <?php if(isset($row->bank_id)): ?>
                  <?php if($row->bank_id != NULL || $row->bank_id !=''): ?>
                  <?php echo e($row->userBank->full_name); ?> <?php echo e($row->userBank->ifsc_code); ?>

                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                </td>
                <td class="sorting_1">
                  <?php if(isset($row->zone_id)): ?>
                  <?php if($row->zone_id != NULL || $row->zone_id !=''): ?>
                  <?php echo e($row->userZone->full_name); ?>

                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                </td>
                <td class="sorting_1">
                  <?php if(isset($row->range_id)): ?>
                  <?php if($row->range_id != NULL || $row->range_id !=''): ?>
                  <?php echo e($row->userRangeOne->full_name); ?>

                  <?php else: ?>
                  No records found
                  <?php endif; ?>
                  <?php else: ?>
                  No records found
                  <?php endif; ?>
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
                  <?php if(isset($row->block)): ?>
                  <?php if($row->block != NULL || $row->block !=''): ?>
                  <?php echo e($row->block); ?>

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
            <tr>
              <td colspan="12" style="text-align: center;">No Records Found</td>
            </tr>
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
<?php echo $__env->make('admin.layouts.app', ['title' => $panel_title], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\matrixmedia\wbcoopcsp\resources\views/admin/bank_user/pacslist.blade.php ENDPATH**/ ?>