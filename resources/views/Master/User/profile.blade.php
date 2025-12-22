@extends('Master.Layouts.app', ['title' => $title])
{{-- Extend layout utama dan kirim variabel title ke layout --}}

@section('content')
{{-- Mulai section content --}}

<!-- PAGE-HEADER -->
<div class="page-header">
    {{-- Wrapper header halaman --}}

    <h1 class="page-title">Profile</h1>
    {{-- Judul halaman --}}

    <div>
        {{-- Wrapper breadcrumb --}}
        <ol class="breadcrumb">
            {{-- List breadcrumb --}}
            <li class="breadcrumb-item text-gray">User</li>
            {{-- Breadcrumb level 1 --}}
            <li class="breadcrumb-item active" aria-current="page">Profile</li>
            {{-- Breadcrumb aktif (halaman sekarang) --}}
        </ol>
        {{-- Penutup breadcrumb --}}
    </div>
    {{-- Penutup wrapper breadcrumb --}}
</div>
<!-- PAGE-HEADER END -->

<div class="row mb-5">
    {{-- Row utama; mb-5 memberi margin bawah --}}

    <div class="col-xl-4">
        {{-- Kolom kiri untuk edit password (4/12 pada xl) --}}

        <div class="card">
            {{-- Card edit password --}}

            <div class="card-header">
                {{-- Header card --}}
                <div class="card-title">Edit Password</div>
                {{-- Judul card --}}
            </div>
            {{-- Penutup card-header --}}

            <form action="{{url('/admin/updatePassword').'/'.$data->user_id}}" method="POST" name="myFormP" enctype="multipart/form-data" onsubmit="return validatePassword()">
                {{-- Form update password; action ke /admin/updatePassword/{user_id}; onsubmit validasi JS --}}

                @csrf
                {{-- CSRF token Laravel --}}

                <div class="card-body">
                    {{-- Body form password --}}

                    <div class="text-center chat-image mb-5">
                        {{-- Area profil ringkas (foto + nama + role) --}}

                        <div class="avatar avatar-xxl chat-profile mb-3 brround">
                            {{-- Container avatar (bulat) ukuran besar --}}

                            @if($data->user_foto == 'undraw_profile.svg' || $data->user_foto == '')
                            {{-- Jika foto default atau kosong, pakai gambar default --}}
                            <img src="{{url('/assets/default/users/undraw_profile.svg')}}" alt="profile-user">
                            {{-- Gambar default --}}
                            @else
                            {{-- Jika ada foto user di storage --}}
                            <img src="{{asset('storage/users/'.$data->user_foto)}}" alt="profile-user">
                            {{-- Gambar user dari storage/users --}}
                            @endif
                            {{-- Tutup kondisi foto --}}
                        </div>
                        {{-- Penutup avatar wrapper --}}

                        <div class="main-chat-msg-name me-4">
                            {{-- Wrapper nama & role --}}
                            <h5 class="mb-1 text-dark fw-semibold">{{$data->user_nmlengkap}}</h5>
                            {{-- Tampilkan nama lengkap user --}}
                            <p class="text-muted mt-0 mb-0 pt-0 fs-13">{{$data->role_title}}</p>
                            {{-- Tampilkan role user --}}
                        </div>
                        {{-- Penutup wrapper nama/role --}}
                    </div>
                    {{-- Penutup area profil ringkas --}}

                    <div class="form-group">
                        {{-- Grup input password saat ini --}}
                        <label class="form-label">Password Saat Ini</label>
                        {{-- Label password saat ini --}}

                        <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                            {{-- Input group + wrapper (biasanya ada fitur show/hide password dari template) --}}

                            <a href="javascript:void(0)" tabindex="-1" class="input-group-text bg-white text-muted">
                                {{-- Tombol/icon mata; tabindex -1 agar tidak ikut tab focus --}}
                                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                {{-- Icon eye (zmdi) untuk toggle show/hide --}}
                            </a>
                            {{-- Penutup tombol icon eye --}}

                            <input class="input100 form-control" value="{{Session::get('currentpassword')}}" name="currentpassword" type="password" placeholder="Password Saat Ini">
                            {{-- Input password saat ini; value dari session (untuk mengisi ulang jika validasi gagal) --}}
                        </div>
                        {{-- Penutup input group password saat ini --}}
                    </div>
                    {{-- Penutup form-group current password --}}

                    <div class="form-group">
                        {{-- Grup input password baru --}}
                        <label class="form-label">Password Baru</label>
                        {{-- Label password baru --}}

                        <div class="wrap-input100 validate-input input-group" id="Password-toggle1">
                            {{-- Wrapper toggle password baru --}}
                            <a href="javascript:void(0)" tabindex="-1" class="input-group-text bg-white text-muted">
                                {{-- Tombol/icon mata --}}
                                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                {{-- Icon eye --}}
                            </a>
                            {{-- Penutup tombol icon eye --}}

                            <input class="input100 form-control" value="{{Session::get('newpassword')}}" name="newpassword" type="password" placeholder="Password Baru">
                            {{-- Input password baru; value dari session jika perlu re-render --}}
                        </div>
                        {{-- Penutup input group password baru --}}
                    </div>
                    {{-- Penutup form-group new password --}}

                    <div class="form-group">
                        {{-- Grup input konfirmasi password --}}
                        <label class="form-label">Konfirmasi Password</label>
                        {{-- Label konfirmasi --}}

                        <div class="wrap-input100 validate-input input-group" id="Password-toggle2">
                            {{-- Wrapper toggle konfirmasi --}}
                            <a href="javascript:void(0)" tabindex="-1" class="input-group-text bg-white text-muted">
                                {{-- Tombol/icon mata --}}
                                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                {{-- Icon eye --}}
                            </a>
                            {{-- Penutup tombol icon eye --}}

                            <input class="input100 form-control" value="{{Session::get('confirmpassword')}}" type="password" name="confirmpassword" placeholder="Konfirmasi Password">
                            {{-- Input konfirmasi password; value dari session jika perlu re-render --}}
                        </div>
                        {{-- Penutup input group konfirmasi --}}
                    </div>
                    {{-- Penutup form-group konfirmasi --}}
                </div>
                {{-- Penutup card-body password --}}

                <div class="card-footer text-end">
                    {{-- Footer form password; text-end agar tombol di kanan --}}

                    <button type="submit" class="btn btn-primary">Perbarui</button>
                    {{-- Submit update password --}}

                    <a href="javascript:void(0)" onclick="resetP()" class="btn btn-danger my-1">Batal</a>
                    {{-- Tombol batal: panggil resetP() untuk kosongkan field password --}}
                </div>
                {{-- Penutup card-footer password --}}
            </form>
            {{-- Penutup form password --}}
        </div>
        {{-- Penutup card kolom kiri --}}
    </div>
    {{-- Penutup col-xl-4 --}}

    <div class="col-xl-8">
        {{-- Kolom kanan untuk edit profile (8/12 pada xl) --}}

        <div class="card">
            {{-- Card edit profile --}}

            <div class="card-header">
                {{-- Header card --}}
                <h3 class="card-title">Edit Profile</h3>
                {{-- Judul card --}}
            </div>
            {{-- Penutup card-header --}}

            <form action="{{url('/admin/updateProfile').'/'.$data->user_id}}" method="POST" name="myFormUpdate" enctype="multipart/form-data" onsubmit="return validateUpdate()">
                {{-- Form update profile; action ke /admin/updateProfile/{user_id}; onsubmit validasi JS --}}

                @csrf
                {{-- CSRF token Laravel --}}

                <div class="card-body">
                    {{-- Body form update profile --}}

                    <div class="form-group">
                        {{-- Grup input nama lengkap --}}
                        <label for="nmlengkap">Nama Lengkap</label>
                        {{-- Label nama lengkap --}}
                        <input type="text" name="nmlengkap" value="{{$data->user_nmlengkap}}" class="form-control" id="nmlengkap" placeholder="Nama Lengkap">
                        {{-- Input nama lengkap; value diisi dari data user --}}
                    </div>
                    {{-- Penutup form-group nmlengkap --}}

                    <div class="form-group">
                        {{-- Grup input username --}}
                        <label for="username">Nama User</label>
                        {{-- Label username --}}
                        <input type="text" name="username" value="{{$data->user_nama}}" class="form-control" id="username" placeholder="Nama User">
                        {{-- Input username; value diisi dari data user --}}
                    </div>
                    {{-- Penutup form-group username --}}

                    <div class="form-group">
                        {{-- Grup input email --}}
                        <label for="email">Email</label>
                        {{-- Label email --}}
                        <input type="email" name="email" value="{{$data->user_email}}" class="form-control" id="email" placeholder="Email">
                        {{-- Input email; type email untuk validasi browser dasar --}}
                    </div>
                    {{-- Penutup form-group email --}}

                    <div class="form-group">
                        {{-- Grup input foto --}}
                        <label for="img">Foto</label>
                        {{-- Label foto --}}
                        <input class="form-control" id="GetFile" name="photoU" type="file" onchange="VerifyFileNameAndFileSize()" accept=".png,.jpeg,.jpg,.svg">
                        {{-- Input file foto; onchange validasi ekstensi/ukuran; accept membatasi tipe file yang dipilih --}}
                    </div>
                    {{-- Penutup form-group foto --}}

                </div>
                {{-- Penutup card-body update profile --}}

                <div class="card-footer text-end">
                    {{-- Footer form profile; tombol di kanan --}}
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                    {{-- Submit update profile --}}
                    <a href="{{url('/admin/profile')}}/{{Session::get('user')->user_id}}" class="btn btn-danger my-1">Batal</a>
                    {{-- Link batal: kembali ke halaman profile user dari session --}}
                </div>
                {{-- Penutup card-footer profile --}}
            </form>
            {{-- Penutup form update profile --}}
        </div>
        {{-- Penutup card edit profile --}}

    </div>
    {{-- Penutup col-xl-8 --}}
