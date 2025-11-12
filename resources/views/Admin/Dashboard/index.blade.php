@extends('Master.Layouts.app', ['title' => $title])

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item text-gray">Admin</li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </div>
    </div>
    <div class="row">

        {{-- Card Jenis Barang --}}
        <a href="{{ url('/admin/jenisbarang') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-primary img-card box-primary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $jenis }}</h2>
                            <p class="text-white mb-0">Jenis Barang </p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        {{-- Card Satuan Barang --}}
        <a href="{{ url('/admin/satuan') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-secondary img-card box-secondary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $satuan }}</h2>
                            <p class="text-white mb-0">Satuan Barang</p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        {{-- Card Merk Barang --}}
        <a href="{{ url('/admin/merk') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card  bg-success img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $merk }}</h2>
                            <p class="text-white mb-0">Merk Barang</p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        {{-- Card Barang --}}
        <a href="{{ url('/admin/barang') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-info img-card box-info-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $barang }}</h2>
                            <p class="text-white mb-0">Barang</p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        {{-- Card Barang Masuk --}}
        <a href="{{ url('/admin/barang-masuk') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3"
            style="text-decoration: none;">
            <div class="card bg-success img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $bm }}</h2>
                            <p class="text-white mb-0">Barang Masuk</p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-repeat text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        {{-- Card Barang Keluar --}}
        <a href="{{ url('/admin/barang-keluar') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3"
            style="text-decoration: none;">
            <div class="card bg-danger img-card box-danger-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $bk }}</h2>
                            <p class="text-white mb-0">Barang Keluar</p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-repeat text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        {{-- Card Customer --}}
        <a href="{{ url('/admin/customer') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-purple img-card box-purple-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $customer }}</h2>
                            <p class="text-white mb-0">Customer</p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-user text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        {{-- Card User --}}
        <a href="{{ url('/admin/user') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3" style="text-decoration: none;">
            <div class="card bg-warning img-card box-warning-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $user }}</h2>
                            <p class="text-white mb-0">User</p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-user text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        {{-- Card Total Stok --}}
        <a href="{{ url('/admin/lap-stok-barang') }}" class="col-sm-6 col-md-6 col-lg-6 col-xl-3"
            style="text-decoration: none;">
            <div class="card bg-secondary img-card box-secondary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white">
                            <h2 class="mb-0 number-font">{{ $total_stok }}</h2>
                            <p class="text-white mb-0">Total Stok Barang</p>
                        </div>
                        <div class="ms-auto"> <i class="fe fe-package text-white fs-40 me-2 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endsection
