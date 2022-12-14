<div class="alert_dic">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
            <div class="alert alert-dismissable alert-{{ $msg }}">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <span>{{ Session::get('alert-' . $msg) }}</span>
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
</div>