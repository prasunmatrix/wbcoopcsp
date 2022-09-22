@extends('admin.layouts.app', ['title' => $panel_title])

@section('content')
<section class="content-header">
    <h1>
        {{ $page_title }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.range.pacs')}}"><i class="fa fa-home" aria-hidden="true"></i> Pacs List</a></li>
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
                                    'route' => ['admin.range.pacsaddSubmit'],
                                    'name'  => 'addCityForm',
                                    'id'    => 'addCityForm',
                                    'files' => true,
		                            'novalidate' => true)) }}
                    <div class="box-body cus-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Full Name<span class="red_star">*</span></label>
                                {{ Form::text('full_name', null, array(
                                                                'id' => 'full_name',
                                                                'placeholder' => 'Name',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }} 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Email<span class="red_star">*</span></label>
                                {{ Form::text('email', null, array(
                                                                'id' => 'email',
                                                                'placeholder' => 'Email',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Phone Number<span class="red_star">*</span></label>
                                {{ Form::text('phone_no', null, array(
                                                                'id' => 'phone_no',
                                                                'placeholder' => 'Phone Number',
                                                                'class' => 'form-control',
                                                                'required' => 'required'
                                                                 )) }} 
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Unique ID</label>
                                {{ Form::text('unique_id', null, array(
                                                                'id' => 'unique_id',
                                                                'placeholder' => 'Unique ID',
                                                                'class' => 'form-control'
                                                                 )) }} 
                                </div>
                            </div>
                            
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Bank<span class="red_star">*</span></label>
                                    <select name="bank_id" id="bank_id" class="form-control" value="{{old('bank_id')}}" required>
                                        <option value="">-Select-</option>
                                @if (count($bankList))
                                    @foreach ($bankList as $state)
                                        <option value="{{$state->id}}" @if($state->id == old('bank_id') ) selected="selected" @endif>{{$state->full_name}} ({{$state->userProfile->ifsc_code}})</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Zone<span class="red_star">*</span></label>
                                    <select name="zone_id" id="zone_id" class="form-control" value="{{old('zone_id')}}"required>
                                         <option value="">-Select-</option> 
                                @if (count($zoneList))
                                    @foreach ($zoneList as $state)
                                        <option value="{{$state->id}}" @if($state->id == old('zone_id') ) selected="selected" @endif>{{$state->full_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Range<span class="red_star">*</span></label>
                                    <select name="range_id" id="range_id" class="form-control" value="{{old('range_id')}}" required>
                                         <option value="">-Select-</option> 
                                @if (count($rangeList))
                                    @foreach ($rangeList as $state)
                                        <option value="{{$state->id}}" @if($state->id == old('range_id') ) selected="selected" @endif>{{$state->full_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">District<span class="red_star">*</span></label>
                                    <select name="district_id" id="district_id" class="form-control" value="{{old('district_id')}}" required>
                                        <option value="">-Select-</option>
                                @if (count($districtList))
                                    @foreach ($districtList as $state)
                                        <option value="{{$state->id}}" @if($state->id == old('district_id') ) selected="selected" @endif>{{$state->district_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Block</label>
                                {{ Form::text('block', null, array(
                                                                'id' => 'block',
                                                                'placeholder' => 'Block',
                                                                'class' => 'form-control'
                                                                 )) }} 
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="title">Service Provider<span class="red_star">*</span></label>
                                    <select name="software_using" id="software_using" class="form-control" value="{{old('software_using')}}" required>
                                        <option value="">-Select-</option>
                                @if (count($softwareList))
                                    @foreach ($softwareList as $state)
                                        <option value="{{$state->id}}" @if($state->id == old('software_using') ) selected="selected" @endif>{{$state->full_name}}</option>
                                    @endforeach
                                @endif
                                    </select>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="class row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Address<span class="red_star">*</span></label>
                                    {{ Form::textarea('address', null, array(
                                                                'id' => 'address',
                                                                'placeholder' => 'Address',
                                                                'class' => 'form-control',
                                                                'rows'  => 3,
                                                                'required' => 'required'
                                                                 )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Society Type<span class="red_star">*</span></label>
                                        <select name="socity_type" id="socity_type" class="form-control" value="{{old('socity_type')}}" required>
                                            <option value="">-Select-</option>
                                                @if (count($societiesList))
                                                    @foreach ($societiesList as $state)
                                                        <option value="{{$state->id}}" @if($state->id == old('socity_type') ) selected="selected" @endif>{{$state->name}}</option>
                                                    @endforeach
                                                @endif
                                        </select>
                                </div>
                            </div>
                        </div>
                        <div class="class row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Society Registration No</label>
                                    {{ Form::text('socity_registration_no', null, array(
                                                                'id' => 'socity_registration_no',
                                                                'placeholder' => 'Society Registration No',
                                                                'class' => 'form-control'
                                                                 )) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">E District Registration No</label>
                                    {{ Form::text('district_registration_no', null, array(
                                                                'id' => 'district_registration_no',
                                                                'placeholder' => 'District Registration No',
                                                                'class' => 'form-control'
                                                                 )) }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="UploadBannerWeb">Profile Image</label>                                        
                                        {{ Form::file('profile_image', array(
                                                                'id' => 'profile_image',
                                                                'class' => 'form-control',
                                                                'placeholder' => 'Image'
                                                                 )) }}
                                        </div>
                                   
                            	    </div>
                                </div>
                        </div>
                    </div>                        
                    <div class="box-footer">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.range.pacs') }}" class="btn btn-block btn-default btn_width_reset">Cancel</a>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
@endsection

<script type="text/javascript">
    function getPacsZone(bank_id){

     $.ajax({
       
        url: "{{route('admin.range.getRangeZone')}}",
        type:'get',
        dataType: "json",
        data:{bank_id:bank_id,_token:"{{ csrf_token() }}"}
       // data:{bank_id:bank_id}
        }).done(function(response) {
           
           console.log(response.status);
            if(response.status){
             console.log(response.allZone);
             var stringified = JSON.stringify(response.allZone);
            var zonedata = JSON.parse(stringified);
             var zone_list = '<option value=""> Select Zone</option>';
             $.each(zonedata,function(index, zone_id){
                    zone_list += '<option value="'+zone_id.id+'">'+ zone_id.full_name +'</option>';
             });
                $("#zone_id").html(zone_list);
            }
        });
    }

    function getPacsRange(zone_id){

$.ajax({
  
   url: "{{route('admin.range.getRangeRange')}}",
   type:'get',
   dataType: "json",
   data:{zone_id:zone_id,_token:"{{ csrf_token() }}"}
  // data:{bank_id:bank_id}
   }).done(function(response) {
      
      console.log(response.status);
       if(response.status){
        console.log(response.allRange);
        var stringified = JSON.stringify(response.allRange);
       var zonedata = JSON.parse(stringified);
        var zone_list = '<option value=""> Select Range</option>';
        $.each(zonedata,function(index, range_id){
               zone_list += '<option value="'+range_id.id+'">'+ range_id.full_name +'</option>';
        });
           $("#range_id").html(zone_list);
       }
   });
}

function getPacsBlock(district_id){

$.ajax({
  
   url: "{{route('admin.range.getRangeBlock')}}",
   type:'get',
   dataType: "json",
   data:{district_id:district_id,_token:"{{ csrf_token() }}"}
  // data:{bank_id:bank_id}
   }).done(function(response) {
      
      console.log(response.status);
       if(response.status){
        console.log(response.allBlock);
        var stringified = JSON.stringify(response.allBlock);
       var zonedata = JSON.parse(stringified);
        var zone_list = '<option value=""> Select Block</option>';
        $.each(zonedata,function(index, block_id){
               zone_list += '<option value="'+block_id.id+'">'+ block_id.block_name +'</option>';
        });
           $("#block_id").html(zone_list);
       }
   });
}
</script>