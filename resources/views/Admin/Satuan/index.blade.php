@extends('Master.Layouts.app', ['title' => $title])
{{-- Menggunakan layout utama Master.Layouts.app dan mengirim variabel title ke layout --}}

@section('content')
{{-- Awal section content (isi halaman) --}}

    <!-- PAGE-HEADER -->
    <div class="page-header">
        {{-- Container header halaman --}}

        <h1 class="page-title">Satuan Barang</h1>
        {{-- Judul halaman --}}

        <div>
            <ol class="breadcrumb">
                {{-- Breadcrumb navigasi (jejak menu) --}}

                <li class="breadcrumb-item text-gray">Master Barang</li>
                {{-- Level menu pertama --}}

                <li class="breadcrumb-item active" aria-current="page">Satuan Barang</li>
                {{-- Level menu aktif saat ini --}}
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->


    <!-- ROW -->
    <div class="row row-sm">
        {{-- Baris grid Bootstrap --}}

        <div class="col-lg-12">
            {{-- Kolom full lebar pada layar besar --}}

            <div class="card">
                {{-- Card untuk membungkus tabel data --}}

                <div class="card-header justify-content-between">
                    {{-- Header card dengan posisi elemen kiri-kanan --}}

                    <h3 class="card-title">Data</h3>
                    {{-- Judul card --}}

                    @if ($hakTambah > 0)
                        {{-- Jika user punya hak tambah (permission) --}}

                        <div>
                            <a class="modal-effect btn btn-primary-light"
                               data-bs-effect="effect-super-scaled"
                               data-bs-toggle="modal"
                               href="#modaldemo8">
                                {{-- Tombol untuk membuka modal tambah (id: modaldemo8) --}}
                                Tambah Data <i class="fe fe-plus"></i>
                                {{-- Teks tombol + icon plus --}}
                            </a>
                        </div>
                    @endif
                    {{-- End pengecekan hak tambah --}}
                </div>

                <div class="card-body">
                    {{-- Isi card --}}

                    <div class="table-responsive">
                        {{-- Membuat tabel bisa scroll horizontal jika sempit --}}

                        <table id="table-1" width="100%"
                            class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                            {{-- Tabel DataTables dengan id table-1 --}}

                            <thead>
                                {{-- Header kolom tabel --}}
                                <th class="border-bottom-0" width="1%">No</th>
                                {{-- Kolom nomor urut --}}
                                <th class="border-bottom-0">Satuan</th>
                                {{-- Kolom nama satuan --}}
                                <th class="border-bottom-0">Keterangan</th>
                                {{-- Kolom keterangan --}}
                                <th class="border-bottom-0" width="1%">Action</th>
                                {{-- Kolom aksi (edit/hapus) --}}
                            </thead>

                            <tbody></tbody>
                            {{-- Data body akan diisi otomatis oleh DataTables via AJAX --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END ROW -->

    @include('Admin.Satuan.tambah')
    {{-- Memanggil file blade modal/komponen tambah satuan --}}

    @include('Admin.Satuan.edit')
    {{-- Memanggil file blade modal/komponen edit satuan --}}

    @include('Admin.Satuan.hapus')
    {{-- Memanggil file blade modal/komponen hapus satuan --}}

    <script>
        function update(data) {
            // Fungsi untuk mengisi form edit dengan data yang dipilih (dipanggil saat klik tombol edit)

            $("input[name='idsatuanU']").val(data.satuan_id);
            // Mengisi hidden input id satuan untuk proses update

            $("input[name='satuanU']").val(data.satuan_nama.replace(/_/g, ' '));
            // Mengisi input satuanU dengan nama satuan (underscore diganti spasi)

            $("textarea[name='ketU']").val(data.satuan_keterangan.replace(/_/g, ' '));
            // Mengisi textarea keterangan pada form edit
        }

        function hapus(data) {
            // Fungsi untuk mengisi modal hapus dengan id dan nama satuan (dipanggil saat klik tombol hapus)

            $("input[name='idsatuan']").val(data.satuan_id);
            // Menyimpan id satuan yang akan dihapus

            $("#vsatuan").html("satuan " + "<b>" + data.satuan_nama.replace(/_/g, ' ') + "</b>");
            // Menampilkan nama satuan di kalimat konfirmasi modal hapus
        }

        function validasi(judul, status) {
            // Fungsi untuk menampilkan alert validasi (SweetAlert)

            swal({
                title: judul,
                type: status,
                confirmButtonText: "Iya."
            });
            // Menampilkan pop-up dengan judul & status (warning/success/dll)
        }
    </script>
@endsection
{{-- Akhir section content --}}

@section('scripts')
{{-- Section scripts khusus halaman (biasanya ditaruh di bawah layout) --}}

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Setup CSRF token untuk semua request AJAX agar lolos proteksi Laravel

        var table;
        // Variabel global untuk menyimpan instance DataTables

        $(document).ready(function() {
            // Saat halaman selesai dimuat

            //datatables
            table = $('#table-1').DataTable({
                // Inisialisasi DataTables pada tabel id table-1

                "processing": true,
                // Menampilkan indikator "processing" saat memuat data

                "serverSide": true,
                // Mode server-side: data diambil dari server per halaman/filter

                "info": true,
                // Menampilkan info jumlah data (contoh: Showing 1 to 10 of 50 entries)

                "order": [],
                // Default tidak ada pengurutan awal

                "stateSave": true,
                // Menyimpan state DataTables (page, search, sort) di browser

                "lengthMenu": [
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                // Pilihan jumlah baris per halaman

                "pageLength": 10,
                // Default tampil 10 data per halaman

                lengthChange: true,
                // Mengizinkan user mengubah jumlah data per halaman

                "ajax": {
                    "url": "{{ route('satuan.getsatuan') }}",
                },
                // Sumber data AJAX dari route satuan.getsatuan

                "columns": [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false
                    },
                    // Kolom nomor urut (tidak bisa dicari)

                    {
                        data: 'satuan_nama',
                        name: 'satuan_nama',
                    },
                    // Kolom nama satuan

                    {
                        data: 'ket',
                        name: 'satuan_keterangan',
                    },
                    // Kolom keterangan (biasanya sudah diformat di server jadi 'ket')

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    // Kolom action (button edit/hapus), tidak bisa diurutkan dan tidak bisa dicari
                ],

            });
        });
    </script>
@endsection
{{-- Akhir section scripts --}}
