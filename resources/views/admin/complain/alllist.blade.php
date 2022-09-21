@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')
@php
$logginUser = AdminHelper::loggedUser()

@endphp


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ $page_title }}</h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>
<section>
    <div class="row-mb-2">
        <div class="col-sm-8">
            @include('admin.elements.notification')
        </div>
    </div>
</section>
<!-- Content Header (Page header) -->
{{-- Page body Content --}}
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $panel_title }}</h3>
                    <div class="row">
                        <form name="filter" id="filter" method="GET" action="{{ route('admin.complain.alllist') }}">

                            <div class="col-sm-6">
                                <label class="w-100">Ticket No
                                    <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1" name="searchText1" id="searchText1" value="@if(isset($searchText1)) {{ $searchText1 }}@endif" style="margin-top:3px;">
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <input type="submit" name="filter" value="Filter" class="btn btn-primary modal-action-btn" style="margin-top:20px;">
                                <a class="btn btn-primary modal-action-btn" href="{{ route('admin.complain.alllist') }}" style="margin-top:20px;">Reset</a>

                            </div>
                            <!-- <div class="col-sm-2">
                                <a class="btn btn-primary modal-action-btn" href="{{ route('admin.complain.add') }}" style="margin-top:20px;">Raise Complain</a>

                            </div> -->
                        </form>
                    </div>
                </div>

                    <div class="respons-table">
                        
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                            Ticket No
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">
                                            Reported By
                                        </th>


                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Reported To
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Submitted On
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Status
                                        </th>

                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Action
                                        </th>

                                        {{-- <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Is Deleted
                                        </th> --}}




                                    </tr>
                                    </thead>
                                    @if(isset($list))
                                    @if(count($list)>0)
                                    @foreach ($list as $key => $row)

                                    <tbody>
                                        <tr role="row" class="odd">
                                            <td class="sorting_1">
                                                @if(isset($row->ticket_no))
                                                    @if($row->ticket_no != NULL || $row->ticket_no !='')
                                                        {{ $row->ticket_no }}
                                                    @else
                                                        No records found
                                                    @endif
                                                @else
                                                    No records found
                                                @endif
                                            </td>
                                            <td>
                                            {{ $row->userDetail->full_name }}
                                            </td>
                                            <td>
                                            @if($row->user_type == 5) 
                                                @if(isset($row->userServiceProvider->full_name))
                                                    @if($row->userServiceProvider->full_name != NULL || $row->userServiceProvider->full_name !='')
                                                        {{ $row->userServiceProvider->full_name }}
                                                    @else
                                                        No records found     
                                                    @endif
                                                @else
                                                    No records found
                                                @endif
                                            @else
                                            
                                            @if(isset($row->userDetails->full_name))
                                                    @if($row->userDetails->full_name != NULL || $row->userDetails->full_name !='')
                                                        {{ $row->userDetails->full_name }}
                                                    @else
                                                        No records found     
                                                    @endif
                                                @else
                                                    No records found
                                                @endif
                                            @endif
                                            </td>
                                            <td>
                                            @if(isset($row->created_at))
                                                    @if($row->created_at != NULL || $row->created_at !='')
                                                        {{ Helper::formattedDateTime(strtotime($row->created_at)) }}
                                                    @else
                                                        No records found
                                                    @endif
                                                @else
                                                    No records found
                                                @endif
                                            </td>
                                           

                                            <td>
                                                @if(isset($row->status))
                                                    @if($row->status!=null || $row->status!='')
                                                    @if($row->status == '1')
                                                    <span class="yellowBt">Ongoing</span>
                                                     @elseif ($row->status == '2')
                                                            <span class="rdBt">Closed</span>
                                                        @else
                                                            <span class="greenBt">Open</span>
                                                        @endif
                                                    @else
                                                        No records found1
                                                    @endif
                                                @else
                                                    No records found2
                                                @endif
                                            </td>

                                            <td>



                                                @if($row->status != '1' && $row->status != '2')
                                                <button>

                                                <a href="{{ route('admin.complain.edit', [$row->id]) }}" title="Reply to Complain">
                                                    <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
                                                </a>

                                                </button>
                                                @endif

                                               <button>

                                                <a href="{{ route('admin.complain.allview', [$row->id]) }}" title="View Complain">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>

                                                </button>

                                            </td>

                                            {{-- <td>
                                                @if(isset($row->deleted_at))
                                                    @if($row->deleted_at!=null || $row->deleted_at!='')
                                                        @if($row->deleted_at == '0')
                                                            <span style="color:green">@lang('admin.no')</span>
                                                        @else
                                                            <span style="color:red">@lang('admin.yes')</span>
                                                        @endif
                                                    @else
                                                        No records found
                                                    @endif
                                                @else
                                                    No records found
                                                @endif
                                            </td> --}}



                                        </tr>
                                    </tbody>
                                    @endforeach
                                
                            @else
                            <tr><td colspan="8" style="text-align: center;">No Records Found</td></tr>
                            @endif
                            </table>
                        @else
                            <p>No User Input found</p>
                        @endif
                    </div>
             @if(count($list) > 0)
            <div class="box-footer">
                <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                            <strong>Show Records:</strong>
                            <strong style="color:green">{{ $list->firstItem() }}</strong>
                                To
                            <strong style="color:green">{{ $list->lastItem() }}</strong>
                                Total
                            <strong style="color:green">{{$list->total()}}</strong>
                                Entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            {{$list->appends(request()->input())->links("pagination::bootstrap-4") }}
                        </div>
                    </div>
            </div>
            @endif
            </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>
{{-- Page body Content --}}

{{-- Page Modal --}}
{{-- <div id="add-guest-user" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Members</h4>
        </div>
        <div class="modal-body">
            <form name="add-member-form" id="add-member-form" method="post" action="#" novalidate="novalidate">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="file" name="upload_csv" id="upload_csv" placeholder="Upload CSV">
            </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="addSubAdmin">Save</button>
        </div>
      </div>

    </div>
</div> --}}
{{-- Page Modal --}}

{{-- Page Modal --}}
{{-- <div id="edit-guest-user" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Guest Details</h4>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="editSubAdmin">Save</button>
        </div>
      </div>
    </div>
</div> --}}
{{-- Page Modal --}}

@endsection

@push('script')
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
@endpush
