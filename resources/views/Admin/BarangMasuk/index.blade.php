@extends('Master.Layouts.app', ['title' => $title]) {{-- Memakai layout utama dan mengirimkan variabel title ke layout --}}

@section('content') {{-- Awal section konten halaman --}}

<!-- PAGE-HEADER --> <!-- Bagian header halaman -->
<div class="page-header"> <!-- Container header -->
    <h1 class="page-title">Barang Masuk</h1> <!-- Judul halaman -->
    <div> <!-- Container breadcrumb -->
        <ol class="breadcrumb"> <!-- Breadcrumb navigasi -->
            <li class="breadcrumb-item text-gray">Transaksi</li> <!-- Breadcrumb level 1 -->
            <li class="breadcrumb-item active" aria-current="page">Barang Masuk</li> <!-- Breadcrumb aktif -->
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END --> <!-- Akhir header halaman -->

<!-- ROW --> <!-- Baris utama untuk card tabel -->
<div class="row row-sm"> <!-- Row Bootstrap -->
    <div class="col-lg-12"> <!-- Kolom full lebar -->
        <div class="card"> <!-- Card container -->
            <div class="card-header justify-content-between"> <!-- Header card, posisi kiri-kanan -->
                <h3 class="card-title">Data</h3> <!-- Judul card -->

                @if ($hakTambah > 0) {{-- Jika user punya hak tambah --}}
                <div> <!-- Container tombol tambah -->
                    <a class="modal-effect btn btn-primary-light"
                       onclick="generateID()" {{-- Generate kode BM otomatis saat tombol diklik --}}
                       data-bs-effect="effect-super-scaled" {{-- Efek modal --}}
                       data-bs-toggle="modal" {{-- Trigger untuk membuka modal --}}
                       href="#modaldemo8"> {{-- Target modal tambah (id=modaldemo8) --}}
                        Tambah Data <i class="fe fe-plus"></i> {{-- Teks tombol + ikon --}}
                    </a>
                </div>
                @endif {{-- Akhir pengecekan hak tambah --}}
            </div>

            <div class="card-body"> <!-- Body card -->
                <div class="table-responsive"> <!-- Agar tabel bisa scroll di layar kecil -->
                    <table id="table-1"
                           class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <!-- Tabel DataTables utama -->
                        <thead> <!-- Header tabel -->
                            <th class="border-bottom-0" width="1%">No</th> <!-- Kolom nomor -->
                            <th class="border-bottom-0">Tanggal Masuk</th> <!-- Kolom tanggal -->
                            <th class="border-bottom-0">Kode Barang Masuk</th> <!-- Kolom kode BM -->
                            <th class="border-bottom-0">Kode Barang</th> <!-- Kolom kode barang -->
                            <th class="border-bottom-0">Customer</th> <!-- Kolom customer -->
                            <th class="border-bottom-0">Barang</th> <!-- Kolom nama barang -->
                            <th class="border-bottom-0">Jumlah Masuk</th> <!-- Kolom jumlah -->
                            <th class="border-bottom-0" width="1%">Action</th> <!-- Kolom aksi -->
                        </thead>
                        <tbody></tbody> <!-- Isi tabel kosong, nanti diisi oleh DataTables via AJAX -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END ROW --> <!-- Akhir row -->

@include('Admin.BarangMasuk.tambah') {{-- Include modal/form tambah barang masuk --}}
@include('Admin.BarangMasuk.edit') {{-- Include modal/form edit barang masuk --}}
@include('Admin.BarangMasuk.hapus') {{-- Include modal konfirmasi hapus --}}
@include('Admin.BarangMasuk.barang') {{-- Include modal daftar/pilih barang --}}

