{{-- ============================= --}}
{{-- MODAL BARANG --}}
{{-- Modal untuk memilih barang (dipakai saat tambah/ubah transaksi, dll) --}}
{{-- ============================= --}}
<div class="modal fade" data-bs-backdrop="static" style="overflow-y:scroll;" id="modalBarang">
    {{-- modal-xl: ukuran extra besar, modal-dialog-scrollable: body bisa scroll --}}
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content modal-content-demo">

            {{-- ============================= --}}
            {{-- HEADER MODAL --}}
            {{-- Judul + tombol close (memanggil resetB()) --}}
            {{-- ============================= --}}
            <div class="modal-header">
                <h6 class="modal-title">Pilih Barang</h6>

                {{-- Tombol close (bukan data-bs-dismiss), karena custom logic --}}
                <button onclick="resetB('tambah')" aria-label="Close" class="btn-close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- ============================= --}}
            {{-- BODY MODAL --}}
            {{-- Berisi input hidden + tabel DataTables list barang --}}
            {{-- ============================= --}}
            <div class="modal-body p-4 pb-5">

                {{-- param: penanda konteks pemilihan (tambah/ubah) --}}
                <input type="hidden" value="tambah" name="param">

                {{-- randkey: key random (biasanya untuk tracking state/anti tabrakan input) --}}
                <input type="hidden" id="randkey">

                {{-- Table responsive untuk DataTables --}}
                <div class="table-responsive">
                    <table id="table-2" width="100%" class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
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
                        {{-- Body akan diisi otomatis oleh DataTables via AJAX --}}
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ============================= --}}
{{-- SECTION JS KHUSUS MODAL BARANG --}}
{{-- Untuk handle: reset modal, pilih barang, dan inisialisasi DataTables --}}
{{-- ============================= --}}
@section('formOtherJS')
<script>
    // ==========================================
    // Set randkey saat script dijalankan
    // randkey = string random untuk identifikasi sesi pemilihan
    // ==========================================
    document.getElementById('randkey').value = makeid(10);

    // ==========================================
    // resetB()
    // Menutup modal barang lalu mengembalikan tampilan ke modal asal
    // - Jika param = 'tambah' => balik ke modal tambah (modaldemo8)
    // - Jika param selain itu => balik ke modal edit (Umodaldemo8)
    // ==========================================
    function resetB() {
        // Ambil param konteks dari input hidden
        param = $('input[name="param"]').val();

        if (param == 'tambah') {
            // Hide modal pilih barang
            $('#modalBarang').modal('hide');

            // Pastikan modal tambah terlihat (jika sebelumnya disembunyikan)
            $('#modaldemo8').removeClass('d-none');
        } else {
            // Hide modal pilih barang
            $('#modalBarang').modal('hide');

            // Pastikan modal edit terlihat
            $('#Umodaldemo8').removeClass('d-none');
        }
    }

    // ==========================================
    // pilihBarang(data)
    // Dipanggil saat user memilih barang untuk FORM TAMBAH
    // data didapat dari onclick=pilihBarang(...) di kolom action DataTables
    // ==========================================
    function pilihBarang(data) {
        // Ambil randkey (kalau perlu dipakai)
        const key = $("#randkey").val();

        // Set status (biasanya menandakan bahwa barang sudah dipilih)
        $("#status").val("true");

        // Set nilai ke field form tambah (di luar modal ini)
        $("input[name='kdbarang']").val(data.barang_kode);
        $("#nmbarang").val(data.barang_nama.replace(/_/g, ' '));
        $("#satuan").val(data.satuan_nama.replace(/_/g, ' '));
        $("#jenis").val(data.jenisbarang_nama.replace(/_/g, ' '));

        // Tampilkan modal tambah lagi
        $('#modaldemo8').removeClass('d-none');

        // Tutup modal pilih barang
        $('#modalBarang').modal('hide');
    }

    // ==========================================
    // pilihBarangU(data)
    // Dipanggil saat user memilih barang untuk FORM UBAH/EDIT
    // ==========================================
    function pilihBarangU(data) {
        // Ambil randkey (kalau perlu dipakai)
        const key = $("#randkey").val();

        // Set status khusus edit
        $("#statusU").val("true");

        // Set nilai ke field form edit (di luar modal ini)
        $("input[name='kdbarangU']").val(data.barang_kode);
        $("#nmbarangU").val(data.barang_nama.replace(/_/g, ' '));
        $("#satuanU").val(data.satuan_nama.replace(/_/g, ' '));
        $("#jenisU").val(data.jenisbarang_nama.replace(/_/g, ' '));

        // Tampilkan modal edit lagi
        $('#Umodaldemo8').removeClass('d-none');

        // Tutup modal pilih barang
        $('#modalBarang').modal('hide');
    }

    // ==========================================
    // Inisialisasi DataTables untuk tabel barang (table-2)
    // Server-side: true => data diambil via AJAX dari backend
    // ==========================================
    var table2;
    $(document).ready(function() {
        table2 = $('#table-2').DataTable({
            // Tampilkan indikator processing saat load data
            "processing": true,

            // Mode server-side DataTables (pagination/filter dari server)
            "serverSide": true,

            // Info "Showing X of Y" dimatikan
            "info": false,

            // Default order kosong
            "order": [],

            // ordering dimatikan
            "ordering": false,

            // scroll horizontal dimatikan (beda dengan table utama yang pakai scrollX)
            "scrollX": false,

            // Jumlah baris per halaman
            "pageLength": 10,

            // Allow user ubah page length (meskipun lengthMenu di-comment)
            "lengthChange": true,

            // Konfigurasi AJAX DataTables
            "ajax": {
                // URL endpoint list barang (param di belakang biasanya placeholder)
                "url": "{{url('admin/barang/listbarang')}}/param",

                // Tambahkan data param ke request (tambah/ubah)
                "data": function(d) {
                    d.param = $('input[name="param"]').val();
                }
            },

            // Definisi kolom DataTables sesuai response JSON dari server
            "columns": [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false
                },
                {
                    data: 'img',
                    name: 'barang_foto',
                    searchable: false,
                    orderable: false
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
                    data: 'jenisbarang',
                    name: 'jenisbarang_nama',
                },
                {
                    data: 'satuan',
                    name: 'satuan_nama',
                },
                {
                    data: 'merk',
                    name: 'merk_nama'
                },
                {
                    data: 'totalstok',
                    name: 'barang_stok'
                },
                {
                    data: 'currency',
                    name: 'barang_harga'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });
    });

    // ==========================================
    // makeid(length)
    // Membuat string random dengan huruf besar/kecil/angka
    // Dipakai untuk mengisi randkey
    // ==========================================
    function makeid(length) {
        var result = '';
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;

        // Loop sepanjang length untuk menyusun string random
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        return result;
    }
</script>
@endsection
