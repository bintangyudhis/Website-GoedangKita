{{-- ============================= --}}
{{-- MODAL HAPUS (KONFIRMASI DELETE BARANG) --}}
{{-- ============================= --}}
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    {{-- Dialog modal (posisi tengah) --}}
    <div class="modal-dialog modal-dialog-centered" role="document">
        {{-- Konten utama modal --}}
        <div class="modal-content modal-content-demo">

            {{-- Body modal: pesan konfirmasi + tombol aksi --}}
            <div class="modal-body text-center p-4 pb-5">

                {{-- Tombol close (X) --}}
                {{-- onclick="resetH()" untuk reset state ketika modal ditutup --}}
                <button type="reset"
                        aria-label="Close"
                        onclick="resetH()"
                        class="btn-close position-absolute"
                        data-bs-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>

                <br>

                {{-- Ikon peringatan --}}
                <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>

                {{-- Pertanyaan konfirmasi --}}
                {{-- <span id="vbarang"></span> akan diisi via JavaScript dengan nama barang --}}
                <h3 class="mb-5">Yakin hapus <span id="vbarang"></span> ?</h3>

                {{-- Hidden input untuk menyimpan ID barang yang akan dihapus --}}
                {{-- Diisi via JS sebelum submit --}}
                <input type="hidden" name="idbarang" id="idbarang">

                {{-- Tombol loader (disembunyikan default) --}}
                {{-- Akan muncul saat request AJAX berjalan --}}
                <button class="btn btn-danger-light pd-x-25 d-none"
                        id="btnLoaderH"
                        type="button"
                        disabled="">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                {{-- Tombol konfirmasi hapus --}}
                {{-- onclick memanggil AJAX delete --}}
                <button onclick="submitFormH()"
                        class="btn btn-danger-light pd-x-25"
                        id="btnSubmit">
                    Iya
                </button>

                {{-- Tombol batal (tutup modal) --}}
                <button type="reset"
                        data-bs-dismiss="modal"
                        class="btn btn-default pd-x-25">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============================= --}}
{{-- SCRIPT: HAPUS BARANG (AJAX) --}}
{{-- ============================= --}}
@section('formHapusJS')
<script>
    // Mengirim request hapus barang menggunakan AJAX
    function submitFormH() {
        // Aktifkan mode loading (tampilkan spinner, sembunyikan tombol submit)
        setLoadingH(true);

        // Ambil ID barang dari input hidden
        const id = $("input[name='idbarang']").val();

        // Request hapus ke endpoint proses_hapus/{id}
        $.ajax({
            type: 'POST',
            url: "{{url('admin/barang/proses_hapus')}}/" + id,
            enctype: 'multipart/form-data', // tidak wajib untuk request ini, tapi dibiarkan mengikuti pola yang ada
            success: function(data) {
                // Notifikasi sukses
                swal({
                    title: "Berhasil dihapus!",
                    type: "success"
                });

                // Tutup modal
                $('#Hmodaldemo8').modal('toggle');

                // Reload datatable tanpa reset paging
                table.ajax.reload(null, false);

                // Reset state modal
                resetH();
            }
        });
    }

    // Reset nilai input & loading state pada modal hapus
    function resetH() {
        // Kosongkan ID barang
        $("input[name='idbarang']").val('');

        // Matikan mode loading
        setLoadingH(false);
    }

    // Mengatur tampilan loading (spinner vs tombol submit)
    function setLoadingH(bool) {
        if (bool == true) {
            // Tampilkan loader, sembunyikan tombol submit
            $('#btnLoaderH').removeClass('d-none');
            $('#btnSubmit').addClass('d-none');
        } else {
            // Tampilkan tombol submit, sembunyikan loader
            $('#btnSubmit').removeClass('d-none');
            $('#btnLoaderH').addClass('d-none');
        }
    }
</script>
@endsection
