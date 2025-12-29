@extends('Master.Layouts.app', ['title' => $title])
{{-- Extend layout utama; kirim variabel title ke layout --}}

<?php
// Blok PHP murni di Blade (bukan directive @php).

use App\Models\Admin\SubmenuModel;
// Import model SubmenuModel agar bisa dipakai langsung di view.
?>

@section('content')
    {{-- Mulai section content yang akan di-render ke layout --}}

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <!-- Wrapper header halaman -->

        <h1 class="page-title">Menu</h1>
        <!-- Judul halaman -->

        <div>
            <!-- Wrapper breadcrumb -->
            <ol class="breadcrumb">
                <!-- List breadcrumb -->
                <li class="breadcrumb-item text-gray">Settings</li>
                <!-- Breadcrumb level 1 -->
                <li class="breadcrumb-item active" aria-current="page">Menu</li>
                <!-- Breadcrumb level aktif (halaman saat ini) -->
            </ol>
            <!-- Penutup breadcrumb -->
        </div>
        <!-- Penutup wrapper breadcrumb -->
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row row-sm">
        <!-- Bootstrap row; row-sm biasanya class template untuk gap lebih rapat -->

        <div class="col-lg-12">
            <!-- Kolom lebar penuh pada layar lg+ -->

            <div class="card">
                <!-- Card container -->

                <div class="card-header justify-content-between">
                    <!-- Header card; justify-content-between untuk pisah kiri-kanan -->

                    <h3 class="card-title">List Menu</h3>
                    <!-- Judul card -->

                    <div>
                        <!-- Area kanan header (biasanya tombol aksi) -->
                        {{-- <a class="modal-effect btn btn-primary-light" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modaldemo8">Tambah Data <i class="fe fe-plus"></i></a> --}}
                        {{-- Tombol tambah data (dikomentari); membuka modal #modaldemo8 --}}
                    </div>
                    <!-- Penutup area kanan header -->
                </div>
                <!-- Penutup card-header -->

                <div class="card-body p-0">
                    <!-- Body card; p-0 agar tabel mepet tanpa padding -->

                    <div class="table-responsive">
                        <!-- Wrapper responsive agar tabel bisa di-scroll horizontal di layar kecil -->

                        <table class="table border text-nowrap text-md-nowrap table-bordered mb-0">
                            <!-- Tabel bootstrap; text-nowrap agar tidak wrap; table-bordered untuk garis; mb-0 hilangkan margin bawah -->

                            <thead>
                                <!-- Header tabel -->
                                <tr>
                                    <!-- Baris header -->
                                    <th class="border-bottom-0" width="1%">Sort</th>
                                    <!-- Kolom sort (lebar kecil) -->
                                    <th class="border-bottom-0" width="1%">Icon</th>
                                    <!-- Kolom icon -->
                                    <th class="border-bottom-0">Judul</th>
                                    <!-- Kolom judul -->
                                    <th class="border-bottom-0">Type</th>
                                    <!-- Kolom type (Menu/Sub Menu) -->
                                    <th class="border-bottom-0">Redirect</th>
                                    <!-- Kolom redirect/route -->
                                    <th class="border-bottom-0" width="1%">Action</th>
                                    <!-- Kolom aksi -->
                                </tr>
                                <!-- Penutup baris header -->
                            </thead>
                            <!-- Penutup thead -->

                            <tbody>
                                <!-- Isi tabel -->
                                @foreach ($data as $d)
                                    {{-- Loop data menu dari controller: setiap item = $d --}}

                                    <tr>
                                        <!-- Baris data menu -->

                                        <td>
                                            <!-- Kolom sort + tombol naik/turun -->

                                            <span class="me-4">{{ $d->menu_sort }}</span>
                                            <!-- Tampilkan angka urutan sort -->

                                            <button type="button" onclick="sortup('{{ $d->menu_sort }}')"
                                                class="btn btn-icon btn-sm {{ $d->menu_sort == 1 ? 'btn-gray' : 'btn-success' }}"
                                                {{ $d->menu_sort == 1 ? 'disabled' : '' }}><i
                                                    class="fe fe-arrow-up"></i></button>
                                            <!-- Tombol naik urutan: disable jika sort sudah 1; warna gray jika disable, success jika aktif -->

                                            <button type="button" onclick="sortdown('{{ $d->menu_sort }}')"
                                                class="btn btn-icon btn-sm {{ $d->menu_sort == count($data) ? 'btn-gray' : 'btn-success' }}"
                                                {{ $d->menu_sort == count($data) ? 'disabled' : '' }}><i
                                                    class="fe fe-arrow-down"></i></button>
                                            <!-- Tombol turun urutan: disable jika sort sudah paling bawah (== jumlah data) -->
                                        </td>
                                        <!-- Penutup kolom sort -->

                                        <td align="center"><i class="fe fe-{{ $d->menu_icon }} text-primary"></i></td>
                                        <!-- Kolom icon: pakai feather icon `fe-...` sesuai menu_icon -->

                                        <td>{{ $d->menu_judul }}</td>
                                        <!-- Kolom judul menu -->

                                        <td>
                                            <!-- Kolom type -->

                                            @if ($d->menu_type == 1)
                                                {{-- Type 1 = Menu utama --}}
                                                <span class="badge bg-primary badge-sm mb-1 mt-1">Menu</span>
                                                <!-- Badge untuk menu utama -->
                                            @elseif($d->menu_type == 2)
                                                {{-- Type 2 = Sub Menu (punya daftar submenu) --}}
                                                <span class="badge bg-success badge-sm mb-1 mt-1">Sub Menu</span>
                                                <!-- Badge untuk submenu -->
                                            @endif
                                            {{-- Tutup kondisi type --}}
                                        </td>
                                        <!-- Penutup kolom type -->

                                        <td>
                                            <!-- Kolom redirect atau daftar submenu -->

                                            @if ($d->menu_type == 1)
                                                {{-- Kalau menu utama: tampilkan redirect langsung --}}
                                                <span class="text-gray fw-medium">{{ $d->menu_redirect }}</span>
                                                <!-- Redirect route/url untuk menu utama -->
                                            @elseif($d->menu_type == 2)
                                                {{-- Kalau submenu: ambil data submenu berdasarkan menu_id --}}
                                                <?php
                                                // Query ke database di dalam view (SubmenuModel) untuk mengambil list submenu.
                                                $submenu = SubmenuModel::where('menu_id', '=', $d->menu_id)->orderBy('submenu_sort', 'ASC')->get();
                                                // Filter menu_id sama dengan $d->menu_id; urut berdasarkan submenu_sort ASC.
                                                ?>
                                                @foreach ($submenu as $sub)
                                                    {{-- Loop list submenu --}}
                                                    <div>
                                                        <!-- Satu baris info submenu -->
                                                        <span
                                                            class="badge bg-success badge-sm me-1 mb-1 mt-1">{{ $sub->submenu_judul }}</span>
                                                        <!-- Badge judul submenu -->
                                                        <span
                                                            class="badge bg-default text-gray badge-sm mb-1 mt-1">{{ $sub->submenu_redirect }}</span>
                                                        <!-- Badge redirect submenu -->
                                                    </div>
                                                    <!-- Penutup satu baris submenu -->
                                                @endforeach
                                                {{-- Tutup loop submenu --}}
                                            @endif
                                            {{-- Tutup kondisi redirect --}}
                                        </td>
                                        <!-- Penutup kolom redirect -->

                                        <td>
                                            <!-- Kolom action -->
                                            <div class="g-2">
                                                <!-- Wrapper tombol; g-2 biasanya untuk gap grid/spacing -->

                                                @if ($d->menu_type == 1)
                                                    {{-- Edit untuk menu utama: kirim data menu saja --}}
                                                    <a class="btn modal-effect text-primary btn-sm"
                                                        data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                                                        href="#Umodaldemo8" data-bs-toggle="tooltip"
                                                        data-bs-original-title="Edit"
                                                        onclick="update({{ $d }})"><span
                                                            class="fe fe-edit text-success fs-14"></span></a>
                                                    <!-- Buka modal edit #Umodaldemo8, lalu jalankan fungsi JS update(data) -->
                                                @elseif($d->menu_type == 2)
                                                    {{-- Edit untuk submenu: kirim data menu + list submenu --}}
                                                    <a class="btn modal-effect text-primary btn-sm"
                                                        data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                                                        href="#Umodaldemo8" data-bs-toggle="tooltip"
                                                        data-bs-original-title="Edit"
                                                        onclick="updatewithsub({{ $d }},{{ $submenu }})"><span
                                                            class="fe fe-edit text-success fs-14"></span></a>
                                                    <!-- Buka modal edit dan isi form + setSub(submenu) -->
                                                @endif
                                                {{-- Tutup kondisi edit --}}

                                                {{-- <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick="hapus({{$d}})"><span class="fe fe-trash-2 fs-14"></span></a> --}}
                                                {{-- Tombol hapus (dikomentari); buka modal hapus #Hmodaldemo8 dan isi data via JS hapus(data) --}}
                                            </div>
                                            <!-- Penutup wrapper tombol -->
                                        </td>
                                        <!-- Penutup kolom action -->
                                    </tr>
                                    <!-- Penutup baris data -->
                                @endforeach
                                {{-- Tutup loop data menu --}}
                            </tbody>
                            <!-- Penutup tbody -->
                        </table>
                        <!-- Penutup table -->
                    </div>
                    <!-- Penutup table-responsive -->
                </div>
                <!-- Penutup card-body -->
            </div>
            <!-- Penutup card -->
        </div>
        <!-- Penutup col -->
    </div>
    <!-- End Row -->

    @include('Master.Menu.tambah')
    {{-- Include partial blade modal/form tambah menu --}}

    @include('Master.Menu.ubah')
    {{-- Include partial blade modal/form ubah menu --}}

    @include('Master.Menu.hapus')
    {{-- Include partial blade modal/form hapus menu --}}

    <script>
        // Script JS untuk pengurutan menu (naik/turun) lewat redirect URL

        function sortup(sort) {
            // Arahkan ke endpoint sortup dengan parameter sort saat ini
            window.location.href = "{{ url('/admin/menu/sortup') }}/" + sort;
            // Redirect browser (GET) ke /admin/menu/sortup/{sort}
        }

        function sortdown(sort) {
            // Arahkan ke endpoint sortdown dengan parameter sort saat ini
            window.location.href = "{{ url('/admin/menu/sortdown') }}/" + sort;
            // Redirect browser (GET) ke /admin/menu/sortdown/{sort}
        }
    </script>

    <script>
        // Script JS untuk mengisi modal edit/hapus + alert validasi

        function update(data) {
            // Set action form update sesuai menu_id yang diedit
            $("#myFormU").attr("action", "{{ url('/admin/menu') }}/" + data.menu_id);

            // Isi field form update (icon, judul, type, redirect)
            $("input[name='uicon']").val(data.menu_icon);
            $("input[name='ujudul']").val(data.menu_judul);
            $("select[name='utype']").val(data.menu_type);
            $("input[name='uredirect']").val(data.menu_redirect);

            // Atur tampilan field berdasarkan type (fungsi ini diasumsikan ada di file lain)
            setTypeU();
        }

        function updatewithsub(data, sub) {
            // Set action form update sesuai menu_id yang diedit (khusus menu type submenu)
            $("#myFormU").attr("action", "{{ url('/admin/menu') }}/" + data.menu_id);

            // Isi field utama form update
            $("input[name='uicon']").val(data.menu_icon);
            $("input[name='ujudul']").val(data.menu_judul);
            $("select[name='utype']").val(data.menu_type);
            $("input[name='uredirect']").val(data.menu_redirect);

            // Atur tampilan field sesuai type
            setTypeU();

            // Isi data submenu ke form (fungsi ini diasumsikan ada di file lain)
            setSub(sub);
        }

        function hapus(data) {
            // Isi input hidden idmenu untuk form hapus
            $("input[name='idmenu']").val(data.menu_id);

            // Isi teks konfirmasi nama menu yang akan dihapus ke elemen #vmenu
            $("#vmenu").html("menu " + "<b>" + data.menu_judul + "</b>");
        }

        function validasi(judul, status) {
            // Tampilkan alert menggunakan SweetAlert (swal)
            swal({
                title: judul,
                type: status,
                confirmButtonText: "Iya."
            });
            // `judul` = teks alert, `status` = tipe (success/error/warning/dll)
        }
    </script>
@endsection
{{-- Tutup section content --}}
