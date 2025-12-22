<!-- MODAL HAPUS -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    {{-- Modal Bootstrap untuk konfirmasi hapus data satuan --}}
    {{-- data-bs-backdrop="static" → modal tidak tertutup saat klik area luar --}}

    <div class="modal-dialog modal-dialog-centered" role="document">
        {{-- Dialog modal ditampilkan di tengah layar --}}

        <div class="modal-content modal-content-demo">
            {{-- Konten utama modal --}}

            <div class="modal-body text-center p-4 pb-5">
                {{-- Body modal, teks rata tengah dengan padding --}}

                <button type="reset" aria-label="Close"
                        class="btn-close position-absolute"
                        data-bs-dismiss="modal">
                    {{-- Tombol close (X) untuk menutup modal --}}
                    <span aria-hidden="true">×</span>
                    {{-- Ikon close --}}
                </button>

                <br>
                {{-- Jarak visual --}}

                <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                {{-- Ikon peringatan (warning) sebagai penanda aksi berbahaya --}}

                <h3 class="mb-5">
                    Yakin hapus <span id="vsatuan"></span> ?
                </h3>
                {{-- Pesan konfirmasi hapus, nama satuan akan diisi lewat JavaScript --}}

                <input type="hidden" name="idsatuan" id="idsatuan">
                {{-- Hidden input untuk menyimpan ID satuan yang akan dihapus --}}

                <button class="btn btn-danger-light pd-x-25 d-none"
                        id="btnLoaderH"
                        type="button"
                        disabled="">
                    {{-- Tombol loader saat proses hapus berlangsung --}}
                    <span class="spinner-border spinner-border-sm me-1"
                          role="status"
                          aria-hidden="true"></span>
                    Loading...
                </button>

                <button onclick="submitFormH()"
                        class="btn btn-danger-light pd-x-25"
                        id="btnSubmit">
                    {{-- Tombol konfirmasi hapus --}}
                    Iya
                </button>

                <button type="reset"
                        data-bs-dismiss="modal"
                        class="btn btn-default pd-x-25">
                    {{-- Tombol batal untuk menutup modal --}}
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>


@section('formHapusJS')
{{-- Section Blade khusus JavaScript modal hapus satuan --}}
<script>

    function submitFormH() {
        // Fungsi untuk mengirim permintaan hapus ke server

        setLoadingH(true);
        // Aktifkan loading (tampilkan spinner, sembunyikan tombol Iya)

        const id = $("input[name='idsatuan']").val();
        // Ambil ID satuan dari input hidden

        $.ajax({
            type: 'POST',
            // Method POST untuk proses hapus

            url: "{{url('admin/satuan/proses_hapus')}}/" + id,
            // URL endpoint hapus satuan + id

            enctype: 'multipart/form-data',
            // Tidak wajib, namun aman digunakan di Laravel

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
                // Reload DataTables tanpa reset halaman

                resetH();
                // Reset form hapus
            }
        });
    }

    function resetH() {
        // Fungsi reset form hapus

        $("input[name='idsatuan']").val('');
        // Kosongkan ID satuan

        setLoadingH(false);
        // Matikan loading
    }

    function setLoadingH(bool) {
        // Fungsi toggle loader dan tombol submit

        if (bool == true) {
            // Jika loading aktif
            $('#btnLoaderH').removeClass('d-none');
            // Tampilkan loader
            $('#btnSubmit').addClass('d-none');
            // Sembunyikan tombol Iya
        } else {
            // Jika loading tidak aktif
            $('#btnSubmit').removeClass('d-none');
            // Tampilkan tombol Iya
            $('#btnLoaderH').addClass('d-none');
            // Sembunyikan loader
        }
    }

</script>
@endsection
{{-- Akhir section formHapusJS --}}
