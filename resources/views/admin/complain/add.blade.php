@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')

<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.complain.list')}}"><i class="fa fa-inbox" aria-hidden="true"></i> Complain List</a></li>
        <li class="active">{{ $page_title }}</li>
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
                                    'route' => ['admin.complain.addSubmit'],
                                    'name'  => 'addMessageForm',
                                    'id'    => 'addMessageForm',
                                    'files' => true,
                                    'novalidate' => true)) }}
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Enter Details<span class="red_star">*</span></label>
                                    {{ Form::textarea('report_details', null, array(
                                                                'id' => 'report_details',
                                                                'placeholder' => 'Text',
                                                                'class' => 'form-control',
                                                                'rows'  => 4,
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                              
                        </div>
                        <div class="row">
                            <div class="col-md-6">            
                                <div class="form-group">
                                    <label for="title">Addressed To<span class="red_star">*</span></label>
                                    <select id="user_type" name="user_type"  class="form-control">
                                        <option value="">Select</option>
                                        <option value="1">Bank</option>
                                        <option value="2">Zone</option>
                                        <option value="3">Range</option>
                                        <option value="4">Pacs</option>
                                        <option value="5">Service Provider</option>
                                        
                                            
                                    </select>
                                </div>
                                                            
                            </div>
                            <div class="col-md-6" id="bankuser" style="display:none">
                                <div class="form-group">
                                <label for="title">Bank<span class="red_star">*</span></label>
                                    <select name="bank_id" id="bank_id" class="form-control common" required>
                                        <option value="">-Select-</option>
                                @if (count($bankList))
                                    @foreach ($bankList as $state)
                                        <option value="{{$state->id}}">{{$state->full_name}} ({{@$state->UserProfile->ifsc_code}})</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="zoneuser" style="display:none">
                                <div class="form-group">
                                <label for="title">Zone<span class="red_star">*</span></label>
                                    <select name="zone_id" id="zone_id" class="form-control common"required>
                                        <option value="">-Select-</option>
                                @if (count($zoneList))
                                    @foreach ($zoneList as $state)
                                        <option value="{{$state->id}}">{{$state->full_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="rangeuser" style="display:none">
                                <div class="form-group">
                                <label for="title">Range<span class="red_star">*</span></label>
                                    <select name="range_id" id="range_id" class="form-control common" required>
                                        <option value="">-Select-</option>
                                @if (count($rangeList))
                                    @foreach ($rangeList as $state)
                                        <option value="{{$state->id}}">{{$state->full_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="paceuser" style="display:none">
                                <div class="form-group">
                                <label for="title">PACS<span class="red_star">*</span></label>
                                    <select name="pacs_id" id="pacs_id" class="form-control common"  required>
                                        <option value="">-Select-</option>
                                @if (count($paceList))
                                    @foreach ($paceList as $state)
                                        <option value="{{$state->id}}">{{$state->full_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6" id="serviceuser" style="display:none">
                                <div class="form-group">
                                <label for="title">Service Provider<span class="red_star">*</span></label>
                                    <select name="service_provider_id" id="service_provider_id" class="form-control common"  required>
                                        <option value="">-Select-</option>
                                @if (count($serviceProviderList))
                                    @foreach ($serviceProviderList as $state)
                                        <option value="{{$state->id}}">{{$state->full_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            
                            
                        <input type="hidden" name="reported_to" id="reported_to">    
                        </div>
                       
                    </div>
                                            
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button onclick="return confirm('Are you sure ? Want to confirm ticket!!')" type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.complain.list') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>


@endsection
@push('script')
<script>
$(document).ready(function(){
 
 
 $('#user_type').on('change', function() {
       var select_user_type = $(this).val();
       
       if(select_user_type == '1'){
           $('#bankuser').show();
           $('#zoneuser').hide();
           $('#rangeuser').hide();
           $('#paceuser').hide();
           $('#serviceuser').hide();
       } else if (select_user_type == '2') {
            $('#bankuser').hide();
            $('#zoneuser').show();
            $('#rangeuser').hide();
            $('#paceuser').hide();
            $('#serviceuser').hide();
       } else if (select_user_type == '3') {
            $('#bankuser').hide();
            $('#zoneuser').hide();
            $('#rangeuser').show();
            $('#paceuser').hide();
            $('#serviceuser').hide();
       } else if (select_user_type == '4') {
            $('#bankuser').hide();
            $('#zoneuser').hide();
            $('#rangeuser').hide();
            $('#paceuser').show();
            $('#serviceuser').hide();
       }
       else if (select_user_type == '5') {
            $('#bankuser').hide();
            $('#zoneuser').hide();
            $('#rangeuser').hide();
            $('#paceuser').hide();
            $('#serviceuser').show();
       }
       
   });

   $('.common').on('change', function() {
       var reported_to = $(this).val();
       
       $('#reported_to').val(reported_to);
   });
 
 
});
</script>
@endpush