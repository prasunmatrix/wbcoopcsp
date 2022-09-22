@extends('admin.layouts.app', ['title' =>$data['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $data['page_title']}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $data['panel_title'] }}</h3>
                

                <div class="box-header">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <div class="alert alert-dismissable alert-{{ $msg }}">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <span>{{ Session::get('alert-' . $msg) }}</span><br/>
                            </div>
                        @endif
                    @endforeach

                    @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            @foreach ($errors->all() as $error)
                                <span>{{ $error }}</span><br/>
                            @endforeach
                        </div>
                    @endif
                </div>
                <form method="POST" name="edit_zone_details" id="edit_zone_details" action="{{route('admin.zone.editSubmit')}}" enctype="multipart/form-data">
                    {{ csrf_field() }} 
                        <div class="card-body">
                        <div class="row">  
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Name<span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="full_name" id="full_name" value="@if(isset($zoneUserDetails->full_name)){{$zoneUserDetails->full_name}}@endif">

                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="point">Email <span class="red_star">*</span></label>
                                    <input type="text" class="form-control" name="email" id="email" readonly="" value="@if(isset($zoneUserDetails->email)){{$zoneUserDetails->email}}@endif">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="point">Phone No <span class="red_star">*</span></label>
                                    <input type="text" class="form-control" name="phone_no" id="phone_no" value="@if(isset($zoneUserDetails->phone_no)){{$zoneUserDetails->phone_no}}@endif">
                                </div>
                            </div>
                        </div>
                        <div class="row">  
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="name">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" id="profile_image">
                                </div>
                                @php
                                $imgPath = \URL:: asset('images').'/admin/'.Helper::NO_IMAGE;
                                if (@$zoneUserDetails->userProfile->profile_image != null) {
                                    if(file_exists(public_path('/uploads/member'.'/'.@$zoneUserDetails->userProfile->profile_image))) {
                                    $imgPath = \URL::asset('uploads/member').'/'.@$zoneUserDetails->userProfile->profile_image;
                                    }
                                }
                                @endphp
                                <img src="{{ $imgPath }}" alt="" height="50px">
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="name">Office address <span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="address" id="address" value="@if(isset($zoneUserDetails->userProfile->address)){{$zoneUserDetails->userProfile->address}}@endif">
                                </div>
                            </div>
                        </div>
                       
                            
                           
                        </div>
                                        
                        <input type="hidden" name="user_id" id="user_id" value="{{$zoneUserDetails->id}}">
                        <!-- /.card-body -->
                        <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Edit Details</button>
                        <a href="{{ route('admin.zone.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection