@extends('Master.Layouts.app', ['title' => $title])
{{-- Extend layout utama dan kirim variabel title ke layout --}}

@section('content')
{{-- Mulai section content --}}

<!-- PAGE-HEADER -->
<div class="page-header">
    {{-- Wrapper header halaman --}}

    <h1 class="page-title">User</h1>
    {{-- Judul halaman --}}

    <div>
        {{-- Wrapper breadcrumb --}}
        <ol class="breadcrumb">
            {{-- List breadcrumb --}}
            <li class="breadcrumb-item text-gray">Settings</li>
            {{-- Breadcrumb level 1 --}}
            <li class="breadcrumb-item text-gray">User</li>
            {{-- Breadcrumb level 2 --}}
            <li class="breadcrumb-item active" aria-current="page">List</li>
            {{-- Breadcrumb aktif (halaman sekarang) --}}
        </ol>
        {{-- Penutup breadcrumb --}}
    </div>
    {{-- Penutup wrapper breadcrumb --}}
</div>
<!-- PAGE-HEADER END -->

<!-- ROW -->
<div class="row row-sm">
    {{-- Bootstrap row; row-sm biasanya class template untuk jarak lebih rapat --}}

    <div class="col-lg-12">
        {{-- Kolom full width pada ukuran lg+ --}}

        <div class="card">
            {{-- Card container --}}

            <div class="card-header justify-content-between">
                {{-- Header card; justify-content-between untuk pisah kiri-kanan --}}

                <h3 class="card-title">List User</h3>
                {{-- Judul card --}}

                <div>
                    {{-- Area tombol aksi di kanan header --}}
                    <a class="modal-effect btn btn-primary-light" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#modaldemo8">Tambah Data <i class="fe fe-plus"></i></a>
                    {{-- Tombol tambah data: membuka modal tambah user dengan id #modaldemo8 --}}
                </div>
                {{-- Penutup area tombol --}}
            </div>
            {{-- Penutup card-header --}}

            <div class="card-body">
                {{-- Body card --}}

                <div class="table-responsive">
                    {{-- Wrapper responsif agar tabel bisa scroll di layar kecil --}}

                    <table id="table-1" width="100%" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        {{-- Tabel DataTables; id dipakai inisialisasi; class tambahan menunjukkan styling + responsive (dtr) --}}

                        <thead>
                            {{-- Header tabel (kolom) --}}
                            <th class="border-bottom-0" width="1%">No</th>
                            {{-- Kolom nomor urut (DT_RowIndex dari server) --}}
                            <th class="border-bottom-0">Foto</th>
                            {{-- Kolom foto user (img dari server) --}}
                            <th class="border-bottom-0">Nama Lengkap</th>
                            {{-- Kolom nama lengkap user --}}
                            <th class="border-bottom-0">Username</th>
                            {{-- Kolom username/login --}}
                            <th class="border-bottom-0">Email</th>
                            {{-- Kolom email --}}
                            <th class="border-bottom-0">Role</th>
                            {{-- Kolom role (title role) --}}
                            <th class="border-bottom-0" width="1%">Action</th>
                            {{-- Kolom aksi (edit/hapus) --}}
                        </thead>
                        {{-- Penutup thead --}}

                        <tbody></tbody>
                        {{-- Body tabel dikosongkan karena akan diisi DataTables via AJAX server-side --}}
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
<!-- END ROW -->

@include('Master.User.tambah', ['role' => $role])
{{-- Include modal/form tambah user; mengirim data role untuk dropdown select --}}

@include('Master.User.ubah', ['role' => $role])
{{-- Include modal/form ubah user; mengirim data role untuk dropdown select --}}

@include('Master.User.hapus')
{{-- Include modal/form hapus user --}}

<script>
    // Script helper untuk isi modal update/hapus + alert validasi

    function update(data) {
        // Set action form update ke endpoint /admin/user/{id}
        $("#myFormU").attr("action", "{{url('/admin/user')}}/" + data.user_id);

        // Isi field nama lengkap; replace underscore jadi spasi (format data dari server)
        $("input[name='nmlengkapU']").val(data.user_nmlengkap.replace(/_/g, ' '));

        // Isi field username; replace underscore jadi spasi
        $("input[name='usernameU']").val(data.user_nama.replace(/_/g, ' '));

        // Isi field email
        $("input[name='emailU']").val(data.user_email);

        // Set value role (dropdown) berdasarkan role_id
        $("select[name='roleU']").val(data.role_id);

        // Simpan nama file foto lama ke input hidden `flama` (dipakai saat update file/foto)
        $("input[name='flama']").val(data.user_foto);

        // Jika foto bukan default (undraw_profile.svg), tampilkan preview foto user
        if(data.user_foto != 'undraw_profile.svg'){
            $("#outputImgU").attr("src", "{{asset('storage/users/')}}"+"/"+data.user_foto);
            // Set src gambar preview ke storage/users/{filename}
        }
    }

    function hapus(data) {
        // Isi input hidden iduser untuk form hapus
        $("input[name='iduser']").val(data.user_id);

        // Isi teks konfirmasi nama user ke elemen #vuser pada modal hapus
        $("#vuser").html("user " + "<b>" + data.user_nama + "</b>");
    }

    function validasi(judul, status) {
        // Menampilkan alert menggunakan SweetAlert (swal)
        swal({
            title: judul,
            type: status,
            confirmButtonText: "Iya."
        });
        // `judul` = teks alert; `status` = tipe alert (warning/success/error/dll)
    }
</script>

@endsection
{{-- Tutup section content --}}

@section('scripts')
{{-- Section scripts tambahan yang biasanya diletakkan di bawah oleh layout --}}

<script>
    // Inisialisasi DataTable untuk list user (server-side)

    var table;
    // Variabel global untuk menyimpan instance DataTable

    $(document).ready(function() {
        // Jalan setelah DOM siap

        //datatables
        table = $('#table-1').DataTable({
            // Inisialisasi DataTables pada tabel #table-1

            "processing": true,
            // Tampilkan indikator loading

            "serverSide": true,
            // Server-side processing (paging/filter/order di server)

            "info": true,
            // Tampilkan informasi jumlah data/halaman

            "order": [],
            // Tidak ada default sorting awal

            "lengthMenu": [
                // Pilihan jumlah baris per halaman
                [5, 10, 25, 50, 100],
                // Nilai
                [5, 10, 25, 50, 100]
                // Label tampilan
            ],

            "pageLength": 10,
            // Default 10 row per halaman

            lengthChange: true,
            // User bisa mengubah page length

            "ajax": {
                // Konfigurasi AJAX
                "url": "{{route('user.getuser')}}",
                // Endpoint server untuk response JSON DataTables
            },

            "columns": [{
                    data: 'DT_RowIndex',
                    // Nomor urut dari server (umumnya Yajra DataTables)
                    name: 'DT_RowIndex',
                    searchable: false
                    // Tidak ikut search
                },
                {
                    data: 'img',
                    // Kolom foto: biasanya HTML <img> dari server
                    name: 'user_foto',
                    searchable: false,
                    // Tidak ikut search
                    orderable: false
                    // Tidak bisa disort karena HTML/gambar
                },
                {
                    data: 'user_nmlengkap',
                    // Kolom nama lengkap
                    name: 'user_nmlengkap',
                },
                {
                    data: 'user_nama',
                    // Kolom username
                    name: 'user_nama',
                },
                {
                    data: 'user_email',
                    // Kolom email
                    name: 'user_email',
                },
                {
                    data: 'role',
                    // Kolom role: biasanya hasil join/relasi yang dirender server
                    name: 'role_title'
                    // Name untuk server-side filter/sort (title role)
                },
                {
                    data: 'action',
                    // Kolom action: HTML tombol edit/hapus dari server
                    name: 'action',
                    orderable: false,
                    // Tidak bisa sort
                    searchable: false
                    // Tidak ikut search
                },
            ],

        });
        // Penutup inisialisasi DataTable
    });
    // Penutup document ready
</script>
@endsection
{{-- Tutup section scripts --}}
