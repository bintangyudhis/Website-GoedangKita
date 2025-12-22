<!-- MODAL EDIT -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    <!-- Modal Bootstrap untuk edit data merk, backdrop static (klik luar tidak menutup modal) -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal berada di tengah -->

        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal -->

            <div class="modal-header">
                <!-- Bagian header modal -->

                <h6 class="modal-title">Ubah Merk Barang</h6>
                <!-- Judul modal -->

                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol untuk menutup modal -->
                    <span aria-hidden="true">&times;</span>
                    <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body">
                <!-- Isi body modal -->

                <input type="hidden" name="idmerkU">
                <!-- Input hidden untuk menyimpan ID merk yang sedang diedit -->

                <div class="form-group">
                    <!-- Grup input untuk merk -->

                    <label for="merkU" class="form-label">
                        Merk Barang <span class="text-danger">*</span>
                        <!-- Label + tanda wajib -->
                    </label>

                    <input type="text" name="merkU" class="form-control" placeholder="">
                    <!-- Input text untuk nama merk barang -->
                </div>

                <div class="form-group">
                    <!-- Grup input untuk keterangan -->

                    <label for="ketU" class="form-label">Keterangan</label>
                    <!-- Label keterangan (opsional) -->

                    <textarea name="ketU" class="form-control" rows="4"></textarea>
                    <!-- Textarea untuk keterangan merk -->
                </div>
            </div>

            <div class="modal-footer">
                <!-- Footer modal berisi tombol aksi -->

                <button class="btn btn-success d-none" id="btnLoaderU" type="button" disabled="">
                    <!-- Tombol loader (default disembunyikan) untuk indikator proses simpan -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    <!-- Spinner kecil -->
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkFormU()" id="btnSimpanU" class="btn btn-success">
                    <!-- Tombol simpan: memanggil checkFormU() untuk validasi -->
                    Simpan Perubahan <i class="fe fe-check"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-light" onclick="resetU()" data-bs-dismiss="modal">
                    <!-- Tombol batal: reset form lalu menutup modal -->
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>

        </div>
    </div>
</div>

@section('formEditJS')
{{-- Section Blade untuk JS modal edit merk --}}
<script>
    function checkFormU() {
        // Fungsi validasi sebelum mengirim data edit

        const merk = $("input[name='merkU']").val();
        // Ambil nilai input merk

        setLoadingU(true);
        // Aktifkan loading (tampilkan loader, sembunyikan tombol simpan)

        resetValidU();
        // Hapus semua tanda invalid sebelumnya

        if (merk == "") {
            // Jika merk masih kosong
            validasi('Merk Barang wajib di isi!', 'warning');
            // Tampilkan notifikasi warning

            $("input[name='merkU']").addClass('is-invalid');
            // Tambahkan class invalid ke input merk

            setLoadingU(false);
            // Matikan loading

            return false;
            // Batalkan proses
        } else {
            // Jika valid
            submitFormU();
            // Lanjut kirim data ke server
        }
    }

    function submitFormU() {
        // Fungsi untuk mengirim data edit ke server via AJAX

        const id = $("input[name='idmerkU']").val();
        // Ambil ID merk yang diedit

        const merk = $("input[name='merkU']").val();
        // Ambil nama merk yang diinput

        const ket = $("textarea[name='ketU']").val();
        // Ambil keterangan merk

        $.ajax({
            type: 'POST',
            // Mengirim data menggunakan metode POST

            url: "{{url('admin/merk/proses_ubah')}}/" + id,
            // URL endpoint proses ubah merk + id

            enctype: 'multipart/form-data',
            // Catatan: tidak wajib karena tidak ada upload file, tapi tetap aman

            data: {
                merk: merk,
                // Data nama merk yang dikirim ke backend

                ket: ket
                // Data keterangan yang dikirim ke backend
            },

            success: function(data) {
                // Jika request berhasil

                swal({
                    title: "Berhasil diubah!",
                    type: "success"
                });
                // Tampilkan SweetAlert sukses

                $('#Umodaldemo8').modal('toggle');
                // Tutup modal edit

                table.ajax.reload(null, false);
                // Reload datatable tanpa reset halaman

                resetU();
                // Reset isi form edit
            }
        });
    }

    function resetValidU() {
        // Fungsi menghapus tanda invalid pada form

        $("input[name='merkU']").removeClass('is-invalid');
        // Hapus invalid dari input merk

        $("textarea[name='ketU']").removeClass('is-invalid');
        // Hapus invalid dari textarea ket (opsional)
    };

    function resetU() {
        // Fungsi untuk mereset form edit

        resetValidU();
        // Reset validasi

        $("input[name='idmerkU']").val('');
        // Kosongkan id merk

        $("input[name='merkU']").val('');
        // Kosongkan input merk

        $("textarea[name='ketU']").val('');
        // Kosongkan input keterangan

        setLoadingU(false);
        // Matikan loading
    }

    function setLoadingU(bool) {
        // Fungsi toggle loader vs tombol simpan

        if (bool == true) {
            // Jika loading aktif
            $('#btnLoaderU').removeClass('d-none');
            // Tampilkan tombol loader

            $('#btnSimpanU').addClass('d-none');
            // Sembunyikan tombol simpan
        } else {
            // Jika loading mati
            $('#btnSimpanU').removeClass('d-none');
            // Tampilkan tombol simpan

            $('#btnLoaderU').addClass('d-none');
            // Sembunyikan tombol loader
        }
    }
</script>
@endsection
{{-- Akhir section formEditJS --}}
