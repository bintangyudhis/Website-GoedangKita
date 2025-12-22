<!-- MODAL EFFECTS -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    <!-- Modal Bootstrap; `fade` untuk animasi; backdrop static agar tidak bisa ditutup klik luar -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Wrapper dialog modal; `modal-dialog-centered` agar modal berada di tengah layar -->

        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal; class tambahan biasanya dari template -->

            <form method="POST" action="{{url('/admin/menu/hapus')}}" name="myFormH" id="myFormH" enctype="multipart/form-data">
                <!-- Form submit POST ke route hapus menu; enctype disiapkan walau tanpa file -->

                @csrf
                <!-- Token CSRF Laravel untuk keamanan form -->

                <div class="modal-body text-center p-4 pb-5">
                    <!-- Body modal; text-center rata tengah; p-4 padding; pb-5 padding bawah lebih besar -->

                    <button type="reset" aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal">
                        <!-- Tombol close modal; type reset agar form ikut direset -->

                        <span aria-hidden="true">×</span>
                        <!-- Icon close (X); aria-hidden untuk aksesibilitas -->
                    </button>

                    <br>
                    <!-- Baris kosong untuk memberi jarak vertikal -->

                    <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                    <!-- Icon peringatan; ukuran besar; warna warning; margin atas-bawah -->

                    <h3 class="mb-5">
                        <!-- Judul konfirmasi -->

                        Yakin hapus <span id="vmenu"></span> ?
                        <!-- Teks konfirmasi; `#vmenu` diisi via JavaScript (nama menu yang akan dihapus) -->
                    </h3>

                    <input type="hidden" name="idmenu" id="idmenu">
                    <!-- Input hidden untuk menyimpan ID menu yang akan dihapus (diisi via JS) -->

                    <button class="btn btn-danger-light pd-x-25">
                        <!-- Tombol submit; warna danger untuk aksi hapus -->
                        Iya
                    </button>

                    <button type="reset" data-bs-dismiss="modal" class="btn btn-default pd-x-25">
                        <!-- Tombol batal; reset form + tutup modal -->
                        Batal
                    </button>
                </div>
                <!-- Penutup modal-body -->
            </form>
            <!-- Penutup form -->
        </div>
        <!-- Penutup modal-content -->
    </div>
    <!-- Penutup modal-dialog -->
</div>
<!-- Penutup modal -->
