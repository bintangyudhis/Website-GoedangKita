<!-- MODAL HAPUS -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    <!-- Modal Bootstrap untuk konfirmasi hapus data merk, backdrop static -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal ditampilkan di tengah layar -->

        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal -->

            <div class="modal-body text-center p-4 pb-5">
                <!-- Body modal, teks di tengah dengan padding -->

                <button type="reset" aria-label="Close"
                        class="btn-close position-absolute"
                        data-bs-dismiss="modal">
                    <!-- Tombol close untuk menutup modal -->
                    <span aria-hidden="true">×</span>
                    <!-- Ikon close -->
                </button>

                <br>

                <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                <!-- Ikon peringatan untuk konfirmasi hapus -->

                <h3 class="mb-5">
                    Yakin hapus <span id="vmerk"></span> ?
                </h3>
                <!-- Pesan konfirmasi hapus, nama merk ditampilkan via JS -->

                <input type="hidden" name="idmerk" id="idmerk">
                <!-- Input hidden untuk menyimpan ID merk yang akan dihapus -->

                <button class="btn btn-danger-light pd-x-25 d-none"
                        id="btnLoaderH"
                        type="button"
                        disabled="">
                    <!-- Tombol loader saat proses hapus berjalan (default disembunyikan) -->

                    <span class="spinner-border spinner-border-sm me-1"
                          role="status"
                          aria-hidden="true"></span>
                    <!-- Spinner loading -->

                    Loading...
                </button>

                <button onclick="submitFormH()"
                        class="btn btn-danger-light pd-x-25"
                        id="btnSubmit">
                    <!-- Tombol konfirmasi hapus -->
                    Iya
                </button>

                <button type="reset"
                        data-bs-dismiss="modal"
                        class="btn btn-default pd-x-25">
                    <!-- Tombol batal untuk menutup modal -->
                    Batal
                </button>

            </div>
        </div>
    </div>
</div>

@section('formHapusJS')
{{-- Section Blade khusus JavaScript hapus merk --}}
<script>
    function submitFormH() {
        // Fungsi untuk mengirim proses hapus ke server

        setLoadingH(true);
        // Aktifkan loading (tampilkan loader, sembunyikan tombol submit)

        const id = $("input[name='idmerk']").val();
        // Ambil ID merk dari input hidden

        $.ajax({
            type: 'POST',
            // Menggunakan method POST untuk hapus data

            url: "{{url('admin/merk/proses_hapus')}}/" + id,
            // URL endpoint hapus merk + id

            enctype: 'multipart/form-data',
            // Tidak wajib, tapi aman meskipun tanpa upload file

            success: function(data) {
                // Jika proses hapus berhasil

                swal({
                    title: "Berhasil dihapus!",
                    type: "success"
                });
                // Tampilkan notifikasi sukses

                $('#Hmodaldemo8').modal('toggle');
                // Tutup modal hapus

                table.ajax.reload(null, false);
                // Reload DataTable tanpa reset halaman

                resetH();
                // Reset form hapus
            }
        });
    }

    function resetH() {
        // Fungsi reset data setelah modal ditutup

        $("input[name='idmerk']").val('');
        // Kosongkan input hidden id merk

        setLoadingH(false);
        // Matikan loading
    }

    function setLoadingH(bool) {
        // Fungsi toggle antara loader dan tombol submit

        if (bool == true) {
            // Jika loading aktif
            $('#btnLoaderH').removeClass('d-none');
            // Tampilkan tombol loader

            $('#btnSubmit').addClass('d-none');
            // Sembunyikan tombol submit
        } else {
            // Jika loading tidak aktif
            $('#btnSubmit').removeClass('d-none');
            // Tampilkan tombol submit

            $('#btnLoaderH').addClass('d-none');
            // Sembunyikan loader
        }
    }
</script>
@endsection
{{-- Akhir section formHapusJS --}}
