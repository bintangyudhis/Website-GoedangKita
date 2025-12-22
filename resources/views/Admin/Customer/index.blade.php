@extends('Master.Layouts.app', ['title' => $title]) {{-- Menggunakan layout utama dan mengirim variabel title --}}

@section('content') {{-- Awal section konten halaman --}}

    <!-- PAGE-HEADER --> <!-- Header halaman -->
    <div class="page-header"> <!-- Container header -->
        <h1 class="page-title">Customer</h1> <!-- Judul halaman -->
        <div> <!-- Container breadcrumb -->
            <ol class="breadcrumb"> <!-- Breadcrumb navigasi -->
                <li class="breadcrumb-item text-gray">Admin</li> <!-- Breadcrumb level 1 -->
                <li class="breadcrumb-item active" aria-current="page">Customer</li> <!-- Breadcrumb aktif -->
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
                                data-bs-toggle="modal" {{-- Trigger buka modal --}}
                                href="#modaldemo8"> {{-- Target modal tambah (id=modaldemo8) --}}
                                Tambah Data <i class="fe fe-plus"></i> {{-- Teks tombol + ikon --}}
                            </a>
                        </div>
                    @endif {{-- Akhir cek hak tambah --}}
                </div>

                <div class="card-body"> <!-- Body card -->
                    <div class="table-responsive"> <!-- Agar tabel responsif/scroll jika sempit -->
                        <table id="table-1" width="100%"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            <!-- Tabel DataTables utama -->
                            <thead> <!-- Header tabel -->
                                <th class="border-bottom-0" width="1%">No</th> <!-- Kolom nomor -->
                                <th class="border-bottom-0">Customer</th> <!-- Kolom nama customer -->
                                <th class="border-bottom-0">No Telp</th> <!-- Kolom nomor telepon -->
                                <th class="border-bottom-0">Alamat</th> <!-- Kolom alamat -->
                                <th class="border-bottom-0" width="1%">Action</th> <!-- Kolom aksi (edit/hapus) -->
                            </thead>
                            <tbody></tbody> <!-- Body kosong, nanti diisi lewat AJAX DataTables -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW --> <!-- Akhir row -->

    @include('Admin.Customer.tambah') {{-- Include modal/form tambah customer --}}
    @include('Admin.Customer.edit') {{-- Include modal/form edit customer --}}
    @include('Admin.Customer.hapus') {{-- Include modal konfirmasi hapus customer --}}

    <script> // Script helper untuk aksi edit & hapus

        function update(data) { // Fungsi mengisi form edit berdasarkan data baris tabel
            $("input[name='idcustomerU']").val(data.customer_id); // Set hidden id customer yang diedit
            $("input[name='customerU']").val(data.customer_nama.replace(/_/g, ' '));
            // Isi nama customer, underscore '_' diganti spasi (jika data tersimpan pakai underscore)

            $("input[name='notelpU']").val(data.customer_notelp); // Isi nomor telepon customer
            $("textarea[name='alamatU']").val(data.customer_alamat.replace(/_/g, ' '));
            // Isi alamat customer, underscore diganti spasi
        }

        function hapus(data) { // Fungsi menyiapkan modal hapus customer
            $("input[name='idcustomer']").val(data.customer_id); // Simpan id customer yang akan dihapus
            $("#vcustomer").html("customer " + "<b>" + data.customer_nama.replace(/_/g, ' ') + "</b>");
            // Tampilkan kalimat konfirmasi hapus dengan nama customer (dibold)
        }

        function validasi(judul, status) { // Fungsi helper untuk menampilkan SweetAlert
            swal({
                title: judul, // Pesan/judul alert
                type: status, // Tipe alert (warning/success/error)
                confirmButtonText: "Iya." // Teks tombol konfirmasi
            });
        }

    </script>
@endsection {{-- Akhir section content --}}

@section('scripts') {{-- Section script tambahan (biasanya diletakkan di bawah layout) --}}
    <script> // Script untuk konfigurasi AJAX & DataTables

        $.ajaxSetup({ // Set header default untuk semua request AJAX jQuery
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // Mengambil token CSRF dari meta tag (wajib untuk POST Laravel)
            }
        });

        var table; // Variabel global untuk instance DataTables

        $(document).ready(function() { // Jalankan saat dokumen siap

            //datatables // Penanda: inisialisasi DataTables
            table = $('#table-1').DataTable({ // Pasang DataTables pada #table-1

                "processing": true, // Tampilkan indikator "processing"
                "serverSide": true, // Mode server-side (paging/search/order di server)
                "info": true, // Tampilkan info jumlah data
                "order": [], // Tidak set default order
                "stateSave": true, // Simpan state tabel (page, search, length)
                "lengthMenu": [ // Opsi jumlah baris per halaman
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                "pageLength": 10, // Default 10 baris per halaman

                lengthChange: true, // User boleh ubah jumlah baris per halaman

                "ajax": { // Sumber data dari server
                    "url": "{{ route('customer.getcustomer') }}",
                    // Endpoint route yang mengembalikan JSON DataTables
                },

                "columns": [ // Mapping kolom tabel ke field JSON dari server
                    {
                        data: 'DT_RowIndex', // Index otomatis (umumnya dari Yajra)
                        name: 'DT_RowIndex',
                        searchable: false // Tidak bisa dicari
                    },
                    {
                        data: 'customer_nama', // Kolom nama customer
                        name: 'customer_nama',
                    },
                    {
                        data: 'notelp', // Kolom nomor telepon versi display
                        name: 'customer_notelp',
                    },
                    {
                        data: 'alamat', // Kolom alamat versi display
                        name: 'customer_alamat',
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