</div>
{{-- Penutup row utama --}}

@endsection
{{-- Tutup section content --}}

@section('scripts')
{{-- Section scripts khusus halaman ini --}}

<script>
    // Validasi form update password

    function validatePassword() {
        // Ambil value dari form myFormP
        const current = document.forms["myFormP"]["currentpassword"].value;
        // Password saat ini
        const newp = document.forms["myFormP"]["newpassword"].value;
        // Password baru
        const confirm = document.forms["myFormP"]["confirmpassword"].value;
        // Konfirmasi password

        resetValidP();
        // Bersihkan state invalid sebelum validasi baru

        if (current == "") {
            // Jika password saat ini kosong
            validasi('Masukan Password Saat Ini!', 'warning');
            $("input[name='currentpassword']").addClass('is-invalid');
            return false;
        }
        if (newp == "") {
            // Jika password baru kosong
            validasi('Masukan Password Baru!', 'warning');
            $("input[name='newpassword']").addClass('is-invalid');
            return false;
        }
        if (confirm == "") {
            // Jika konfirmasi kosong
            validasi('Masukan Konfirmasi Password!', 'warning');
            $("input[name='confirmpassword']").addClass('is-invalid');
            return false;
        } else if (newp !== '' || confirm !== '') {
            // Jika field password baru/konfirmasi terisi (cek aturan panjang dan kecocokan)

            if (newp.length < 6) {
                // Minimal panjang password 6 karakter
                validasi('Panjang Password minimal 6 karakter!', 'warning');
                $("input[name='newpassword']").addClass('is-invalid');
                $("input[name='confirmpassword']").addClass('is-invalid');
                return false;
            } else if (newp !== confirm) {
                // Password baru dan konfirmasi harus sama
                validasi('Konfirmasi Password tidak sesuai!', 'warning');
                $("input[name='newpassword']").addClass('is-invalid');
                $("input[name='confirmpassword']").addClass('is-invalid');
                return false;
            }
        }
    }
    // Penutup validatePassword

    function validateUpdate() {
        // Validasi form update profile

        const nmlengkap = document.forms["myFormUpdate"]["nmlengkap"].value;
        // Nama lengkap
        const username = document.forms["myFormUpdate"]["username"].value;
        // Username
        const email = document.forms["myFormUpdate"]["email"].value;
        // Email

        resetValid();
        // Bersihkan state invalid sebelum validasi baru

        if (nmlengkap == "") {
            // Nama lengkap wajib
            validasi('Nama Lengkap Wajib di isi!', 'warning');
            $("input[name='nmlengkap']").addClass('is-invalid');
            return false;
        } else if (username == "") {
            // Username wajib
            validasi('Nama User Wajib di isi!', 'warning');
            $("input[name='username']").addClass('is-invalid');
            return false;
        } else if (email == "") {
            // Email wajib
            validasi('Email Wajib di isi!', 'warning');
            $("input[name='email']").addClass('is-invalid');
            return false;
        }
    }
    // Penutup validateUpdate

    function resetValidP() {
        // Hapus class invalid pada input password
        $("input[name='currentpassword']").removeClass('is-invalid');
        $("input[name='newpassword']").removeClass('is-invalid');
        $("input[name='confirmpassword']").removeClass('is-invalid');
    };
    // Penutup resetValidP

    function resetValid() {
        // Hapus class invalid pada input profile
        $("input[name='nmlengkap']").removeClass('is-invalid');
        $("input[name='username']").removeClass('is-invalid');
        $("input[name='email']").removeClass('is-invalid');
    };
    // Penutup resetValid

    function resetP() {
        // Reset field password (clear value + clear invalid)
        resetValidP();
        $("input[name='currentpassword']").val('');
        $("input[name='newpassword']").val('');
        $("input[name='confirmpassword']").val('');
    }
    // Penutup resetP

    function validasi(judul, status) {
        // SweetAlert helper untuk menampilkan pesan
        swal({
            title: judul,
            type: status,
            confirmButtonText: "OK"
        });
    }
    // Penutup validasi

    function fileIsValid(fileName) {
        // Cek ekstensi file sesuai whitelist (png/jpeg/jpg/svg)

        var ext = fileName.match(/\.([^\.]+)$/)[1];
        // Ambil ekstensi file dari nama file (setelah titik terakhir)

        ext = ext.toLowerCase();
        // Normalisasi ekstensi ke huruf kecil

        var isValid = true;
        // Flag validasi

        switch (ext) {
            // Cek ekstensi yang diizinkan
            case 'png':
            case 'jpeg':
            case 'jpg':
            case 'svg':
                break;
            default:
                this.value = '';
                // Set value kosong (di konteks ini `this` tidak selalu input file; tapi dibiarkan karena tidak mengubah kode)
                isValid = false;
        }
        return isValid;
        // Kembalikan hasil validasi ekstensi
    }
    // Penutup fileIsValid

    function VerifyFileNameAndFileSize() {
        // Validasi file yang dipilih: ekstensi + ukuran

        var file = document.getElementById('GetFile').files[0];
        // Ambil file pertama dari input file #GetFile

        if (file != null) {
            // Jika ada file dipilih

            var fileName = file.name;
            // Nama file

            if (fileIsValid(fileName) == false) {
                // Jika format tidak valid
                validasi('Format bukan gambar!', 'warning');
                document.getElementById('GetFile').value = null;
                // Kosongkan input file
                return false;
            }

            var content;
            // Variabel tidak dipakai (mungkin sisa implementasi); dibiarkan

            var size = file.size;
            // Ukuran file dalam bytes

            if ((size != null) && ((size / (1024 * 1024)) > 3)) {
                // Jika ukuran file > 3 MB
                validasi('Ukuran maximum 1024px', 'warning');
                // Pesan warning (teks menyebut 1024px meski logic berbasis MB; dibiarkan)
                document.getElementById('GetFile').value = null;
                // Kosongkan input file
                return false;
            }

            var ext = fileName.match(/\.([^\.]+)$/)[1];
            // Ambil ekstensi lagi (sebenarnya sudah di fileIsValid, tapi diulang)
            ext = ext.toLowerCase();
            // Normalisasi ekstensi

            // $(".custom-file-label").addClass("selected").html(file.name);
            // (opsional) update label custom file input (dikomentari)

            // document.getElementById('outputImg').src = window.URL.createObjectURL(file);
            // (opsional) preview gambar dengan URL objek (dikomentari)

            return true;
            // Lolos validasi
        } else
            return false;
            // Jika file null (tidak memilih file)
    }
    // Penutup VerifyFileNameAndFileSize
</script>
@endsection
{{-- Tutup section scripts --}}
