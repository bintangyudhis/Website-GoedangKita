<!-- MODAL EFFECTS -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <!-- Modal Bootstrap untuk tambah role; `fade` animasi; backdrop static = klik luar tidak menutup; id dipakai saat trigger -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal; centered agar posisinya di tengah -->

        <div class="modal-content modal-content-demo">
            <!-- Konten modal; class tambahan dari template -->

            <div class="modal-header">
                <!-- Header modal -->

                <h6 class="modal-title">Tambah Role</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <!-- Judul modal + tombol close; data-bs-dismiss menutup modal -->
            </div>
            <!-- Penutup modal-header -->

            <form method="POST" action="{{ route('role.store') }}" name="myForm" enctype="multipart/form-data" onsubmit="return validateForm()">
                <!-- Form tambah role: POST ke route role.store; onsubmit validasi JS sebelum submit; enctype disiapkan walau tanpa file -->

                @csrf
                <!-- CSRF token Laravel -->

                <div class="modal-body">
                    <!-- Body modal berisi field input -->

                    <div class="form-group">
                        <!-- Grup field title -->

                        <label for="title" class="form-label">Title</label>
                        <!-- Label untuk input title -->

                        <input type="text" id="title" name="title" class="form-control" placeholder="Title Role">
                        <!-- Input title role -->
                    </div>
                    <!-- Penutup form-group title -->

                    <div class="form-group">
                        <!-- Grup field description -->

                        <label for="desc" class="form-label">Description</label>
                        <!-- Label untuk textarea desc -->

                        <textarea name="desc" id="desc" rows="4" class="form-control" placeholder="Deskipsi.."></textarea>
                        <!-- Textarea deskripsi role; rows=4 tinggi awal textarea -->
                    </div>
                    <!-- Penutup form-group desc -->
                </div>
                <!-- Penutup modal-body -->

                <div class="modal-footer">
                    <!-- Footer modal (tombol aksi) -->

                    <button type="submit" class="btn btn-primary">Simpan <i class="fe fe-check"></i></button>
                    <!-- Tombol submit untuk menyimpan role -->

                    <button type="reset" class="btn btn-light" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></button>
                    <!-- Tombol batal: reset form + tutup modal -->
                </div>
                <!-- Penutup modal-footer -->
            </form>
            <!-- Penutup form -->
        </div>
        <!-- Penutup modal-content -->
    </div>
    <!-- Penutup modal-dialog -->
</div>
<!-- Penutup modal -->

<script>
    // Validasi sederhana sebelum submit form tambah role

    function validateForm() {
        // Ambil nilai title dari form
        const title = document.forms["myForm"]["title"].value;

        if (title == '') {
            // Jika title kosong -> tampilkan warning dan hentikan submit
            validasi('Title wajib di isi!', 'warning');
            // Panggil swal melalui fungsi validasi() (diasumsikan sudah ada di halaman utama)
            $("input[name='title']").addClass('is-invalid');
            // Tambahkan class invalid agar input ditandai merah
            return false;
            // Stop submit
        }

    }
    // Penutup validateForm
</script>
