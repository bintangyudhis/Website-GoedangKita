<!-- MODAL EFFECTS -->
<div class="modal fade" data-bs-backdrop="static" id="Hmodaldemo8">
    <!-- Modal Bootstrap untuk konfirmasi hapus role; `fade` animasi; backdrop static = klik luar tidak menutup -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Wrapper dialog modal; centered agar posisinya di tengah -->

        <div class="modal-content modal-content-demo">
            <!-- Konten modal; class tambahan dari template -->

            <form method="POST" action="{{url('/admin/role/hapus')}}" name="myFormH" id="myFormH" enctype="multipart/form-data">
                <!-- Form hapus role: submit POST ke /admin/role/hapus; enctype disiapkan walau tanpa file -->

                @csrf
                <!-- CSRF token Laravel untuk keamanan request -->

                <div class="modal-body text-center p-4 pb-5">
                    <!-- Body modal; text-center rata tengah; p-4 padding; pb-5 padding bawah ekstra -->

                    <button type="reset" aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span aria-hidden="true">×</span></button>
                    <!-- Tombol close; type reset agar form direset; data-bs-dismiss menutup modal -->

                    <br>
                    <!-- Spasi baris untuk jarak vertikal -->

                    <i class="icon icon-exclamation fs-70 text-warning lh-1 my-5 d-inline-block"></i>
                    <!-- Icon peringatan; ukuran besar; warna warning; margin atas-bawah -->

                    <h3 class="mb-5">Yakin hapus <span id="vrole"></span> ?</h3>
                    <!-- Teks konfirmasi; `#vrole` biasanya diisi via JS dengan nama role yang akan dihapus -->

                    <input type="hidden" name="idrole" id="idrole">
                    <!-- Input hidden untuk menyimpan id role yang akan dihapus (diisi via JS) -->

                    <button class="btn btn-danger-light pd-x-25">Iya</button>
                    <!-- Tombol konfirmasi hapus; default type=submit karena berada di dalam form -->

                    <button type="reset" data-bs-dismiss="modal" class="btn btn-default pd-x-25">Batal</button>
                    <!-- Tombol batal: reset form + tutup modal -->
                </div>
                <!-- Penutup modal-body -->
            </form>
            <!-- Penutup form hapus -->
        </div>
        <!-- Penutup modal-content -->
    </div>
    <!-- Penutup modal-dialog -->
</div>
<!-- Penutup modal -->
