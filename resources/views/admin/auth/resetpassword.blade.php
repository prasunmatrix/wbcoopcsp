@extends('admin.layouts.login', ['title' => $page_title])

@section('content')

<!-- <p class="login-box-msg">Sign In</p> -->

@foreach (['danger', 'warning', 'success', 'info'] as $msg)
@if(Session::has('alert-' . $msg))
<div class="alert alert-dismissable alert-{{ $msg }}">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <span>{{ Session::get('alert-' . $msg) }}</span><br />
</div>
@endif
@endforeach

@if (count($errors) > 0)
<div class="alert alert-danger alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  @foreach ($errors->all() as $error)
  <span>{{ $error }}</span><br />
  @endforeach

</div>
@endif
<div align="center">
  <h2>Reset Password </h2>
</div>
<form role="form" action="{{ route('admin.reset-password') }}" method="post" autocomplete="off" id="resetPassword">

  <input type="hidden" value="<?= csrf_token() ?>" name="_token">
  <input type="hidden" name="tok3n" value="{{ $tok3n }}">

  <div class="form-group has-feedback">
    <input name="password" id="password" type="password" class="form-control resetInput" required placeholder="New Password">
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    <span class="star errMsg" id="password_msg">{{ $errors->first('password') }}</span>
  </div>
  <div class="form-group has-feedback">
    <!-- <label for="password_confirmation">Re-type New Password</label> -->
    <input name="password_confirmation" id="password_confirmation" type="password" class="form-control resetInput" required placeholder="Re-type New Password">
    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    <span class="star errMsg" id="password_confirmation_msg" style="color:red;">{{ $errors->first('password_confirmation') }}</span>
  </div>
  <span class="star errMsg">{{ $errors->first('tok3n') }}</span>
  <span class="star errMsg">{{ $errors->first('reseterror') }}</span>
  <div class="row">
    <div class="col-xs-12 text-center">
      <button type="submit" class="btn _btn-primary _btn-block _btn-flat btn-submit">Submit</button>
    </div>
  </div>

</form>

@endsection