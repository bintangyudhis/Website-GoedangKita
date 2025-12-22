@extends('Master.Layouts.app', ['title' => $title]) {{-- Menggunakan layout utama Master.Layouts.app dan mengirim variabel title ke layout --}}

@section('content')
    {{-- Awal section konten utama halaman --}}

    <!-- PAGE-HEADER --> <!-- Bagian header halaman -->
    <div class="page-header"> <!-- Container header halaman -->
        <h1 class="page-title">Barang Keluar</h1> <!-- Judul halaman -->
        <div> <!-- Container breadcrumb -->
            <ol class="breadcrumb"> <!-- Breadcrumb navigation -->
                <li class="breadcrumb-item text-gray">Transaksi</li> <!-- Breadcrumb level 1 -->
                <li class="breadcrumb-item active" aria-current="page">Barang Keluar</li> <!-- Breadcrumb level 2 (aktif) -->
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END --> <!-- Akhir header -->

    <!-- ROW --> <!-- Baris utama untuk konten tabel -->
    <div class="row row-sm"> <!-- Row Bootstrap ukuran kecil -->
        <div class="col-lg-12"> <!-- Kolom full lebar pada layar besar -->
            <div class="card"> <!-- Card container -->
                <div class="card-header justify-content-between"> <!-- Header card, isi berjauhan kiri-kanan -->
                    <h3 class="card-title">Data</h3> <!-- Judul card -->

                    @if ($hakTambah > 0)
                        {{-- Jika user punya hak tambah (permission) --}}
                        <div> <!-- Container tombol tambah -->
                            <a class="modal-effect btn btn-primary-light" onclick="generateID()" {{-- Saat klik, generate kode BK otomatis --}}
                                data-bs-effect="effect-super-scaled" {{-- Efek animasi modal --}} data-bs-toggle="modal"
                                {{-- Trigger Bootstrap modal --}} href="#modaldemo8"> {{-- Target modal tambah (id=modaldemo8) --}}
                                Tambah Data <i class="fe fe-plus"></i> {{-- Teks tombol + ikon --}}
                            </a>
                        </div>
                    @endif {{-- Akhir pengecekan hak tambah --}}
                </div>

                <div class="card-body"> <!-- Isi card -->
                    <div class="table-responsive"> <!-- Biar tabel bisa scroll di layar kecil -->
                        <table id="table-1"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <!-- Tabel DataTables: id table-1 + class untuk styling dan fitur responsive -->
                            <thead> <!-- Head tabel -->
                                <th class="border-bottom-0" width="1%">No</th> <!-- Kolom nomor -->
                                <th class="border-bottom-0">Tanggal Keluar</th> <!-- Kolom tanggal -->
                                <th class="border-bottom-0">Kode Barang Keluar</th> <!-- Kolom kode BK -->
                                <th class="border-bottom-0">Kode Barang</th> <!-- Kolom kode barang -->
                                <th class="border-bottom-0">Barang</th> <!-- Kolom nama barang -->
                                <th class="border-bottom-0">Jumlah Keluar</th> <!-- Kolom jumlah -->
                                <th class="border-bottom-0">Tujuan</th> <!-- Kolom tujuan -->
                                <th class="border-bottom-0" width="1%">Action</th> <!-- Kolom aksi (edit/hapus) -->
                            </thead>
                            <tbody></tbody> <!-- Body kosong, nanti diisi oleh DataTables via AJAX -->
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END ROW --> <!-- Akhir row tabel -->

    @include('Admin.BarangKeluar.tambah') {{-- Memanggil partial view modal/form tambah --}}
    @include('Admin.BarangKeluar.edit') {{-- Memanggil partial view modal/form edit --}}
    @include('Admin.BarangKeluar.hapus') {{-- Memanggil partial view modal konfirmasi hapus --}}
    @include('Admin.BarangKeluar.barang') {{-- Memanggil partial view modal daftar/pilih barang --}}

    <script>
        // Script JS inline untuk fungsi-fungsi halaman

        function generateID() { // Fungsi generate kode barang keluar otomatis
            id = new Date().getTime(); // Ambil timestamp milidetik (unik berdasarkan waktu)
            $("input[name='bkkode']").val("BK-" + id); // Isi input bkkode di form tambah: "BK-{timestamp}"
        }

        function update(data) { // Fungsi untuk mengisi form edit dari data baris tabel
            $("input[name='idbkU']").val(data.bk_id); // Set hidden id edit
            $("input[name='bkkodeU']").val(data.bk_kode); // Set kode BK edit
            $("input[name='kdbarangU']").val(data.barang_kode); // Set kode barang edit
            $("input[name='jmlU']").val(data.bk_jumlah); // Set jumlah keluar edit
            $("input[name='tujuanU']").val(data.bk_tujuan.replace(/_/g, ' '));
            // Set tujuan; underscore '_' diganti spasi (asumsi data tujuan disimpan pakai underscore)

            getbarangbyidU(data.barang_kode);
            // Ambil detail barang (nama, satuan, jenis) lewat AJAX untuk mengisi field readonly di form edit

            $("input[name='tglkeluarU]").bootstrapdatepicker({
                
                format: 'yyyy-mm-dd', // Format tanggal
                autoclose: true // Setelah pilih tanggal, datepicker menutup otomatis
            }).bootstrapdatepicker("update", data.bk_tanggal); // Set nilai datepicker ke tanggal dari data
        }

        function hapus(data) { // Fungsi untuk menyiapkan modal hapus
            $("input[name='idbk']").val(data.bk_id); // Simpan id yang mau dihapus ke hidden input
            $("#vbk").html("Kode BK " + "<b>" + data.bk_kode + "</b>");
            // Tampilkan teks konfirmasi: "Kode BK {bk_kode}" dengan huruf tebal
        }

        function validasi(judul, status) { // Fungsi helper untuk menampilkan alert validasi
            swal({ // Memanggil SweetAlert
                title: judul, // Judul/pesan alert
                type: status, // Tipe alert (success/warning/error)
                confirmButtonText: "Iya." // Teks tombol konfirmasi
            });
        }
    </script>
