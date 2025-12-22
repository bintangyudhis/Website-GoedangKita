{{-- ============================= --}}
{{-- MODAL TAMBAH BARANG --}}
{{-- Modal bootstrap untuk input data barang baru --}}
{{-- ============================= --}}
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    {{-- modal-lg: ukuran besar, modal-dialog-centered: posisi tengah --}}
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-content-demo">

            {{-- ============================= --}}
            {{-- HEADER MODAL --}}
            {{-- Judul + tombol close (yang juga memanggil reset()) --}}
            {{-- ============================= --}}
            <div class="modal-header">
                <h6 class="modal-title">Tambah Barang</h6>

                {{-- Klik close: reset field form + tutup modal --}}
                <button onclick="reset()" aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- ============================= --}}
            {{-- BODY MODAL (FORM INPUT) --}}
            {{-- Berisi field-field barang: kode, nama, jenis, satuan, merk, harga, foto --}}
            {{-- ============================= --}}
            <div class="modal-body">
                <div class="row">

                    {{-- Kolom kiri: input text + select --}}
                    <div class="col-md-7">

                        {{-- Input Kode Barang (readonly karena digenerate otomatis) --}}
                        <div class="form-group">
                            <label for="kode" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                            <input type="text" name="kode" readonly class="form-control">
                        </div>

                        {{-- Input Nama Barang --}}
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control">
                        </div>

                        {{-- Select Jenis Barang (opsional) --}}
                        <div class="form-group">
                            <label for="jenisbarang" class="form-label">Jenis Barang</label>
                            <select name="jenisbarang" class="form-control">
                                <option value="">-- Pilih --</option>
                                {{-- Loop data jenisbarang dari controller --}}
                                @foreach ($jenisbarang as $jb)
                                    <option value="{{$jb->jenisbarang_id}}">{{$jb->jenisbarang_nama}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select Satuan Barang (opsional) --}}
                        <div class="form-group">
                            <label for="satuan" class="form-label">Satuan Barang</label>
                            <select name="satuan" class="form-control">
                                <option value="">-- Pilih --</option>
                                {{-- Loop data satuan dari controller --}}
                                @foreach ($satuan as $s)
                                <option value="{{$s->satuan_id}}">{{$s->satuan_nama}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Select Merk Barang (opsional) --}}
                        <div class="form-group">
                            <label for="merk" class="form-label">Merk Barang</label>
                            <select name="merk" class="form-control">
                                <option value="">-- Pilih --</option>
                                {{-- Loop data merk dari controller --}}
                                @foreach ($merk as $m)
                                <option value="{{$m->merk_id}}">{{$m->merk_nama}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input Harga (wajib) --}}
                        {{-- oninput: membatasi input hanya angka/titik --}}
                        <div class="form-group">
                            <label for="harga" class="form-label">Harga Barang <span class="text-danger">*</span></label>
                            <input type="text"
                                   oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                   name="harga"
                                   class="form-control">
                        </div>
                    </div>

                    {{-- Kolom kanan: preview foto + input upload --}}
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="title" class="form-label">Foto</label>

                            {{-- Preview gambar default sebelum upload --}}
                            <center>
                                <img src="{{url('/assets/default/barang/image.png')}}"
                                     width="80%"
                                     alt="profile-user"
                                     id="outputImg"
                                     class="">
                            </center>

                            {{-- Input file upload foto --}}
                            {{-- onchange memanggil VerifyFileNameAndFileSize() untuk validasi ekstensi & ukuran --}}
                            <input class="form-control mt-5"
                                   id="GetFile"
                                   name="photo"
                                   type="file"
                                   onchange="VerifyFileNameAndFileSize()"
                                   accept=".png,.jpeg,.jpg,.svg">
                        </div>
                    </div>

                </div>
            </div>

            {{-- ============================= --}}
            {{-- FOOTER MODAL (AKSI) --}}
            {{-- Tombol simpan + cancel + loader --}}
            {{-- ============================= --}}
            <div class="modal-footer">

                {{-- Tombol loader (disembunyikan default), muncul saat proses submit --}}
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                {{-- Tombol simpan: memanggil checkForm() untuk validasi sebelum submit AJAX --}}
                <a href="javascript:void(0)" onclick="checkForm()" id="btnSimpan" class="btn btn-primary">
                    Simpan <i class="fe fe-check"></i>
                </a>

                {{-- Tombol batal: reset field + tutup modal --}}
                <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- ============================= --}}
{{-- SECTION JS KHUSUS MODAL TAMBAH --}}
{{-- Biasanya di-yield di layout / view utama pada posisi yang sesuai --}}
{{-- ============================= --}}
@section('formTambahJS')
<script>
    // ==========================================
    // checkForm()
    // Validasi sederhana input wajib sebelum submit
    // ==========================================
    function checkForm() {
        const kode  = $("input[name='kode']").val();   // ambil kode barang
        const nama  = $("input[name='nama']").val();   // ambil nama barang
        const harga = $("input[name='harga']").val();  // ambil harga barang

        setLoading(true);  // tampilkan loader
        resetValid();      // reset status invalid sebelum validasi ulang

        // Validasi: kode wajib terisi
        if (kode == "") {
            validasi('Kode Barang wajib di isi!', 'warning');
            $("input[name='kode']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        // Validasi: nama wajib terisi
        else if (nama == "") {
            validasi('Nama Barang wajib di isi!', 'warning');
            $("input[name='nama']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        // Validasi: harga wajib terisi
        else if (harga == "") {
            validasi('Harga Barang wajib di isi!', 'warning');
            $("input[name='harga']").addClass('is-invalid');
            setLoading(false);
            return false;
        }
        // Jika lolos validasi, lanjut submit AJAX
        else {
            submitForm();
        }
    }

    // ==========================================
    // submitForm()
    // Mengirim data tambah barang via AJAX FormData
    // ==========================================
    function submitForm() {
        // Ambil nilai input form
        const kode       = $("input[name='kode']").val();
        const nama       = $("input[name='nama']").val();
        const jenisbarang= $("select[name='jenisbarang']").val();
        const satuan     = $("select[name='satuan']").val();
        const merk       = $("select[name='merk']").val();
        const harga      = $("input[name='harga']").val();

        // Ambil file foto (jika ada)
        const foto = $('#GetFile')[0].files;

        // Buat FormData untuk upload file + data lainnya
        var fd = new FormData();

        // Append data (yang dikirim ke controller)
        fd.append('foto', foto[0]);          // file foto (bisa undefined kalau user tidak pilih file)
        fd.append('kode', kode);             // kode barang
        fd.append('nama', nama);             // nama barang
        fd.append('jenisbarang', jenisbarang); // id jenis barang
        fd.append('satuan', satuan);         // id satuan
        fd.append('merk', merk);             // id merk
        fd.append('harga', harga);           // harga barang

        // AJAX request untuk simpan data barang
        $.ajax({
            type: 'POST',
            url: "{{route('barang.store')}}", // route backend untuk proses tambah
            processData: false,               // wajib false karena pakai FormData
            contentType: false,               // wajib false karena pakai FormData
            dataType: 'json',
            data: fd,
            success: function(data) {
                // Tutup modal
                $('#modaldemo8').modal('toggle');

                // Notifikasi sukses
                swal({
                    title: "Berhasil ditambah!",
                    type: "success"
                });

                // Reload data tabel tanpa reset pagination
                table.ajax.reload(null, false);

                // Reset form tambah
                reset();
            }
        });
    }

    // ==========================================
    // resetValid()
    // Menghapus class is-invalid pada semua input/select
    // ==========================================
    function resetValid() {
        $("input[name='kode']").removeClass('is-invalid');
        $("input[name='nama']").removeClass('is-invalid');
        $("select[name='jenisbarang']").removeClass('is-invalid');
        $("select[name='satuan']").removeClass('is-invalid');
        $("select[name='merk']").removeClass('is-invalid');
        $("input[name='harga']").removeClass('is-invalid');
    };

    // ==========================================
    // reset()
    // Mengosongkan semua field + reset preview gambar + loader
    // ==========================================
    function reset() {
        resetValid(); // bersihkan error state

        // kosongkan value input & select
        $("input[name='kode']").val('');
        $("input[name='nama']").val('');
        $("select[name='jenisbarang']").val('');
        $("select[name='satuan']").val('');
        $("select[name='merk']").val('');
        $("input[name='harga']").val('');

        // kembalikan preview gambar ke default
        $("#outputImg").attr("src", "{{url('/assets/default/barang/image.png')}}");

        // kosongkan input file
        $("#GetFile").val('');

        // matikan mode loading
        setLoading(false);
    }

    // ==========================================
    // setLoading(bool)
    // Mengatur tampilan tombol simpan vs loader
    // ==========================================
    function setLoading(bool) {
        if (bool == true) {
            $('#btnLoader').removeClass('d-none'); // tampilkan loader
            $('#btnSimpan').addClass('d-none');    // sembunyikan tombol simpan
        } else {
            $('#btnSimpan').removeClass('d-none'); // tampilkan tombol simpan
            $('#btnLoader').addClass('d-none');    // sembunyikan loader
        }
    }

    // ==========================================
    // fileIsValid(fileName)
    // Validasi ekstensi file upload (png/jpeg/jpg/svg)
    // ==========================================
    function fileIsValid(fileName) {
        var ext = fileName.match(/\.([^\.]+)$/)[1]; // ambil ekstensi
        ext = ext.toLowerCase();

        var isValid = true;
        switch (ext) {
            case 'png':
            case 'jpeg':
            case 'jpg':
            case 'svg':
                break; // valid
            default:
                this.value = '';
                isValid = false; // tidak valid
        }
        return isValid;
    }

    // ==========================================
    // VerifyFileNameAndFileSize()
    // Validasi file yang dipilih:
    // - memastikan format gambar
    // - membatasi ukuran max (di sini dicek > 3MB)
    // - menampilkan preview gambar ke <img id="outputImg">
    // ==========================================
    function VerifyFileNameAndFileSize() {
        var file = document.getElementById('GetFile').files[0];

        // Jika user memilih file
        if (file != null) {
            var fileName = file.name;

            // Validasi ekstensi file
            if (fileIsValid(fileName) == false) {
                validasi('Format bukan gambar!', 'warning');
                document.getElementById('GetFile').value = null;
                return false;
            }

            // Validasi ukuran file
            var size = file.size;
            if ((size != null) && ((size / (1024 * 1024)) > 3)) {
                validasi('Ukuran Maximum 1 MB', 'warning'); // (teks masih 1MB, tapi ceknya 3MB)
                document.getElementById('GetFile').value = null;
                return false;
            }

            // Jika lolos validasi, tampilkan preview gambar
            document.getElementById('outputImg').src = window.URL.createObjectURL(file);
            return true;
        }
        // Jika tidak ada file dipilih
        else {
            return false;
        }
    }
</script>
@endsection
