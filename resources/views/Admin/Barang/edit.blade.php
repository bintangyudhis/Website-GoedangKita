{{-- ============================= --}}
{{-- MODAL EDIT (UBAH BARANG) --}}
{{-- ============================= --}}
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    {{-- Container modal (ukuran besar + posisi tengah) --}}
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        {{-- Isi modal --}}
        <div class="modal-content modal-content-demo">

            {{-- Header modal: judul + tombol close --}}
            <div class="modal-header">
                {{-- Judul modal --}}
                <h6 class="modal-title">Ubah Barang</h6>

                {{-- Tombol close: saat ditutup juga memanggil resetU() agar form bersih --}}
                <button onclick="resetU()"
                        aria-label="Close"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body modal: form input --}}
            <div class="modal-body">
                {{-- Hidden input: menyimpan id barang yang sedang diedit --}}
                <input type="hidden" name="idbarangU">

                <div class="row">

                    {{-- Kolom kiri: input data barang --}}
                    <div class="col-md-7">

                        {{-- Input Kode Barang (readonly karena biasanya kode tidak diubah) --}}
                        <div class="form-group">
                            <label for="kodeU" class="form-label">
                                Kode Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="kodeU" readonly class="form-control">
                        </div>

                        {{-- Input Nama Barang --}}
                        <div class="form-group">
                            <label for="namaU" class="form-label">
                                Nama Barang <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="namaU" class="form-control">
                        </div>

                        {{-- Select Jenis Barang (data dari controller: $jenisbarang) --}}
                        <div class="form-group">
                            <label for="jenisbarangU" class="form-label">Jenis Barang</label>
                            <select name="jenisbarangU" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($jenisbarang as $jb)
                                    <option value="{{$jb->jenisbarang_id}}">{{$jb->jenisbarang_nama}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select Satuan Barang (data dari controller: $satuan) --}}
                        <div class="form-group">
                            <label for="satuanU" class="form-label">Satuan Barang</label>
                            <select name="satuanU" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($satuan as $s)
                                <option value="{{$s->satuan_id}}">{{$s->satuan_nama}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select Merk Barang (data dari controller: $merk) --}}
                        <div class="form-group">
                            <label for="merkU" class="form-label">Merk Barang</label>
                            <select name="merkU" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($merk as $m)
                                <option value="{{$m->merk_id}}">{{$m->merk_nama}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Stok Awal (wajib) --}}
                        {{-- oninput: membatasi hanya angka dan 1 titik desimal (kalau ada) --}}
                        <div class="form-group">
                            <label for="stokU" class="form-label">
                                Stok Awal <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                   name="stokU"
                                   class="form-control">
                        </div>

                        {{-- Input Harga (wajib) --}}
                        {{-- oninput: membatasi hanya angka dan 1 titik desimal (kalau ada) --}}
                        <div class="form-group">
                            <label for="hargaU" class="form-label">
                                Harga <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                   name="hargaU"
                                   class="form-control">
                        </div>
                    </div>

                    {{-- Kolom kanan: foto barang --}}
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="title" class="form-label">Foto</label>

                            {{-- Preview gambar default (akan diganti saat pilih file / saat data edit di-load) --}}
                            <center>
                                <img src="{{url('/assets/default/barang/image.png')}}"
                                     width="80%"
                                     alt="profile-user"
                                     id="outputImgU"
                                     class="">
                            </center>

                            {{-- Input file untuk upload foto --}}
                            {{-- onchange: validasi ekstensi & ukuran + set preview gambar --}}
                            <input class="form-control mt-5"
                                   id="GetFileU"
                                   name="photoU"
                                   type="file"
                                   onchange="VerifyFileNameAndFileSizeU()"
                                   accept=".png,.jpeg,.jpg,.svg">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer modal: tombol submit / loading / batal --}}
            <div class="modal-footer">
                {{-- Tombol loader (ditampilkan saat proses AJAX submit berjalan) --}}
                <button class="btn btn-success d-none"
                        id="btnLoaderU"
                        type="button"
                        disabled="">
                    <span class="spinner-border spinner-border-sm me-1"
                          role="status"
                          aria-hidden="true"></span>
                    Loading...
                </button>

                {{-- Tombol simpan (klik → checkFormU() untuk validasi) --}}
                <a href="javascript:void(0)"
                   onclick="checkFormU()"
                   id="btnSimpanU"
                   class="btn btn-success">
                    Simpan Perubahan <i class="fe fe-check"></i>
                </a>

                {{-- Tombol batal (reset form + tutup modal) --}}
                <a href="javascript:void(0)"
                   class="btn btn-light"
                   onclick="resetU()"
                   data-bs-dismiss="modal">
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ============================= --}}
{{-- SECTION SCRIPT: FORM EDIT (AJAX) --}}
{{-- ============================= --}}
@section('formEditJS')
<script>
    /**
     * checkFormU()
     * Fungsi validasi sebelum submit.
     * - Ambil value input wajib
     * - Jika kosong: tampilkan alert validasi + kasih class is-invalid
     * - Jika lolos: lanjut submitFormU()
     */
    function checkFormU() {
        // Ambil nilai dari input (kode, nama, harga, stok)
        const kode  = $("input[name='kodeU']").val();
        const nama  = $("input[name='namaU']").val();
        const harga = $("input[name='hargaU']").val();
        const stok  = $("input[name='stokU']").val();

        // Aktifkan UI loading (sembunyikan tombol simpan, tampilkan spinner)
        setLoadingU(true);

        // Reset state invalid agar validasi sebelumnya tidak menumpuk
        resetValidU();

        // Validasi: kode wajib diisi
        if (kode == "") {
            validasi('Kode Barang wajib di isi!', 'warning');
            $("input[name='kodeU']").addClass('is-invalid');
            setLoadingU(false);
            return false;

        // Validasi: nama wajib diisi
        } else if (nama == "") {
            validasi('Nama Barang wajib di isi!', 'warning');
            $("input[name='namaU']").addClass('is-invalid');
            setLoadingU(false);
            return false;

        // Validasi: harga wajib diisi
        } else if (harga == "") {
            validasi('Harga Barang wajib di isi!', 'warning');
            $("input[name='hargaU']").addClass('is-invalid');
            setLoadingU(false);
            return false;

        // Validasi: stok wajib diisi
        } else if (stok == "") {
            validasi('Stok Awal wajib di isi!', 'warning');

            // Menandai input stok sebagai invalid (sesuai name yang dipakai pada input)
            $("input[name='stok']").addClass('is-invalid');

            setLoadingU(false);
            return false;

        // Jika semua valid → submit
        } else {
            submitFormU();
        }
    }

    /**
     * submitFormU()
     * Fungsi submit data update ke server pakai AJAX + FormData.
     * - Ambil value input
     * - Susun FormData (termasuk file)
     * - Kirim POST ke endpoint proses_ubah/{id}
     * - Jika sukses: tampil swal, tutup modal, reload table, reset form
     */
    function submitFormU() {
        // Ambil data yang akan dikirim
        const id         = $("input[name='idbarangU']").val();
        const kode       = $("input[name='kodeU']").val();
        const nama       = $("input[name='namaU']").val();
        const jenisbarang= $("select[name='jenisbarangU']").val();
        const satuan     = $("select[name='satuanU']").val();
        const merk       = $("select[name='merkU']").val();
        const harga      = $("input[name='hargaU']").val();
        const stok       = $("input[name='stokU']").val();

        // Ambil file (jika user memilih file di input file)
        const foto = $('#GetFileU')[0].files;

        // Siapkan FormData untuk mengirim data + file (multipart/form-data)
        var fd = new FormData();

        // Append data file foto (index ke-0 karena hanya 1 file)
        // Catatan: ini akan mengirim file, atau undefined jika user tidak pilih file
        fd.append('foto', foto[0]);

        // Append data text ke FormData
        fd.append('kode', kode);
        fd.append('nama', nama);
        fd.append('jenisbarang', jenisbarang);
        fd.append('satuan', satuan);
        fd.append('merk', merk);
        fd.append('harga', harga);
        fd.append('stok', stok);

        // Kirim request AJAX
        $.ajax({
            type: 'POST', // method POST sesuai route/proses_ubah
            url: "{{url('admin/barang/proses_ubah')}}/" + id, // endpoint + id barang
            processData: false, // wajib false untuk FormData
            contentType: false, // wajib false untuk FormData
            dataType: 'json', // respon dari server diharapkan JSON
            data: fd, // payload FormData
            success: function(data) {
                // Jika sukses: tampilkan notifikasi swal
                swal({
                    title: "Berhasil diubah!",
                    type: "success"
                });

                // Tutup modal edit
                $('#Umodaldemo8').modal('toggle');

                // Reload DataTables tanpa reset pagination
                table.ajax.reload(null, false);

                // Reset form edit
                resetU();
            }
        });
    }

    /**
     * resetValidU()
     * Menghapus class is-invalid dari semua input agar tampilan normal lagi.
     */
    function resetValidU() {
        $("input[name='kodeU']").removeClass('is-invalid');
        $("input[name='namaU']").removeClass('is-invalid');
        $("select[name='jenisbarangU']").removeClass('is-invalid');
        $("select[name='satuanU']").removeClass('is-invalid');
        $("select[name='merkU']").removeClass('is-invalid');
        $("input[name='hargaU']").removeClass('is-invalid');
        $("input[name='stokU']").removeClass('is-invalid');
    };

    /**
     * resetU()
     * Mengosongkan semua input pada modal edit + reset preview gambar ke default.
     * Biasanya dipanggil saat modal ditutup atau setelah sukses submit.
     */
    function resetU() {
        // Reset validasi (hapus is-invalid)
        resetValidU();

        // Kosongkan field-field form edit
        $("input[name='idbarangU']").val('');
        $("input[name='noU']").val('');
        $("input[name='namaU']").val('');
        $("select[name='jenisbarangU']").val('');
        $("select[name='satuanU']").val('');
        $("select[name='merkU']").val('');
        $("input[name='hargaU']").val('');
        $("input[name='stokU']").val('');

        // Reset preview gambar ke default
        $("#outputImgU").attr("src", "{{url('/assets/default/barang/image.png')}}");

        // Reset input file
        $("#GetFileU").val('');

        // Matikan loading (tombol simpan muncul lagi)
        setLoadingU(false);
    }

    /**
     * setLoadingU(bool)
     * Mengatur tampilan tombol simpan vs tombol loading.
     * - true  → tampilkan loader, sembunyikan tombol simpan
     * - false → tampilkan tombol simpan, sembunyikan loader
     */
    function setLoadingU(bool) {
        if (bool == true) {
            $('#btnLoaderU').removeClass('d-none');
            $('#btnSimpanU').addClass('d-none');
        } else {
            $('#btnSimpanU').removeClass('d-none');
            $('#btnLoaderU').addClass('d-none');
        }
    }

    /**
     * fileIsValidU(fileName)
     * Mengecek ekstensi file supaya hanya gambar tertentu yang diterima.
     * Return: true jika valid, false jika tidak.
     */
    function fileIsValidU(fileName) {
        // Ambil ekstensi dari fileName (contoh: "foto.jpg" → "jpg")
        var ext = fileName.match(/\.([^\.]+)$/)[1];
        ext = ext.toLowerCase();

        // Default valid
        var isValid = true;

        // Switch untuk whitelist ekstensi
        switch (ext) {
            case 'png':
            case 'jpeg':
            case 'jpg':
            case 'svg':
                break;
            default:
                // Jika ekstensi tidak valid, kosongkan value (prevent upload)
                this.value = '';
                isValid = false;
        }

        return isValid;
    }

    /**
     * VerifyFileNameAndFileSizeU()
     * Validasi file upload:
     * - cek file ada atau tidak
     * - cek ekstensi sesuai whitelist
     * - cek ukuran file (maksimum)
     * - jika valid, set preview gambar ke file yang dipilih
     */
    function VerifyFileNameAndFileSizeU() {
        // Ambil file pertama dari input file
        var file = document.getElementById('GetFileU').files[0];

        // Jika file ada
        if (file != null) {
            var fileName = file.name;

            // Validasi ekstensi
            if (fileIsValidU(fileName) == false) {
                validasi('Format bukan gambar!', 'warning');
                document.getElementById('GetFileU').value = null;
                return false;
            }

            // Variabel content (tidak dipakai di sini, tapi ada di kode awal)
            var content;

            // Cek ukuran file (byte → MB)
            var size = file.size;

            // Jika ukuran lebih dari 3MB → tampilkan warning dan reset input file
            if ((size != null) && ((size / (1024 * 1024)) > 3)) {
                validasi('Ukuran Maximum 1 MB', 'warning');
                document.getElementById('GetFileU').value = null;
                return false;
            }

            // Ambil ekstensi (tidak wajib dipakai, tapi disimpan sesuai kode awal)
            var ext = fileName.match(/\.([^\.]+)$/)[1];
            ext = ext.toLowerCase();

            // Set preview image dengan object URL dari file yang dipilih
            document.getElementById('outputImgU').src = window.URL.createObjectURL(file);

            return true;
        } else {
            // Jika file null (tidak ada file dipilih)
            return false;
        }
    }
</script>
@endsection
