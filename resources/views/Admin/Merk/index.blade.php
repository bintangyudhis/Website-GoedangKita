@extends('Master.Layouts.app', ['title' => $title])
{{-- Memakai layout utama aplikasi dan mengirim variabel title --}}

@section('content')
{{-- Section utama untuk isi halaman --}}

    <!-- PAGE-HEADER -->
    <div class="page-header">
        {{-- Header halaman --}}

        <h1 class="page-title">Merk Barang</h1>
        {{-- Judul halaman --}}

        <div>
            <ol class="breadcrumb">
                {{-- Breadcrumb navigasi --}}
                <li class="breadcrumb-item text-gray">Master Barang</li>
                {{-- Parent menu --}}
                <li class="breadcrumb-item active" aria-current="page">Merk Barang</li>
                {{-- Halaman aktif --}}
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->


    <!-- ROW -->
    <div class="row row-sm">
        {{-- Baris layout bootstrap --}}

        <div class="col-lg-12">
            {{-- Kolom lebar penuh pada layar besar --}}

            <div class="card">
                {{-- Card container --}}

                <div class="card-header justify-content-between">
                    {{-- Header card: judul dan tombol di sisi kanan --}}

                    <h3 class="card-title">Data</h3>
                    {{-- Judul card --}}

                    @if ($hakTambah > 0)
                        {{-- Cek hak akses tambah data: hanya tampil jika hakTambah > 0 --}}

                        <div>
                            <a class="modal-effect btn btn-primary-light"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#modaldemo8">
                                {{-- Tombol buka modal tambah (id modaldemo8) --}}
                                Tambah Data <i class="fe fe-plus"></i>
                                {{-- Label tombol + ikon --}}
                            </a>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    {{-- Isi card --}}

                    <div class="table-responsive">
                        {{-- Agar tabel bisa scroll horizontal di layar kecil --}}

                        <table id="table-1" width="100%"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            {{-- Tabel untuk DataTables serverSide, id table-1 --}}

                            <thead>
                                {{-- Header kolom tabel --}}
                                <th class="border-bottom-0" width="1%">No</th>
                                {{-- Kolom nomor --}}
                                <th class="border-bottom-0">Merk</th>
                                {{-- Kolom nama merk --}}
                                <th class="border-bottom-0">Keterangan</th>
                                {{-- Kolom keterangan --}}
                                <th class="border-bottom-0" width="1%">Action</th>
                                {{-- Kolom aksi (edit/hapus) --}}
                            </thead>

                            <tbody></tbody>
                            {{-- Body tabel akan diisi otomatis oleh DataTables melalui AJAX --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->

    @include('Admin.Merk.tambah')
    {{-- Memanggil partial view modal tambah merk --}}

    @include('Admin.Merk.edit')
    {{-- Memanggil partial view modal edit merk --}}

    @include('Admin.Merk.hapus')
    {{-- Memanggil partial view modal hapus merk --}}

    <script>
        function update(data) {
            // Fungsi untuk mengisi form edit dari data yang dipilih (biasanya dari tombol edit di tabel)

            $("input[name='idmerkU']").val(data.merk_id);
            // Set ID merk ke input hidden edit

            $("input[name='merkU']").val(data.merk_nama.replace(/_/g, ' '));
            // Set nama merk, sekaligus mengganti underscore "_" menjadi spasi

            $("textarea[name='ketU']").val(data.merk_keterangan.replace(/_/g, ' '));
            // Set keterangan merk, underscore diganti spasi
        }

        function hapus(data) {
            // Fungsi untuk mengisi ID merk yang akan dihapus dan menampilkan nama merk pada teks konfirmasi

            $("input[name='idmerk']").val(data.merk_id);
            // Isi input hidden idmerk untuk proses hapus

            $("#vmerk").html("merk " + "<b>" + data.merk_nama.replace(/_/g, ' ') + "</b>");
            // Tampilkan nama merk yang akan dihapus pada modal hapus
        }

        function validasi(judul, status) {
            // Fungsi notifikasi menggunakan SweetAlert

            swal({
                title: judul,
                type: status,
                confirmButtonText: "Iya."
            });
        }
    </script>
@endsection
{{-- Akhir section content --}}

@section('scripts')
{{-- Section khusus script tambahan halaman --}}

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Mengatur header CSRF untuk semua request AJAX (wajib untuk POST di Laravel)

        var table;
        // Variabel global untuk DataTables

        $(document).ready(function() {
            // Menjalankan kode saat halaman selesai dimuat

            //datatables
            table = $('#table-1').DataTable({

                "processing": true,
                // Menampilkan indikator "processing" saat loading data

                "serverSide": true,
                // Mode server-side processing (data diambil dari server per request)

                "info": true,
                // Menampilkan info jumlah data (mis. "Showing 1 to 10...")

                "order": [],
                // Default tanpa sorting awal

                "stateSave": true,
                // Menyimpan state DataTables (page, search, dll) di browser

                "lengthMenu": [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                // Pilihan jumlah data per halaman

                "pageLength": 10,
                // Default jumlah data per halaman = 10

                lengthChange: true,
                // Mengizinkan user mengganti pageLength

                "ajax": {
                    "url": "{{ route('merk.getmerk') }}",
                },
                // AJAX mengambil data dari route merk.getmerk

                "columns": [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    // Kolom nomor urut otomatis dari backend (Yajra DataTables biasanya)

                    {
                        data: 'merk_nama',
                        name: 'merk_nama',
                    },
                    // Kolom nama merk dari database

                    {
                        data: 'ket',
                        name: 'merk_keterangan',
                    },
                    // Kolom keterangan (biasanya hasil format dari backend)

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    // Kolom tombol aksi (edit/hapus), tidak bisa diurutkan & tidak bisa dicari
                ],

            });
        });
    </script>
@endsection
{{-- Akhir section scripts --}}
