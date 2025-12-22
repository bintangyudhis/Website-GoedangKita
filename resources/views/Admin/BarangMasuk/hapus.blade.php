<!-- MODAL HAPUS --> <!-- Penanda: modal konfirmasi untuk menghapus data barang masuk -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup modal) -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal ditampilkan di tengah layar -->
        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal -->
            <div class="modal-body text-center p-4 pb-5">
                <!-- Body modal, teks rata tengah, padding atas-bawah -->

                <button type="reset" aria-label="Close" onclick="resetH()" class="btn-close position-absolute" data-bs-dismiss="modal">
                    <!-- Tombol close: jalankan resetH() lalu tutup modal -->
                    <span aria-hidden="true">×</span> <!-- Ikon close (x) -->
                </button>

                <br> <!-- Pindah baris -->

                <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                <!-- Ikon peringatan (tanda seru), ukuran besar, warna warning -->

                <h3 class="mb-5">Yakin hapus <span id="vbm"></span> ?</h3>
                <!-- Pertanyaan konfirmasi: span #vbm diisi via JS (misal kode BM yang mau dihapus) -->

                <input type="hidden" name="idbm" id="idbm">
                <!-- Hidden input untuk menyimpan id barang masuk yang akan dihapus -->

                <button class="btn btn-danger-light pd-x-25 d-none" id="btnLoaderH" type="button" disabled="">
                    <!-- Tombol loader saat proses hapus sedang berjalan, default hidden (d-none) -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    <!-- Spinner loading kecil -->
                    Loading...
                </button>

                <button onclick="submitFormH()" class="btn btn-danger-light pd-x-25" id="btnSubmit">Iya</button>
                <!-- Tombol konfirmasi hapus: jalankan submitFormH() -->

                <button type="reset" data-bs-dismiss="modal" class="btn btn-default pd-x-25">Batal</button>
                <!-- Tombol batal: menutup modal tanpa menghapus data -->
            </div>
        </div>
    </div>
</div>

@section('formHapusJS') {{-- Section Blade: script JS khusus hapus --}}
<script> // Awal script JS

    function submitFormH() { // Fungsi untuk mengirim request hapus ke server
        setLoadingH(true); // Aktifkan mode loading: tampilkan loader dan sembunyikan tombol submit

        const id = $("input[name='idbm']").val(); // Ambil id yang akan dihapus dari hidden input

        $.ajax({ // Mulai AJAX jQuery
            type: 'POST', // Method POST (hapus data melalui endpoint)
            url: "{{url('admin/barang-masuk/proses_hapus')}}/" + id,
            // Endpoint hapus barang masuk + id

            enctype: 'multipart/form-data',
            // Catatan: ini biasa untuk upload file, tidak wajib kalau tidak ada file yang diupload

            success: function(data) { // Jika server berhasil menghapus
                swal({ // Tampilkan notifikasi SweetAlert
                    title: "Berhasil dihapus!", // Pesan sukses
                    type: "success" // Tipe sukses
                });

                $('#Hmodaldemo8').modal('toggle'); // Tutup modal hapus
                table.ajax.reload(null, false); // Reload DataTables utama tanpa reset pagination
                resetH(); // Reset form hapus (kosongkan id + matikan loading)
            }
        });
    }

    function resetH() { // Fungsi reset state modal hapus
        $("input[name='idbm']").val(''); // Kosongkan id yang tersimpan
        setLoadingH(false); // Matikan loading, tampilkan tombol "Iya" lagi
    }

    function setLoadingH(bool) { // Fungsi toggle tampilan loader dan tombol submit
        if (bool == true) { // Jika loading aktif
            $('#btnLoaderH').removeClass('d-none'); // Tampilkan tombol loader
            $('#btnSubmit').addClass('d-none'); // Sembunyikan tombol submit "Iya"
        } else { // Jika loading nonaktif
            $('#btnSubmit').removeClass('d-none'); // Tampilkan tombol submit
            $('#btnLoaderH').addClass('d-none'); // Sembunyikan tombol loader
        }
    }

</script>
@endsection {{-- Akhir section formHapusJS --}}
