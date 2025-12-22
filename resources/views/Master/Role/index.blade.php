@extends('Master.Layouts.app', ['title' => $title])
{{-- Extend layout utama dan kirim parameter title ke layout --}}

@section('content')
{{-- Mulai section content --}}

<!-- PAGE-HEADER -->
<div class="page-header">
    {{-- Wrapper header halaman --}}

    <h1 class="page-title">Role</h1>
    {{-- Judul halaman --}}

    <div>
        {{-- Wrapper breadcrumb --}}
        <ol class="breadcrumb">
            {{-- List breadcrumb --}}
            <li class="breadcrumb-item text-gray">Settings</li>
            {{-- Level 1 breadcrumb --}}
            <li class="breadcrumb-item text-gray">User</li>
            {{-- Level 2 breadcrumb --}}
            <li class="breadcrumb-item active" aria-current="page">Role</li>
            {{-- Level aktif (halaman saat ini) --}}
        </ol>
        {{-- Penutup breadcrumb --}}
    </div>
    {{-- Penutup wrapper breadcrumb --}}
</div>
<!-- PAGE-HEADER END -->

<!-- Row -->
<div class="row row-sm">
    {{-- Bootstrap row; row-sm biasanya class template untuk jarak lebih rapat --}}

    <div class="col-lg-12">
        {{-- Kolom full width pada ukuran lg+ --}}

        <div class="card">
            {{-- Card container --}}

            <div class="card-header justify-content-between">
                {{-- Header card; justify-content-between untuk pisah kiri-kanan --}}

                <h3 class="card-title">List Role</h3>
                {{-- Judul card --}}

                <div>
                    {{-- Area tombol aksi di kanan header --}}
                    <a class="modal-effect btn btn-primary-light" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modaldemo8">Tambah Data <i class="fe fe-plus"></i></a>
                    {{-- Tombol tambah data: membuka modal #modaldemo8 (Master.Role.tambah) --}}
                </div>
                {{-- Penutup area aksi header --}}
            </div>
            {{-- Penutup card-header --}}

            <div class="card-body">
                {{-- Body card --}}

                <div class="table-responsive">
                    {{-- Wrapper responsif agar tabel bisa scroll horizontal di layar kecil --}}

                    <table id="table-1" width="100%" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        {{-- Tabel DataTables; id dipakai untuk inisialisasi; class tambahan menunjukkan styling + responsive (dtr) --}}

                        <thead>
                            {{-- Header tabel (kolom) --}}
                            <th class="border-bottom-0" width="1%">No</th>
                            {{-- Kolom nomor (DT_RowIndex) --}}
                            <th class="border-bottom-0">Title</th>
                            {{-- Kolom title role --}}
                            <th class="border-bottom-0">Slug</th>
                            {{-- Kolom slug role --}}
                            <th class="border-bottom-0">Description</th>
                            {{-- Kolom deskripsi role --}}
                            <th class="border-bottom-0" width="1%">Action</th>
                            {{-- Kolom aksi (edit/hapus) --}}
                        </thead>
                        {{-- Penutup thead --}}

                        <tbody></tbody>
                        {{-- Body tabel dikosongkan karena diisi via DataTables AJAX server-side --}}
                    </table>
                    {{-- Penutup table --}}
                </div>
                {{-- Penutup table-responsive --}}
            </div>
            {{-- Penutup card-body --}}
        </div>
        {{-- Penutup card --}}
    </div>
    {{-- Penutup col --}}
</div>
<!-- End Row -->

@include('Master.Role.tambah')
{{-- Include modal/form tambah role --}}

@include('Master.Role.ubah')
{{-- Include modal/form ubah role --}}

@include('Master.Role.hapus')
{{-- Include modal/form hapus role --}}

<script>
    // Script helper untuk isi modal update/hapus + alert validasi

    function update(data) {
        // Set action form update ke endpoint /admin/role/{id}
        $("#myFormU").attr("action", "{{url('/admin/role')}}/" + data.role_id);

        // Isi field title update; replace underscore jadi spasi (format data dari server)
        $("input[name='utitle']").val(data.role_title.replace(/_/g, ' '));

        // Isi textarea desc update; replace underscore jadi spasi
        $("textarea[name='udesc']").val(data.role_desc.replace(/_/g, ' '));
    }

    function hapus(data) {
        // Isi idrole (hidden) untuk form hapus
        $("input[name='idrole']").val(data.role_id);

        // Isi teks konfirmasi (nama role) ke elemen #vrole di modal hapus
        $("#vrole").html("role "+"<b>"+data.role_title+"</b>");
    }

    function validasi(judul, status) {
        // Menampilkan SweetAlert (swal) dengan title dan type (status)
        swal({
            title: judul,
            type: status,
            confirmButtonText: "Iya."
        });
    }
</script>

@endsection
{{-- Tutup section content --}}

@section('scripts')
{{-- Section scripts tambahan (biasanya ditumpuk di bawah oleh layout) --}}

<script>
    // Inisialisasi DataTable untuk list role (server-side)

    var table;
    // Variable global untuk instance DataTable

    $(document).ready(function() {
        // Jalankan setelah DOM siap

        //datatables
        table = $('#table-1').DataTable({
            // Inisialisasi DataTable pada tabel dengan id table-1

            "processing": true,
            // Tampilkan indikator processing saat load data

            "serverSide": true,
            // Mode server-side: paging/filter/order dilakukan di server

            "info": true,
            // Tampilkan info jumlah data dan halaman

            "order": [],
            // Default tidak ada sorting awal (server yang menentukan atau tidak sorting)

            "lengthMenu": [
                // Pilihan jumlah data per halaman
                [5, 10, 25, 50, 100],
                // Nilai angka
                [5, 10, 25, 50, 100]
                // Label tampilan (sama)
            ],

            "pageLength": 10,
            // Default tampil 10 baris per halaman

            lengthChange: true,
            // Izinkan user mengganti page length

            "ajax": {
                // Konfigurasi AJAX untuk ambil data
                "url": "{{route('role.getrole')}}",
                // Endpoint server untuk JSON DataTables
            },

            "columns": [
                // Definisi kolom sesuai response JSON dari server
                {
                    data: 'DT_RowIndex',
                    // Kolom nomor index dari server (biasanya Yajra DataTables)
                    name: 'DT_RowIndex',
                    searchable: false
                    // Non-searchable agar tidak ikut pencarian
                },
                {
                    data: 'role_title',
                    // Kolom title role
                    name: 'role_title',
                },
                {
                    data: 'role_slug',
                    // Kolom slug role
                    name: 'role_slug',
                },
                {
                    data: 'role_desc',
                    // Kolom deskripsi role
                    name: 'role_desc'
                },
                {
                    data: 'action',
                    // Kolom action biasanya berisi HTML tombol edit/hapus dari server
                    name: 'action',
                    orderable: false,
                    // Tidak bisa di-sort
                    searchable: false
                    // Tidak ikut search (biasanya HTML)
                },
            ],

        });
        // Penutup DataTable init
    });
    // Penutup document ready
</script>
@endsection
{{-- Tutup section scripts --}}