@endsection {{-- Akhir section content --}}

@section('scripts')
    {{-- Section khusus script tambahan (biasanya di-include di bawah layout) --}}
    <script>
        // Awal script untuk DataTables & setup AJAX

        $.ajaxSetup({ // Setup default header AJAX untuk semua request jQuery
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // Ambil CSRF token dari meta tag, diperlukan untuk request POST di Laravel
            }
        });

        var table; // Deklarasi variabel global untuk instance DataTable

        $(document).ready(function() { // Jalankan ketika DOM sudah siap

            //datatables // Komentar penanda bagian DataTables
            table = $('#table-1').DataTable({ // Inisialisasi DataTable pada tabel #table-1

                "processing": true, // Menampilkan indikator "processing" saat load data
                "serverSide": true, // Mode server-side: paging/filter/sort diproses di server
                "info": true, // Menampilkan info jumlah data (Showing X to Y of Z)
                "order": [], // Default tidak ada urutan khusus (pakai dari server / default)
                "scrollX": true, // Aktifkan scroll horizontal jika kolom banyak
                "stateSave": true, // Simpan state tabel (page, search, length) di browser
                "lengthMenu": [ // Opsi jumlah data per halaman
                    [5, 10, 25, 50, 100], // Nilai
                    [5, 10, 25, 50, 100] // Label yang ditampilkan
                ],
                "pageLength": 10, // Default 10 baris per halaman

                lengthChange: true, // User bisa ganti jumlah baris per halaman

                "ajax": { // Sumber data DataTables
                    "url": "{{ route('barang-keluar.getbarang-keluar') }}",
                    // Endpoint route Laravel yang mengembalikan JSON untuk DataTables
                },

                "columns": [ // Definisi kolom yang dipetakan ke data JSON
                    {
                        data: 'DT_RowIndex', // Index otomatis dari server (umumnya Yajra)
                        name: 'DT_RowIndex', // Nama kolom di server
                        searchable: false // Tidak bisa dicari
                    },
                    {
                        data: 'tgl', // Kolom tanggal tampil (format tampilan)
                        name: 'bk_tanggal', // Field asli di database/server
                    },
                    {
                        data: 'bk_kode', // Kolom kode BK
                        name: 'bk_kode',
                    },
                    {
                        data: 'barang_kode', // Kolom kode barang
                        name: 'barang_kode',
                    },
                    {
                        data: 'barang', // Kolom nama barang (hasil join)
                        name: 'barang_nama',
                    },
                    {
                        data: 'bk_jumlah', // Kolom jumlah
                        name: 'bk_jumlah',
                    },
                    {
                        data: 'tujuan', // Kolom tujuan
                        name: 'bk_tujuan',
                    },
                    {
                        data: 'action', // Kolom action (HTML tombol edit/hapus)
                        name: 'action',
                        orderable: false, // Tidak bisa diurutkan
                        searchable: false // Tidak bisa dicari
                    },
                ],

            });

        });
    </script>
@endsection {{-- Akhir section scripts --}}
