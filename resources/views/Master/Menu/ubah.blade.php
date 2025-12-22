<!-- MODAL EFFECTS -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    <!-- Modal Bootstrap untuk Ubah Menu; backdrop static agar tidak ketutup saat klik luar; id dipakai pemanggilan -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal; centered agar posisinya di tengah -->

        <div class="modal-content modal-content-demo">
            <!-- Konten modal; class tambahan dari template -->

            <div class="modal-header">
                <!-- Header modal -->

                <h6 class="modal-title">Ubah Menu</h6><button aria-label="Close" onclick="resetU()" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <!-- Judul modal + tombol close; onclick resetU() untuk bersihkan form edit sebelum ditutup -->
            </div>
            <!-- Penutup modal-header -->

            <form method="POST" name="myFormU" id="myFormU" enctype="multipart/form-data" onsubmit="return validateFormUpdate()">
                <!-- Form update; method POST + spoof PUT; action akan di-set via JS update()/updatewithsub() -->

                @csrf
                <!-- CSRF token Laravel -->

                @method('PUT')
                <!-- Spoof method PUT agar sesuai REST update -->

                <div class="modal-body">
                    <!-- Body modal berisi field input -->

                    <div class="form-group">
                        <!-- Grup input icon -->

                        <label for="uicon" class="form-label">Icon</label>
                        <!-- Label icon untuk form update -->

                        <span class="text-gray d-block mb-1">Cari & salin nama dari Icon <a target="_blank" href="https://feathericons.com/">https://feathericons.com/</a></span></span>
                        <!-- Info bantuan icon; link feathericons; (ada </span> ekstra, tidak diubah) -->

                        <div class="input-group">
                            <!-- Input group prefix icon -->

                            <span class="input-group-text bg-gray-light" id="basic-addon1">fe-</span>
                            <!-- Prefix `fe-` -->

                            <input type="text" id="uicon" name="uicon" class="form-control" placeholder="home" aria-label="icon" aria-describedby="basic-addon1">
                            <!-- Input icon update; diisi via JS ketika klik edit -->
                        </div>
                        <!-- Penutup input-group -->
                    </div>
                    <!-- Penutup form-group icon -->

                    <div class="form-group">
                        <!-- Grup input judul -->

                        <label for="ujudul" class="form-label">Judul</label>
                        <!-- Label judul -->

                        <input type="text" id="ujudul" name="ujudul" class="form-control" placeholder="Judul Menu">
                        <!-- Input judul update; diisi via JS -->
                    </div>
                    <!-- Penutup form-group judul -->

                    <div class="form-group">
                        <!-- Grup select type -->

                        <label for="type" class="form-label">Type</label>
                        <!-- Label type -->

                        <select name="utype" class="form-control" onchange="setTypeU()">
                            <!-- Select type update; onchange setTypeU() untuk toggle tampilan menu/submenu -->

                            <option value="">-- Pilih --</option>
                            <!-- Opsi default kosong -->
                            <option value="1">Menu</option>
                            <!-- Type menu utama -->
                            <option value="2">Sub Menu</option>
                            <!-- Type submenu -->
                        </select>
                        <!-- Penutup select -->
                    </div>
                    <!-- Penutup form-group type -->

                    <div class="form-group d-none" id="vTypeMenuU">
                        <!-- Wrapper redirect update (untuk type=1); default hidden -->

                        <label for="Uredirect" class="form-label">Redirect</label>
                        <!-- Label redirect (catatan: for="Uredirect" tapi input id="uredirect") -->

                        <input type="text" id="uredirect" name="uredirect" class="form-control" placeholder="/redirect">
                        <!-- Input redirect update; wajib untuk menu utama -->
                    </div>
                    <!-- Penutup vTypeMenuU -->

                    <div class="form-group d-none" id="vTypeSubU">
                        <!-- Wrapper list submenu update; default hidden -->

                        <div class="d-flex justify-content-end mb-2">
                            <!-- Baris tombol tambah submenu di kanan -->

                            <input type="hidden" id="urandkey">
                            <!-- Hidden key unik untuk id item submenu pada mode update -->

                            <button type="button" onclick="addSubU()" class="btn btn-primary-light">Tambah Sub Menu <i class="fa fa-plus"></i></button>
                            <!-- Tombol tambah input submenu baru; type=button agar tidak submit -->
                        </div>
                        <!-- Penutup row tombol -->

                        <ul class="list-group" id="ulistsub"></ul>
                        <!-- Container list item submenu untuk update (diisi lewat setSub() atau addSubU()) -->
                    </div>
                    <!-- Penutup vTypeSubU -->
                </div>
                <!-- Penutup modal-body -->

                <div class="modal-footer">
                    <!-- Footer modal -->

                    <button type="submit" class="btn btn-success">Simpan Perubahan <i class="fe fe-check"></i></button>
                    <!-- Submit update -->

                    <a href="javascript:void(0)" onclick="resetU()" class="btn btn-light" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
                    <!-- Tombol batal: reset form update lalu tutup modal -->
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
    // Script validasi & manipulasi form update menu/submenu

    function validateFormUpdate() {
        // Validasi sebelum submit update; return false untuk mencegah submit

        const icon = document.forms["myFormU"]["uicon"].value;
        // Ambil nilai icon update

        const judul = document.forms["myFormU"]["ujudul"].value;
        // Ambil nilai judul update

        const redirect = document.forms["myFormU"]["uredirect"].value;
        // Ambil nilai redirect update (wajib jika type=1)

        const type = document.forms["myFormU"]["utype"].value;
        // Ambil nilai type update

        if (icon == "") {
            // Icon wajib
            validasi('Icon wajib di isi!', 'warning');
            $("input[name='uicon']").addClass('is-invalid');
            return false;
        } else if (judul == '') {
            // Judul wajib
            validasi('Judul wajib di isi!', 'warning');
            $("input[name='ujudul']").addClass('is-invalid');
            return false;
        } else if (type == '') {
            // Type wajib dipilih
            validasi('Type wajib di pilih!', 'warning');
            $("input[name='utype']").addClass('is-invalid');
            // Catatan: ini select; selector yang pas biasanya `select[name="utype"]` (tapi tidak diubah)
            return false;
        } else if (type == 1) {
            // Jika type menu utama
            if (redirect == "") {
                // Redirect wajib untuk menu utama
                validasi('Redirect wajib di isi!', 'warning');
                $("input[name='uredirect']").addClass('is-invalid');
                return false;
            }
        } else if (type == 2) {
            // Jika type submenu
            if ($('#ulistsub li').length == 0) {
                // Harus ada minimal 1 item submenu
                validasi('Belum ada Sub Menu!', 'warning');
                return false;
            }
        }

    }
    // Penutup validateFormUpdate

    function resetU() {
        // Reset field pada form update

        $("input[name='uicon']").val('');
        // Kosongkan icon

        $("input[name='ujudul']").val('');
        // Kosongkan judul

        $("select[name='utype']").val('');
        // Reset type

        $("input[name='uredirect']").val('');
        // Kosongkan redirect

        $("#ulistsub").empty();
        // Hapus item submenu yang tampil

        setTypeU();
        // Sembunyikan/atur ulang tampilan section berdasarkan type
    }
    // Penutup resetU

    document.getElementById('urandkey').value = makeid(10);
    // Inisialisasi key unik pertama untuk list item submenu update (pakai makeid dari script lain)

    function setTypeU() {
        // Toggle tampilan berdasarkan type update

        if ($("select[name='utype']").val() == 1) {
            // Type menu utama
            $("#vTypeMenuU").removeClass('d-none');
            // Tampilkan field redirect
            $("#vTypeSubU").addClass('d-none');
            // Sembunyikan section submenu
        } else if ($("select[name='utype']").val() == 2) {
            // Type submenu
            $("#vTypeMenuU").addClass('d-none');
            // Sembunyikan redirect
            $("#vTypeSubU").removeClass('d-none');
            // Tampilkan section submenu
        } else {
            // Type belum dipilih
            $("#vTypeMenuU").addClass('d-none');
            $("#vTypeSubU").addClass('d-none');
            // Sembunyikan keduanya
        }
    }
    // Penutup setTypeU

    function setSub(data) {
        // Mengisi list submenu berdasarkan data array (biasanya hasil passing dari Blade ke JS)

        for (let i = 0; i < data.length; i++) {
            // Loop tiap item submenu

            const key = $("#urandkey").val();
            // Ambil key unik untuk id li

            $("#ulistsub").append('<li class="list-group-item list-sub-menu p-0" id="' + key + '">' +
                // Append li dengan id unik agar bisa dihapus per item
                '<div class="d-flex">' +
                // Wrapper flex
                '<input type="text" autocomplete="off" value="' + data[i].submenu_judul + '" name="usubjudul[]" class="form-control border-0 me-4" placeholder="Sub Menu">' +
                // Input judul submenu terisi value dari data
                '<input type="text" autocomplete="off" value="' + data[i].submenu_redirect + '" name="uredirectsub[]" class="form-control border-0" placeholder="/redirect">' +
                // Input redirect submenu terisi value dari data
                '<div class="p-2"><button onclick="hapusSubU(`' + key + '`)" type="button" class="btn btn-danger-light"><i class="fa fa-trash"></i></button></div>' +
                // Tombol hapus item submenu (memanggil hapusSubU)
                '</div>' +
                // Tutup flex
                '</li>'
                // Tutup li
            );
            // Penutup append

            $("#urandkey").val(makeid(10));
            // Generate key baru untuk item berikutnya
        }
    }
    // Penutup setSub

    function addSubU() {
        // Tambahkan item submenu kosong (mode update)

        const key = $("#urandkey").val();
        // Ambil key unik

        $("#ulistsub").append('<li class="list-group-item list-sub-menu p-0" id="' + key + '">' +
            // Append li baru
            '<div class="d-flex">' +
            // Wrapper flex
            '<input type="text" autocomplete="off" name="usubjudul[]" class="form-control border-0 me-4" placeholder="Sub Menu">' +
            // Input judul submenu (kosong)
            '<input type="text" autocomplete="off" name="uredirectsub[]" class="form-control border-0" placeholder="/redirect">' +
            // Input redirect submenu (kosong)
            '<div class="p-2"><button onclick="hapusSub(`' + key + '`)" type="button" class="btn btn-danger-light"><i class="fa fa-trash"></i></button></div>' +
            // Tombol hapus; catatan: di sini memanggil `hapusSub(...)` bukan `hapusSubU(...)` (tapi saya tidak ubah)
            '</div>' +
            // Tutup flex
            '</li>'
            // Tutup li
        );
        // Penutup append

        $("#urandkey").val(makeid(10));
        // Generate key baru untuk item berikutnya
    }
    // Penutup addSubU

    function hapusSubU(key) {
        // Hapus item submenu update berdasarkan key

        $("#" + key).remove();
        // Remove elemen li dengan id=key
    }
    // Penutup hapusSubU
</script>
