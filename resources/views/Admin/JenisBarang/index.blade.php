@extends('Master.Layouts.app', ['title' => $title]) {{-- Menggunakan layout utama dan mengirim variabel title --}}

@section('content') {{-- Awal section content --}}

    <!-- PAGE-HEADER --> <!-- Bagian header halaman -->
    <div class="page-header"> <!-- Container header -->
        <h1 class="page-title">Jenis Barang</h1> <!-- Judul halaman -->
        <div> <!-- Container breadcrumb -->
            <ol class="breadcrumb"> <!-- Breadcrumb navigasi -->
                <li class="breadcrumb-item text-gray">Master Barang</li> <!-- Breadcrumb level 1 -->
                <li class="breadcrumb-item active" aria-current="page">Jenis Barang</li> <!-- Breadcrumb aktif -->
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END --> <!-- Akhir header -->

    <!-- ROW --> <!-- Baris utama untuk card tabel -->
    <div class="row row-sm"> <!-- Row Bootstrap -->
        <div class="col-lg-12"> <!-- Kolom full lebar -->
            <div class="card"> <!-- Card container -->
                <div class="card-header justify-content-between"> <!-- Header card, posisi kiri-kanan -->
                    <h3 class="card-title">Data</h3> <!-- Judul card -->

                    @if ($hakTambah > 0) {{-- Jika user punya hak tambah --}}
                        <div> <!-- Container tombol tambah -->
                            <a class="modal-effect btn btn-primary-light"
                                data-bs-effect="effect-super-scaled" {{-- Efek modal --}}
                                data-bs-toggle="modal" {{-- Trigger membuka modal --}}
                                href="#modaldemo8"> {{-- Target modal tambah (id=modaldemo8) --}}
                                Tambah Data <i class="fe fe-plus"></i> {{-- Teks tombol + ikon --}}
                            </a>
                        </div>
                    @endif {{-- Akhir cek hak tambah --}}
                </div>

                <div class="card-body"> <!-- Body card -->
                    <div class="table-responsive"> <!-- Agar tabel responsif -->
                        <table id="table-1" width="100%"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <!-- Tabel DataTables utama -->
                            <thead> <!-- Header kolom tabel -->
                                <th class="border-bottom-0" width="1%">No</th> <!-- Kolom nomor -->
                                <th class="border-bottom-0">Jenis Barang</th> <!-- Kolom nama jenis barang -->
                                <th class="border-bottom-0">Keterangan</th> <!-- Kolom keterangan -->
                                <th class="border-bottom-0" width="1%">Action</th> <!-- Kolom aksi (edit/hapus) -->
                            </thead>
                            <tbody></tbody> <!-- Body kosong, diisi DataTables via AJAX -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW --> <!-- Akhir row -->

    @include('Admin.JenisBarang.tambah') {{-- Include modal/form tambah jenis barang --}}
    @include('Admin.JenisBarang.edit') {{-- Include modal/form edit jenis barang --}}
    @include('Admin.JenisBarang.hapus') {{-- Include modal konfirmasi hapus jenis barang --}}

    <script> // Script helper untuk isi modal edit/hapus dan alert validasi

        function update(data) { // Fungsi mengisi form edit menggunakan data baris tabel
            $("input[name='idjenisbarangU']").val(data.jenisbarang_id); // Isi hidden id untuk edit
            $("input[name='jenisbarangU']").val(data.jenisbarang_nama.replace(/_/g, ' '));
            // Isi nama jenis barang (underscore diganti spasi)

            $("textarea[name='ketU']").val(data.jenisbarang_ket.replace(/_/g, ' '));
            // Isi keterangan (underscore diganti spasi)
        }

        function hapus(data) { // Fungsi menyiapkan modal hapus
            $("input[name='idjenisbarang']").val(data.jenisbarang_id); // Simpan id yang akan dihapus
            $("#vjenisbarang").html("jenis " + "<b>" + data.jenisbarang_nama.replace(/_/g, ' ') + "</b>");
            // Isi kalimat konfirmasi pada modal hapus (menampilkan nama jenis barang)
        }

        function validasi(judul, status) { // Fungsi helper menampilkan SweetAlert
            swal({
                title: judul, // Pesan/judul alert
                type: status, // Tipe alert (warning/success/error)
                confirmButtonText: "Iya." // Text tombol konfirmasi
            });
        }

    </script>
@endsection {{-- Akhir section content --}}

@section('scripts') {{-- Section scripts tambahan untuk DataTables --}}
    <script> // Awal script DataTables

        $.ajaxSetup({ // Menambahkan header CSRF untuk semua AJAX (wajib di Laravel untuk POST)
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // Ambil token dari meta tag csrf-token
            }
        });

        var table; // Variabel global untuk menyimpan instance DataTables

        $(document).ready(function() { // Saat dokumen siap

            //datatables // Penanda: inisialisasi DataTables
            table = $('#table-1').DataTable({ // Inisialisasi DataTables

                "processing": true, // Tampilkan indikator proses
                "serverSide": true, // Proses paging/search/order di server
                "info": true, // Tampilkan info jumlah data
                "stateSave": true, // Simpan state tabel (page/search/length) di browser
                "order": [], // Tidak set default order

                "lengthMenu": [ // Pilihan jumlah baris per halaman
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                "pageLength": 10, // Default 10 baris per halaman

                lengthChange: true, // User bisa ubah jumlah baris per halaman

                "ajax": { // Sumber data JSON dari server
                    "url": "{{ route('jenisbarang.getjenisbarang') }}",
                    // Endpoint route untuk mengambil data jenis barang (format DataTables)
                },

                "columns": [ // Mapping kolom DataTables -> field JSON
                    {
                        data: 'DT_RowIndex', // Nomor urut otomatis
                        name: 'DT_RowIndex',
                        searchable: false // Tidak bisa dicari
                    },
                    {
                        data: 'jenisbarang_nama', // Nama jenis barang
                        name: 'jenisbarang_nama',
                    },
                    {
                        data: 'ket', // Keterangan versi display
                        name: 'jenisbarang_ket',
                    },
                    {
                        data: 'action', // Kolom aksi (tombol edit/hapus)
                        name: 'action',
                        orderable: false, // Tidak bisa diurutkan
                        searchable: false // Tidak bisa dicari
                    },
                ],

            });

        });

    </script>
@endsection {{-- Akhir section scripts --}}
