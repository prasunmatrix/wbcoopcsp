@extends('admin.layouts.app', ['title' => $data['panel_title']])
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ $data['page_title'] }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <!-- <div class="box-header with-border">
                    
                </div> -->

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

                {{ Form::open(array(
		                            'method'=> 'POST',
		                            'class' => '',
                                    'route' => ['admin.site-settings'],
                                    'name'  => 'updateSiteSettingsForm',
                                    'id'    => 'updateSiteSettingsForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body cus-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="FirstName">From Email<span class="red_star">*</span></label>
                                    {{ Form::text('from_email', $data['from_email'], array(
                                                                'id' => 'from_email',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'From Email',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="LastName">To Email<span class="red_star">*</span></label>
                                    {{ Form::text('to_email', $data['to_email'], array(
                                                                'id' => 'to_email',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'To Email',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>
                        </div>
                        
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Default Meta Title</label>
                                    {{ Form::text('default_meta_title', $data['default_meta_title'], array(
                                                                'id' => 'default_meta_title',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Default Meta Title' )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Default Meta Keywords</label>
                                    {{ Form::textarea('default_meta_keywords', $data['default_meta_keywords'], array(
                                                                'id' => 'default_meta_keywords',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'cols' => 4,
                                                                'placeholder' => 'Default Meta Keywords' )) }}
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="PhoneNumber">Default Meta Description</label>
                                    {{ Form::textarea('default_meta_description', $data['default_meta_description'], array(
                                                                'id' => 'default_meta_description',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'cols' => 4,
                                                                'placeholder' => 'Default Meta Description' )) }}
                                </div>
                            </div>
                        </div> --}}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Address</label>
                                    {{ Form::textarea('address', $data['address'], array(
                                                                'id' => 'address',
                                                                'class' => 'form-control',
                                                                'rows' => 4,
                                                                'placeholder' => 'Address' )) }}
                                </div>
                            </div>     
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="PhoneNumber">Website Title For Email<span class="red_star">*</span></label>
                                    {{ Form::text('website_title', $data['website_title'], array(
                                                                'id' => 'website_title',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Website Title',
                                                                'required' => 'required' )) }}
                                </div>
                            </div>                      
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@endsection