@extends('Master.Layouts.app', ['title' => $title]) {{-- Memakai layout utama dan mengirim variabel title --}}

@section('content') {{-- Awal section konten halaman --}}

<!-- PAGE-HEADER --> <!-- Header halaman -->
<div class="page-header"> <!-- Container header -->
    <h1 class="page-title">Laporan Stok Barang</h1> <!-- Judul halaman -->
    <div> <!-- Container breadcrumb -->
        <ol class="breadcrumb"> <!-- Breadcrumb navigasi -->
            <li class="breadcrumb-item text-gray">Laporan</li> <!-- Breadcrumb level 1 -->
            <li class="breadcrumb-item active" aria-current="page">Stok Barang</li> <!-- Breadcrumb aktif -->
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END --> <!-- Akhir header -->

<!-- ROW --> <!-- Row utama -->
<div class="row row-sm">
    <div class="col-lg-12"> <!-- Kolom full -->
        <div class="card"> <!-- Card container -->
            <div class="card-header justify-content-between"> <!-- Header card -->
                <h3 class="card-title">Data</h3> <!-- Judul card -->
            </div>

            <div class="card-body"> <!-- Body card -->

                <div class="row mb-4"> <!-- Baris filter tanggal + tombol -->
                    <div class="col-12">
                        <label for="" class="fw-bold">Filter Tanggal</label>
                        <!-- Label filter tanggal -->
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="tglawal" class="form-control datepicker-date" placeholder="Tanggal Awal">
                            <!-- Input tanggal awal -->
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="tglakhir" class="form-control datepicker-date" placeholder="Tanggal Akhir">
                            <!-- Input tanggal akhir -->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Tombol aksi laporan -->
                        <button class="btn btn-success-light" onclick="filter()">
                            <!-- Tombol filter: reload datatable sesuai tanggal -->
                            <i class="fe fe-filter"></i> Filter
                        </button>

                        <button class="btn btn-secondary-light" onclick="reset()">
                            <!-- Tombol reset: kosongkan input tanggal + reload data -->
                            <i class="fe fe-refresh-ccw"></i> Reset
                        </button>

                        <button class="btn btn-primary-light" onclick="print()">
                            <!-- Tombol print: buka halaman print -->
                            <i class="fe fe-printer"></i> Print
                        </button>

                        <button class="btn btn-danger-light" onclick="pdf()">
                            <!-- Tombol pdf: export PDF -->
                            <i class="fa fa-file-pdf-o"></i> PDF
                        </button>
                    </div>
                </div>

                <div class="table-responsive"> <!-- Tabel dibuat responsif -->
                    <table id="table-1" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <!-- Tabel DataTables laporan stok -->
                        <thead>
                            <th class="border-bottom-0" width="1%">No</th> <!-- Nomor urut -->
                            <th class="border-bottom-0">Kode Barang</th> <!-- Kode barang -->
                            <th class="border-bottom-0">Barang</th> <!-- Nama barang -->
                            <th class="border-bottom-0">Stok Awal</th> <!-- Stok awal -->
                            <th class="border-bottom-0">Jumlah Masuk</th> <!-- Total masuk -->
                            <th class="border-bottom-0">Jumlah Keluar</th> <!-- Total keluar -->
                            <th class="border-bottom-0">Total Stok</th> <!-- Stok akhir/total -->
                        </thead>
                        <tbody></tbody>
                        <!-- Body kosong karena diisi oleh AJAX DataTables -->
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END ROW --> <!-- Akhir row -->

@endsection {{-- Akhir section content --}}

