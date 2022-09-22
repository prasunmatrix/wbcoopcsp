<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Administrator - @if($title){{$title}} @else {{ Helper::getAppName() }} @endif</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/site/favicon.png') }}"/>
    <!-- Styles -->
    <link href="{{ asset('css/admin/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('css/admin/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Ionicons -->
    <link href="{{ asset('css/admin/bower_components/Ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <!-- Daterange picker -->
    {{-- <link rel="stylesheet" href="{{ asset('css/admin/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('css/admin/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- Theme style -->
    <link href="{{ asset('css/admin/dist/css/AdminLTE.min.css') }}" rel="stylesheet">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('css/admin/dist/css/skins/_all-skins.min.css') }}">
    <!-- iCheck -->
    <link href="{{ asset('css/admin/plugins/iCheck/square/blue.css') }}" rel="stylesheet">
    <!-- Pace style -->
    <link rel="stylesheet" href="{{ asset('css/admin/plugins/pace/pace.min.css') }}">
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link href="{{ asset('css/admin/development_admin.css') }}" rel="stylesheet">
    <!-- jQuery 3 -->
    <script src="{{ asset('js/admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.5/bootstrap-clockpicker.css" integrity="sha512-WkiKthFwSoB86+7U4Pzoc+6Rmm5vMs5kElND6woI6+NPKfx+da0ZwdziYGccvcbHPm0u8qafBjOiN8k9oXhQJw==" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.5/bootstrap-clockpicker.min.css" integrity="sha512-ueColcngdZuWcX7BQDDafvwSqoh+iSucEcxuxUwx/76HQ2R9cgiozM4jqUQXCapatXAghus6ZCg5jriHvIyVaA==" crossorigin="anonymous" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.5/jquery-clockpicker.min.js" integrity="sha512-VuTa0GkSbgoJTNbOoGgfj+otleeLNK7EVgcUf78WdXr4lo+7lSMicdL/nvojckru9VoJk2i7n/jHmfFQKKCUpA==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.5/bootstrap-clockpicker.js" integrity="sha512-uLSmknhJUZF75y0phc+sU4XIRFjIySAADwtQkJqryeT8hZPiiEFOEYLbL69GE94dmTKNRnBVXu2mzAl6+F9eeA==" crossorigin="anonymous"></script>

  
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            @include('admin.elements.navbar')
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            @include('admin.elements.sidebar')
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        
        <footer class="main-footer">
            @include('admin.elements.footer')
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('js/admin/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>    
    <!-- Select2 -->
    <script src="{{ asset('js/admin/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- PACE -->
    <script src="{{ asset('js/admin/bower_components/PACE/pace.min.js') }}"></script>    
    <!-- date-range-picker -->
    <script src="{{ asset('js/admin/bower_components/moment/min/moment.min.js') }}"></script>
    {{-- <script src="{{ asset('js/admin/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script> --}}
    <script src="{{ asset('css/admin/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('css/admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ asset('js/admin/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    
    <script>
    var date = new Date();
    date.setDate(date.getDate());

    $('#event_start_date').datepicker({
        autoclose: true,
        startDate: date,
        format: 'yyyy-mm-dd', 
    });
    </script>
    <script>
    var date = new Date();
    date.setDate(date.getDate());

    $('#event_end_date').datepicker({
        autoclose: true, 
        startDate: date,
        format: 'yyyy-mm-dd',
    });
    </script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/admin/dist/js/adminlte.min.js') }}"></script>
    <!-- Sparkline -->
    <!-- <script src="{{ asset('js/admin/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script> -->
    <!-- SlimScroll -->
    <script src="{{ asset('js/admin/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('js/admin/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/admin/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/admin/development_admin.js') }}"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
      

    <script>
    // @if (Route::current()->getName() != 'admin.order.list' && Route::current()->getName() != 'admin.contract.list')
    // $(function () {
    //     //Initialize Select2 Elements
    //     $('.select2').select2()
    // });
    // @endif

    $(document).ajaxStart(function() {
        // To make Pace works on Ajax calls
        $(document).ajaxStart(function () {
            Pace.restart()
        });
    });
    </script>
    @stack('script')   


</body>
</html>