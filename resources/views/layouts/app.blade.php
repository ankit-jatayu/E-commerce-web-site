<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{env('APP_NAME')}} |  @yield('title')</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" ></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{env('APP_NAME')}}" />
    <meta name="keywords" content="{{env('APP_NAME')}}">
    <meta name="author" content="JATAYU TECHNOLOGIES" />
    <link rel="icon" type="image/png" href="{{ asset('admin/icons/favicon.ico') }}"/>
    <!-- Styles -->
    
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}

    @guest
        <!-- Login  -->

        <!--===============================================================================================-->  
            
        <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/vendor/bootstrap/css/bootstrap.min.css') }}">
        <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
        <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/fonts/iconic/css/material-design-iconic-font.min.css') }}">
        <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/vendor/animate/animate.css') }}">
        <!--===============================================================================================-->  
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/vendor/css-hamburgers/hamburgers.min.css') }}">
        <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/vendor/animsition/css/animsition.min.css') }}">
        <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/vendor/select2/select2.min.css') }}">
        <!--===============================================================================================-->  
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/vendor/daterangepicker/daterangepicker.css') }}">
        <!--===============================================================================================-->
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/css/util.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('admin/login/css/main.css') }}">

        <!--===============================================================================================-->
            <!-- Login  -->
    @else
        @include('layouts.external_links')

    @endguest
    <style type="text/css">
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            padding: .375rem .75rem;
            line-height:16px;
            background-color:transparent;
        }
        
        .select2-container .select2-selection--single{
            height: 35px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #4099ff!important;
            border: 1px solid #73b4ff!important;
        }
        .select2-container *:focus {
          border: 1px solid #4099ff;
          color: #495057;
          background-color: #fff;
          box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);
          outline: 0;
        }
        .select2-selection--single:focus{
            border: 1px solid #4099ff !important;
            color: #495057 !important;
            background-color: #fff !important;
            outline: 0 !important;
            /* box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%) !important;*/

        }

        .error{
            color:red!important;
            border-color:red!important;
        }
        
        .card .card-header span{
            margin-top: 0;
        }
        /*.table td, .table th {
            padding: 0.75rem 0.75rem;
        }*/
        .btn-default{
            border-color: #ccc !important;
        }

        .dataTables_scrollBody > table > thead > tr {
            visibility: collapse;
            height: 0px !important;
        }
        .dataTables_scrollBody > table > tfoot > tr {
            visibility: collapse;
            height: 0px !important;
        }
        .label-purple{
          background-color:#A020F0;
          color:#fff;
        }
        .label-maroon{
          background-color:#800000;
          color:#fff;
        }

        .error{
            color: red;
            border-color:red;
        }
         .filterTableModal .modal-dialog {
            position: fixed;
            margin: auto;
            width: 100%;
            height: 100%;
            right: 0px;
        }
        .filterTableModal .modal-content {
            height: 100%;
        }
        .filterTableModal .modal-body{
            max-height: calc(100vh - 50px);
            overflow-y: auto;
        }

        label.required::after {
            content: "*";
            color: red; /* You can adjust the color */
            margin-left: 2px; /* Adjust the spacing between text and star */
        }

        
        label{
            font-size:14px!important;
        }

        input[type="text"] {
            height:30px!important;
        }

        input[type="date"] {
            height:30px!important;
        }

        input[type="datetime-local"] {
            height:30px!important;
        }
        input[type="file"] {
            height:30px!important;
        }

        .select2-selection--single{
            height:30px!important;
        }
        .form-group{
            margin-bottom:7px!important;
        }
        .input-group{
            margin-bottom:0px!important;
        }
        .input-group .select2-container{
            width:85%!important;
        }

        .plus-add{
            color:blue;
        }
        .input-group-text{
            padding-left:5px!important;
            padding-right:5px!important;
        }

        .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto {
            position: relative!important;
            width: 100%!important;
            padding-right: 8px!important;
            padding-left: 8px!important;
        }

       /* td:hover {
            background-color: #ed9923;
            color: black;
        }*/
        
        .yellowClass{
            background-color: #ffb64d !important; 
        }
        .greenClass{
            background-color: #2ed8b6 !important; 
        }
        .redClass{
            background-color: #ff5370 !important; 
        }
        .infoClass{
            background-color: #D2B4DE !important; 
        }
        .blueClass{
            background-color: #00bcd4 !important; 
        }

        .dataTable.table td, .dataTable.table th {
            padding: 5px 21px 5px 3px!important;
        }
        
        fieldset, legend {
            all: revert;
        }

    </style>
