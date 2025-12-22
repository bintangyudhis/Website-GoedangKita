@extends('Master.Layouts.app', ['title' => $title])
{{-- Pakai layout utama aplikasi, kirim variabel $title ke layout --}}

@section('content')
{{-- =========================
     PAGE HEADER
========================= --}}
<div class="page-header">
    {{-- Judul halaman --}}
    <h1 class="page-title">Tampilan/Tema</h1>

    {{-- Breadcrumb navigasi --}}
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-gray">Settings</li>
            <li class="breadcrumb-item active" aria-current="page">Tampilan/Tema</li>
        </ol>
    </div>
</div>
{{-- PAGE-HEADER END --}}

<div class="row">
    {{-- ======================================
         CARD 1: Layout Menu (Vertical/Horizontal)
    ======================================= --}}
    <div class="col-md-6">
        <div class="card mb-5">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Layout Menu</h3>
            </div>

            {{-- Form submit pengaturan layout --}}
            <form action="{{url('admin/appreance/layout')}}" method="POST">
                @csrf {{-- CSRF token Laravel wajib untuk POST --}}

                <div class="card-body">
                    <div class="form-group">
                        {{-- Pilihan radio bentuk pill --}}
                        <div class="selectgroup selectgroup-pills d-flex justify-content-center">

                            {{-- Opsi 1: Vertical Layout (sidebar-mini) --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    {{-- Jika sudah ada data, gunakan value tersimpan sebagai checked --}}
                                    <input type="radio" name="layout" value="sidebar-mini"
                                           class="selectgroup-input"
                                           {{$data->appreance_layout == 'sidebar-mini' ? 'checked' : ''}}>
                                @else
                                    {{-- Jika belum ada data, default vertical checked --}}
                                    <input type="radio" name="layout" value="sidebar-mini"
                                           class="selectgroup-input" checked>
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-layout fs-40 d-block my-4"></i>
                                        Vertical Layout
                                    </div>
                                </span>
                            </label>

                            {{-- Opsi 2: Horizontal Layout --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="layout" value="horizontal"
                                           class="selectgroup-input"
                                           {{$data->appreance_layout == 'horizontal' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="layout" value="horizontal"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-layout fs-40 d-block my-4"></i>
                                        Horizontal Layout
                                    </div>
                                </span>
                            </label>

                        </div>
                    </div>
                </div>

                {{-- Tombol submit --}}
                <div class="card-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Atur Tampilan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================
         CARD 2: Tema (Light/Dark Mode)
    ======================================= --}}
    <div class="col-md-6">
        <div class="card mb-5">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Tema</h3>
            </div>

            {{-- Form submit pengaturan theme --}}
            <form action="{{url('admin/appreance/theme')}}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <div class="selectgroup selectgroup-pills d-flex justify-content-center">

                            {{-- Opsi 1: Light Mode --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="theme" value="light-mode"
                                           class="selectgroup-input"
                                           {{$data->appreance_theme == 'light-mode' ? 'checked' : ''}}>
                                @else
                                    {{-- Default: light-mode checked --}}
                                    <input type="radio" name="theme" value="light-mode"
                                           class="selectgroup-input" checked>
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-sun fs-40 d-block my-4"></i>
                                        Light Mode
                                    </div>
                                </span>
                            </label>

                            {{-- Opsi 2: Dark Mode --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="theme" value="dark-mode"
                                           class="selectgroup-input"
                                           {{$data->appreance_theme == 'dark-mode' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="theme" value="dark-mode"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-moon fs-40 d-block my-4"></i>
                                        Dark Mode
                                    </div>
                                </span>
                            </label>

                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Atur Tampilan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================
         CARD 3: Warna Menu (Sidebar/Menu Color)
    ======================================= --}}
    <div class="col-md-6">
        <div class="card mb-5">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Warna Menu</h3>
            </div>

            {{-- Form submit pengaturan warna menu --}}
            <form action="{{url('admin/appreance/menu')}}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <div class="selectgroup selectgroup-pills d-flex justify-content-center">

                            {{-- Light Menu --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="menu" value="light-menu"
                                           class="selectgroup-input"
                                           {{$data->appreance_menu == 'light-menu' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="menu" value="light-menu"
                                           class="selectgroup-input" checked>
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-circle fs-40 d-block my-4"></i>
                                        Light Menu
                                    </div>
                                </span>
                            </label>

                            {{-- Color Menu --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="menu" value="color-menu"
                                           class="selectgroup-input"
                                           {{$data->appreance_menu == 'color-menu' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="menu" value="color-menu"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-circle fs-40 d-block my-4"></i>
                                        Color Menu
                                    </div>
                                </span>
                            </label>

                            {{-- Dark Menu --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="menu" value="dark-menu"
                                           class="selectgroup-input"
                                           {{$data->appreance_menu == 'dark-menu' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="menu" value="dark-menu"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-circle fs-40 d-block my-4"></i>
                                        Dark Menu
                                    </div>
                                </span>
                            </label>

                            {{-- Gradient Menu --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="menu" value="gradient-menu"
                                           class="selectgroup-input"
                                           {{$data->appreance_menu == 'gradient-menu' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="menu" value="gradient-menu"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-circle fs-40 d-block my-4"></i>
                                        Gradient Menu
                                    </div>
                                </span>
                            </label>

                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Atur Tampilan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================
         CARD 4: Warna Header
    ======================================= --}}
    <div class="col-md-6">
        <div class="card mb-5">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Warna Header</h3>
            </div>

            {{-- Form submit pengaturan warna header --}}
            <form action="{{url('admin/appreance/header')}}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <div class="selectgroup selectgroup-pills d-flex justify-content-center">

                            {{-- Header Light --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="header" value="header-light"
                                           class="selectgroup-input"
                                           {{$data->appreance_header == 'header-light' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="header" value="header-light"
                                           class="selectgroup-input" checked>
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-circle fs-40 d-block my-4"></i>
                                        Light Header
                                    </div>
                                </span>
                            </label>

                            {{-- Color Header --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="header" value="color-header"
                                           class="selectgroup-input"
                                           {{$data->appreance_header == 'color-header' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="header" value="color-header"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-circle fs-40 d-block my-4"></i>
                                        Color Header
                                    </div>
                                </span>
                            </label>

                            {{-- Dark Header --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="header" value="dark-header"
                                           class="selectgroup-input"
                                           {{$data->appreance_header == 'dark-header' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="header" value="dark-header"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-circle fs-40 d-block my-4"></i>
                                        Dark Header
                                    </div>
                                </span>
                            </label>

                            {{-- Gradient Header --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="header" value="gradient-header"
                                           class="selectgroup-input"
                                           {{$data->appreance_header == 'gradient-header' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="header" value="gradient-header"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-circle fs-40 d-block my-4"></i>
                                        Gradient Header
                                    </div>
                                </span>
                            </label>

                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Atur Tampilan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================
         CARD 5: Side Menu Style
         Catatan: hanya tampil kalau layout = sidebar-mini
    ======================================= --}}
    @if($data != '')
        {{-- Kalau ada data: tampilkan kolom ini hanya saat layout vertical (sidebar-mini) --}}
        <div class="col-md-6 {{$data->appreance_layout == 'sidebar-mini' ? '' : 'd-none'}}">
    @else
        {{-- Kalau belum ada data: default tampil --}}
        <div class="col-md-6">
    @endif

        <div class="card mb-5">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Side Menu Style</h3>
            </div>

            {{-- Form submit pengaturan style sidebar --}}
            <form action="{{url('admin/appreance/sidestyle')}}" method="POST">
                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <div class="selectgroup selectgroup-pills d-flex justify-content-center">

                            {{-- Default Menu --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="sidestyle" value="default-menu"
                                           class="selectgroup-input"
                                           {{$data->appreance_sidestyle == 'default-menu' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="sidestyle" value="default-menu"
                                           class="selectgroup-input" checked>
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-layout fs-40 d-block my-4"></i>
                                        Default Menu
                                    </div>
                                </span>
                            </label>

                            {{-- Icon Overlay (sidenav-toggled) --}}
                            <label class="selectgroup-item">
                                @if($data != '')
                                    <input type="radio" name="sidestyle" value="sidenav-toggled"
                                           class="selectgroup-input"
                                           {{$data->appreance_sidestyle == 'sidenav-toggled' ? 'checked' : ''}}>
                                @else
                                    <input type="radio" name="sidestyle" value="sidenav-toggled"
                                           class="selectgroup-input">
                                @endif

                                <span class="selectgroup-button">
                                    <div>
                                        <i class="fe fe-layout fs-40 d-block my-4"></i>
                                        Icon Overlay
                                    </div>
                                </span>
                            </label>

                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Atur Tampilan</button>
                </div>
            </form>
        </div>
    </div> {{-- end col-md-6 (Side Menu Style) --}}

</div> {{-- end row --}}
@endsection

@section('scripts')
{{-- Section scripts kosong: kalau nanti butuh JS (misal hide/show dinamis) taruh di sini --}}
@endsection
