<!-- MODAL HAPUS --> <!-- Penanda: modal untuk konfirmasi hapus data -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8"> <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup) -->
    <div class="modal-dialog modal-dialog-centered" role="document"> <!-- Dialog modal posisi tengah -->
        <div class="modal-content modal-content-demo"> <!-- Kontainer konten modal -->
            <div class="modal-body text-center p-4 pb-5"> <!-- Body modal, teks rata tengah + padding -->

                <button type="reset" aria-label="Close" onclick="resetH()" class="btn-close position-absolute" data-bs-dismiss="modal">
                    <!-- Tombol close: reset form via resetH() lalu menutup modal -->
                    <span aria-hidden="true">×</span> <!-- Ikon close (x) -->
                </button>

                <br> <!-- Pindah baris -->

                <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                <!-- Ikon peringatan (tanda seru), ukuran besar, warna warning -->

                <h3 class="mb-5">Yakin hapus <span id="vbk"></span> ?</h3>
                <!-- Pertanyaan konfirmasi; span #vbk biasanya diisi via JS (misal kode barang keluar) -->

                <input type="hidden" name="idbk" id="idbk">
                <!-- Hidden input untuk menyimpan id data yang akan dihapus -->

                <button class="btn btn-danger-light pd-x-25 d-none" id="btnLoaderH" type="button" disabled="">
                    <!-- Tombol loader saat proses hapus berjalan; d-none = disembunyikan awalnya -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    <!-- Spinner loading kecil -->
                    Loading...
                </button>

                <button onclick="submitFormH()" class="btn btn-danger-light pd-x-25" id="btnSubmit">Iya</button>
                <!-- Tombol konfirmasi hapus: jalankan submitFormH() -->

                <button type="reset" data-bs-dismiss="modal" class="btn btn-default pd-x-25">Batal</button>
                <!-- Tombol batal: menutup modal (tidak hapus) -->
            </div>
        </div>
    </div>
</div>

@section('formHapusJS') {{-- Section Blade untuk script JS khusus fitur hapus --}}
<script> // Awal script

    function submitFormH() { // Fungsi untuk mengirim request hapus ke server
        setLoadingH(true); // Aktifkan loading: tampilkan spinner, sembunyikan tombol "Iya"

        const id = $("input[name='idbk']").val(); // Ambil id data dari hidden input idbk

        $.ajax({ // Mulai AJAX jQuery
            type: 'POST', // Method POST untuk menghapus data (sesuai endpoint)
            url: "{{url('admin/barang-keluar/proses_hapus')}}/" + id,
            // URL endpoint hapus + id (dibentuk oleh Blade url())

            enctype: 'multipart/form-data',
            // Catatan: ini biasanya untuk upload file; kalau tidak ada file, ini tidak wajib

            success: function(data) { // Callback jika server berhasil memproses hapus
                swal({ // Tampilkan SweetAlert
                    title: "Berhasil dihapus!", // Pesan sukses
                    type: "success" // Tipe sukses (di beberapa versi swal bisa pakai icon: "success")
                });

                $('#Hmodaldemo8').modal('toggle'); // Tutup modal hapus
                table.ajax.reload(null, false); // Reload datatable tanpa reset halaman/pagination
                resetH(); // Reset nilai input + matikan loading
            }
        });
    }

    function resetH() { // Fungsi reset state form hapus
        $("input[name='idbk']").val(''); // Kosongkan id yang tersimpan
        setLoadingH(false); // Matikan loading: tampilkan tombol "Iya" lagi
    }

    function setLoadingH(bool) { // Fungsi untuk toggle tampilan loader vs tombol submit
        if (bool == true) { // Jika loading aktif
            $('#btnLoaderH').removeClass('d-none'); // Munculkan tombol loader
            $('#btnSubmit').addClass('d-none'); // Sembunyikan tombol "Iya"
        } else { // Jika loading mati
            $('#btnSubmit').removeClass('d-none'); // Tampilkan tombol "Iya"
            $('#btnLoaderH').addClass('d-none'); // Sembunyikan loader
        }
    }

</script>
@endsection {{-- Akhir section --}}
