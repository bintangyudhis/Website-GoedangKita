@extends('Master.Layouts.app', ['title' => $title])
{{-- Extend layout utama dan kirim variabel title ke layout --}}

@section('content')
{{-- Mulai section content --}}

<!-- PAGE-HEADER -->
<div class="page-header">
    {{-- Wrapper header halaman --}}

    <h1 class="page-title">Pengaturan Website</h1>
    {{-- Judul halaman --}}

    <div>
        {{-- Wrapper breadcrumb --}}
        <ol class="breadcrumb">
            {{-- List breadcrumb --}}
            <li class="breadcrumb-item text-gray">Admin</li>
            {{-- Breadcrumb level 1 --}}
            <li class="breadcrumb-item active" aria-current="page">Pengaturan Website</li>
            {{-- Breadcrumb aktif (halaman saat ini) --}}
        </ol>
        {{-- Penutup breadcrumb --}}
    </div>
    {{-- Penutup wrapper breadcrumb --}}
</div>
<!-- PAGE-HEADER END -->

<div class="row">
    {{-- Row utama, berisi 2 kolom: profil website & form ubah pengaturan --}}

    <div class="col-12 col-md-12 col-lg-6 mb-4">
        {{-- Kolom kiri: tampilkan ringkasan profil website; full di mobile, setengah di lg --}}

        <div class="card border-0 shadow">
            {{-- Card profil website; border-0 tanpa border; shadow untuk bayangan --}}

            <div class="card-header">
                {{-- Header card --}}
                <h6 class="fw-bold mt-2">Profil Website</h6>
                {{-- Judul card --}}
            </div>
            {{-- Penutup card-header --}}

            <div class="card-body">
                {{-- Body card --}}

                @foreach($data as $d)
                {{-- Loop data website (biasanya hanya 1 record, tapi tetap dibuat foreach) --}}

                <div class="text-center py-5 mb-4">
                    {{-- Area logo di tengah dengan padding vertikal --}}

                    @if($d->web_logo == '' || $d->web_logo == 'default.png')
                    {{-- Jika logo kosong atau default, tampilkan logo default dari assets --}}
                    <img src="{{ url('assets/default/web/default.png') }}" alt="logo" width="120">
                    {{-- Gambar logo default --}}
                    @else
                    {{-- Jika ada logo custom, ambil dari storage/web --}}
                    <img src="{{asset('storage/web/' . $d->web_logo)}}" alt="logo" width="120">
                    {{-- Gambar logo dari storage --}}
                    @endif
                    {{-- Penutup kondisi logo --}}
                </div>
                {{-- Penutup area logo --}}

                @endforeach
                {{-- Penutup foreach data --}}

                <div class="d-flex justify-content-between mx-4">
                    {{-- Baris info judul website; flex untuk kiri-kanan; mx-4 margin horizontal --}}
                    <h6 class="mr-4">Judul Website</h6>
                    {{-- Label judul --}}
                    <h6 class="font-weight-bold">{{$d->web_nama}}</h6>
                    {{-- Nilai judul website (pakai $d dari loop sebelumnya) --}}
                </div>

                <hr class="">
                {{-- Garis pemisah --}}

                <div class="d-flex justify-content-between mx-4">
                    {{-- Baris info deskripsi website --}}
                    <h6 class="mr-4">Deskripsi</h6>
                    {{-- Label deskripsi --}}
                    <h6 class="font-weight-bold">{{$d->web_deskripsi == "" ? "-" : $d->web_deskripsi}}</h6>
                    {{-- Nilai deskripsi; jika kosong tampilkan "-" --}}
                </div>
            </div>
            {{-- Penutup card-body --}}
        </div>
        {{-- Penutup card --}}
    </div>
    {{-- Penutup kolom kiri --}}

    <div class="col-12 col-md-12 col-lg-6 mb-4">
        {{-- Kolom kanan: form update pengaturan website --}}

        <form action="{{ route('web.update', $d->web_id) }}" method="POST" name="myForm" enctype="multipart/form-data" onsubmit="return validateForm()">
            {{-- Form update website; route web.update dengan parameter web_id; onsubmit validasi JS; enctype karena upload logo --}}

            <div class="card shadow border-0">
                {{-- Card untuk form update --}}

                <div class="card-header">
                    {{-- Header card form --}}
                    <h6 class="mt-2 fw-bold">Ubah Pengaturan</h6>
                    {{-- Judul form --}}
                </div>
                {{-- Penutup card-header --}}

                <div class="card-body">
                    {{-- Body form --}}

                    @csrf
                    {{-- CSRF token Laravel --}}

                    @method('PUT')
                    {{-- Spoof method PUT untuk update --}}

                    <div class="alert alert-info alert-icon d-flex shadow" role="alert">
                        {{-- Alert info mengenai ekstensi gambar; d-flex untuk layout icon + konten --}}

                        <div class="alert-icon-aside">
                            {{-- Bagian icon alert --}}
                            <i class="fa fa-exclamation-circle"></i>
                            {{-- Icon peringatan/info (fontawesome) --}}
                        </div>

                        <div class="alert-icon-content ms-1 mt-1">
                            {{-- Konten alert; ms-1 margin kiri; mt-1 margin atas --}}
                            <h6 class="alert-heading">Extensi Gambar</h6>
                            {{-- Judul alert --}}
                            .jpg .jpeg .png
                            {{-- Info ekstensi yang disarankan (teks) --}}
                        </div>
                        {{-- Penutup konten alert --}}
                    </div>
                    {{-- Penutup alert --}}

                    <div class="form-group">
                        {{-- Grup input logo --}}
                        <label for="formFile" class="form-label mt-0">Logo</label>
                        {{-- Label logo --}}
                        <input class="form-control" id="GetFile" name="photo" type="file" accept=".png,.jpeg,.jpg,.svg" onchange="VerifyFileNameAndFileSize()">
                        {{-- Input file logo; accept membatasi tipe; onchange validasi ekstensi & ukuran --}}
                    </div>
                    {{-- Penutup form-group logo --}}

                    <div class="form-group">
                        {{-- Grup input nama website --}}
                        <label>Judul Website</label>
                        {{-- Label judul website --}}
                        <input type="text" class="form-control" name="nmweb" value="{{$d->web_nama}}">
                        {{-- Input judul website; value dari database --}}
                    </div>
                    {{-- Penutup form-group nmweb --}}

                    <div class="form-group">
                        {{-- Grup textarea deskripsi --}}
                        <label>Deskripsi Website</label>
                        {{-- Label deskripsi --}}
                        <textarea name="desk" rows="5" class="form-control">{{$d->web_deskripsi}}</textarea>
                        {{-- Textarea deskripsi website; rows=5 tinggi awal --}}
                    </div>
                    {{-- Penutup form-group desk --}}

                </div>
                {{-- Penutup card-body form --}}

                <div class="card-footer">
                    {{-- Footer form --}}
                    <div class="mb-2">
                        {{-- Wrapper tombol dengan margin bawah --}}
                        <button type="submit" class="btn btn-success btn-md shadow">Simpan Perubahan
                            <i class="fa fa-check-circle"></i></button>
                        {{-- Tombol submit simpan perubahan --}}
                    </div>
                    {{-- Penutup wrapper tombol --}}
                </div>
                {{-- Penutup card-footer --}}
            </div>
            {{-- Penutup card form --}}
        </form>
        {{-- Penutup form --}}
    </div>
    {{-- Penutup kolom kanan --}}