</head>
<body>
    <div id="app">
        @guest
            @yield('content')
        @else
            
            <!-- header -->
            @include('layouts.header')
            <!-- sidebar -->
            @include('layouts.sidebar')

            @yield('content')
            <!-- footer -->
            @include('layouts.footer')
        @endguest
    </div>
@guest
    <!-- Login  -->
    <!--===============================================================================================-->
        <script src="{{ asset('admin/login/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset('admin/login/vendor/animsition/js/animsition.min.js') }}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset('admin/login/vendor/bootstrap/js/popper.js') }}"></script>
        <script src="{{ asset('admin/login/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset('admin/login/vendor/select2/select2.min.js') }}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset('admin/login/vendor/daterangepicker/moment.min.js') }}"></script>
        <script src="{{ asset('admin/login/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset('admin/login/vendor/countdowntime/countdowntime.js') }}"></script>
    <!--===============================================================================================-->
        <script src="{{ asset('admin/login/js/main.js') }}"></script>
    <!-- Login  -->
@else
    <script type="text/javascript">
        $(document).ready(function(){

            $('.select2').select2();
            //form validation
            $("#main").validate({
                ignore: [],
                rules: {
                  //email: "required",
                },
                messages: {},
                highlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass); 
                    } else {
                        elem.addClass(errorClass);
                    }
                },    
                unhighlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass); 
                    } else {
                        elem.addClass(errorClass);
                    }
                },
                errorPlacement: function(error, element) {
                   var elem = $(element);
                   if (elem.hasClass("select2-hidden-accessible") && elem.closest('.input-group').length==0) {
                       element = $("#select2-" + elem.attr("id") + "-container").parent(); 
                       error.insertAfter(element);
                   } else if (elem.closest('.input-group').length) {
                        error.insertAfter(elem.closest('.input-group'));
                   } else {
                       error.insertAfter(element);
                   }
                },
            });

            $(".need-validation").validate({
                ignore: [],
                rules: {
                  //email: "required",
                },
                messages: {},
                highlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass); 
                    } else {
                        elem.addClass(errorClass);
                    }
                },    
                unhighlight: function (element, errorClass, validClass) {
                    var elem = $(element);
                    if (elem.hasClass("select2-hidden-accessible")) {
                        $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass); 
                    } else {
                        elem.addClass(errorClass);
                    }
                },
                errorPlacement: function(error, element) {
                   var elem = $(element);
                   if (elem.hasClass("select2-hidden-accessible") && elem.closest('.input-group').length==0) {
                       element = $("#select2-" + elem.attr("id") + "-container").parent(); 
                       error.insertAfter(element);
                   } else if (elem.closest('.input-group').length) {
                        error.insertAfter(elem.closest('.input-group'));
                   } else {
                       error.insertAfter(element);
                   }
                },
            });

            //form validation
        });//dom close

        $('body').on('keypress','.decimal-only',function(e){
            if(e.which == 46){
                if($(this).val().indexOf('.') != -1) {
                  return false;
                }
            }
            if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
              return false;
            }
        });

        $('body').on('keypress','.integers-only',function(e){
            if(e.which == 46){
                if($(this).val().indexOf('.') != 0) {
                  return false;
                }
            }
            if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $('body').on('keyup','.prevent-zero',function(e){
            if($(this).val().match(/^0/)){
                $(this).val('');
                return false;
            }
        });

        $(".filter-datatable").click(function() {
            $('#dt-ajax-array').DataTable().ajax.reload();
        });

        $(".clear-filter-datatable-without-reload").click(function() {
            $(".filter-input").val('');
            $(".filter-input-select").val('').select2();
            $('#dt-ajax-array').DataTable().ajax.reload();
        });
            
        $(".clear-filter-datatable-with-reload").click(function() {
           location.reload();
        });
        
        function ymdtodmy(temp_dt){
            var todaydate = new Date(temp_dt);  //pass val varible in Date(val)
            var dd = todaydate .getDate();
            var mm = todaydate .getMonth()+1; //January is 0!
            var yyyy = todaydate .getFullYear();
            if(dd<10){  dd='0'+dd } 
            if(mm<10){  mm='0'+mm } 
            var date = dd+'/'+mm+'/'+yyyy;
            return date;
        }

</script>
@endguest
</body>
</html>