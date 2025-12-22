<!doctype html>
<html lang="en" dir="ltr">
{{-- Dokumen HTML, bahasa Inggris, arah teks LTR --}}

<?php
use App\Models\Admin\WebModel;
// Import model WebModel untuk ambil data konfigurasi web (nama, logo, deskripsi, dll)

$web = WebModel::first();
// Ambil 1 data pertama dari tabel web (biasanya konfigurasi utama)
?>

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    {{-- Set encoding karakter UTF-8 --}}
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    {{-- Mengatur tampilan responsif di mobile; user-scalable=0 menonaktifkan zoom --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    {{-- Kompatibilitas mode rendering untuk browser lama --}}
    <meta name="description" content="{{$web->web_deskripsi}}">
    {{-- Deskripsi halaman dari database --}}
    <meta name="author" content="{{$web->web_nama}}">
    {{-- Author halaman (nama web) --}}
    <meta name="keywords" content="">
    {{-- Kata kunci SEO (kosong) --}}

    <!-- FAVICON -->
    @if($web->web_logo == '' || $web->web_logo == 'default.png')
    {{-- Jika logo kosong atau default --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/default/web/default.png')}}" />
    {{-- Favicon memakai default --}}
    @else
    {{-- Jika logo custom --}}
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/web/' . $web->web_logo)}}" />
    {{-- Favicon mengambil dari storage --}}
    @endif

    <!-- TITLE -->
    <title>Halaman tidak ditemukan | {{$web->web_nama}}</title>
    {{-- Judul tab browser: halaman 404 --}}

   <!-- STYLE CSS -->
   <link href="{{url('/assets/css/style.css')}}" rel="stylesheet" />
   {{-- CSS utama --}}
    <link href="{{url('/assets/css/dark-style.css')}}" rel="stylesheet" />
    {{-- CSS mode gelap --}}
    <link href="{{url('/assets/css/transparent-style.css')}}" rel="stylesheet">
    {{-- CSS mode transparan --}}
    <link href="{{url('/assets/css/skin-modes.css')}}" rel="stylesheet" />
    {{-- CSS skin/mode tambahan --}}

    <!--- FONT-ICONS CSS -->
    <link href="{{url('/assets/css/icons.css')}}" rel="stylesheet" />
    {{-- CSS ikon/font icons --}}

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{url('/assets/colors/color1.css')}}" />
    {{-- CSS tema warna (skin) default color1 --}}
</head>

<body class="">
{{-- Body halaman (class kosong, bisa diisi jika butuh styling tertentu) --}}

    <!-- BACKGROUND-IMAGE -->
    <div class="login-img">
        {{-- Container background login/halaman error (biasanya ada background image via CSS) --}}

        <!-- GLOBAL-LOADER -->
        <div id="global-loader">
            {{-- Loader global saat halaman sedang loading --}}
            <img src="{{url('/assets/images/loader.svg')}}" class="loader-img" alt="Loader">
            {{-- Gambar loader --}}
        </div>
        <!-- End GLOBAL-LOADER -->

        <!-- PAGE -->
        <div class="page">
            {{-- Wrapper halaman utama --}}

            <!-- PAGE-CONTENT OPEN -->
            <div class="page-content error-page error2 text-white">
                {{-- Konten error page + text putih --}}
                <div class="container text-center">
                    {{-- Container bootstrap, text rata tengah --}}
                    <div class="error-template">
                        {{-- Template tampilan error --}}

                        <h1 class="display-1 mb-2">
                            {{-- Heading besar untuk kode error --}}
                            4
                            <span class="custom-emoji">
                                {{-- Span untuk ikon custom (emoji) --}}
                                <svg xmlns="http://www.w3.org/2000/svg" height="140" width="140" data-name="Layer 1" viewBox="0 0 24 24">
                                    {{-- SVG icon (gambar lingkaran + simbol) --}}
                                    <circle cx="12" cy="12" r="10" fill="#a2a1ff"/>
                                    {{-- Lingkaran latar SVG --}}
                                    <path fill="#6563ff" d="M15.999,17a.99764.99764,0,0,1-.59912-.2002l-.7334-.5498-.73291.5498a.99755.99755,0,0,1-1.20019,0L12,16.25l-.7334.5498a.9999.9999,0,0,1-1.20019-1.5996l1.33349-1a.99757.99757,0,0,1,1.2002,0l.7334.5498.73291-.5498a.99755.99755,0,0,1,1.20019,0l1.3335,1A1.00013,1.00013,0,0,1,15.999,17Z"/>
                                    {{-- Path SVG bagian wajah/mulut --}}
                                    <path fill="#6563ff" d="M13.33252 17a.9976.9976 0 0 1-.59912-.2002L12 16.25l-.7334.5498a.99755.99755 0 0 1-1.20019 0L9.3335 16.25l-.7334.5498a.9999.9999 0 0 1-1.2002-1.5996l1.3335-1a.99755.99755 0 0 1 1.20019 0l.73291.5498.7334-.5498a.99757.99757 0 0 1 1.2002 0l1.33349 1A1.00013 1.00013 0 0 1 13.33252 17zM8.37109 12.5a1 1 0 0 1-.707-1.707L8.457 10l-.793-.793A.99989.99989 0 0 1 9.07812 7.793l1.5 1.5a.99962.99962 0 0 1 0 1.41406l-1.5 1.5A.99676.99676 0 0 1 8.37109 12.5zM15.87109 12.5a.99678.99678 0 0 1-.707-.293l-1.5-1.5a.99964.99964 0 0 1 0-1.41406l1.5-1.5A.99989.99989 0 0 1 16.57812 9.207l-.793.793.793.793a1 1 0 0 1-.707 1.707z"/>
                                    {{-- Path SVG bagian mata/ikon panah --}}
                                </svg>
                            </span>
                            4
                            {{-- Menampilkan 404 dengan 0 diganti ikon SVG --}}
                        </h1>

                        <h5 class="error-details">
                            {{-- Pesan error --}}
                            Maaf, telah terjadi kesalahan, Halaman yang diminta tidak ditemukan!
                        </h5>

                        <div class="text-center">
                            {{-- Area tombol aksi --}}
                            <a class="btn btn-primary mt-5 mb-5" href="{{url('/admin')}}">
                                {{-- Tombol kembali ke halaman admin --}}
                                <i class="fa fa-long-arrow-left"></i>
                                {{-- Icon panah kiri --}}
                                Kembali
                            </a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- PAGE-CONTENT OPEN CLOSED -->
        </div>
        <!-- End PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE -->

    <!-- JQUERY JS -->
    <script src="{{url('/assets/js/jquery.min.js')}}"></script>
    {{-- Import jQuery --}}

    <!-- BOOTSTRAP JS -->
    <script src="{{url('/assets/plugins/bootstrap/js/popper.min.js')}}"></script>
    {{-- Popper untuk tooltip/dropdown bootstrap --}}
    <script src="{{url('/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    {{-- Bootstrap JS utama --}}

    <!-- Perfect SCROLLBAR JS-->
    <script src="{{url('/assets/plugins/p-scroll/perfect-scrollbar.js')}}"></script>
    {{-- Plugin scrollbar halus --}}

    <!-- Color Theme js -->
    <script src="{{url('/assets/js/themeColors.js')}}"></script>
    {{-- Script pengaturan warna tema --}}

    <!-- Sticky js -->
    <script src="{{url('/assets/js/sticky.js')}}"></script>
    {{-- Script elemen sticky (misal navbar) --}}

    <!-- CUSTOM JS -->
    <script src="{{url('/assets/js/custom.js')}}"></script>
    {{-- Script custom proyek --}}

</body>
</html>
