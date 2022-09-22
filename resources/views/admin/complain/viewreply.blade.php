@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')
@php
$logginUser = AdminHelper::loggedUser()

@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.complain.alllreply')}}"><i class="fa fa-cubes"></i>Complain Reply</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content cus-listing">
<div class="row">
    <div class="col-sm-5" ><strong>Submitted On :</strong></div>
    <div class="col-sm-7" >{{ Helper::formattedDateTime(strtotime($replyData->created_at)) }}</div>
	</div>
<div class="row">
    <div class="col-sm-5" ><strong>Reported By :</strong></div>
    <div class="col-sm-7" >{{ $replyData->userDetail->full_name }}</div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Reported To :</strong></div>
    <div class="col-sm-7" >
      @if($replyData->user_type == 5) 
            @if(isset($replyData->userServiceProvider->full_name))
                @if($replyData->userServiceProvider->full_name != NULL || $replyData->userServiceProvider->full_name !='')
                    {{ $replyData->userServiceProvider->full_name }}
                @else
                    NA     
                @endif
            @else
                NA
            @endif
        @else
        
        @if(isset($replyData->userDetails->full_name))
                @if($replyData->userDetails->full_name != NULL || $replyData->userDetails->full_name !='')
                    {{ $replyData->userDetails->full_name }}
                @else
                    NA     
                @endif
            @else
                NA
            @endif
      @endif
    </div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Ticket No :</strong></div>
    <div class="col-sm-7" >{{ $replyData->ticket_no }}</div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Description :</strong></div>
    <div class="col-sm-7" >{{ $replyData->report_details }}</div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Disposing Note :</strong></div>
    <div class="col-sm-7" >@if(isset($replyData->disposing_note)){{ $replyData->disposing_note }}@else NA @endif</div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Disposed On :</strong></div>
    <div class="col-sm-7" >@if(isset($replyData->updated_at)){{ Helper::formattedDateTime(strtotime($replyData->updated_at)) }}@else NA @endif</div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Disposed By :</strong></div>
    <div class="col-sm-7" >@if(isset($replyData->userDetailReply->full_name)){{ $replyData->userDetailReply->full_name }}@else NA @endif</div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Status :</strong></div>
    <div class="col-sm-7" >
      @if(isset($replyData->status))
            @if($replyData->status!=null || $replyData->status!='')
              @if($replyData->status == '1')
              <span class="yellowBt">Ongoing</span>
                @elseif($replyData->status == '2')
                <span class="rdBt">Closed</span>
              @else
                <span class="greenBt">Open</span>
              @endif  
            @else
                NA
            @endif
        @else
        NA
    @endif
    </div>
	</div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <a href="{{ route('admin.complain.alllreply') }}" class="btn btn-info">Back</a>
      </div>
    </div>
    @if($replyData->status != '1' && $replyData->status != '2')
    @if(\Auth::guard('admin')->user()->id == $replyData->reported_to)
    <div class="col-md-6 text-right">
    
    <a class="btn btn-info" href="{{ route('admin.complain.edit', [$replyData->id]) }}" title="Reply to Complain">
        <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
    </a>
    </div>
    @endif
    @endif
  </div>
</section>

@endsection