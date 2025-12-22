<!-- MODAL HAPUS --> <!-- Penanda: modal konfirmasi untuk menghapus data customer -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup modal) -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal ditengah layar -->
        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal -->
            <div class="modal-body text-center p-4 pb-5">
                <!-- Body modal, teks rata tengah + padding -->

                <button type="reset" aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal">
                    <!-- Tombol close: menutup modal (tidak memanggil resetH()) -->
                    <span aria-hidden="true">×</span> <!-- Ikon close -->
                </button>

                <br> <!-- Pindah baris -->

                <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                <!-- Ikon peringatan (tanda seru), ukuran besar, warna warning -->

                <h3 class="mb-5">Yakin hapus <span id="vcustomer"></span> ?</h3>
                <!-- Pertanyaan konfirmasi; span #vcustomer diisi via JS (misal nama customer) -->

                <input type="hidden" name="idcustomer" id="idcustomer">
                <!-- Hidden input untuk menyimpan id customer yang akan dihapus -->

                <button class="btn btn-danger-light pd-x-25 d-none" id="btnLoaderH" type="button" disabled="">
                    <!-- Tombol loader saat proses hapus berjalan (default disembunyikan) -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
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

@section('formHapusJS') {{-- Section Blade: script JS khusus hapus customer --}}
<script> // Awal script JS

    function submitFormH() { // Fungsi untuk mengirim request hapus customer
        setLoadingH(true); // Aktifkan loading: tampilkan loader, sembunyikan tombol "Iya"

        const id = $("input[name='idcustomer']").val(); // Ambil id customer dari hidden input

        $.ajax({ // Mulai AJAX jQuery
            type: 'POST', // Method POST
            url: "{{url('admin/customer/proses_hapus')}}/" + id,
            // Endpoint hapus customer + id

            enctype: 'multipart/form-data',
            // Catatan: tidak wajib jika tidak ada upload file

            success: function(data) { // Jika server sukses menghapus
                swal({ // SweetAlert notifikasi
                    title: "Berhasil dihapus!",
                    type: "success"
                });

                $('#Hmodaldemo8').modal('toggle'); // Tutup modal hapus
                table.ajax.reload(null, false); // Reload DataTables utama tanpa reset pagination
                resetH(); // Reset field id + matikan loading
            }
        });
    }

    function resetH() { // Fungsi reset state modal hapus
        $("input[name='idcustomer']").val(''); // Kosongkan id
        setLoadingH(false); // Matikan loading
    }

    function setLoadingH(bool) { // Fungsi toggle loader dan tombol submit
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
