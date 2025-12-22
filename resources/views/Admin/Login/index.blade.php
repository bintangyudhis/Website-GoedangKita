@extends('Master.Layouts.app_login', ['title' => $title])
{{-- Menggunakan layout khusus login dan mengirim variabel title ke layout --}}

@section('content')
{{-- Awal section konten halaman login --}}

<div class="container-login100">
    {{-- Container utama tampilan login (biasanya dari template login100) --}}

    <div class="wrap-login100 p-6">
        {{-- Wrapper kotak login + padding --}}

        <div class="d-flex justify-content-center align-items-center">
            {{-- Flex untuk menempatkan logo di tengah --}}

            @if($web->web_logo == '' || $web->web_logo == 'default.png')
                {{-- Jika logo website kosong atau masih default --}}
                <img src="{{url('/assets/default/web/default.png')}}" height="150px" class="" alt="logo">
                {{-- Menampilkan logo default --}}
            @else
                {{-- Jika logo custom ada --}}
                <img src="{{asset('storage/web/' . $web->web_logo)}}" height="150px" class="" alt="logo">
                {{-- Menampilkan logo dari storage --}}
            @endif
        </div>

        <div class="text-center">
            {{-- Bagian judul halaman --}}
            <h4 class="fw-bold mt-4 text-black text-uppercase text-truncate">
                {{$web->web_nama}}
                {{-- Nama aplikasi/website --}}
                <span class="text-gray">| LOGIN</span>
                {{-- Label tambahan LOGIN --}}
            </h4>
        </div>

        <form class="login100-form validate-form"
              method="POST"
              name="myForm"
              action="{{ url('admin/proseslogin') }}"
              enctype="multipart/form-data"
              onsubmit="return validateForm()">
            {{-- Form login: method POST, action ke proses login, validasi via JS sebelum submit --}}

            @csrf
            {{-- Token CSRF Laravel agar POST aman --}}

            <div class="panel panel-primary">
                {{-- Panel container (komponen tampilan) --}}

                <div class="panel-body tabs-menu-body p-0 pt-5">
                    {{-- Body panel + padding atas --}}

                    <div class="tab-content">
                        {{-- Wrapper tab (meskipun hanya 1 tab) --}}

                        <div class="tab-pane active" id="tab5">
                            {{-- Tab aktif berisi input login --}}

                            <div class="wrap-input100 validate-input input-group"
                                 data-bs-validate="Valid username is required">
                                {{-- Grup input username dengan icon dan validasi dari template --}}

                                <a tabindex="-1" href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    {{-- Icon wrapper (tidak bisa difokuskan tab) --}}
                                    <i class="zmdi zmdi-account text-muted ms-1" aria-hidden="true"></i>
                                    {{-- Icon user --}}
                                </a>

                                <input name="user"
                                       value="{{Session::get('userInput')}}"
                                       class="input100 border-start-0 form-control ms-0"
                                       type="text"
                                       placeholder="Username"
                                       autocomplete="off">
                                {{-- Input username (diisi ulang dari session jika sebelumnya gagal login) --}}
                            </div>

                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                {{-- Grup input password + icon --}}
                                <a tabindex="-1" href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                    {{-- Icon wrapper --}}
                                    <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                    {{-- Icon eye (umumnya untuk toggle show/hide password) --}}
                                </a>

                                <input name="pwd"
                                       class="input100 border-start-0 form-control ms-0"
                                       type="password"
                                       placeholder="Password"
                                       autocomplete="off">
                                {{-- Input password --}}
                            </div>

                            <!-- <div class="text-end pt-4">
                                <p class="mb-0"><a href="forgot-password.html" class="text-primary ms-1">Forgot Password?</a></p>
                            </div> -->
                            {{-- Bagian forgot password masih dikomentari (tidak dipakai) --}}

                            <div class="container-login100-form-btn">
                                {{-- Container tombol login --}}

                                <button type="button"
                                        class="login100-form-btn btn btn-primary d-none"
                                        id="btnLoader"
                                        type="button"
                                        disabled="">
                                    {{-- Tombol loader saat proses login berlangsung (default disembunyikan) --}}
                                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                    {{-- Spinner loading --}}
                                    Loading...
                                </button>

                                <button type="submit"
                                        class="login100-form-btn btn btn-primary"
                                        id="btnLogin">
                                    {{-- Tombol submit login --}}
                                    Login
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </form>
        {{-- Akhir form login --}}
    </div>
</div>

@endsection
{{-- Akhir section content --}}

@section('scripts')
{{-- Section khusus script halaman login --}}

<script>
    function validateForm() {
        // Fungsi validasi sebelum form dikirim ke server

        var usr = document.forms["myForm"]["user"].value;
        // Ambil nilai input username dari form

        var pwd = document.forms["myForm"]["pwd"].value;
        // Ambil nilai input password dari form

        setLoading(true);
        // Aktifkan loading (tampilkan tombol loader, sembunyikan tombol login)

        if (usr == "") {
            // Jika username kosong
            validasi('Username masih kosong!', 'warning');
            // Tampilkan alert warning
            setLoading(false);
            // Matikan loading
            return false;
            // Batalkan submit form
        } else if (pwd == '') {
            // Jika password kosong
            validasi('Password masih kosong!', 'warning');
            // Tampilkan alert warning
            setLoading(false);
            // Matikan loading
            return false;
            // Batalkan submit form
        }

        // Jika valid, form akan lanjut submit
        // (return true tidak wajib karena defaultnya lanjut jika tidak return false)
    }

    function setLoading(bool){
        // Fungsi untuk toggle tombol loader dan tombol login
        if(bool == true){
            $('#btnLoader').removeClass('d-none');
            // Tampilkan tombol loader
            $('#btnLogin').addClass('d-none');
            // Sembunyikan tombol login
        }else{
            $('#btnLogin').removeClass('d-none');
            // Tampilkan tombol login
            $('#btnLoader').addClass('d-none');
            // Sembunyikan loader
        }
    }

    function validasi(judul, status) {
        // Fungsi helper SweetAlert untuk menampilkan pesan
        swal({
            title: judul,
            type: status,
            confirmButtonText: "OK"
        });
    }
</script>

@endsection
{{-- Akhir section scripts --}}
