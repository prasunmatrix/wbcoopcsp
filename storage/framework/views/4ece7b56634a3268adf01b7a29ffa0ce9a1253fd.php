<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo e($data['page_title']); ?></h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo e($data['page_title']); ?></li>
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
                    <h3 class="box-title"><?php echo e($data['panel_title']); ?></h3>
                    <div class="row">
                        <form name="filter" id="filter" method="GET" action="<?php echo e(route('admin.zone.list')); ?>">


                            <div class="col-sm-6">
                                <label class="w-100">[Name/Phone/Email]
                                    <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1" name="searchText" id="searchText" value="<?php if(isset($data['searchText'])): ?> <?php echo e($data['searchText']); ?><?php endif; ?>" style="margin-top:3px;">
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="submit" name="filter" value="Filter" class="btn btn-primary modal-action-btn" style="margin-top:20px;">
                                <a class="btn btn-primary modal-action-btn" href="<?php echo e(route('admin.zone.list')); ?>" style="margin-top:20px;">Reset</a>
                                
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-primary modal-action-btn" href="<?php echo e(route('admin.zone.add')); ?>" style="margin-top:20px;">Add Zone</a>
                                
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row" style="margin:5px;">
                    <div class="col-sm-12">
                      <div class="respons-table">
                        <?php if(isset($data)): ?>
                            <?php if(count($data)>0): ?>
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                            Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">
                                            Email
                                        </th>

                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">
                                            Phone Number
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Last Login Time
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Created At
                                        </th>
                                        <?php if( (Auth::guard('admin')->user()->user_type==0)): ?>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Status
                                        </th>
                                        <?php endif; ?>
                                        
                                        <?php if( (Auth::guard('admin')->user()->user_type==0)): ?>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Action
                                        </th>
                                        <?php endif; ?>
                                    </tr>
                                    </thead>
                                    <?php if(count($data['list'])>0): ?>
                                        <?php $__currentLoopData = $data['list']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                                    <?php if(isset($row->email)): ?>
                                                        <?php if($row->email != NULL || $row->email !=''): ?>
                                                            <?php echo e($row->email); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if(isset($row->phone_no)): ?>
                                                        <?php if($row->phone_no != NULL || $row->phone_no !=''): ?>
                                                            <?php echo e($row->phone_no); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if(isset($row->lastlogintime)): ?>
                                                        <?php if($row->lastlogintime != NULL ||$row->lastlogintime !=''): ?>
                                                            <?php echo e(date('d M Y, h:m:s', $row->lastlogintime)); ?>

                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if(isset($row->created_at)): ?>
                                                        <?php
                                                        $startDateObj = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at);
                                                            $date = $startDateObj->format('d M Y');
                                                        ?>
                                                        <?php echo e($date); ?>

                                                    <?php else: ?>
                                                        No records found
                                                    <?php endif; ?>
                                                </td>
                                                <?php if( (Auth::guard('admin')->user()->user_type==0)): ?>
                                                <td>
                                                    <?php if(isset($row->status)): ?>
                                                        <?php if($row->status!=null || $row->status!=''): ?>
                                                        <?php if($row->status == '1'): ?>
                                                            <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="<?php echo e(route('admin.zone.status', [$row->id])); ?>" title="Change Status" style="color:green"><i class="fa fa-check" aria-hidden="true"></i></a>
                                                        <?php else: ?>
                                                            <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="<?php echo e(route('admin.zone.status', [$row->id])); ?>"  title="Change Status" style="color:red">X</a>
                                                        <?php endif; ?>
                                                        <?php else: ?>
                                                            No records found
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        No records found
                                                    <?php endif; ?>
                                                </td>
                                                <?php endif; ?>
                                                
                                                <?php if( (Auth::guard('admin')->user()->user_type==0)): ?>
                                                <td>
                                                    <button>
                                                    <a href="<?php echo e(route('admin.zone.edit',[$row->id])); ?>" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    </button>
                                                </td>
                                                <?php endif; ?>
                                            </tr>
                                        </tbody>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                    <tr><td colspan="8" style="color:white; text-align: center;">No Records Found</td></tr>
                                    <?php endif; ?>
                                </table>

                            <?php endif; ?>
                        <?php else: ?>
                            <p style="color:red; text-align: center;">No Records found</p>
                        <?php endif; ?>
                    </div>
                    <?php if(count($data['list']) > 0): ?>   
            <div class="box-footer">
                <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                            <strong>Show Records:</strong>
                            <strong style="color:green"><?php echo e($data['list']->firstItem()); ?></strong>
                                To
                            <strong style="color:green"><?php echo e($data['list']->lastItem()); ?></strong>
                                Total
                            <strong style="color:green"><?php echo e($data['list']->total()); ?></strong>
                                Entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            <?php echo e($data['list']->appends(request()->input())->links("pagination::bootstrap-4")); ?>

                        </div>
                    </div>
            </div>
            <?php endif; ?>
                    </div>
                </div>

            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
$(function(){
    $(".close-modal").on("click", function(){
        location.reload();
        return false;
    });
});
</script>
<script>
        $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        //Datemask dd/mm/yyyy
        $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
        //Datemask2 mm/dd/yyyy
        $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
        //Money Euro
        $('[data-mask]').inputmask()

        //Date range picker
            $('#reservation').daterangepicker({
                maxDate: new Date,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            })

            $('#reservation').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('#reservation').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });


        //Date range picker with time picker
        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 30,
            locale: {
            format: 'MM/DD/YYYY hh:mm A'
            }
        })
        //Date range as a button
        $('#daterange-btn').daterangepicker(
            {
            ranges   : {
                'Today'       : [moment(), moment()],
                'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate  : moment()
            },
            function (start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
            }
        )

        //Timepicker
        $('#timepicker').datetimepicker({
            format: 'LT'
        })

        //Bootstrap Duallistbox
        $('.duallistbox').bootstrapDualListbox()

        //Colorpicker
        $('.my-colorpicker1').colorpicker()
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function(event) {
            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        });

        $("input[data-bootstrap-switch]").each(function(){
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

        })
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', ['title' => $data['panel_title']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/prasun/wbcoopcsp/resources/views/admin/zone_user/list.blade.php ENDPATH**/ ?>