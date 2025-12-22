@extends('Master.Layouts.app', ['title' => $title]) {{-- Menggunakan layout utama dan mengirim title --}}

@section('content') {{-- Awal section content --}}

<!-- PAGE-HEADER --> <!-- Header halaman -->
<div class="page-header"> <!-- Container header -->
    <h1 class="page-title">Laporan Barang Keluar</h1> <!-- Judul halaman -->
    <div> <!-- Container breadcrumb -->
        <ol class="breadcrumb"> <!-- Breadcrumb navigasi -->
            <li class="breadcrumb-item text-gray">Laporan</li> <!-- Breadcrumb level 1 -->
            <li class="breadcrumb-item active" aria-current="page">Barang Keluar</li> <!-- Breadcrumb aktif -->
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END --> <!-- Akhir header -->

<!-- ROW --> <!-- Row utama untuk card laporan -->
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
                        <!-- Kumpulan tombol aksi -->
                        <button class="btn btn-success-light" onclick="filter()">
                            <!-- Tombol filter: reload datatable sesuai tanggal -->
                            <i class="fe fe-filter"></i> Filter
                        </button>

                        <button class="btn btn-secondary-light" onclick="reset()">
                            <!-- Tombol reset: kosongkan input + reload datatable -->
                            <i class="fe fe-refresh-ccw"></i> Reset
                        </button>

                        <button class="btn btn-primary-light" onclick="print()">
                            <!-- Tombol print: buka halaman print (tab baru) -->
                            <i class="fe fe-printer"></i> Print
                        </button>

                        <button class="btn btn-danger-light" onclick="pdf()">
                            <!-- Tombol PDF: export pdf (tab baru) -->
                            <i class="fa fa-file-pdf-o"></i> PDF
                        </button>
                    </div>
                </div>

                <div class="table-responsive"> <!-- Tabel responsif -->
                    <table id="table-1" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <!-- Tabel DataTables laporan -->
                        <thead>
                            <th class="border-bottom-0" width="1%">No</th> <!-- Nomor urut -->
                            <th class="border-bottom-0">Tanggal Keluar</th> <!-- Tanggal transaksi keluar -->
                            <th class="border-bottom-0">Kode Barang Keluar</th> <!-- Kode BK -->
                            <th class="border-bottom-0">Kode Barang</th> <!-- Kode barang -->
                            <th class="border-bottom-0">Barang</th> <!-- Nama barang -->
                            <th class="border-bottom-0">Jumlah Keluar</th> <!-- Jumlah keluar -->
                            <th class="border-bottom-0">Tujuan</th> <!-- Tujuan pengeluaran -->
                        </thead>
                        <tbody></tbody> <!-- Body kosong, diisi lewat AJAX -->
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END ROW --> <!-- Akhir row -->

@endsection {{-- Akhir section content --}}

