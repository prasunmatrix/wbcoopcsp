@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.block.list')}}"><i class="fa fa-home" aria-hidden="true"></i>Block List</a></li>
        <li class="active">{{ $data['page_title'] }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                @include('admin.elements.notification')
                
                {{ Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.block.editSubmit', $details["id"]],
                                    'title'  => 'editCityForm',
                                    'id'    => 'editCityForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body cus-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">District Name<span class="red_star">*</span></label>
                                    <select name="district_id" id="district_id" class="form-control" value="{{old('district_id')}}" required>
                                        <option value="">-Select-</option>
                                @if (count($districtList))
                                    @foreach ($districtList as $state)
                                        <option value="{{$state->id}}" @if($state->id == $details['district_id']) selected="selected" @endif>{{$state->district_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Block Name<span class="red_star">*</span></label>
                                    {{ Form::text('block_name', $details->block_name, array(
                                                                'id' => 'block_name',
                                                                'placeholder' => 'Name',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" title="Submit">Update</button>
                            <a href="{{ route('admin.block.list').'?page='.$data['pageNo'] }}" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection