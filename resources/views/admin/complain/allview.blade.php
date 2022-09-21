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
        <li><a href="{{route('admin.complain.list')}}"><i class="fa fa-cubes"></i>Complain List</a></li>
        <li class="active">{{ $page_title }}</li>
    </ol>
</section>

<section class="content cus-listing">
<div class="row">
    <div class="col-sm-5" ><strong>Submitted On :</strong></div>
    <div class="col-sm-7" >{{ Helper::formattedDateTime(strtotime($saleData->created_at)) }}</div>
	</div>
<div class="row">
    <div class="col-sm-5" ><strong>Reported By :</strong></div>
    <div class="col-sm-7" >{{ $saleData->userDetail->full_name }}</div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Reported To :</strong></div>
    <div class="col-sm-7" > 
        @if($saleData->user_type == 5) 
          @if(isset($saleData->userServiceProvider->full_name))
              @if($saleData->userServiceProvider->full_name != NULL || $saleData->userServiceProvider->full_name !='')
                  {{ $saleData->userServiceProvider->full_name }}
              @else
                  NA     
              @endif
          @else
              NA
          @endif
      @else

      @if(isset($saleData->userDetails->full_name))
              @if($saleData->userDetails->full_name != NULL || $saleData->userDetails->full_name !='')
                  {{ $saleData->userDetails->full_name }}
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
    <div class="col-sm-7" >{{ $saleData->ticket_no }}</div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Description :</strong></div>
    <div class="col-sm-7" >{{ $saleData->report_details }}</div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Disposing Note :</strong></div>
    <div class="col-sm-7" >@if(isset($saleData->disposing_note)){{ $saleData->disposing_note }}@else NA @endif</div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Disposed On :</strong></div>
    <div class="col-sm-7" >@if(isset($saleData->updated_at)){{ Helper::formattedDateTime(strtotime($saleData->updated_at)) }}@else NA @endif</div>
	</div>
    <div class="row">
    <div class="col-sm-5" ><strong>Disposed By :</strong></div>
    <div class="col-sm-7" >@if(isset($saleData->userDetailReply->full_name)){{ $saleData->userDetailReply->full_name }}@else NA @endif</div>
	</div>
  <div class="row">
    <div class="col-sm-5" ><strong>Status :</strong></div>
    <div class="col-sm-7" >
      @if(isset($saleData->status))
            @if($saleData->status!=null || $saleData->status!='')
              @if($saleData->status == '1')
              <span class="yellowBt">Ongoing</span>
              @elseif($saleData->status == '2')
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
        <a href="{{ route('admin.complain.alllist') }}" class="btn btn-info">Back</a>
      </div>
    </div>
    @if($saleData->status != '1' && $saleData->status != '2')

    <div class="col-md-6 text-right">
      <a class="btn btn-info" href="{{ route('admin.complain.edit', [$saleData->id]) }}" title="Reply to Complain">
          <i class="fa fa-mail-reply-all" aria-hidden="true"></i>
      </a>
    </div>

    @endif

      </div>
    </div>
  </div>
</section>

@endsection
