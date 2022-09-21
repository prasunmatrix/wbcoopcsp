@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')
@php
$arr = ['0' => 'No', '1'=>'Yes'	]

@endphp

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Dashboard of <strong> {{ Helper::getAppName() }} </strong></h1>
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
                                            @sortablelink('userDistrict.district_name', 'District')
                                        </th>
                                        <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" >
                                            @sortablelink('userBlock.block_name', 'Block')
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
                                                {{$row->userDetail->full_name}}
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
                                                    @if(isset($row->block_id))
                                                        @if($row->block_id != NULL || $row->userProfile->block_id !='')
                                                            {{ $row->userBlock->block_name }}
                                                        @else
                                                            No records found
                                                        @endif
                                                    @else
                                                        No records found
                                                @endif 
                                            </td>
                                            <td class="sorting_1">
                                                    @if(isset($row->socity_type))
                                                        @if($row->socity_type != NULL || $row->socity_type !='')
                                                            {{ $row->userSocietie->name }}
                                                        @else
                                                            No records found
                                                        @endif
                                                    @else
                                                        No records found
                                                @endif 
                                            </td>
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
                                                    @if(isset($row->pacs_using_software))
                                                        @if($row->pacs_using_software != NULL || $row->pacs_using_software !='')
                                                            {{ $arr[$row->pacs_using_software] }}
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
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
</section>
<!-- /.content -->
@if(count($list)>0)
<section class="content cus-listing">
@if(\Auth::guard('admin')->user()->user_type != '4')                                              
  <div class="row">
  <div class="col-sm-12">
    <div class="col-sm-3"><strong>Service Provider</strong></div>
    <div class="col-sm-9"><strong>Cumulative Number of PACS</strong></div>
    </div>
    </div>
   
    @foreach ($softwareDetails as $row)
    
    <div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
        {{$row->userSoftware->full_name}} 
        </div>
        <div class="col-md-9">
         {{ $row->total }}
        </div>
    </div>
    </div>
    
    @endforeach
    <div class="row">
    <div class="col-md-12">
        <div class="col-md-3">
            Total 
        </div>
        <div class="col-md-9">
        {{ $test }}
        </div>
    </div>
    </div>
   
    @endif
    
  
  
</section>
@endif


@endsection


