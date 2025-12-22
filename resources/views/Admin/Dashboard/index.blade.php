@extends('Master.Layouts.app', ['title' => $title]) {{-- Menggunakan layout utama dan mengirim variabel title ke layout --}}

@section('content') {{-- Awal section konten dashboard --}}

    <div class="page-header"> {{-- Header halaman --}}
        <h1 class="page-title">Dashboard</h1> {{-- Judul halaman --}}
        <div> {{-- Container breadcrumb --}}
            <ol class="breadcrumb"> {{-- Breadcrumb navigasi --}}
                <li class="breadcrumb-item text-gray">Admin</li> {{-- Breadcrumb level 1 --}}
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li> {{-- Breadcrumb aktif --}}
            </ol>
        </div>
    </div>

    <div class="row"> {{-- Row untuk kumpulan card dashboard --}}

        {{-- Card Jenis Barang --}} {{-- Komentar Blade: card menuju halaman jenis barang --}}
        <a href="{{ url('/admin/jenisbarang') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            {{-- Link (sekaligus grid kolom) ke halaman /admin/jenisbarang; text-decoration none menghilangkan garis bawah --}}
            <div class="card bg-primary img-card box-primary-shadow"> {{-- Card berwarna primary dengan shadow --}}
                <div class="card-body"> {{-- Isi card --}}
                    <div class="d-flex"> {{-- Flexbox untuk membagi kiri (angka+teks) dan kanan (ikon) --}}
                        <div class="text-white"> {{-- Bagian kiri berwarna putih --}}
                            <h2 class="mb-0 number-font">{{ $jenis }}</h2> {{-- Menampilkan jumlah jenis barang --}}
                            <p class="text-white mb-0">Jenis Barang </p> {{-- Label card --}}
                        </div>
                        <div class="ms-auto">
                            {{-- Bagian kanan, ms-auto mendorong ke kanan --}}
                            <i class="fe fe-package text-white fs-40 me-2 mt-2"></i>
                            {{-- Ikon package, warna putih, ukuran 40 --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card Satuan Barang --}} {{-- Card menuju halaman satuan --}}
        <a href="{{ url('/admin/satuan') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-secondary img-card box-secondary-shadow"> {{-- Card warna secondary --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $satuan }}</h2> {{-- Menampilkan jumlah satuan --}}
                            <p class="text-white mb-0">Satuan Barang</p> {{-- Label --}}
                        </div>
                        <div class="ms-auto">
                            <i class="fe fe-package text-white fs-40 me-2 mt-2"></i> {{-- Ikon --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card Merk Barang --}} {{-- Card menuju halaman merk --}}
        <a href="{{ url('/admin/merk') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card  bg-success img-card box-success-shadow">
                {{-- Card warna success --}}
                {{-- CATATAN: setelah kata "card" ada karakter spasi tidak normal (non-breaking space) -> bisa bikin class tidak terbaca sempurna --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $merk }}</h2> {{-- Menampilkan jumlah merk --}}
                            <p class="text-white mb-0">Merk Barang</p> {{-- Label --}}
                        </div>
                        <div class="ms-auto">
                            <i class="fe fe-package text-white fs-40 me-2 mt-2"></i> {{-- Ikon --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card Barang --}} {{-- Card menuju halaman daftar barang --}}
        <a href="{{ url('/admin/barang') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-info img-card box-info-shadow"> {{-- Card warna info --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $barang }}</h2> {{-- Menampilkan jumlah barang --}}
                            <p class="text-white mb-0">Barang</p> {{-- Label --}}
                        </div>
                        <div class="ms-auto">
                            <i class="fe fe-package text-white fs-40 me-2 mt-2"></i> {{-- Ikon --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card Barang Masuk --}} {{-- Card menuju halaman transaksi barang masuk --}}
        <a href="{{ url('/admin/barang-masuk') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3"
            style="text-decoration: none;">
            <div class="card bg-success img-card box-success-shadow"> {{-- Card warna success --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $bm }}</h2> {{-- Menampilkan jumlah transaksi barang masuk --}}
                            <p class="text-white mb-0">Barang Masuk</p> {{-- Label --}}
                        </div>
                        <div class="ms-auto">
                            <i class="fe fe-repeat text-white fs-40 me-2 mt-2"></i>
                            {{-- Ikon repeat (transaksi masuk/keluar) --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card Barang Keluar --}} {{-- Card menuju halaman transaksi barang keluar --}}
        <a href="{{ url('/admin/barang-keluar') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3"
            style="text-decoration: none;">
            <div class="card bg-danger img-card box-danger-shadow"> {{-- Card warna danger --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $bk }}</h2> {{-- Menampilkan jumlah transaksi barang keluar --}}
                            <p class="text-white mb-0">Barang Keluar</p> {{-- Label --}}
                        </div>
                        <div class="ms-auto">
                            <i class="fe fe-repeat text-white fs-40 me-2 mt-2"></i> {{-- Ikon repeat --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card Customer --}} {{-- Card menuju halaman customer --}}
        <a href="{{ url('/admin/customer') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-purple img-card box-purple-shadow"> {{-- Card warna ungu --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $customer }}</h2> {{-- Menampilkan jumlah customer --}}
                            <p class="text-white mb-0">Customer</p> {{-- Label --}}
                        </div>
                        <div class="ms-auto">
                            <i class="fe fe-user text-white fs-40 me-2 mt-2"></i> {{-- Ikon user --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card User --}} {{-- Card menuju halaman user --}}
        <a href="{{ url('/admin/user') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-warning img-card box-warning-shadow"> {{-- Card warna warning --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $user }}</h2> {{-- Menampilkan jumlah user --}}
                            <p class="text-white mb-0">User</p> {{-- Label --}}
                        </div>
                        <div class="ms-auto">
                            <i class="fe fe-user text-white fs-40 me-2 mt-2"></i> {{-- Ikon user --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

        {{-- Card Total Stok --}} {{-- Card menuju laporan stok barang --}}
        <a href="{{ url('/admin/lap-stok-barang') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3"
            style="text-decoration: none;">
            <div class="card bg-secondary img-card box-secondary-shadow"> {{-- Card warna secondary --}}
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $total_stok }}</h2> {{-- Menampilkan total stok barang --}}
                            <p class="text-white mb-0">Total Stok Barang</p> {{-- Label --}}
                        </div>
                        <div class="ms-auto">
                            <i class="fe fe-package text-white fs-40 me-2 mt-2"></i> {{-- Ikon package --}}
                        </div>
                    </div>
                </div>
            </div>
        </a>

    </div> {{-- Akhir row card --}}
@endsection {{-- Akhir section content --}}