</div>
{{-- Penutup row utama --}}

@endsection
{{-- Tutup section content --}}

@section('scripts')
{{-- Section scripts khusus halaman --}}

<script>
    // Validasi form update pengaturan website

    function validateForm() {
        // Ambil nilai judul website (nmweb) dari form myForm
        var nmweb = document.forms["myForm"]["nmweb"].value;

        if (nmweb == "") {
            // Jika judul kosong, tampilkan warning dan tandai input invalid
            validasi('Judul Website wajib di isi!', 'warning');
            $("input[name='nmweb']").addClass('is-invalid');
            return false;
        }

    }
    // Penutup validateForm

    function validasi(judul, status) {
        // Helper SweetAlert untuk menampilkan pesan
        swal({
            title: judul,
            type: status,
            confirmButtonText: "Iya."
        });
    }
    // Penutup validasi

    function fileIsValid(fileName) {
        // Validasi ekstensi file upload logo

        var ext = fileName.match(/\.([^\.]+)$/)[1];
        // Ambil ekstensi (setelah titik terakhir)

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
                // Kosongkan (konteks `this` tidak selalu input file; tapi tidak diubah)
                isValid = false;
        }
        return isValid;
        // Kembalikan hasil validasi
    }
    // Penutup fileIsValid

    function VerifyFileNameAndFileSize() {
        // Validasi file logo: ekstensi + ukuran

        var file = document.getElementById('GetFile').files[0];
        // Ambil file pertama dari input file #GetFile

        if (file != null) {
            // Jika file dipilih

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
            // Variabel tidak dipakai (sisa implementasi); dibiarkan

            var size = file.size;
            // Ukuran file dalam bytes

            if ((size != null) && ((size / (1024 * 1024)) > 3)) {
                // Jika ukuran file > 3MB
                validasi('Ukuran maximum 1024px', 'warning');
                // Pesan warning (teks menyebut px; logic berbasis MB; dibiarkan)
                document.getElementById('GetFile').value = null;
                // Kosongkan input file
                return false;
            }

            var ext = fileName.match(/\.([^\.]+)$/)[1];
            // Ambil ekstensi lagi (sebenarnya sudah dicek di fileIsValid)
            ext = ext.toLowerCase();
            // Normalisasi ekstensi

            return true;
            // Lolos validasi

        } else
            return false;
            // Jika tidak ada file dipilih
    }
    // Penutup VerifyFileNameAndFileSize
</script>
@endsection
{{-- Tutup section scripts --}}
