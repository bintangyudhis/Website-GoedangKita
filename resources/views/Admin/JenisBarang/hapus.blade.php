<!-- MODAL HAPUS --> <!-- Penanda: modal konfirmasi untuk menghapus data jenis barang -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup modal) -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal ditengah layar -->
        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal -->
            <div class="modal-body text-center p-4 pb-5">
                <!-- Body modal: teks rata tengah + padding -->

                <button type="reset" aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal">
                    <!-- Tombol close (X): menutup modal (tidak memanggil resetH) -->
                    <span aria-hidden="true">×</span>
                    <!-- Ikon close -->
                </button>

                <br> <!-- Pindah baris -->

                <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                <!-- Ikon peringatan (tanda seru), warna warning, ukuran besar -->

                <h3 class="mb-5">Yakin hapus <span id="vjenisbarang"></span> ?</h3>
                <!-- Kalimat konfirmasi; span #vjenisbarang diisi via JS (nama/kode jenis barang) -->

                <input type="hidden" name="idjenisbarang" id="idjenisbarang">
                <!-- Hidden input untuk menyimpan id jenis barang yang akan dihapus -->

                <button class="btn btn-danger-light pd-x-25 d-none" id="btnLoaderH" type="button" disabled="">
                    <!-- Tombol loader saat proses hapus berlangsung (default hidden) -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    <!-- Spinner loading kecil -->
                    Loading...
                </button>

                <button onclick="submitFormH()" class="btn btn-danger-light pd-x-25" id="btnSubmit">Iya</button>
                <!-- Tombol konfirmasi hapus: jalankan submitFormH() -->

                <button type="reset" data-bs-dismiss="modal" class="btn btn-default pd-x-25">Batal</button>
                <!-- Tombol batal: tutup modal tanpa menghapus -->
            </div>
        </div>
    </div>
</div>

@section('formHapusJS') {{-- Section Blade: JS khusus hapus jenis barang --}}
<script> // Awal script JS

    function submitFormH() { // Fungsi AJAX untuk proses hapus
        setLoadingH(true); // Aktifkan loading (tampilkan loader, sembunyikan tombol "Iya")

        const id = $("input[name='idjenisbarang']").val(); // Ambil id jenis barang dari hidden input

        $.ajax({
            type: 'POST', // Method POST (hapus data via endpoint)
            url: "{{url('admin/jenisbarang/proses_hapus')}}/" + id,
            // Endpoint proses hapus jenis barang + id

            enctype: 'multipart/form-data',
            // Catatan: tidak wajib jika tidak ada upload file

            success: function(data) { // Jika hapus berhasil
                swal({ // SweetAlert notifikasi sukses
                    title: "Berhasil dihapus!",
                    type: "success"
                });

                $('#Hmodaldemo8').modal('toggle'); // Tutup modal hapus
                table.ajax.reload(null, false); // Reload DataTables utama tanpa reset pagination
                resetH(); // Reset state modal hapus
            }
        });
    }

    function resetH() { // Reset field dan status loading modal hapus
        $("input[name='idjenisbarang']").val(''); // Kosongkan id
        setLoadingH(false); // Matikan loading (tampilkan tombol "Iya" lagi)
    }

    function setLoadingH(bool) { // Toggle tampilan loader dan tombol submit
        if (bool == true) { // Jika loading aktif
            $('#btnLoaderH').removeClass('d-none'); // Tampilkan loader
            $('#btnSubmit').addClass('d-none'); // Sembunyikan tombol "Iya"
        } else { // Jika loading mati
            $('#btnSubmit').removeClass('d-none'); // Tampilkan tombol "Iya"
            $('#btnLoaderH').addClass('d-none'); // Sembunyikan loader
        }
    }

</script>
@endsection {{-- Akhir section formHapusJS --}}
