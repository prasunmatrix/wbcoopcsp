@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')


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
            <form name="filter" id="filter" method="GET" action="{{ route('admin.bank.pacslist') }}">

              <div class="col-sm-6">
                <label class="w-100">[Name/Email/Phone No]
                  <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1" name="searchText" id="searchText" value="@if(isset($searchText)) {{ $searchText }}@endif" style="margin-top:3px;">
                </label>
              </div>
              <div class="col-sm-4">
                <input type="submit" name="filter" value="Filter" class="btn btn-primary modal-action-btn" style="margin-top:20px;">
                <a class="btn btn-primary modal-action-btn" href="{{ route('admin.bank.pacslist') }}" style="margin-top:20px;">Reset</a>

              </div>
              <!-- <div class="col-sm-2">
                <a class="btn btn-primary modal-action-btn" href="{{ route('admin.range.pacsadd') }}" style="margin-top:20px;">Add PACS</a>
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
            @if(isset($list))
            @if(count($list)>0)
            @foreach ($list as $key => $row)
            <tbody>
              <tr role="row" class="odd">
                <td class="sorting_1">
                  @if(isset($row->unique_id))
                  @if($row->unique_id != NULL || $row->unique_id !='')
                  {{ $row->unique_id }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->userDetail->full_name))
                  @if($row->userDetail->full_name != NULL || $row->userDetail->full_name !='')
                  {{ $row->userDetail->full_name }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->userDetail->email))
                  @if($row->userDetail->email != NULL || $row->userDetail->email !='')
                  {{ $row->userDetail->email }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->userDetail->phone_no))
                  @if($row->userDetail->phone_no != NULL || $row->userDetail->phone_no !='')
                  {{ $row->userDetail->phone_no }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->bank_id))
                  @if($row->bank_id != NULL || $row->bank_id !='')
                  {{ $row->userBank->full_name }} {{$row->userBank->ifsc_code}}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->zone_id))
                  @if($row->zone_id != NULL || $row->zone_id !='')
                  {{ $row->userZone->full_name }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->range_id))
                  @if($row->range_id != NULL || $row->range_id !='')
                  {{ $row->userRangeOne->full_name }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->district_id))
                  @if($row->district_id != NULL || $row->district_id !='')
                  {{ $row->userDistrict->district_name }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->block))
                  @if($row->block != NULL || $row->block !='')
                  {{ $row->block }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>
                <td class="sorting_1">
                  @if(isset($row->software_using))
                  @if($row->software_using != NULL || $row->software_using !='')
                  {{ $row->userSoftware->full_name }}
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>




                {{--<td>
                  @if(isset($row->status))
                  @if($row->status!=null || $row->status!='')
                  @if($row->status == '1')
                  <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="{{ route('admin.range.pacsstatus', [$row->id]) }}" title="Change Status" style="color:green"><i class="fa fa-check" aria-hidden="true"></i></a>
                  @else
                  <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="{{ route('admin.range.pacsstatus', [$row->id]) }}" title="Change Status" style="color:red">X</a>
                  @endif
                  @else
                  No records found
                  @endif
                  @else
                  No records found
                  @endif
                </td>--}}

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

                {{--<td>
                  <button>

                    <a href="{{ route('admin.range.pacsedit', [$row->id]) }}" title="Edit">
                      <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a>

                  </button>


                </td>--}}

              </tr>
            </tbody>
            @endforeach

            @else
            <tr>
              <td colspan="12" style="text-align: center;">No Records Found</td>
            </tr>
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
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
</section>


@endsection