@section('scripts') {{-- Section scripts tambahan --}}
<script> // Awal script JS halaman laporan

    $.ajaxSetup({ // Set header CSRF untuk semua AJAX jQuery
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Token CSRF Laravel
        }
    });

    $(document).ready(function() { // Saat halaman siap
        getData(); // Panggil fungsi inisialisasi DataTables
    });

    function getData() { // Inisialisasi DataTables laporan
        //datatables // Penanda: DataTables

        table = $('#table-1').DataTable({ // Buat DataTables pada tabel #table-1

            "processing": true, // Tampilkan loading "processing"
            "serverSide": true, // Mode server-side (paging/search di server)
            "info": true, // Tampilkan info jumlah data
            "order": [], // Tidak set default order
            "scrollX": true, // Aktifkan scroll horizontal
            "stateSave": true, // Simpan state tabel (page, filter, dll)
            "lengthMenu": [ // Pilihan jumlah data per halaman
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, 'Semua'] // -1 artinya tampilkan semua data
            ],
            "pageLength": 10, // Default 10 baris per halaman

            lengthChange: true, // User boleh ubah jumlah baris per halaman

            "ajax": { // Pengambilan data via AJAX
                "url": "{{ route('lap-bk.getlap-bk') }}", // Endpoint data laporan barang keluar
                "data": function(d) { // Data tambahan yang dikirim ke server
                    d.tglawal = $('input[name="tglawal"]').val(); // Kirim tanggal awal
                    d.tglakhir = $('input[name="tglakhir"]').val(); // Kirim tanggal akhir
                }
            },

            "columns": [ // Mapping kolom DataTables ke field JSON
                {
                    data: 'DT_RowIndex', // Nomor urut otomatis (umumnya dari Yajra)
                    name: 'DT_RowIndex',
                    searchable: false // Tidak bisa dicari
                },
                {
                    data: 'tgl', // Tanggal keluar versi display
                    name: 'bk_tanggal',
                },
                {
                    data: 'bk_kode', // Kode barang keluar
                    name: 'bk_kode',
                },
                {
                    data: 'barang_kode', // Kode barang
                    name: 'barang_kode',
                },
                {
                    data: 'barang', // Nama barang versi display
                    name: 'barang_nama',
                },
                {
                    data: 'bk_jumlah', // Jumlah keluar
                    name: 'bk_jumlah',
                },
                {
                    data: 'tujuan', // Tujuan versi display
                    name: 'bk_tujuan',
                },
            ],

        });
    }

    function filter() { // Fungsi untuk menjalankan filter tanggal
        var tglawal = $('input[name="tglawal"]').val(); // Ambil input tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil input tanggal akhir

        if (tglawal != '' && tglakhir != '') { // Jika keduanya terisi
            table.ajax.reload(null, false); // Reload tabel sesuai filter tanpa reset halaman
        } else { // Jika ada yang kosong
            validasi("Isi dulu Form Filter Tanggal!", 'warning'); // Tampilkan warning
        }
    }

    function reset() { // Fungsi reset filter
        $('input[name="tglawal"]').val(''); // Kosongkan tanggal awal
        $('input[name="tglakhir"]').val(''); // Kosongkan tanggal akhir
        table.ajax.reload(null, false); // Reload tabel (kembali semua data)
    }

    function print() { // Fungsi print laporan
        var tglawal = $('input[name="tglawal"]').val(); // Ambil tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil tanggal akhir

        if (tglawal != '' && tglakhir != '') { // Jika filter tanggal terisi
            window.open(
                "{{route('lap-bk.print')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
                '_blank'
            );
            // Buka halaman print dengan parameter tglawal & tglakhir
        } else { // Jika filter kosong, konfirmasi print semua data
            swal({
                title: "Yakin Print Semua Data?", // Pertanyaan konfirmasi
                type: "warning", // Tipe warning
                buttons: true,
                dangerMode: true,
                confirmButtonText: "Yakin", // Tombol konfirmasi
                cancelButtonText: 'Batal', // Tombol batal
                showCancelButton: true, // Tampilkan tombol cancel
                showConfirmButton: true, // Tampilkan tombol confirm
                closeOnConfirm: false, // Jangan tutup otomatis sebelum callback
                confirmButtonColor: '#09ad95', // Warna tombol confirm
            }, function(value) { // Callback hasil konfirmasi
                if (value == true) { // Jika user setuju
                    window.open("{{route('lap-bk.print')}}", '_blank'); // Print semua data
                    swal.close(); // Tutup swal
                }
            });
        }
    }

    function pdf() { // Fungsi export PDF laporan
        var tglawal = $('input[name="tglawal"]').val(); // Ambil tanggal awal
        var tglakhir = $('input[name="tglakhir"]').val(); // Ambil tanggal akhir

        if (tglawal != '' && tglakhir != '') { // Jika filter tanggal terisi
            window.open(
                "{{route('lap-bk.pdf')}}?tglawal=" + tglawal + "&tglakhir=" + tglakhir,
                '_blank'
            );
            // Buka PDF dengan parameter filter tanggal
        } else { // Jika filter kosong, konfirmasi export semua data
            swal({
                title: "Yakin export PDF Semua Data?", // Pertanyaan konfirmasi
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
                    window.open("{{route('lap-bk.pdf')}}", '_blank'); // Export semua data
                    swal.close(); // Tutup swal
                }
            });
        }
    }

    function validasi(judul, status) { // Helper menampilkan SweetAlert sederhana
        swal({
            title: judul, // Judul/pesan
            type: status, // Status alert
            confirmButtonText: "Iya." // Tombol OK
        });
    }

</script>
@endsection {{-- Akhir section scripts --}}
