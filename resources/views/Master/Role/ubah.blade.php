<!-- MODAL EFFECTS -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    <!-- Modal Bootstrap untuk ubah role; backdrop static agar tidak tertutup saat klik luar -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal; modal-dialog-centered agar tampil di tengah layar -->

        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal; class tambahan dari template -->

            <div class="modal-header">
                <!-- Header modal (judul + tombol close) -->

                <h6 class="modal-title">Ubah Role</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <!-- Judul modal + tombol close; data-bs-dismiss menutup modal -->
            </div>
            <!-- Penutup modal-header -->

            <form method="POST" name="myFormU" id="myFormU" enctype="multipart/form-data" onsubmit="return validateFormUpdate()">
                <!-- Form update role; action di-set via JavaScript; onsubmit validasi sebelum submit -->

                @csrf
                <!-- CSRF token Laravel -->

                @method('PUT')
                <!-- Spoof method PUT agar sesuai REST update -->

                <div class="modal-body">
                    <!-- Body modal berisi field input -->

                    <div class="form-group">
                        <!-- Grup field title -->

                        <label for="utitle" class="form-label">Title</label>
                        <!-- Label input title update -->

                        <input type="text" id="utitle" name="utitle" class="form-control" placeholder="Title Role">
                        <!-- Input title role; diisi via JS saat klik edit -->
                    </div>
                    <!-- Penutup form-group title -->

                    <div class="form-group">
                        <!-- Grup field description -->

                        <label for="udesc" class="form-label">Description</label>
                        <!-- Label textarea deskripsi -->

                        <textarea name="udesc" id="udesc" rows="4" class="form-control" placeholder="Deskipsi.."></textarea>
                        <!-- Textarea deskripsi role; rows=4 tinggi awal -->
                    </div>
                    <!-- Penutup form-group description -->
                </div>
                <!-- Penutup modal-body -->

                <div class="modal-footer">
                    <!-- Footer modal -->

                    <button type="submit" class="btn btn-success">Simpan Perubahan <i class="fe fe-check"></i></button>
                    <!-- Tombol submit untuk menyimpan perubahan role -->

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
    // Validasi form update role sebelum submit

    function validateFormUpdate() {
        // Ambil nilai title dari form update
        const title = document.forms["myForm"]["utitle"].value;
        // Catatan: mengakses form bernama "myForm"; field utitle

        if (title == '') {
            // Jika title kosong
            validasi('Title wajib di isi!', 'warning');
            // Tampilkan alert peringatan (SweetAlert)
            $("input[name='utitle']").addClass('is-invalid');
            // Tandai input sebagai invalid
            return false;
            // Hentikan proses submit
        }

    }
    // Penutup validateFormUpdate
</script>
