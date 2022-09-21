@extends('admin.layouts.app', ['title' =>$bankUserDetails['panel_title']])

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $bankUserDetails['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $bankUserDetails['page_title']}}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    

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
                <form method="POST" name="add_bank_details" id="add_bank_details" action="{{route('admin.bank.bankSubmit')}}" enctype="multipart/form-data">
                    {{ csrf_field() }} 
                        <div class="card-body">
                        <div class="row">  
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Name<span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="full_name" id="full_name" value="{{old('full_name')}}">
                                
                                <!-- @if($errors->has('full_name'))
                                   <span class="error">{{$errors->first('full_name')}}</span>
                                @endif -->
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="point">Email<span class="red_star">*</span></label>
                                    <input type="text" class="form-control" name="email" id="email" value="{{old('email')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="point">Phone No<span class="red_star">*</span></label>
                                    <input type="text" class="form-control" name="phone_no" id="phone_no" value="{{old('phone_no')}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">  
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">IFSC Code(Head Office)<span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" value="{{old('ifsc_code')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Profile Image</label>
                                <input type="file" class="form-control" name="profile_image" id="profile_image">
                                </div>
                                
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="name">Office address<span class="red_star">*</span></label>
                                <input type="text" class="form-control" name="address" id="address" value="{{old('address')}}">
                                </div>
                            </div>
                        </div>
                       
                                        
                        <input type="hidden" name="user_id" id="user_id" value="{{@$bankUserDetails->id}}">
                        <!-- /.card-body -->
                        <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Add Details</button>
                        <a href="{{ route('admin.bank.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection