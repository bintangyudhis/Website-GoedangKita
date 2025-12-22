<!doctype html>
<html lang="en" dir="ltr">

<?php

use App\Http\Controllers\Admin\LoginController;
use App\Models\Admin\AppreanceModel;
use App\Models\Admin\WebModel;
use Illuminate\Support\Facades\Session;

// Ambil data web (nama, logo, deskripsi)
$web = WebModel::first();

// Ambil preferensi tampilan user yang sedang login (layout/theme/menu/header/sidestyle)
$appreance = AppreanceModel::where('user_id', '=', Session::get('user')->user_id)->first();

?>

<head>
    <!-- =========================
         META DATA
    ========================== -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="{{ $web->web_deskripsi }}">
    <meta name="author" content="{{ $web->web_nama }}">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- =========================
         FAVICON
    ========================== -->
    @if ($web->web_logo == '' || $web->web_logo == 'default.png')
        <link rel="shortcut icon" type="image/x-icon" href="{{ url('/assets/default/web/default.png') }}" />
    @else
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/web/' . $web->web_logo) }}" />
    @endif

    <!-- =========================
         TITLE
    ========================== -->
    <title>{{ $title }} | {{ $web->web_nama }}</title>

    <!-- =========================
         CSS
    ========================== -->
    <link id="style" href="{{ url('/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />

    <link href="{{ url('/assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ url('/assets/css/dark-style.css') }}" rel="stylesheet" />
    <link href="{{ url('/assets/css/transparent-style.css') }}" rel="stylesheet">
    <link href="{{ url('/assets/css/skin-modes.css') }}" rel="stylesheet" />

    <link href="{{ url('/assets/css/icons.css') }}" rel="stylesheet" />

    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{ url('/assets/colors/color1.css') }}" />

    <!-- =========================
         Custom style fix z-index & scrollbar
    ========================== -->
    <style>
        modal.fade {
            z-index: 1050 !important;
        }

        .datepicker {
            z-index: 20000000 !important;
        }

        button.cancel {
            background-color: gray !important;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
            border-radius: 10px;
        }

        ::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
        }

        .dataTables_scrollBody::-webkit-scrollbar {
            width: 6px;
            background-color: #F5F5F5;
            height: 10px !important;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #777 !important;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #777;
            border-radius: 10px;
        }
    </style>
</head>

<!-- =========================
     BODY CLASS berdasarkan Appreance user
     Jika belum ada setting → default sidebar-mini + light-mode
========================== -->
@if ($appreance != '')
    <body class="app ltr
        {{ $appreance->appreance_layout }}
        {{ $appreance->appreance_theme }}
        {{ $appreance->appreance_menu }}
        {{ $appreance->appreance_header }}
        {{ $appreance->appreance_sidestyle }}">
@else
    <body class="app sidebar-mini ltr light-mode">
@endif

<!-- =========================
     GLOBAL LOADER
     Jika dark-mode → background loader jadi gelap
========================== -->
@if ($appreance != '')
    <div id="global-loader" class="{{ $appreance->appreance_theme == 'dark-mode' ? 'bg-dark' : '' }}">
@else
    <div id="global-loader">
@endif
        <img src="{{ url('/assets/images/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
<!-- /GLOBAL LOADER -->

<!-- =========================
     PAGE WRAPPER
========================== -->
<div class="page">
    <div class="page-main">

        <!-- HEADER -->
        @include('Master.Layouts.header', ['web' => $web])
        <!-- END HEADER -->

        <!-- SIDEBAR LEFT -->
        @include('Master.Layouts.sidebar-left', ['web' => $web])
        <!-- END SIDEBAR -->

        <!-- MAIN CONTENT -->
        <div class="main-content app-content mt-0">
            <div class="side-app">
                <div class="main-container container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
        <!-- END MAIN CONTENT -->

    </div>

    <!-- FOOTER -->
    @include('Master.Layouts.footer', ['web' => $web])
    <!-- END FOOTER -->
</div>

<!-- =========================
     MODAL LOGOUT
========================== -->
<div class="modal fade" data-bs-backdrop="static" id="modalLogout">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">
            <form method="GET" action="{{ url('admin/logout') }}" name="myFormH" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center p-4 pb-5">
                    <button type="reset" aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                    <br>
                    <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                    <h3 class="mb-5">Yakin logout ?</h3>
                    <button type="submit" class="btn btn-danger-light pd-x-25">Iya</button>
                    <button type="reset" data-bs-dismiss="modal" class="btn btn-default pd-x-25">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BACK TO TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

<!-- =========================
     JS: Core
========================== -->
<script src="{{ url('/assets/js/jquery.min.js') }}"></script>
<script src="{{ url('/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
<script src="{{ url('/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<!-- Sticky -->
<script src="{{ url('/assets/js/sticky.js') }}"></script>

<!-- Input Mask -->
<script src="{{ url('/assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

<!-- Side Menu -->
<script src="{{ url('/assets/plugins/sidemenu/sidemenu.js') }}"></script>

<!-- Sidebar -->
<script src="{{ url('/assets/plugins/sidebar/sidebar.js') }}"></script>

<!-- Perfect Scrollbar -->
<script src="{{ url('/assets/plugins/p-scroll/perfect-scrollbar.js') }}"></script>
<script src="{{ url('/assets/plugins/p-scroll/pscroll.js') }}"></script>
<script src="{{ url('/assets/plugins/p-scroll/pscroll-1.js') }}"></script>

<!-- File Upload -->
<script src="{{ url('/assets/plugins/fileuploads/js/fileupload.js') }}"></script>
<script src="{{ url('/assets/plugins/fileuploads/js/file-upload.js') }}"></script>

<!-- Daterangepicker + Moment -->
<script src="{{ url('/assets/plugins/bootstrap-daterangepicker/moment.min.js') }}"></script>
<script src="{{ url('/assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- Bootstrap Datepicker -->
<script src="{{ url('/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>

<!-- Select2 -->
<script src="{{ url('/assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ url('/assets/js/select2.js') }}"></script>

<!-- SumoSelect -->
<script src="{{ url('/assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

<!-- Timepicker -->
<script src="{{ url('/assets/plugins/time-picker/jquery.timepicker.js') }}"></script>
<script src="{{ url('/assets/plugins/time-picker/toggles.min.js') }}"></script>

<!-- intlTelInput -->
<script src="{{ url('/assets/plugins/intl-tel-input-master/intlTelInput.js') }}"></script>
<script src="{{ url('/assets/plugins/intl-tel-input-master/country-select.js') }}"></script>
<script src="{{ url('/assets/plugins/intl-tel-input-master/utils.js') }}"></script>

<!-- Transfer -->
<script src="{{ url('/assets/plugins/jQuerytransfer/jquery.transfer.js') }}"></script>

<!-- Multi -->
<script src="{{ url('/assets/plugins/multi/multi.min.js') }}"></script>

<!-- Date Picker -->
<script src="{{ url('/assets/plugins/date-picker/date-picker.js') }}"></script>
<script src="{{ url('/assets/plugins/date-picker/jquery-ui.js') }}"></script>
<script src="{{ url('/assets/plugins/input-mask/jquery.maskedinput.js') }}"></script>

<!-- Color Picker -->
<script src="{{ url('/assets/plugins/pickr-master/pickr.es5.min.js') }}"></script>
<script src="{{ url('/assets/js/picker.js') }}"></script>

<!-- Multiple Select -->
<script src="{{ url('/assets/plugins/multipleselect/multiple-select.js') }}"></script>
<script src="{{ url('/assets/plugins/multipleselect/multi-select.js') }}"></script>

<!-- SweetAlert -->
<script src="{{ url('/assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ url('/assets/js/sweet-alert.js') }}"></script>

<!-- ChartJS -->
<script src="{{ url('/assets/plugins/chart/Chart.bundle.js') }}"></script>
<script src="{{ url('/assets/plugins/chart/rounded-barchart.js') }}"></script>
<script src="{{ url('/assets/plugins/chart/utils.js') }}"></script>

<!-- DataTables -->
<script src="{{ url('/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('/assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ url('/assets/js/table-data.js') }}"></script>

<!-- Index -->
<script src="{{ url('/assets/js/index1.js') }}"></script>

<!-- Theme Colors -->
<script src="{{ url('/assets/js/themeColors.js') }}"></script>

<!-- Custom -->
<script src="{{ url('/assets/js/custom.js') }}"></script>

<!-- =========================
     Init Datepicker
     NOTE: Plugin biasanya dipanggil .datepicker()
     Kalau library kamu memang bootstrapdatepicker, OK.
========================== -->
<script>
    $(document).ready(function() {
        // BOOTSTRAP DATEPICKER
        $('.datepicker-date').bootstrapdatepicker({
            format: "yyyy-mm-dd",
            viewMode: "date",
            autoclose: true,
        });
    });
</script>

<!-- =========================
     Flash Message (Swal)
========================== -->
@if (Session::get('status') == 'success')
    <script>
        $(document).ready(function() {
            swal({
                title: "{{ Session::get('msg') }}",
                type: "success"
            });
        });
    </script>
@elseif (Session::get('status') == 'error')
    <script>
        $(document).ready(function() {
            swal({
                title: "{{ Session::get('msg') }}",
                type: "error"
            });
        });
    </script>
@endif

<!-- =========================
     Slot Script (Blade)
========================== -->
@yield('scripts')
@yield('formTambahJS')
@yield('formEditJS')
@yield('formHapusJS')
@yield('formOtherJS')

</body>
</html>
