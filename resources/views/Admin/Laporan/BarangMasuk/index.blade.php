@extends('Master.Layouts.app', ['title' => $title]) {{-- Menggunakan layout utama dan mengirim variabel title --}}

@section('content') {{-- Awal section content --}}

<!-- PAGE-HEADER --> <!-- Header halaman -->
<div class="page-header"> <!-- Container header -->
    <h1 class="page-title">Laporan Barang Masuk</h1> <!-- Judul halaman -->
    <div> <!-- Container breadcrumb -->
        <ol class="breadcrumb"> <!-- Breadcrumb navigasi -->
            <li class="breadcrumb-item text-gray">Laporan</li> <!-- Breadcrumb level 1 -->
            <li class="breadcrumb-item active" aria-current="page">Barang Masuk</li> <!-- Breadcrumb aktif -->
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
                        <!-- Judul filter -->
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="tglawal" class="form-control datepicker-date" placeholder="Tanggal Awal">
                            <!-- Input tanggal awal (datepicker) -->
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="tglakhir" class="form-control datepicker-date" placeholder="Tanggal Akhir">
                            <!-- Input tanggal akhir (datepicker) -->
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Tombol aksi laporan -->
                        <button class="btn btn-success-light" onclick="filter()">
                            <!-- Menjalankan filter() untuk reload data sesuai tanggal -->
                            <i class="fe fe-filter"></i> Filter
                        </button>

                        <button class="btn btn-secondary-light" onclick="reset()">
                            <!-- Reset input tanggal + reload tabel -->
                            <i class="fe fe-refresh-ccw"></i> Reset
                        </button>

                        <button class="btn btn-primary-light" onclick="print()">
                            <!-- Buka halaman print (tab baru) -->
                            <i class="fe fe-printer"></i> Print
                        </button>

                        <button class="btn btn-danger-light" onclick="pdf()">
                            <!-- Buka export pdf (tab baru) -->
                            <i class="fa fa-file-pdf-o"></i> PDF
                        </button>
                    </div>
                </div>

                <div class="table-responsive"> <!-- Tabel responsif -->
                    <table id="table-1" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <!-- Tabel DataTables laporan barang masuk -->
                        <thead>
                            <th class="border-bottom-0" width="1%">No</th> <!-- Nomor urut -->
                            <th class="border-bottom-0">Tanggal Masuk</th> <!-- Tanggal transaksi masuk -->
                            <th class="border-bottom-0">Kode Barang Masuk</th> <!-- Kode BM -->
                            <th class="border-bottom-0">Kode Barang</th> <!-- Kode barang -->
                            <th class="border-bottom-0">Customer</th> <!-- Nama customer -->
                            <th class="border-bottom-0">Barang</th> <!-- Nama barang -->
                            <th class="border-bottom-0">Jumlah Masuk</th> <!-- Jumlah masuk -->
                        </thead>
                        <tbody></tbody>
                        <!-- Body tabel kosong (diisi melalui AJAX DataTables) -->
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END ROW --> <!-- Akhir row -->

@endsection {{-- Akhir section content --}}