@section('scripts') {{-- Section scripts untuk DataTables & tombol aksi --}}
<script>
    $.ajaxSetup({ // Setting CSRF token untuk semua AJAX jQuery
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ambil token CSRF
        }
    });

    $(document).ready(function() { // Saat halaman siap
        getData(); // Inisialisasi DataTables
    });

    function getData() { // Fungsi membangun DataTables laporan stok
        //datatables // Penanda konfigurasi DataTables
        table = $('#table-1').DataTable({ // Inisialisasi DataTables di tabel #table-1

            "processing": true, // Menampilkan loading processing
            "serverSide": true, // Data diproses oleh server (paging/search)
            "info": true, // Menampilkan info data
            "order": [], // Tidak ada default sorting
            "scrollX": true, // Scroll horizontal
            "stateSave": true, // Simpan state DataTables (page/filter)
            "lengthMenu": [ // Pilihan jumlah data per halaman
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'Semua'] // -1 berarti tampil semua
            ],
            "pageLength": 10, // Default 10 data per halaman

            lengthChange: true, // User boleh ubah jumlah data

            "ajax": { // Ambil data via AJAX
                "url": "{{ route('lap-sb.getlap-sb') }}", // Route ambil data laporan stok
                "data": function(d) { // Parameter tambahan ke server
                    d.tglawal = $('input[name="tglawal"]').val(); // Kirim tanggal awal
                    d.tglakhir = $('input[name="tglakhir"]').val(); // Kirim tanggal akhir
                }
            },

            "columns": [ // Mapping kolom DataTables
                {
                    data: 'DT_RowIndex', // Nomor urut otomatis (Yajra)
                    name: 'DT_RowIndex',
                    searchable: false // Tidak ikut pencarian
                },
                {
                    data: 'barang_kode', // Kode barang
                    name: 'barang_kode',
                },
                {
                    data: 'barang_nama', // Nama barang
                    name: 'barang_nama',
                },
                {
                    data: 'stokawal', // Stok awal (hasil hitung dari backend)
                    name: 'barang_stok',
                },
                {
                    data: 'jmlmasuk', // Jumlah barang masuk pada periode filter
                    name: 'barang_kode', // Dipakai sebagai name (umumnya untuk query server)
                    orderable: false, // Tidak bisa diurutkan
                },
                {
                    data: 'jmlkeluar', // Jumlah barang keluar pada periode filter
                    name: 'barang_kode',
                    searchable: false, // Tidak bisa dicari
                    orderable: false, // Tidak bisa diurutkan
                },
                {
                    data: 'totalstok', // Total stok akhir (stok awal + masuk - keluar)
                    name: 'barang_kode',
                    searchable: false, // Tidak bisa dicari
                    orderable: false, // Tidak bisa diurutkan
                },
            ],

        });
    }

    function filter() { // Fungsi menjalankan filter tanggal
        var tglawal = $('input[name="tglawal"]').val(); // Ambil tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil tanggal akhir

        if (tglawal != '' && tglakhir != '') { // Jika keduanya terisi
            table.ajax.reload(null, false); // Reload tabel tanpa reset halaman
        } else { // Jika kosong
            validasi("Isi dulu Form Filter Tanggal!", 'warning'); // Tampilkan peringatan
        }
    }

    function reset() { // Fungsi reset filter
        $('input[name="tglawal"]').val(''); // Kosongkan tanggal awal
        $('input[name="tglakhir"]').val(''); // Kosongkan tanggal akhir
        table.ajax.reload(null, false); // Reload tabel (kembali default)
    }

    function print() { // Fungsi print laporan stok
        var tglawal = $('input[name="tglawal"]').val(); // Ambil tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil tanggal akhir

        window.open(
            "{{route('lap-sb.print')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
            '_blank'
        );
        // Buka halaman print pada tab baru (dengan parameter tanggal)
    }

    function pdf() { // Fungsi export PDF laporan stok
        var tglawal = $('input[name="tglawal"]').val(); // Ambil tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil tanggal akhir

        window.open(
            "{{route('lap-sb.pdf')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
            '_blank'
        );
        // Buka export PDF pada tab baru (dengan parameter tanggal)
    }

    function validasi(judul, status) { // Helper SweetAlert
        swal({
            title: judul, // Pesan alert
            type: status, // Jenis alert (warning/success/dll)
            confirmButtonText: "Iya." // Tombol OK
        });
    }
</script>
@endsection {{-- Akhir section scripts --}}