<script> // Script helper untuk aksi tambah/edit/hapus

    function generateID() { // Fungsi membuat kode BM otomatis
        id = new Date().getTime(); // Timestamp milidetik sebagai id unik
        $("input[name='bmkode']").val("BM-" + id); // Isi input bmkode pada form tambah
    }

    function update(data) { // Fungsi mengisi form edit dengan data yang dipilih dari tabel
        $("input[name='idbmU']").val(data.bm_id); // Set hidden id edit
        $("input[name='bmkodeU']").val(data.bm_kode); // Set kode BM
        $("input[name='kdbarangU']").val(data.barang_kode); // Set kode barang
        $("select[name='customerU']").val(data.customer_id); // Set customer yang dipilih
        $("input[name='jmlU']").val(data.bm_jumlah); // Set jumlah masuk

        getbarangbyidU(data.barang_kode);
        // Ambil detail barang (nama/satuan/jenis) untuk mengisi field readonly di form edit

        $("input[name='tglmasukU").bootstrapdatepicker({
            // BUG: selector kurang penutup -> harusnya $("input[name='tglmasukU']")
            format: 'yyyy-mm-dd', // Format tanggal
            autoclose: true // Datepicker otomatis menutup setelah pilih tanggal
        }).bootstrapdatepicker("update", data.bm_tanggal); // Set nilai datepicker ke tanggal data
    }

    function hapus(data) { // Fungsi menyiapkan modal hapus
        $("input[name='idbm']").val(data.bm_id); // Isi hidden id yang akan dihapus
        $("#vbm").html("Kode BM " + "<b>" + data.bm_kode + "</b>");
        // Tampilkan teks konfirmasi hapus di modal (menampilkan kode BM)
    }

    function validasi(judul, status) { // Fungsi helper untuk notifikasi validasi
        swal({ // SweetAlert
            title: judul, // Pesan/judul alert
            type: status, // Jenis alert (warning/success/error)
            confirmButtonText: "Iya." // Teks tombol konfirmasi
        });
    }

</script>
@endsection {{-- Akhir section content --}}

@section('scripts') {{-- Section script tambahan untuk DataTables --}}
<script> // Script konfigurasi AJAX & DataTables

    $.ajaxSetup({ // Setup header AJAX default (CSRF)
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // Ambil token dari meta csrf-token agar request POST Laravel aman
        }
    });

    var table; // Variabel global DataTables instance

    $(document).ready(function() { // Saat halaman selesai dimuat

        //datatables // Penanda bagian inisialisasi DataTables
        table = $('#table-1').DataTable({ // Inisialisasi DataTables pada #table-1

            "processing": true, // Tampilkan indikator proses
            "serverSide": true, // Mode server-side (paging/filter/sort di server)
            "info": true, // Tampilkan info jumlah data
            "order": [], // Tidak set default ordering
            "scrollX": true, // Scroll horizontal jika kolom banyak
            "stateSave": true, // Simpan state (page/search/length) di browser
            "lengthMenu": [ // Pilihan jumlah baris per halaman
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "pageLength": 10, // Default 10 baris

            lengthChange: true, // Boleh ubah jumlah baris per halaman

            "ajax": { // Sumber data JSON dari server
                "url": "{{ route('barang-masuk.getbarang-masuk') }}",
                // Endpoint route yang mengembalikan data barang masuk untuk DataTables
            },

            "columns": [ // Mapping kolom DataTables ke field JSON
                {
                    data: 'DT_RowIndex', // Index otomatis dari server (umumnya Yajra)
                    name: 'DT_RowIndex',
                    searchable: false // Tidak bisa dicari
                },
                {
                    data: 'tgl', // Tanggal tampil (format display)
                    name: 'bm_tanggal', // Field asli di DB
                },
                {
                    data: 'bm_kode', // Kode BM
                    name: 'bm_kode',
                },
                {
                    data: 'barang_kode', // Kode barang
                    name: 'barang_kode',
                },
                {
                    data: 'customer', // Nama customer (hasil join)
                    name: 'customer_nama',
                },
                {
                    data: 'barang', // Nama barang (hasil join)
                    name: 'barang_nama',
                },
                {
                    data: 'bm_jumlah', // Jumlah masuk
                    name: 'bm_jumlah',
                },
                {
                    data: 'action', // Kolom aksi (HTML tombol edit/hapus)
                    name: 'action',
                    orderable: false, // Tidak bisa diurutkan
                    searchable: false // Tidak bisa dicari
                },
            ],

        });

    });
</script>
@endsection {{-- Akhir section scripts --}}
