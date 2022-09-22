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
                        <form name="filter" id="filter" method="GET" action="{{ route('admin.district.list') }}">
                           
                            <div class="col-sm-6">
                                <label class="w-100">Name 
                                    <input type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1" name="searchText" id="searchText" value="@if(isset($searchText)) {{ $searchText }}@endif" style="margin-top:3px;">
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="submit" name="filter" value="Filter" class="btn btn-primary modal-action-btn" style="margin-top:20px;">   
                                <a class="btn btn-primary modal-action-btn" href="{{ route('admin.district.list') }}" style="margin-top:20px;">Reset</a>
                                
                            </div>
                            <div class="col-sm-2">
                                   
                                <a class="btn btn-primary modal-action-btn" href="{{ route('admin.district.add') }}" style="margin-top:20px;">Add District</a>
                                
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
                                        
                                        {{-- <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Is Deleted
                                        </th> --}}
                                        
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">
                                            Action
                                        </th>
                                        
                                        
                                    </tr>
                                    </thead>
                                    @if(isset($list))
                                        @if(count($list)>0)
                                    @foreach ($list as $key => $row)
                                    <tbody>
                                        <tr role="row" class="odd">
                                            <td class="sorting_1">
                                                @if(isset($row->district_name))
                                                    @if($row->district_name != NULL || $row->district_name !='')
                                                        {{ $row->district_name }}
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
                                                        <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="{{ route('admin.district.status', [$row->id]) }}" title="Change Status" style="color:green"><i class="fa fa-check" aria-hidden="true"></i></a>
                                                    @else
                                                        <a onclick="return confirm('Are you sure ? Want to change the status!!')" href="{{ route('admin.district.status', [$row->id]) }}"  title="Change Status" style="color:red">X</a>
                                                    @endif  
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
                                            
                                            <td>
                                                <button>
                                                
                                                <a href="{{ route('admin.district.edit', [$row->id]) }}" title="Edit">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a>
                                                
                                                </button>
                                               
                                           
                                            </td>
                                            
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


@endsection


