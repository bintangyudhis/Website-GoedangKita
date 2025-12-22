{{-- Menggunakan layout utama dari folder Master/Layout --}}
{{-- Mengirim variable title ke layout supaya bisa dipakai di <title> / header layout --}}
@extends('Master.Layouts.app', ['title' => $title])

@section('content')
{{-- ============================= --}}
{{-- PAGE HEADER --}}
{{-- Menampilkan judul halaman + breadcrumb navigasi --}}
{{-- ============================= --}}
<div class="page-header">
    {{-- Judul halaman --}}
    <h1 class="page-title">{{$title}}</h1>

    {{-- Breadcrumb untuk menunjukkan posisi halaman --}}
    <div>
        <ol class="breadcrumb">
            <li class="breadcrumb-item text-gray">Master Data</li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
        </ol>
    </div>
</div>
{{-- PAGE-HEADER END --}}

{{-- ============================= --}}
{{-- ROW: KONTEN UTAMA (CARD + TABLE) --}}
{{-- ============================= --}}
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card">

            {{-- Header card (judul + tombol tambah jika punya hak akses) --}}
            <div class="card-header justify-content-between">
                <h3 class="card-title">Data</h3>

                {{-- Tombol Tambah hanya tampil jika user punya hak tambah --}}
                @if($hakTambah > 0)
                <div>
                    {{-- onclick generateID() untuk membuat kode barang otomatis --}}
                    {{-- data-bs-toggle/modal untuk membuka modal tambah (#modaldemo8) --}}
                    <a class="modal-effect btn btn-primary-light"
                       onclick="generateID()"
                       data-bs-effect="effect-super-scaled"
                       data-bs-toggle="modal"
                       href="#modaldemo8">
                        Tambah Data <i class="fe fe-plus"></i>
                    </a>
                </div>
                @endif
            </div>

            {{-- Body card berisi tabel DataTables --}}
            <div class="card-body">
                <div class="table-responsive">

                    {{-- Table utama untuk DataTables --}}
                    {{-- tbody akan diisi otomatis oleh DataTables via AJAX --}}
                    <table id="table-1" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <thead>
                            {{-- Header kolom tabel --}}
                            <th class="border-bottom-0" width="1%">No</th>
                            <th class="border-bottom-0">Gambar</th>
                            <th class="border-bottom-0">Kode Barang</th>
                            <th class="border-bottom-0">Nama Barang</th>
                            <th class="border-bottom-0">Jenis</th>
                            <th class="border-bottom-0">Satuan</th>
                            <th class="border-bottom-0">Merk</th>
                            <th class="border-bottom-0">Stok</th>
                            <th class="border-bottom-0">Harga</th>
                            <th class="border-bottom-0" width="1%">Action</th>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
{{-- END ROW --}}

{{-- ============================= --}}
{{-- INCLUDE PARTIAL VIEW (MODAL) --}}
{{-- Setiap include adalah modal terpisah: tambah, edit, hapus, gambar --}}
{{-- ============================= --}}
@include('Admin.Barang.tambah', ['jenisbarang' => $jenisbarang, 'satuan' => $satuan, 'merk' => $merk])
@include('Admin.Barang.edit',  ['jenisbarang' => $jenisbarang, 'satuan' => $satuan, 'merk' => $merk])
@include('Admin.Barang.hapus')
@include('Admin.Barang.gambar')

{{-- ============================= --}}
{{-- SCRIPT HELPER: GENERATE ID + ISI DATA KE MODAL --}}
{{-- Fungsi-fungsi ini biasanya dipanggil dari tombol pada kolom Action DataTables --}}
{{-- ============================= --}}
<script>
    // Membuat kode barang otomatis berdasarkan timestamp (unik)
    function generateID(){
        id = new Date().getTime(); // ambil timestamp (ms)
        $("input[name='kode']").val("BRG-"+id); // set input kode pada form tambah
    }

    // Mengisi data ke form modal EDIT
    // data ini dikirim dari tombol action (onclick=update(...))
    function update(data){
        $("input[name='idbarangU']").val(data.barang_id); // id barang (hidden)
        $("input[name='kodeU']").val(data.barang_kode); // kode barang (readonly)
        $("input[name='namaU']").val(data.barang_nama.replace(/_/g, ' ')); // slug -> normal text
        $("select[name='jenisbarangU']").val(data.jenisbarang_id); // pilih jenis barang
        $("select[name='satuanU']").val(data.satuan_id); // pilih satuan
        $("select[name='merkU']").val(data.merk_id); // pilih merk
        $("input[name='stokU']").val(data.barang_stok); // stok awal
        $("input[name='hargaU']").val(data.barang_harga.replace(/_/g, ' ')); // harga (jaga-jaga kalau ada underscore)

        // Jika gambar bukan default, tampilkan gambar dari storage
        if(data.barang_gambar != 'image.png'){
            $("#outputImgU").attr("src", "{{asset('storage/barang/')}}"+"/"+data.barang_gambar);
        }
    }

    // Mengisi data ke modal HAPUS
    function hapus(data) {
        $("input[name='idbarang']").val(data.barang_id); // set id barang yang akan dihapus
        // Menampilkan nama barang pada teks konfirmasi
        $("#vbarang").html("barang " + "<b>" + data.barang_nama.replace(/_/g, ' ') + "</b>");
    }

    // Menampilkan gambar pada modal GAMBAR
    function gambar(data) {
        // Jika gambar bukan default, ambil dari storage
        if(data.barang_gambar != 'image.png'){
            $("#outputImgG").attr("src", "{{asset('storage/barang/')}}"+"/"+data.barang_gambar);
        }else{
            // Jika default, pakai aset default
            $("#outputImgG").attr("src", "{{url('/assets/default/barang/image.png')}}");
        }
    }

    // Helper notifikasi menggunakan SweetAlert
    function validasi(judul, status) {
        swal({
            title: judul,
            type: status,
            confirmButtonText: "Iya"
        });
    }
</script>
@endsection

{{-- ============================= --}}
{{-- SECTION SCRIPTS: KONFIGURASI DATATABLES --}}
{{-- Biasanya layout utama memanggil @yield('scripts') di bagian bawah --}}
{{-- ============================= --}}
@section('scripts')
<script>
    // Set header CSRF untuk semua request AJAX (Laravel butuh token ini)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Variabel global untuk instance datatable (agar bisa dipanggil reload di modal)
    var table;

    $(document).ready(function() {
        // Inisialisasi DataTables
        table = $('#table-1').DataTable({
            "processing": true,   // tampilkan indikator loading saat proses
            "serverSide": true,   // mode server-side (data diambil via AJAX)
            "info": true,         // tampilkan info jumlah data
            "order": [],          // default: tidak ada order awal
            "stateSave":true,     // simpan state tabel (paging, search, dll)
            "scrollX": true,      // aktifkan scroll horizontal (kolom banyak)

            // pilihan jumlah data per halaman
            "lengthMenu": [
                [5, 10, 25, 50, 100],
                [5, 10, 25, 50, 100]
            ],
            "pageLength": 10,     // default 10 data per halaman
            lengthChange: true,   // izinkan user mengganti page length

            // AJAX sumber data untuk DataTables (route backend)
            "ajax": {
                "url": "{{route('barang.getbarang')}}",
            },

            // Mapping kolom DataTables ke field response JSON
            "columns": [
                {
                    data: 'DT_RowIndex', // index otomatis dari DataTables Yajra
                    name: 'DT_RowIndex',
                    searchable: false    // index tidak untuk search
                },
                {
                    data: 'img',         // kolom HTML gambar dari controller
                    name: 'barang_gambar',
                    searchable: false,
                    orderable: false     // gambar tidak perlu diurutkan
                },
                {
                    data: 'barang_kode',
                    name: 'barang_kode',
                },
                {
                    data: 'barang_nama',
                    name: 'barang_nama',
                },
                {
                    data: 'jenisbarang', // hasil format nama jenis dari controller
                    name: 'jenisbarang_nama',
                },
                {
                    data: 'satuan',      // hasil format nama satuan dari controller
                    name: 'satuan_nama',
                },
                {
                    data: 'merk',        // hasil format nama merk dari controller
                    name: 'merk_nama',
                },
                {
                    data: 'totalstok',   // stok total (HTML warna) dari controller
                    name: 'barang_stok',
                },
                {
                    data: 'currency',    // format rupiah dari controller
                    name: 'barang_harga'
                },
                {
                    data: 'action',      // tombol action (HTML) dari controller
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });
    });
</script>
@endsection
