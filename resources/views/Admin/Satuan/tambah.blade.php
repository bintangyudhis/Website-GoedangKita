<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    {{-- Modal Bootstrap untuk form tambah satuan --}}
    {{-- data-bs-backdrop="static" → modal tidak tertutup saat klik area luar --}}

    <div class="modal-dialog modal-dialog-centered" role="document">
        {{-- Dialog modal ditampilkan di tengah layar --}}

        <div class="modal-content modal-content-demo">
            {{-- Konten utama modal --}}

            <div class="modal-header">
                {{-- Bagian header modal --}}

                <h6 class="modal-title">Tambah Satuan</h6>
                {{-- Judul modal --}}

                <button aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal">
                    {{-- Tombol close untuk menutup modal --}}
                    <span aria-hidden="true">&times;</span>
                    {{-- Ikon close (X) --}}
                </button>
            </div>

            <div class="modal-body">
                {{-- Bagian body modal (isi form) --}}

                <div class="form-group">
                    {{-- Form group input satuan --}}

                    <label for="satuan" class="form-label">
                        Nama Satuan <span class="text-danger">*</span>
                        {{-- Label input + tanda wajib --}}
                    </label>

                    <input type="text" name="satuan" class="form-control" placeholder="">
                    {{-- Input text untuk memasukkan nama satuan --}}
                </div>

                <div class="form-group">
                    {{-- Form group textarea keterangan --}}

                    <label for="ket" class="form-label">Keterangan</label>
                    {{-- Label keterangan (opsional) --}}

                    <textarea name="ket" class="form-control" rows="4"></textarea>
                    {{-- Textarea untuk keterangan satuan --}}
                </div>
            </div>

            <div class="modal-footer">
                {{-- Bagian footer modal (tombol aksi) --}}

                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    {{-- Tombol loader: muncul saat proses simpan berlangsung --}}
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    {{-- Ikon spinner kecil --}}
                    Loading...
                    {{-- Teks loading --}}
                </button>

                <a href="javascript:void(0)" onclick="checkForm()" id="btnSimpan" class="btn btn-primary">
                    {{-- Tombol simpan: memanggil fungsi checkForm() dulu --}}
                    Simpan <i class="fe fe-check"></i>
                    {{-- Icon centang --}}
                </a>

                <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">
                    {{-- Tombol batal: reset form lalu tutup modal --}}
                    Batal <i class="fe fe-x"></i>
                    {{-- Icon silang --}}
                </a>
            </div>
        </div>
    </div>
</div>


@section('formTambahJS')
{{-- Section Blade khusus JavaScript untuk form tambah satuan --}}
<script>

    function checkForm() {
        // Fungsi validasi sebelum data disimpan

        const satuan = $("input[name='satuan']").val();
        // Ambil nilai input nama satuan

        setLoading(true);
        // Aktifkan loading (tampilkan loader, sembunyikan tombol simpan)

        resetValid();
        // Hapus tanda invalid sebelumnya (jika ada)

        if (satuan == "") {
            // Jika nama satuan kosong

            validasi('Nama Satuan wajib di isi!', 'warning');
            // Tampilkan alert peringatan

            $("input[name='satuan']").addClass('is-invalid');
            // Beri tanda invalid pada input satuan

            setLoading(false);
            // Matikan loading karena validasi gagal

            return false;
            // Stop proses
        } else {
            submitForm();
            // Jika valid → lanjut kirim data ke server
        }
    }

    function submitForm() {
        // Fungsi AJAX untuk menyimpan data satuan ke database

        const satuan = $("input[name='satuan']").val();
        // Ambil nilai nama satuan

        const ket = $("textarea[name='ket']").val();
        // Ambil nilai keterangan

        $.ajax({
            type: 'POST',
            // Menggunakan method POST untuk simpan data baru

            url: "{{ route('satuan.store') }}",
            // Route Laravel untuk proses simpan satuan

            enctype: 'multipart/form-data',
            // Tidak wajib jika tanpa upload, tapi tidak masalah

            data: {
                satuan: satuan,
                // Data nama satuan yang dikirim ke backend
                ket: ket
                // Data keterangan yang dikirim ke backend
            },

            success: function(data) {
                // Jika request berhasil

                $('#modaldemo8').modal('toggle');
                // Tutup modal tambah

                swal({
                    title: "Berhasil ditambah!",
                    type: "success"
                });
                // Tampilkan notifikasi sukses

                table.ajax.reload(null, false);
                // Reload DataTables agar data terbaru muncul (tanpa reset halaman)

                reset();
                // Reset input form setelah berhasil
            }
        });
    }

    function resetValid() {
        // Fungsi untuk menghapus class invalid pada input

        $("input[name='satuan']").removeClass('is-invalid');
        // Hilangkan tanda invalid pada input satuan
    };

    function reset() {
        // Fungsi untuk mengosongkan semua input form

        resetValid();
        // Reset validasi

        $("input[name='satuan']").val('');
        // Kosongkan input satuan

        $("textarea[name='ket']").val('');
        // Kosongkan textarea keterangan

        setLoading(false);
        // Matikan loading (tampilkan tombol simpan lagi)
    }

    function setLoading(bool) {
        // Fungsi untuk toggle loader dan tombol simpan

        if (bool == true) {
            // Jika loading aktif

            $('#btnLoader').removeClass('d-none');
            // Tampilkan tombol loader

            $('#btnSimpan').addClass('d-none');
            // Sembunyikan tombol simpan
        } else {
            // Jika loading tidak aktif

            $('#btnSimpan').removeClass('d-none');
            // Tampilkan tombol simpan

            $('#btnLoader').addClass('d-none');
            // Sembunyikan tombol loader
        }
    }

</script>
@endsection
{{-- Akhir section formTambahJS --}}
