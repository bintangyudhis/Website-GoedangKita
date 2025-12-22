<!-- MODAL UBAH -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    <!-- Modal Bootstrap untuk ubah user; backdrop static agar tidak tertutup saat klik luar -->

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <!-- Dialog modal ukuran besar (modal-lg) dan posisinya di tengah -->

        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal; class tambahan dari template -->

            <div class="modal-header">
                <!-- Header modal -->

                <h6 class="modal-title">Ubah User</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <!-- Judul modal + tombol close; data-bs-dismiss menutup modal -->
            </div>
            <!-- Penutup modal-header -->

            <form method="POST" name="myFormU" id="myFormU" enctype="multipart/form-data" onsubmit="return validateFormUpdate()">
                <!-- Form update user; action biasanya diset lewat JS (update(data)); enctype karena upload foto -->

                @csrf
                <!-- CSRF token Laravel -->

                @method('PUT')
                <!-- Spoof method PUT untuk request update -->

                <div class="modal-body">
                    <!-- Body modal berisi field update -->

                    <div class="row">
                        <!-- Layout row: kiri data user, kanan foto -->

                        <div class="col-md-7">
                            <!-- Kolom kiri untuk data user (7/12) -->

                            <div class="form-group">
                                <!-- Grup input nama lengkap -->
                                <label for="nmlengkapU" class="form-label">Nama Lengkap</label>
                                <!-- Label nama lengkap -->
                                <input type="text" name="nmlengkapU" class="form-control" placeholder="Nama Lengkap..">
                                <!-- Input nama lengkap (diisi via JS saat klik edit) -->
                            </div>
                            <!-- Penutup form-group nmlengkapU -->

                            <div class="form-group">
                                <!-- Grup input username -->
                                <label for="usernameU" class="form-label">Username</label>
                                <!-- Label username -->
                                <input type="text" name="usernameU" class="form-control" placeholder="Username..">
                                <!-- Input username (diisi via JS) -->
                            </div>
                            <!-- Penutup form-group usernameU -->

                            <div class="form-group">
                                <!-- Grup input email -->
                                <label for="emailU" class="form-label">Email</label>
                                <!-- Label email -->
                                <input type="email" name="emailU" class="form-control" placeholder="Email@mail.com..">
                                <!-- Input email (diisi via JS); type=email validasi dasar browser -->
                            </div>
                            <!-- Penutup form-group emailU -->

                            <div class="form-group">
                                <!-- Grup select role -->
                                <label for="roleU" class="form-label">Role</label>
                                <!-- Label role -->
                                <select name="roleU" class="form-control">
                                    <!-- Dropdown role user -->
                                    <option value="">-- Pilih --</option>
                                    <!-- Opsi default kosong -->
                                    @foreach($role as $r)
                                    <!-- Loop role untuk opsi -->
                                    <option value="{{$r->role_id}}">{{$r->role_title}}</option>
                                    <!-- Value role_id, label role_title -->
                                    @endforeach
                                    <!-- Penutup loop role -->
                                </select>
                                <!-- Penutup select roleU -->
                            </div>
                            <!-- Penutup form-group roleU -->

                            <div class="alert alert-info" role="alert">
                                <!-- Info: password boleh dikosongkan jika tidak ingin diubah -->
                                <span class="alert-inner--icon"><i class="fe fe-info"></i></span>
                                <!-- Icon info -->
                                <span class="alert-inner--text">Lewati jika tidak ingin merubah password.</span>
                                <!-- Pesan petunjuk -->
                            </div>
                            <!-- Penutup alert info password -->

                            <div class="form-group">
                                <!-- Grup input password (opsional) -->
                                <label for="pwdU" class="form-label">Password</label>
                                <!-- Label password -->
                                <input type="password" name="pwdU" class="form-control" placeholder="Password..">
                                <!-- Input password baru (boleh kosong jika tidak ubah) -->
                            </div>
                            <!-- Penutup form-group pwdU -->

                            <div class="form-group">
                                <!-- Grup input ulangi password (opsional) -->
                                <label for="pwdUU" class="form-label">Ulangi Password</label>
                                <!-- Label konfirmasi password -->
                                <input type="password" name="pwdUU" class="form-control" placeholder="Password..">
                                <!-- Input konfirmasi password baru -->
                            </div>
                            <!-- Penutup form-group pwdUU -->
                        </div>
                        <!-- Penutup kolom kiri -->

                        <div class="col-md-5">
                            <!-- Kolom kanan untuk foto (5/12) -->

                            <div class="alert alert-info" role="alert">
                                <!-- Info: foto boleh dikosongkan jika tidak ingin diubah -->
                                <span class="alert-inner--icon"><i class="fe fe-info"></i></span>
                                <!-- Icon info -->
                                <span class="alert-inner--text">Lewati jika tidak ingin merubah foto.</span>
                                <!-- Pesan petunjuk -->
                            </div>
                            <!-- Penutup alert info foto -->

                            <div class="form-group">
                                <!-- Grup foto -->
                                <label for="title" class="form-label">Foto</label>
                                <!-- Label foto (for="title" hanya label; dibiarkan) -->

                                <center>
                                    <!-- Center preview foto -->
                                    <img src="{{url('/assets/default/users/undraw_profile.svg')}}" width="80%" alt="profile-user" id="outputImgU" class="brround">
                                    <!-- Preview foto update; id outputImgU akan diganti via JS bila pilih file -->
                                </center>

                                <input type="hidden" name="flama" id="flama">
                                <!-- Hidden untuk menyimpan nama file foto lama (dipakai di backend saat update) -->

                                <input class="form-control mt-5" id="GetFileU" name="photoU" type="file" onchange="VerifyFileNameAndFileSizeU()" accept=".png,.jpeg,.jpg,.svg">
                                <!-- Input upload foto baru; onchange validasi ekstensi & size; accept batasi tipe file -->
                            </div>
                            <!-- Penutup form-group foto -->
                        </div>
                        <!-- Penutup kolom kanan -->
                    </div>
                    <!-- Penutup row -->
                </div>
                <!-- Penutup modal-body -->

                <div class="modal-footer">
                    <!-- Footer modal (tombol aksi) -->

                    <button type="submit" class="btn btn-success">Simpan Perubahan <i class="fe fe-check"></i></button>
                    <!-- Submit update user -->

                    <a href="javascript:void(0)" class="btn btn-light" onclick="resetU()" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
                    <!-- Tombol batal: reset form update + tutup modal -->
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
    // Validasi form update user + helper reset + validasi file foto

    function validateFormUpdate() {
        // Ambil nilai field dari form myFormU
        const namaL = document.forms["myFormU"]["nmlengkapU"].value;
        // Nama lengkap
        const user = document.forms["myFormU"]["usernameU"].value;
        // Username
        const email = document.forms["myFormU"]["emailU"].value;
        // Email
        const role = document.forms["myFormU"]["roleU"].value;
        // Role
        const pwd = document.forms["myFormU"]["pwdU"].value;
        // Password baru (opsional)
        const kpwd = document.forms["myFormU"]["pwdUU"].value;
        // Konfirmasi password baru (opsional)

        resetValidU();
        // Bersihkan state invalid sebelum validasi baru

        if (namaL == "") {
            // Nama lengkap wajib
            validasi('Nama Lengkap wajib di isi!', 'warning');
            $("input[name='nmlengkapU']").addClass('is-invalid');
            return false;
        } else if (user == '') {
            // Username wajib
            validasi('Username wajib di isi!', 'warning');
            $("input[name='usernameU']").addClass('is-invalid');
            return false;
        } else if (email == '') {
            // Email wajib
            validasi('Email wajib di isi!', 'warning');
            $("input[name='emailU']").addClass('is-invalid');
            return false;
        } else if (role == '') {
            // Role wajib dipilih
            validasi('Role wajib di pilih!', 'warning');
            $("select[name='roleU']").addClass('is-invalid');
            return false;
        } else if (pwd !== '' || kpwd !== '') {
            // Jika salah satu field password diisi, maka lakukan validasi password

            if (pwd.length < 6) {
                // Minimal 6 karakter
                validasi('Panjang Password minimal 6 karakter!', 'warning');
                $("input[name='pwdU']").addClass('is-invalid');
                $("input[name='pwdUU']").addClass('is-invalid');
                return false;
            } else if (pwd !== kpwd) {
                // Password & konfirmasi harus sama
                validasi('Konfirmasi Password tidak sesuai!', 'warning');
                $("input[name='pwdU']").addClass('is-invalid');
                $("input[name='pwdUU']").addClass('is-invalid');
                return false;
            }

        }

    }
    // Penutup validateFormUpdate

    function resetValidU() {
        // Hapus class invalid untuk semua field update
        $("input[name='nmlengkapU']").removeClass('is-invalid');
        $("input[name='usernameU']").removeClass('is-invalid');
        $("input[name='emailU']").removeClass('is-invalid');
        $("input[name='roleU']").removeClass('is-invalid');
        // Catatan: roleU itu select; biasanya `select[name="roleU"]` (tapi tidak diubah)
        $("input[name='pwdU']").removeClass('is-invalid');
        $("input[name='pwdUU']").removeClass('is-invalid');
    };
    // Penutup resetValidU

    function resetU() {
        // Reset semua field update ke kosong + reset preview foto
        resetValidU();
        $("input[name='nmlengkapU']").val('');
        $("input[name='usernameU']").val('');
        $("input[name='emailU']").val('');
        $("input[name='roleU']").val('');
        // Catatan: roleU itu select; biasanya `select[name="roleU"]` (tapi tidak diubah)
        $("input[name='pwdU']").val('');
        $("input[name='pwdUU']").val('');
        $("input[name='flama']").val('');
        // Kosongkan nama file foto lama
        $("#outputImgU").attr("src", "{{url('/assets/default/users/undraw_profile.svg')}}");
        // Kembalikan preview foto ke default
        $("#GetFileU").val('');
        // Kosongkan input file
    }
    // Penutup resetU

    function fileIsValidU(fileName) {
        // Validasi ekstensi file (gambar) untuk update foto

        var ext = fileName.match(/\.([^\.]+)$/)[1];
        // Ambil ekstensi file
        ext = ext.toLowerCase();
        // Normalisasi ekstensi
        var isValid = true;
        // Flag validasi
        switch (ext) {
            case 'png':
            case 'jpeg':
            case 'jpg':
            case 'svg':
                // Ekstensi valid
                break;
            default:
                this.value = '';
                // Kosongkan (dalam konteks ini `this` bisa bukan input; dibiarkan)
                isValid = false;
        }
        return isValid;
        // Kembalikan hasil validasi
    }
    // Penutup fileIsValidU

    function VerifyFileNameAndFileSizeU() {
        // Validasi file foto update + set preview gambar

        var file = document.getElementById('GetFileU').files[0];
        // Ambil file pertama dari input file update

        if (file != null) {
            // Jika file dipilih
            var fileName = file.name;
            // Nama file

            if (fileIsValidU(fileName) == false) {
                // Jika ekstensi tidak valid
                validasi('Format bukan gambar!', 'warning');
                document.getElementById('GetFileU').value = null;
                // Kosongkan input file
                return false;
            }

            var content;
            // Variabel tidak dipakai (sisa implementasi); dibiarkan

            var size = file.size;
            // Ukuran file dalam bytes

            if ((size != null) && ((size / (1024 * 1024)) > 3)) {
                // Jika size > 3 MB
                validasi('Ukuran maximum 1024px', 'warning');
                // Pesan warning (teks menyebut px meski logic MB; dibiarkan)
                document.getElementById('GetFileU').value = null;
                // Kosongkan input file
                return false;
            }

            var ext = fileName.match(/\.([^\.]+)$/)[1];
            // Ambil ekstensi lagi (sebenarnya sudah dicek di fileIsValidU)
            ext = ext.toLowerCase();
            // Normalisasi ekstensi

            // $(".custom-file-label").addClass("selected").html(file.name);
            // (opsional) update label input file (dikomentari)

            document.getElementById('outputImgU').src = window.URL.createObjectURL(file);
            // Tampilkan preview foto baru dari file lokal

            return true;
            // Lolos validasi

        } else
            return false;
            // Jika tidak ada file dipilih
    }
    // Penutup VerifyFileNameAndFileSizeU
</script>

