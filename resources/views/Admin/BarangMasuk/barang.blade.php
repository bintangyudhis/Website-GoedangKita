<!-- MODAL BARANG --> <!-- Penanda: modal untuk memilih barang dari daftar -->
<div class="modal fade" data-bs-backdrop="static" style="overflow-y:scroll;" id="modalBarang">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup), overflow-y scroll untuk isi panjang -->
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <!-- Dialog modal ukuran extra large + bisa scroll di dalam body -->
        <div class="modal-content modal-content-demo"> <!-- Konten utama modal -->
            <div class="modal-header"> <!-- Header modal -->
                <h6 class="modal-title">Pilih Barang</h6> <!-- Judul modal -->
                <button onclick="resetB('tambah')" aria-label="Close" class="btn-close">
                    <!-- Tombol close: menjalankan resetB() (param 'tambah' di sini tidak dipakai oleh fungsi) -->
                    <span aria-hidden="true">&times;</span> <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body p-4 pb-5"> <!-- Body modal + padding -->
                <input type="hidden" value="tambah" name="param">
                <!-- Hidden param: menandai mode yang sedang aktif (tambah / ubah) -->

                <input type="hidden" id="randkey">
                <!-- Hidden randkey: menyimpan string acak (dipakai/direncanakan untuk identifikasi sesi, meski di fungsi pilihBarang tidak digunakan) -->

                <div class="table-responsive"> <!-- Container tabel responsif -->
                    <table id="table-2" width="100%"
                        class="table table-bordered text-nowrap border-bottom dataTable no-footer dtr-inline collapsed">
                        <!-- Tabel DataTables untuk daftar barang -->
                        <thead> <!-- Head tabel -->
                            <th class="border-bottom-0" width="1%">No</th> <!-- Kolom nomor -->
                            <th class="border-bottom-0">Gambar</th> <!-- Kolom gambar barang -->
                            <th class="border-bottom-0">Kode Barang</th> <!-- Kolom kode -->
                            <th class="border-bottom-0">Nama Barang</th> <!-- Kolom nama -->
                            <th class="border-bottom-0">Jenis</th> <!-- Kolom jenis -->
                            <th class="border-bottom-0">Satuan</th> <!-- Kolom satuan -->
                            <th class="border-bottom-0">Merk</th> <!-- Kolom merk -->
                            <th class="border-bottom-0">Stok</th> <!-- Kolom stok -->
                            <th class="border-bottom-0">Harga</th> <!-- Kolom harga -->
                            <th class="border-bottom-0" width="1%">Action</th> <!-- Kolom aksi pilih -->
                        </thead>
                        <tbody></tbody> <!-- Body tabel kosong, diisi DataTables dari server -->
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@section('formOtherJS') {{-- Section Blade: script JS lain-lain (modal barang + datatable table2) --}}
<script> // Awal script

    document.getElementById('randkey').value = makeid(10);
    // Mengisi input hidden randkey dengan string random sepanjang 10 karakter

    function resetB() { // Fungsi untuk menutup modal barang dan mengembalikan tampilan modal sebelumnya
        param = $('input[name="param"]').val(); // Ambil nilai mode dari hidden input param

        if (param == 'tambah') { // Jika mode tambah
            $('#modalBarang').modal('hide'); // Tutup modal barang
            $('#modaldemo8').removeClass('d-none'); // Tampilkan lagi modal tambah (yang tadi disembunyikan pakai d-none)
        } else { // Jika mode ubah/edit
            $('#modalBarang').modal('hide'); // Tutup modal barang
            $('#Umodaldemo8').removeClass('d-none'); // Tampilkan lagi modal edit
        }
    }

    function pilihBarang(data) { // Fungsi ketika user memilih barang untuk form tambah
        const key = $("#randkey").val();
        // Mengambil randkey (catatan: variable key tidak digunakan lagi setelah ini)

        $("#status").val("true"); // Set status valid (barang sudah dipilih)
        $("input[name='kdbarang']").val(data.barang_kode); // Isi input kode barang pada form tambah
        $("#nmbarang").val(data.barang_nama.replace(/_/g, ' ')); // Isi nama barang, underscore diganti spasi
        $("#satuan").val(data.satuan_nama.replace(/_/g, ' ')); // Isi satuan, underscore diganti spasi
        $("#jenis").val(data.jenisbarang_nama.replace(/_/g, ' ')); // Isi jenis, underscore diganti spasi

        $('#modaldemo8').removeClass('d-none'); // Tampilkan kembali modal tambah
        $('#modalBarang').modal('hide'); // Tutup modal barang
    }

    function pilihBarangU(data) { // Fungsi ketika user memilih barang untuk form edit
        const key = $("#randkey").val();
        // Mengambil randkey (catatan: variable key juga tidak digunakan)

        $("#statusU").val("true"); // Set status valid untuk mode edit
        $("input[name='kdbarangU']").val(data.barang_kode); // Isi kode barang di form edit
        $("#nmbarangU").val(data.barang_nama.replace(/_/g, ' ')); // Isi nama barang edit
        $("#satuanU").val(data.satuan_nama.replace(/_/g, ' ')); // Isi satuan edit
        $("#jenisU").val(data.jenisbarang_nama.replace(/_/g, ' ')); // Isi jenis edit

        $('#Umodaldemo8').removeClass('d-none'); // Tampilkan kembali modal edit
        $('#modalBarang').modal('hide'); // Tutup modal barang
    }

    var table2; // Variabel global untuk DataTable daftar barang

    $(document).ready(function() { // Jalankan saat dokumen siap
        //datatables // Penanda bagian inisialisasi DataTables
        table2 = $('#table-2').DataTable({ // Inisialisasi DataTable pada table-2

            "processing": true, // Tampilkan indikator processing saat load
            "serverSide": true, // Paging/filter dari server
            "info": false, // Sembunyikan info "Showing X to Y of Z"
            "order": [], // Tidak set default order
            "ordering": false, // Matikan sorting kolom (klik header tidak mengurutkan)
            "scrollX": false, // Tidak pakai scroll horizontal
            // "lengthMenu": [ // Opsi pilihan jumlah data per halaman (dikomentari)
            //     [5, 10, 25, 50, 100],
            //     [5, 10, 25, 50, 100]
            // ],
            "pageLength": 10, // Default 10 baris per halaman
            "lengthChange": true, // User boleh ubah page length

            "ajax": { // Sumber data dari server
                "url": "{{url('admin/barang/listbarang')}}/param",
                // URL endpoint list barang (ada /param di belakang, kemungkinan placeholder)

                "data": function(d) { // Menambahkan parameter ke request AJAX DataTables
                    d.param = $('input[name="param"]').val();
                    // Kirim mode (tambah/ubah) ke server, biasanya untuk filter stok/aturan tertentu
                }
            },

            "columns": [ // Mapping kolom DataTables ke field JSON
                {
                    data: 'DT_RowIndex', // Index otomatis dari server (misal Yajra)
                    name: 'DT_RowIndex',
                    searchable: false // Tidak bisa dicari
                },
                {
                    data: 'img', // Kolom gambar (biasanya HTML <img>)
                    name: 'barang_foto',
                    searchable: false,
                    orderable: false // Tidak bisa diurutkan
                },
                {
                    data: 'barang_kode', // Kolom kode barang
                    name: 'barang_kode',
                },
                {
                    data: 'barang_nama', // Kolom nama barang
                    name: 'barang_nama',
                },
                {
                    data: 'jenisbarang', // Kolom jenis (display)
                    name: 'jenisbarang_nama',
                },
                {
                    data: 'satuan', // Kolom satuan (display)
                    name: 'satuan_nama',
                },
                {
                    data: 'merk', // Kolom merk
                    name: 'merk_nama'
                },
                {
                    data: 'totalstok', // Kolom total stok
                    name: 'barang_stok'
                },
                {
                    data: 'currency', // Kolom harga dalam format currency
                    name: 'barang_harga'
                },
                {
                    data: 'action', // Kolom action (tombol pilih)
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });
    });

    function makeid(length) { // Fungsi membuat string acak
        var result = ''; // Variabel hasil
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        // Kumpulan karakter yang boleh dipakai
        var charactersLength = characters.length; // Panjang kumpulan karakter
        for (var i = 0; i < length; i++) { // Loop sepanjang length
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            // Ambil karakter random dan gabungkan ke result
        }
        return result; // Kembalikan string random
    }

</script>
@endsection {{-- Akhir section formOtherJS --}}