@section('scripts') {{-- Section scripts untuk DataTables dan fungsi filter/print/pdf --}}
<script> // Awal script JS

    $.ajaxSetup({ // Set header CSRF untuk semua request AJAX
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ambil token dari meta csrf-token
        }
    });

    $(document).ready(function() { // Saat halaman selesai dimuat
        getData(); // Inisialisasi DataTables
    });

    function getData() { // Fungsi membangun DataTables laporan
        //datatables // Penanda DataTables

        table = $('#table-1').DataTable({ // Inisialisasi DataTables pada #table-1

            "processing": true, // Menampilkan indikator proses
            "serverSide": true, // Mode server-side (data diproses di server)
            "info": true, // Menampilkan info jumlah data
            "order": [], // Tidak ada default order
            "scrollX": true, // Scroll horizontal jika kolom banyak
            "stateSave": true, // Menyimpan state tabel (halaman, filter, length)
            "lengthMenu": [ // Pilihan jumlah baris per halaman
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'Semua'] // -1 = tampilkan semua data
            ],
            "pageLength": 10, // Default tampil 10 data

            lengthChange: true, // User boleh ubah jumlah baris

            "ajax": { // Sumber data dari server
                "url": "{{ route('lap-bm.getlap-bm') }}", // Route ambil data laporan barang masuk
                "data": function(d) { // Kirim parameter filter ke server
                    d.tglawal = $('input[name="tglawal"]').val(); // Parameter tanggal awal
                    d.tglakhir = $('input[name="tglakhir"]').val(); // Parameter tanggal akhir
                }
            },

            "columns": [ // Mapping kolom tabel ke field JSON
                {
                    data: 'DT_RowIndex', // Nomor urut otomatis
                    name: 'DT_RowIndex',
                    searchable: false // Tidak bisa dicari
                },
                {
                    data: 'tgl', // Tanggal masuk versi display
                    name: 'bm_tanggal',
                },
                {
                    data: 'bm_kode', // Kode barang masuk
                    name: 'bm_kode',
                },
                {
                    data: 'barang_kode', // Kode barang
                    name: 'barang_kode',
                },
                {
                    data: 'customer', // Customer versi display
                    name: 'customer_nama',
                },
                {
                    data: 'barang', // Nama barang versi display
                    name: 'barang_nama',
                },
                {
                    data: 'bm_jumlah', // Jumlah masuk
                    name: 'bm_jumlah',
                },
            ],

        });
    }

    function filter() { // Fungsi filter berdasarkan tanggal
        var tglawal = $('input[name="tglawal"]').val(); // Ambil nilai tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil nilai tanggal akhir

        if (tglawal != '' && tglakhir != '') { // Jika keduanya diisi
            table.ajax.reload(null, false); // Reload tabel sesuai filter
        } else { // Jika salah satu kosong
            validasi("Isi dulu Form Filter Tanggal!", 'warning'); // Tampilkan peringatan
        }
    }

    function reset() { // Reset filter tanggal
        $('input[name="tglawal"]').val(''); // Kosongkan tanggal awal
        $('input[name="tglakhir"]').val(''); // Kosongkan tanggal akhir
        table.ajax.reload(null, false); // Reload tabel (tampilkan semua data)
    }

    function print() { // Fungsi print laporan
        var tglawal = $('input[name="tglawal"]').val(); // Ambil tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil tanggal akhir

        if (tglawal != '' && tglakhir != '') { // Jika filter ada
            window.open(
                "{{route('lap-bm.print')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
                '_blank'
            );
            // Buka halaman print sesuai range tanggal
        } else { // Jika tidak ada filter, konfirmasi print semua data
            swal({
                title: "Yakin Print Semua Data?",
                type: "warning",
                buttons: true,
                dangerMode: true,
                confirmButtonText: "Yakin",
                cancelButtonText: 'Batal',
                showCancelButton: true,
                showConfirmButton: true,
                closeOnConfirm: false,
                confirmButtonColor: '#09ad95',
            }, function(value) {
                if (value == true) { // Jika user setuju
                    window.open("{{route('lap-bm.print')}}", '_blank'); // Print semua data
                    swal.close(); // Tutup swal
                }
            });
        }
    }

    function pdf() { // Fungsi export PDF laporan
        var tglawal = $('input[name="tglawal"]').val(); // Ambil tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil tanggal akhir

        if (tglawal != '' && tglakhir != '') { // Jika filter ada
            window.open(
                "{{route('lap-bm.pdf')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
                '_blank'
            );
            // Export PDF sesuai range tanggal
        } else { // Jika tidak ada filter, konfirmasi export semua data
            swal({
                title: "Yakin export PDF Semua Data?",
                type: "warning",
                buttons: true,
                dangerMode: true,
                confirmButtonText: "Yakin",
                cancelButtonText: 'Batal',
                showCancelButton: true,
                showConfirmButton: true,
                closeOnConfirm: false,
                confirmButtonColor: '#09ad95',
            }, function(value) {
                if (value == true) { // Jika user setuju
                    window.open("{{route('lap-bm.pdf')}}", '_blank'); // Export semua data
                    swal.close(); // Tutup swal
                }
            });
        }
    }

    function validasi(judul, status) { // Helper SweetAlert sederhana
        swal({
            title: judul, // Judul/pesan alert
            type: status, // Jenis alert
            confirmButtonText: "Iya." // Tombol OK
        });
    }

</script>
@endsection {{-- Akhir section scripts --}}
