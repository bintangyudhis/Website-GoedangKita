<!doctype html>
<html lang="en" dir="ltr">
<?php
use App\Http\Controllers\Admin\DashboardController;
use App\Models\Admin\WebModel;
use Illuminate\Support\Facades\Session;

// Ambil data web (nama, logo, deskripsi, dll)
$web = WebModel::first();
?>

<head>
    <!-- =========================
         META DATA
    ========================== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{$web->web_deskripsi}}">
    <meta name="author" content="{{$web->web_nama}}">
    <meta name="keywords" content="">

    <!-- =========================
         FAVICON
         Jika logo kosong / default → pakai default.png
         Jika ada → pakai logo dari storage
    ========================== -->
    @if($web->web_logo == '' || $web->web_logo == 'default.png')
        <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/default/web/default.png')}}" />
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/web/' . $web->web_logo)}}" />
    @endif

    <!-- =========================
         TITLE
         Judul halaman dinamis: $title | nama web
    ========================== -->
    <title>{{$title}} | {{$web->web_nama}}</title>

    <!-- =========================
         CSS: Bootstrap + Theme
    ========================== -->
    <link id="style" href="{{ url('/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <link href="{{url('/assets/css/style.css')}}" rel="stylesheet" />
    <link href="{{url('/assets/css/dark-style.css')}}" rel="stylesheet" />
    <link href="{{url('/assets/css/transparent-style.css')}}" rel="stylesheet">
    <link href="{{url('/assets/css/skin-modes.css')}}" rel="stylesheet" />

    <!-- ICONS -->
    <link href="{{url('/assets/css/icons.css')}}" rel="stylesheet" />

    <!-- COLOR SKIN -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{url('/assets/colors/color1.css')}}" />

    <!-- Override: agar halaman login bisa scroll -->
    <style>
        html, body {
            overflow: auto;
        }
    </style>
</head>

<body class="app sidebar-mini ltr">

    <!-- =========================
         BACKGROUND IMAGE WRAPPER
    ========================== -->
    <div class="login-img">

        <!-- GLOBAL LOADER -->
        <div id="global-loader">
            <img src="{{url('/assets/images/loader.svg')}}" class="loader-img" alt="Loader">
        </div>
        <!-- /GLOBAL LOADER -->

        <!-- =========================
             PAGE
        ========================== -->
        <div class="page">
            <div class="">
                <!-- Tempat konten halaman (login, forgot, dll) -->
                @yield('content')
            </div>
        </div>
        <!-- End PAGE -->
    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    <!-- =========================
         JS: jQuery + Bootstrap
    ========================== -->
    <script src="{{url('/assets/js/jquery.min.js')}}"></script>

    <script src="{{url('/assets/plugins/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{url('/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- SweetAlert -->
    <script src="{{url('/assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{url('/assets/js/sweet-alert.js')}}"></script>

    <!-- Show password + OTP -->
    <script src="{{url('/assets/js/show-password.min.js')}}"></script>
    <script src="{{url('/assets/js/generate-otp.js')}}"></script>

    <!-- Perfect Scrollbar -->
    <script src="{{url('/assets/plugins/p-scroll/perfect-scrollbar.js')}}"></script>

    <!-- Theme Colors -->
    <script src="{{url('/assets/js/themeColors.js')}}"></script>

    <!-- Custom -->
    <script src="{{url('/assets/js/custom.js')}}"></script>

    <!-- =========================
         Flash Message via Session
         status = success / error → tampil swal saat page load
    ========================== -->
    @if(Session::get('status') == 'success')
        <script>
            $(document).ready(function () {
                swal({
                    title: "{{ Session::get('msg') }}",
                    type: "success"
                });
            });
        </script>
    @elseif(Session::get('status') == 'error')
        <script>
            $(document).ready(function () {
                swal({
                    title: "{{ Session::get('msg') }}",
                    type: "error"
                });
            });
        </script>
    @endif

    <!-- Tempat script tambahan per-halaman -->
    @yield('scripts')
</body>
</html>
