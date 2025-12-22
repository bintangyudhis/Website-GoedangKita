<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <!-- Modal Bootstrap tambah user; backdrop static agar tidak tertutup saat klik luar; id dipanggil oleh tombol "Tambah Data" -->

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <!-- Dialog modal ukuran besar (modal-lg) dan centered di tengah layar -->

        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal; class tambahan dari template -->

            <div class="modal-header">
                <!-- Header modal -->

                <h6 class="modal-title">Tambah User</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <!-- Judul modal + tombol close; data-bs-dismiss menutup modal -->
            </div>
            <!-- Penutup modal-header -->

            <form method="POST" action="{{ route('user.store') }}" name="myForm" enctype="multipart/form-data" onsubmit="return validateForm()">
                <!-- Form tambah user; submit ke route user.store; onsubmit validasi JS; enctype karena ada upload foto -->

                @csrf
                <!-- CSRF token Laravel -->

                <div class="modal-body">
                    <!-- Body modal berisi form input -->

                    <div class="row">
                        <!-- Row layout form: kiri data, kanan foto -->

                        <div class="col-md-7">
                            <!-- Kolom kiri untuk field user (7/12) -->

                            <div class="form-group">
                                <!-- Grup input nama lengkap -->
                                <label for="nmlengkap" class="form-label">Nama Lengkap</label>
                                <!-- Label nama lengkap -->
                                <input type="text" name="nmlengkap" class="form-control" placeholder="Nama Lengkap..">
                                <!-- Input nama lengkap -->
                            </div>
                            <!-- Penutup form-group nama lengkap -->

                            <div class="form-group">
                                <!-- Grup input username -->
                                <label for="username" class="form-label">Username</label>
                                <!-- Label username -->
                                <input type="text" name="username" class="form-control" placeholder="Username..">
                                <!-- Input username/login -->
                            </div>
                            <!-- Penutup form-group username -->

                            <div class="form-group">
                                <!-- Grup input email -->
                                <label for="email" class="form-label">Email</label>
                                <!-- Label email -->
                                <input type="email" name="email" class="form-control" placeholder="Email@mail.com..">
                                <!-- Input email; type=email memberi validasi browser dasar -->
                            </div>
                            <!-- Penutup form-group email -->

                            <div class="form-group">
                                <!-- Grup select role -->
                                <label for="role" class="form-label">Role</label>
                                <!-- Label role -->
                                <select name="role" class="form-control">
                                    <!-- Dropdown role -->
                                    <option value="">-- Pilih --</option>
                                    <!-- Opsi default kosong -->
                                    @foreach($role as $r)
                                    <!-- Loop data role dari controller -->
                                    <option value="{{$r->role_id}}">{{$r->role_title}}</option>
                                    <!-- Opsi role: value=role_id, teks=role_title -->
                                    @endforeach
                                    <!-- Penutup loop role -->
                                </select>
                                <!-- Penutup select role -->
                            </div>
                            <!-- Penutup form-group role -->

                            <div class="form-group">
                                <!-- Grup input password -->
                                <label for="pwd" class="form-label">Password</label>
                                <!-- Label password -->
                                <input type="password" name="pwd" class="form-control" placeholder="Password..">
                                <!-- Input password -->
                            </div>
                            <!-- Penutup form-group password -->

                            <div class="form-group">
                                <!-- Grup input ulangi password -->
                                <label for="pwdU" class="form-label">Ulangi Password</label>
                                <!-- Label konfirmasi password -->
                                <input type="password" name="pwdU" class="form-control" placeholder="Password..">
                                <!-- Input konfirmasi password -->
                            </div>
                            <!-- Penutup form-group ulangi password -->
                        </div>
                        <!-- Penutup kolom kiri -->

                        <div class="col-md-5">
                            <!-- Kolom kanan untuk foto (5/12) -->

                            <div class="form-group">
                                <!-- Grup foto -->
                                <label for="title" class="form-label">Foto</label>
                                <!-- Label foto (for mengarah ke title, tapi ini hanya label; dibiarkan) -->

                                <center>
                                    <!-- Center preview gambar -->
                                    <img src="{{url('/assets/default/users/undraw_profile.svg')}}" width="80%" alt="profile-user" id="outputImg" class="brround">
                                    <!-- Preview foto default; id outputImg untuk diganti via JS saat pilih file -->
                                </center>

                                <input class="form-control mt-5" id="GetFile" name="photo" type="file" onchange="VerifyFileNameAndFileSize()" accept=".png,.jpeg,.jpg,.svg">
                                <!-- Input file upload foto; onchange validasi ekstensi & size; accept batasi tipe file -->
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

                    <button type="submit" class="btn btn-primary">Simpan <i class="fe fe-check"></i></button>
                    <!-- Submit form tambah user -->

                    <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
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
    // Validasi form tambah user + helper reset + validasi file foto

    function validateForm() {
        // Ambil nilai input dari form myForm
        const namaL = document.forms["myForm"]["nmlengkap"].value;
        // Nama lengkap
        const user = document.forms["myForm"]["username"].value;
        // Username
        const email = document.forms["myForm"]["email"].value;
        // Email
        const role = document.forms["myForm"]["role"].value;
        // Role
        const pwd = document.forms["myForm"]["pwd"].value;
        // Password
        const kpwd = document.forms["myForm"]["pwdU"].value;
        // Konfirmasi password

        resetValid();
        // Bersihkan state invalid sebelum validasi baru

        if (namaL == "") {
            // Nama lengkap wajib
            validasi('Nama Lengkap wajib di isi!', 'warning');
            $("input[name='nmlengkap']").addClass('is-invalid');
            return false;
        } else if (user == '') {
            // Username wajib
            validasi('Username wajib di isi!', 'warning');
            $("input[name='username']").addClass('is-invalid');
            return false;
        } else if (email == '') {
            // Email wajib
            validasi('Email wajib di isi!', 'warning');
            $("input[name='email']").addClass('is-invalid');
            return false;
        } else if (role == '') {
            // Role wajib dipilih
            validasi('Role wajib di pilih!', 'warning');
            $("select[name='role']").addClass('is-invalid');
            return false;
        } else if (pwd == '') {
            // Password wajib
            validasi('Password wajib di isi!', 'warning');
            $("input[name='pwd']").addClass('is-invalid');
            $("input[name='pwdU']").addClass('is-invalid');
            return false;
        } else if (pwd !== '' || kpwd !== '') {
            // Jika password/konfirmasi terisi, cek aturan tambahan

            if (pwd.length < 6) {
                // Minimal 6 karakter
                validasi('Panjang Password minimal 6 karakter!', 'warning');
                $("input[name='pwd']").addClass('is-invalid');
                $("input[name='pwdU']").addClass('is-invalid');
                return false;
            } else if (pwd !== kpwd) {
                // Harus sama dengan konfirmasi
                validasi('Konfirmasi Password tidak sesuai!', 'warning');
                $("input[name='pwd']").addClass('is-invalid');
                $("input[name='pwdU']").addClass('is-invalid');
                return false;
            }

        }

    }
    // Penutup validateForm

    function resetValid() {
        // Hapus class invalid pada semua input yang mungkin error
        $("input[name='nmlengkap']").removeClass('is-invalid');
        $("input[name='username']").removeClass('is-invalid');
        $("input[name='email']").removeClass('is-invalid');
        $("input[name='role']").removeClass('is-invalid');
        // Catatan: role itu select, tapi dibiarkan karena tidak mengubah kode
        $("input[name='pwd']").removeClass('is-invalid');
        $("input[name='pwdU']").removeClass('is-invalid');
    };
    // Penutup resetValid

    function reset() {
        // Reset semua field form ke kondisi awal
        resetValid();
        $("input[name='nmlengkap']").val('');
        $("input[name='username']").val('');
        $("input[name='email']").val('');
        $("input[name='role']").val('');
        // Catatan: role itu select; biasanya `select[name="role"]` (tapi tidak diubah)
        $("input[name='pwd']").val('');
        $("input[name='pwdU']").val('');
        $("#outputImg").attr("src", "{{url('/assets/default/users/undraw_profile.svg')}}");
        // Kembalikan preview ke foto default
        $("#GetFile").val('');
        // Kosongkan input file
    }
    // Penutup reset

    function fileIsValid(fileName) {
        // Validasi ekstensi file (hanya gambar tertentu)

        var ext = fileName.match(/\.([^\.]+)$/)[1];
        // Ambil ekstensi dari nama file
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
                // Kosongkan (di konteks ini `this` bisa bukan input; dibiarkan)
                isValid = false;
        }
        return isValid;
        // Kembalikan hasil validasi
    }
    // Penutup fileIsValid

    function VerifyFileNameAndFileSize() {
        // Validasi file yang dipilih + set preview gambar

        var file = document.getElementById('GetFile').files[0];
        // Ambil file pertama dari input file

        if (file != null) {
            // Jika ada file dipilih
            var fileName = file.name;
            // Nama file

            if (fileIsValid(fileName) == false) {
                // Jika ekstensi tidak valid
                validasi('Format bukan gambar!', 'warning');
                document.getElementById('GetFile').value = null;
                // Kosongkan file input
                return false;
            }

            var content;
            // Variabel tidak dipakai (sisa implementasi); dibiarkan

            var size = file.size;
            // Ukuran file (bytes)

            if ((size != null) && ((size / (1024 * 1024)) > 3)) {
                // Jika ukuran > 3 MB
                validasi('Ukuran maximum 1024px', 'warning');
                // Pesan warning (teks menyebut px, logic MB; dibiarkan)
                document.getElementById('GetFile').value = null;
                // Kosongkan file input
                return false;
            }

            var ext = fileName.match(/\.([^\.]+)$/)[1];
            // Ambil ekstensi lagi (sebenarnya sudah di fileIsValid)
            ext = ext.toLowerCase();
            // Normalisasi ekstensi

            // $(".custom-file-label").addClass("selected").html(file.name);
            // (opsional) update label file input (dikomentari)

            document.getElementById('outputImg').src = window.URL.createObjectURL(file);
            // Set preview gambar dari file lokal yang dipilih

            return true;
            // Lolos validasi

        } else
            return false;
            // Jika tidak ada file dipilih
    }
    // Penutup VerifyFileNameAndFileSize
</script>
