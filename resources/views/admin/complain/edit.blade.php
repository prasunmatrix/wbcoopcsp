@extends('admin.layouts.app', ['title' => $data['panel_title']])

@section('content')
@php
$logginUser = AdminHelper::loggedUser()

@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $data['page_title'] }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
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
                                    'route' => ['admin.complain.editSubmit', $details["id"]],
                                    'id'    => 'replyComplainForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body cus-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Submitted On : </label>
                                    {{Helper::formattedDateTime(strtotime($details->created_at))}}
                                </div>
                             </div>
                            </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Reported By : </label>
                                    {{$details->userDetail->full_name}}
                                </div>
                             </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Reported To : </label>
                                    @if($details->user_type == 5) 
                                        @if(isset($details->userServiceProvider->full_name))
                                            @if($details->userServiceProvider->full_name != NULL || $details->userServiceProvider->full_name !='')
                                                {{ $details->userServiceProvider->full_name }}
                                            @else
                                                NA     
                                            @endif
                                        @else
                                            NA
                                        @endif
                                    @else
                                    
                                    @if(isset($details->userDetails->full_name))
                                            @if($details->userDetails->full_name != NULL || $details->userDetails->full_name !='')
                                                {{ $details->userDetails->full_name }}
                                            @else
                                                NA     
                                            @endif
                                        @else
                                            NA
                                        @endif
                                    @endif
                                </div>
                             </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Ticket No : </label>
                                    {{$details->ticket_no}}
                                </div>
                             </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Description : </label>
                                    {{$details->report_details}}
                                </div>
                             </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="name">Disposing Note<span class="red_star">*</span></label>
                                    {{ Form::textarea('disposing_note', $details->disposing_note, array(
                                                                'id' => 'disposing_note',
                                                                'placeholder' => 'Disposing Note',
                                                                'class' => 'form-control',
                                                                'rows'  => 3,
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                             </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" title="Submit">Reply</button>
                            <a onclick="window.history.back()" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                            <!-- <a href="{{ route('admin.complain.alllreply').'?page='.$data['pageNo'] }}" title="Cancel" class="btn btn-block btn-default btn_width_reset">Cancel</a> -->
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection