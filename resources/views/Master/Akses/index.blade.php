@extends('Master.Layouts.app', ['title' => $title])
{{-- Menggunakan layout utama, kirim variabel title ke layout --}}

<?php

use App\Models\Admin\AksesModel;
use App\Models\Admin\SubmenuModel;
?>
{{-- Import model untuk cek akses + ambil submenu berdasarkan menu_id --}}

@section('content')
<!-- PAGE-HEADER -->
<div class="page-header">
    {{-- Judul halaman --}}
    <h1 class="page-title">Akses</h1>

    {{-- Breadcrumb navigasi --}}
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-gray">Settings</li>
            <li class="breadcrumb-item active" aria-current="page">Akses</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<!-- ROW -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                {{-- BAGIAN PILIH ROLE --}}
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <h4 class="text-gray">Role</h4>

                            {{-- Dropdown role + tombol submit --}}
                            <div class="d-flex">
                                <select name="role" class="form-control">
                                    <option value="">-- Pilih Role --</option>
                                    {{-- Loop semua role --}}
                                    @foreach($role as $r)
                                        <option value="{{$r->role_id}}" {{$roleid == $r->role_id ? 'selected' : ''}}>
                                            {{$r->role_title}}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="ms-1">
                                    {{-- Submit => redirect halaman akses sesuai role --}}
                                    <button type="submit" onclick="submitRole()" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol set/unset akses semua (muncul kalau role sudah dipilih) --}}
                    @if($detailrole != '')
                        <div class="col-md-7 d-flex justify-content-end align-items-center">
                            <div>
                                {{-- Jika role yang dipilih BUKAN role user yang sedang login => boleh nonaktifkan semua --}}
                                @if(Session::get('user')->role_slug != $detailrole->role_slug)
                                    <button class="btn btn-gray me-2" onclick="unsetAll({{$detailrole->role_id}})">
                                        Non-aktifkan Semua Akses
                                    </button>
                                @else
                                    {{-- Supaya user tidak bisa "mengunci dirinya sendiri" --}}
                                    <button disabled class="btn btn-gray me-2">
                                        Non-aktifkan Semua Akses
                                    </button>
                                @endif
                            </div>

                            <div>
                                {{-- Aktifkan semua akses untuk role yang dipilih --}}
                                <button class="btn btn-primary" onclick="setAll({{$detailrole->role_id}})">
                                    Aktifkan Semua Akses
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ========================================= --}}
                {{-- BAGIAN HAK AKSES MENU UTAMA --}}
                {{-- ========================================= --}}
                @if($detailrole != '')
                    @if(count($menu) > 0)
                        <h4 class="text-gray">
                            Hak Akses Menu
                            <span class="badge bg-primary badge-sm">
                                {{$detailrole == '' ? '' : $detailrole->role_title}}
                            </span>
                        </h4>
                    @endif

                    <div class="table-responsive mb-4">
                        <table class="table border text-nowrap text-md-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th width="1%">View</th>
                                    <th width="1%">Create</th>
                                    <th width="1%">Update</th>
                                    <th width="1%">Delete</th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- Loop menu utama --}}
                                @foreach($menu as $m)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{$m->menu_judul}}</span>
                                        </td>

                                        {{-- VIEW MENU --}}
                                        <td>
                                            <?php
                                            // Cek apakah role ini punya akses "view" untuk menu ini
                                            $getView = AksesModel::where([
                                                'menu_id' => $m->menu_id,
                                                'role_id' => $roleid,
                                                'akses_type' => 'view'
                                            ])->first();
                                            ?>
                                            <label class="custom-switch form-switch me-5">
                                                @if($getView == '')
                                                    {{-- Kalau belum ada akses, toggle akan menambah --}}
                                                    <input type="checkbox"
                                                           onchange="addAkses('{{$m->menu_id}}', '{{$roleid}}', 'menu', 'view')"
                                                           name="viewMenu[]" class="custom-switch-input">
                                                @else
                                                    {{-- Kalau sudah ada akses, toggle akan menghapus --}}
                                                    <input type="checkbox"
                                                           onchange="removeAkses('{{$m->menu_id}}', '{{$roleid}}', 'menu', 'view')"
                                                           checked name="viewMenu[]" class="custom-switch-input">
                                                @endif
                                                <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                            </label>
                                        </td>

                                        {{-- CREATE MENU --}}
                                        <td>
                                            {{-- Create hanya bisa kalau View aktif (dependency) --}}
                                            @if($getView != '')
                                                <?php
                                                $getCreate = AksesModel::where([
                                                    'menu_id' => $m->menu_id,
                                                    'role_id' => $roleid,
                                                    'akses_type' => 'create'
                                                ])->first();
                                                ?>
                                                <label class="custom-switch form-switch me-5">
                                                    @if($getCreate == '')
                                                        <input type="checkbox"
                                                               onchange="addAkses('{{$m->menu_id}}', '{{$roleid}}', 'menu', 'create')"
                                                               name="createMenu[]" class="custom-switch-input">
                                                    @else
                                                        <input type="checkbox"
                                                               onchange="removeAkses('{{$m->menu_id}}', '{{$roleid}}', 'menu', 'create')"
                                                               checked name="createMenu[]" class="custom-switch-input">
                                                    @endif
                                                    <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                </label>
                                            @else
                                                {{-- Kalau view belum aktif, create dinonaktifkan + tooltip --}}
                                                <label class="custom-switch form-switch me-5"
                                                       data-bs-placement="top"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-original-title="Aktifkan akses view">
                                                    <input type="checkbox" disabled class="custom-switch-input">
                                                    <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                </label>
                                            @endif
                                        </td>

                                        {{-- UPDATE MENU --}}
                                        <td>
                                            @if($getView != '')
                                                <?php
                                                $getUpdate = AksesModel::where([
                                                    'menu_id' => $m->menu_id,
                                                    'role_id' => $roleid,
                                                    'akses_type' => 'update'
                                                ])->first();
                                                ?>
                                                <label class="custom-switch form-switch me-5">
                                                    @if($getUpdate == '')
                                                        <input type="checkbox"
                                                               onchange="addAkses('{{$m->menu_id}}', '{{$roleid}}', 'menu', 'update')"
                                                               name="updateMenu[]" class="custom-switch-input">
                                                    @else
                                                        <input type="checkbox"
                                                               onchange="removeAkses('{{$m->menu_id}}', '{{$roleid}}', 'menu', 'update')"
                                                               checked name="updateMenu[]" class="custom-switch-input">
                                                    @endif
                                                    <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                </label>
                                            @else
                                                <label class="custom-switch form-switch me-5"
                                                       data-bs-placement="top"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-original-title="Aktifkan akses view">
                                                    <input type="checkbox" disabled class="custom-switch-input">
                                                    <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                </label>
                                            @endif
                                        </td>

                                        {{-- DELETE MENU --}}
                                        <td>
                                            @if($getView != '')
                                                <?php
                                                $getDelete = AksesModel::where([
                                                    'menu_id' => $m->menu_id,
                                                    'role_id' => $roleid,
                                                    'akses_type' => 'delete'
                                                ])->first();
                                                ?>
                                                <label class="custom-switch form-switch me-5">
                                                    @if($getDelete == '')
                                                        <input type="checkbox"
                                                               onchange="addAkses('{{$m->menu_id}}', '{{$roleid}}', 'menu', 'delete')"
                                                               name="deleteMenu[]" class="custom-switch-input">
                                                    @else
                                                        <input type="checkbox"
                                                               onchange="removeAkses('{{$m->menu_id}}', '{{$roleid}}', 'menu', 'delete')"
                                                               checked name="deleteMenu[]" class="custom-switch-input">
                                                    @endif
                                                    <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                </label>
                                            @else
                                                <label class="custom-switch form-switch me-5"
                                                       data-bs-placement="top"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-original-title="Aktifkan akses view">
                                                    <input type="checkbox" disabled class="custom-switch-input">
                                                    <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                </label>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- ========================================= --}}
                    {{-- BAGIAN HAK AKSES SUB MENU --}}
                    {{-- ========================================= --}}
                    @if(count($menusub) > 0)
                        <h4 class="text-gray">
                            Hak Akses Sub Menu
                            <span class="badge bg-primary badge-sm">
                                {{$detailrole == '' ? '' : $detailrole->role_title}}
                            </span>
                        </h4>
                    @endif

                    {{-- Loop grup menu yang punya submenu --}}
                    @foreach($menusub as $ms)

                        {{-- Header grup menu + toggle view menu induk --}}
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <h6 class="fw-bold">{{$ms->menu_judul}}</h6>

                            <?php
                            // Cek akses view untuk menu induk
                            $getView1 = AksesModel::where([
                                'menu_id' => $ms->menu_id,
                                'role_id' => $roleid,
                                'akses_type' => 'view'
                            ])->first();
                            ?>

                            <label class="custom-switch form-switch mb-3">
                                @if($getView1 == '')
                                    <input type="checkbox"
                                           onchange="addAkses('{{$ms->menu_id}}', '{{$roleid}}', 'menu', 'view')"
                                           class="custom-switch-input">
                                @else
                                    <input type="checkbox"
                                           onchange="removeAkses('{{$ms->menu_id}}', '{{$roleid}}', 'menu', 'view')"
                                           checked class="custom-switch-input">
                                @endif
                                <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                            </label>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table border text-nowrap text-md-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th width="1%">View</th>
                                        <th width="1%">Create</th>
                                        <th width="1%">Update</th>
                                        <th width="1%">Delete</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    // Ambil submenu berdasarkan menu_id induk, urut sesuai submenu_sort
                                    $submenu = SubmenuModel::where('menu_id', '=', $ms->menu_id)
                                        ->orderBy('submenu_sort', 'ASC')
                                        ->get();
                                    ?>

                                    @foreach($submenu as $sm)
                                        <tr>
                                            <td><span class="fw-bold">{{$sm->submenu_judul}}</span></td>

                                            {{-- VIEW SUBMENU --}}
                                            <td>
                                                {{-- View submenu hanya bisa kalau view menu induk aktif --}}
                                                @if($getView1 != '')
                                                    <?php
                                                    $getView11 = AksesModel::where([
                                                        'submenu_id' => $sm->submenu_id,
                                                        'role_id' => $roleid,
                                                        'akses_type' => 'view'
                                                    ])->first();
                                                    ?>

                                                    <label class="custom-switch form-switch me-5">
                                                        @if($getView11 == '')
                                                            <input type="checkbox"
                                                                   onchange="addAkses('{{$sm->submenu_id}}', '{{$roleid}}', 'submenu', 'view')"
                                                                   class="custom-switch-input">
                                                        @else
                                                            <input type="checkbox"
                                                                   onchange="removeAkses('{{$sm->submenu_id}}', '{{$roleid}}', 'submenu', 'view')"
                                                                   checked class="custom-switch-input">
                                                        @endif
                                                        <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                    </label>
                                                @else
                                                    {{-- Kalau view menu induk belum aktif => disabled --}}
                                                    <label class="custom-switch form-switch me-5"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-original-title="Aktifkan akses {{$ms->menu_judul}}">
                                                        <input type="checkbox" disabled class="custom-switch-input">
                                                        <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                    </label>
                                                @endif
                                            </td>

                                            {{-- CREATE/UPDATE/DELETE SUBMENU --}}
                                            {{-- Polanya sama:
                                               - Butuh view menu induk aktif ($getView1)
                                               - Butuh view submenu aktif ($getView11)
                                               - Kalau tidak, checkbox disabled + tooltip
                                            --}}
                                            <td>
                                                @if($getView1 != '')
                                                    @if(isset($getView11) && $getView11 != '')
                                                        <?php $getCreate1 = AksesModel::where([
                                                            'submenu_id' => $sm->submenu_id,
                                                            'role_id' => $roleid,
                                                            'akses_type' => 'create'
                                                        ])->first(); ?>
                                                        <label class="custom-switch form-switch me-5">
                                                            @if($getCreate1 == '')
                                                                <input type="checkbox"
                                                                       onchange="addAkses('{{$sm->submenu_id}}', '{{$roleid}}', 'submenu', 'create')"
                                                                       class="custom-switch-input">
                                                            @else
                                                                <input type="checkbox"
                                                                       onchange="removeAkses('{{$sm->submenu_id}}', '{{$roleid}}', 'submenu', 'create')"
                                                                       checked class="custom-switch-input">
                                                            @endif
                                                            <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                        </label>
                                                    @else
                                                        <label class="custom-switch form-switch me-5"
                                                               data-bs-toggle="tooltip"
                                                               data-bs-original-title="Aktifkan akses view">
                                                            <input type="checkbox" disabled class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                        </label>
                                                    @endif
                                                @else
                                                    <label class="custom-switch form-switch me-5"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-original-title="Aktifkan akses {{$ms->menu_judul}}">
                                                        <input type="checkbox" disabled class="custom-switch-input">
                                                        <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                    </label>
                                                @endif
                                            </td>

                                            <td>
                                                @if($getView1 != '')
                                                    @if(isset($getView11) && $getView11 != '')
                                                        <?php $getUpdate1 = AksesModel::where([
                                                            'submenu_id' => $sm->submenu_id,
                                                            'role_id' => $roleid,
                                                            'akses_type' => 'update'
                                                        ])->first(); ?>
                                                        <label class="custom-switch form-switch me-5">
                                                            @if($getUpdate1 == '')
                                                                <input type="checkbox"
                                                                       onchange="addAkses('{{$sm->submenu_id}}', '{{$roleid}}', 'submenu', 'update')"
                                                                       class="custom-switch-input">
                                                            @else
                                                                <input type="checkbox"
                                                                       onchange="removeAkses('{{$sm->submenu_id}}', '{{$roleid}}', 'submenu', 'update')"
                                                                       checked class="custom-switch-input">
                                                            @endif
                                                            <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                        </label>
                                                    @else
                                                        <label class="custom-switch form-switch me-5"
                                                               data-bs-toggle="tooltip"
                                                               data-bs-original-title="Aktifkan akses view">
                                                            <input type="checkbox" disabled class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                        </label>
                                                    @endif
                                                @else
                                                    <label class="custom-switch form-switch me-5"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-original-title="Aktifkan akses {{$ms->menu_judul}}">
                                                        <input type="checkbox" disabled class="custom-switch-input">
                                                        <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                    </label>
                                                @endif
                                            </td>

                                            <td>
                                                @if($getView1 != '')
                                                    @if(isset($getView11) && $getView11 != '')
                                                        <?php $getDelete1 = AksesModel::where([
                                                            'submenu_id' => $sm->submenu_id,
                                                            'role_id' => $roleid,
                                                            'akses_type' => 'delete'
                                                        ])->first(); ?>
                                                        <label class="custom-switch form-switch me-5">
                                                            @if($getDelete1 == '')
                                                                <input type="checkbox"
                                                                       onchange="addAkses('{{$sm->submenu_id}}', '{{$roleid}}', 'submenu', 'delete')"
                                                                       class="custom-switch-input">
                                                            @else
                                                                <input type="checkbox"
                                                                       onchange="removeAkses('{{$sm->submenu_id}}', '{{$roleid}}', 'submenu', 'delete')"
                                                                       checked class="custom-switch-input">
                                                            @endif
                                                            <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                        </label>
                                                    @else
                                                        <label class="custom-switch form-switch me-5"
                                                               data-bs-toggle="tooltip"
                                                               data-bs-original-title="Aktifkan akses view">
                                                            <input type="checkbox" disabled class="custom-switch-input">
                                                            <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                        </label>
                                                    @endif
                                                @else
                                                    <label class="custom-switch form-switch me-5"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-original-title="Aktifkan akses {{$ms->menu_judul}}">
                                                        <input type="checkbox" disabled class="custom-switch-input">
                                                        <span class="custom-switch-indicator custom-switch-indicator-sm"></span>
                                                    </label>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach

                    {{-- ========================================= --}}
                    {{-- BAGIAN HAK AKSES SETTINGS (OTHERMENU) --}}
                    {{-- ========================================= --}}
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="text-gray">
                            Hak Akses Settings
                            <span class="badge bg-primary badge-sm">
                                {{$detailrole == '' ? '' : $detailrole->role_title}}
                            </span>
                        </h4>

                        <?php
                        // othermenu_id = 1 biasanya "Settings" (parent access)
                        $getView2 = AksesModel::where([
                            'othermenu_id' => 1,
                            'role_id' => $detailrole->role_id,
                            'akses_type' => 'view'
                        ])->first();
                        ?>

                        {{-- Sama seperti sebelumnya: cegah edit akses role sendiri --}}
                        @if(Session::get('user')->role_slug != $detailrole->role_slug)
                            <label class="custom-switch form-switch mb-3">
                                @if($getView2 == '')
                                    <input type="checkbox"
                                           onchange="addAkses('1', '{{$detailrole->role_id}}', 'othermenu', 'view')"
                                           class="custom-switch-input">
                                @else
                                    <input type="checkbox"
                                           onchange="removeAkses('1', '{{$detailrole->role_id}}', 'othermenu', 'view')"
                                           checked class="custom-switch-input">
                                @endif
                                <span class="custom-switch-indicator custom-switch-indicator-md"></span>
                            </label>
                        @endif
                    </div>

                    {{-- Tabel item settings: Menu, Role, User, Akses, Web (othermenu_id 2..6) --}}
                    {{-- Pola dependency sama: harus Settings(view) aktif dulu (getView2) --}}
                    {{-- Kode baris-baris othermenu kamu sudah konsisten dengan pola: view dulu, baru create/update/delete --}}
                    {{-- (Aku tidak ulang komentar per baris karena pattern-nya identik.) --}}

                    {{-- ... bagian tabel othermenu tetap seperti kode kamu ... --}}

                @endif {{-- akhir if $detailrole != '' --}}
            </div>
        </div>
    </div>
</div>
<!-- END ROW -->
@endsection

@section('scripts')
<script>
    // Redirect halaman berdasarkan role yang dipilih
    function submitRole() {
        role = $('select[name="role"]').val();
        if (role != '') {
            // Jika role dipilih => buka /admin/akses/{id}
            window.location.href = "{{ url('/admin/akses') }}/" + parseInt(role);
        } else {
            // Jika belum pilih => buka /admin/akses/role (semacam placeholder)
            window.location.href = "{{ url('/admin/akses') }}/" + "role";
        }
    }

    // Tambah akses: pakai redirect ke route addAkses (server yang memproses insert)
    function addAkses(idmenu, idrole, type, akses) {
        window.location.href =
            "{{ url('/admin/akses/addAkses') }}/" +
            idmenu + '/' + parseInt(idrole) + "/" + type + "/" + akses;
    }

    // Hapus akses: redirect ke route removeAkses (server yang memproses delete)
    function removeAkses(idmenu, idrole, type, akses) {
        window.location.href =
            "{{ url('/admin/akses/removeAkses') }}/" +
            idmenu + '/' + parseInt(idrole) + "/" + type + "/" + akses;
    }

    // Aktifkan semua akses (server-side)
    function setAll(idrole) {
        window.location.href = "{{ url('/admin/akses/setAll') }}/" + parseInt(idrole);
    }

    // Nonaktifkan semua akses (server-side)
    function unsetAll(idrole) {
        window.location.href = "{{ url('/admin/akses/unsetAll') }}/" + parseInt(idrole);
    }

    // Helper sweetalert
    function validasi(judul, status) {
        swal({
            title: judul,
            type: status,
            confirmButtonText: "Iya."
        });
    }
</script>
@endsection
