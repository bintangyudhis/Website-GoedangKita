<!-- MODAL HAPUS -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    <!-- Modal Bootstrap untuk konfirmasi hapus user; backdrop static agar modal tidak tertutup saat klik luar -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal; modal-dialog-centered agar tampil di tengah layar -->

        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal; class tambahan dari template -->

            <form method="POST" action="{{url('/admin/user/hapus')}}" name="myFormH" id="myFormH" enctype="multipart/form-data">
                <!-- Form hapus user; submit POST ke endpoint /admin/user/hapus -->

                @csrf
                <!-- CSRF token Laravel untuk keamanan request -->

                <div class="modal-body text-center p-4 pb-5">
                    <!-- Body modal; text-center rata tengah; p-4 padding; pb-5 padding bawah ekstra -->

                    <button type="reset" aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal">
                        <!-- Tombol close modal; type reset agar form kembali ke kondisi awal -->
                        <span aria-hidden="true">×</span>
                        <!-- Icon close (X); aria-hidden untuk aksesibilitas -->
                    </button>

                    <br>
                    <!-- Spasi baris untuk jarak vertikal -->

                    <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                    <!-- Icon peringatan; ukuran besar; warna warning; margin atas-bawah -->

                    <h3 class="mb-5">Yakin hapus <span id="vuser"></span> ?</h3>
                    <!-- Teks konfirmasi hapus; `#vuser` diisi via JavaScript dengan nama user -->

                    <input type="hidden" name="iduser" id="iduser">
                    <!-- Input hidden untuk menyimpan ID user yang akan dihapus -->

                    <button class="btn btn-danger-light pd-x-25">Iya</button>
                    <!-- Tombol konfirmasi hapus; default type=submit karena berada dalam form -->

                    <button type="reset" data-bs-dismiss="modal" class="btn btn-default pd-x-25">Batal</button>
                    <!-- Tombol batal: reset form dan tutup modal -->
                </div>
                <!-- Penutup modal-body -->
            </form>
            <!-- Penutup form hapus user -->
        </div>
        <!-- Penutup modal-content -->
    </div>
    <!-- Penutup modal-dialog -->
</div>
<!-- Penutup modal -->
