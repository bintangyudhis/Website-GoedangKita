<!-- MODAL EFFECTS -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <!-- Modal Bootstrap: `fade` animasi; backdrop static = klik luar tidak menutup; id untuk dipanggil dari tombol -->

    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Pembungkus dialog; centered supaya modal berada di tengah -->

        <div class="modal-content modal-content-demo">
            <!-- Konten modal; class tambahan dari template -->

            <div class="modal-header">
                <!-- Header modal (judul + tombol close) -->

                <h6 class="modal-title">Tambah Menu</h6><button onclick="reset()" aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <!-- Judul modal + tombol close (1 baris); onclick reset() untuk bersihkan form sebelum tutup -->
            </div>
            <!-- Penutup modal-header -->

            <form method="POST" action="{{ route('menu.store') }}" name="myForm" enctype="multipart/form-data" onsubmit="return validateForm()">
                <!-- Form tambah menu: POST ke route menu.store; onsubmit validasi JS; enctype disiapkan walau tanpa upload -->

                @csrf
                <!-- CSRF token Laravel -->

                <div class="modal-body">
                    <!-- Body modal berisi field form -->

                    <div class="form-group">
                        <!-- Grup field icon -->

                        <label for="judul" class="form-label">Icon</label>
                        <!-- Label field icon (catatan: for="judul" sebenarnya mengarah ke id judul, tapi ini hanya label) -->

                        <span class="text-gray d-block mb-1">Cari & salin nama dari Icon <a target="_blank" href="https://feathericons.com/">https://feathericons.com/</a></span></span>
                        <!-- Info bantuan; link ke feathericons; (ada </span> ekstra, tapi saya tidak ubah) -->

                        <div class="input-group">
                            <!-- Input group Bootstrap untuk prefix + input -->

                            <span class="input-group-text bg-gray-light" id="basic-addon1">fe-</span>
                            <!-- Prefix icon `fe-` agar user hanya isi nama icon (contoh: home) -->

                            <input type="text" name="icon" class="form-control" placeholder="home" aria-label="icon" aria-describedby="basic-addon1">
                            <!-- Input nama icon; aria-describedby mengaitkan dengan addon prefix -->
                        </div>
                        <!-- Penutup input-group -->
                    </div>
                    <!-- Penutup form-group icon -->

                    <div class="form-group">
                        <!-- Grup field judul -->

                        <label for="judul" class="form-label">Judul</label>
                        <!-- Label judul -->

                        <input type="text" id="judul" name="judul" class="form-control" placeholder="Judul Menu">
                        <!-- Input judul menu; id dipakai oleh label for -->
                    </div>
                    <!-- Penutup form-group judul -->

                    <div class="form-group">
                        <!-- Grup field type (menu / submenu) -->

                        <label for="type" class="form-label">Type</label>
                        <!-- Label type -->

                        <select name="type" class="form-control" onchange="setType()">
                            <!-- Select type; onchange setType() untuk show/hide bagian redirect atau submenu -->

                            <option value="">-- Pilih --</option>
                            <!-- Opsi default kosong -->
                            <option value="1">Menu</option>
                            <!-- Type = 1 (menu utama) -->
                            <option value="2">Sub Menu</option>
                            <!-- Type = 2 (punya daftar submenu) -->
                        </select>
                        <!-- Penutup select -->
                    </div>
                    <!-- Penutup form-group type -->

                    <div class="form-group d-none" id="vTypeMenu">
                        <!-- Grup redirect untuk type Menu; default disembunyikan pakai d-none -->

                        <label for="redirect" class="form-label">Redirect</label>
                        <!-- Label redirect -->

                        <input type="text" id="redirect" name="redirect" class="form-control" placeholder="/redirect">
                        <!-- Input redirect (route/url) untuk menu utama -->
                    </div>
                    <!-- Penutup vTypeMenu -->

                    <div class="form-group d-none" id="vTypeSub">
                        <!-- Grup untuk type Sub Menu; default disembunyikan -->

                        <div class="d-flex justify-content-end mb-2">
                            <!-- Baris atas: tombol tambah submenu di kanan -->

                            <input type="hidden" id="randkey">
                            <!-- Hidden untuk menyimpan key unik item submenu (dipakai sebagai id <li>) -->

                            <button type="button" onclick="addSub()" class="btn btn-primary-light">Tambah Sub Menu <i class="fa fa-plus"></i></button>
                            <!-- Tombol tambah baris submenu; type=button supaya tidak submit form -->
                        </div>
                        <!-- Penutup row tombol -->

                        <ul class="list-group" id="listsub"></ul>
                        <!-- Container list submenu yang akan diappend via JS -->
                    </div>
                    <!-- Penutup vTypeSub -->
                </div>
                <!-- Penutup modal-body -->

                <div class="modal-footer">
                    <!-- Footer modal (tombol aksi) -->

                    <button type="submit" class="btn btn-primary">Simpan <i class="fe fe-check"></i></button>
                    <!-- Submit form tambah menu -->

                    <a href="javascript:void(0)" onclick="reset()" class="btn btn-light" data-bs-dismiss="modal">Batal <i class="fe fe-x"></i></a>
                    <!-- Tombol batal: reset form lalu tutup modal -->
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
    // Script validasi & manipulasi UI form tambah menu

    function validateForm() {
        // Validasi form sebelum submit; return false untuk mencegah submit

        const icon = document.forms["myForm"]["icon"].value;
        // Ambil nilai input icon dari form myForm

        const judul = document.forms["myForm"]["judul"].value;
        // Ambil nilai input judul

        const type = document.forms["myForm"]["type"].value;
        // Ambil nilai select type

        const redirect = document.forms["myForm"]["redirect"].value;
        // Ambil nilai input redirect (relevan jika type=1)

        if (icon == "") {
            // Jika icon kosong -> tampilkan warning
            validasi('Icon wajib di isi!', 'warning');
            // Panggil swal via fungsi validasi()
            $("input[name='icon']").addClass('is-invalid');
            // Tambahkan class invalid untuk styling error
            return false;
            // Hentikan submit
        } else if (judul == '') {
            // Jika judul kosong
            validasi('Judul wajib di isi!', 'warning');
            $("input[name='judul']").addClass('is-invalid');
            return false;
        } else if (type == '') {
            // Jika type belum dipilih
            validasi('Type wajib di pilih!', 'warning');
            $("input[name='type']").addClass('is-invalid');
            // Catatan: yang ini select, biasanya selector yang tepat `select[name="type"]` (tapi saya tidak ubah)
            return false;
        } else if (type == 1) {
            // Jika type menu utama (1)
            if (redirect == "") {
                // Redirect wajib untuk menu utama
                validasi('Redirect wajib di isi!', 'warning');
                $("input[name='redirect']").addClass('is-invalid');
                return false;
            }
        } else if (type == 2) {
            // Jika type submenu (2)
            if ($('#listsub li').length == 0){
                // Pastikan minimal ada 1 item submenu yang ditambahkan
                validasi('Belum ada Sub Menu!', 'warning');
                return false;
            }
        }

    }
    // Penutup validateForm

    function reset() {
        // Reset field-field form ke kondisi awal

        $("input[name='icon']").val('');
        // Kosongkan input icon

        $("input[name='judul']").val('');
        // Kosongkan input judul

        $("select[name='type']").val('');
        // Reset pilihan type

        $("input[name='redirect']").val('');
        // Kosongkan input redirect

        $("#listsub").empty();
        // Hapus semua item submenu yang sudah ditambahkan

        setType();
        // Panggil setType() agar bagian vTypeMenu/vTypeSub kembali disembunyikan
    }
    // Penutup reset

    document.getElementById('randkey').value = makeid(10);
    // Inisialisasi randkey pertama kali dengan string random panjang 10

    function makeid(length) {
        // Generator string acak untuk key unik list item submenu

        var result = '';
        // Variabel hasil random

        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        // Kumpulan karakter yang mungkin dipakai

        var charactersLength = characters.length;
        // Panjang kumpulan karakter

        for (var i = 0; i < length; i++) {
            // Loop sepanjang length
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            // Ambil char acak lalu gabungkan ke result
        }
        return result;
        // Kembalikan string random
    }
    // Penutup makeid

    function setType() {
        // Show/hide field berdasarkan nilai type

        $("#listsub").empty();
        // Bersihkan list submenu saat type berubah (menghindari data lama nyangkut)

        if ($("select[name='type']").val() == 1) {
            // Jika pilih type Menu
            $("#vTypeMenu").removeClass('d-none');
            // Tampilkan field redirect
            $("#vTypeSub").addClass('d-none');
            // Sembunyikan area submenu
        } else if ($("select[name='type']").val() == 2) {
            // Jika pilih type Sub Menu
            $("#vTypeMenu").addClass('d-none');
            // Sembunyikan field redirect
            $("#vTypeSub").removeClass('d-none');
            // Tampilkan area list submenu
        } else {
            // Jika belum pilih type / kosong
            $("#vTypeMenu").addClass('d-none');
            // Sembunyikan keduanya
            $("#vTypeSub").addClass('d-none');
            // Sembunyikan keduanya
        }
    }
    // Penutup setType


    function addSub() {
        // Tambahkan 1 baris input submenu ke dalam #listsub

        const key = $("#randkey").val();
        // Ambil key unik untuk dijadikan id <li>

        $("#listsub").append('<li class="list-group-item list-sub-menu p-0" id="' + key + '">' +
            // Append li baru ke listsub; id=key supaya bisa dihapus per-item
            '<div class="d-flex">' +
            // Wrapper flex untuk 2 input + tombol delete
            '<input type="text" autocomplete="off" required name="subjudul[]" class="form-control border-0 me-4" placeholder="Sub Menu">' +
            // Input judul submenu; name array agar terkirim multiple
            '<input type="text" autocomplete="off" required name="redirectsub[]" class="form-control border-0" placeholder="/redirect">' +
            // Input redirect submenu; name array
            '<div class="p-2"><button onclick="hapusSub(`' + key + '`)" type="button" class="btn btn-danger-light"><i class="fa fa-trash"></i></button></div>' +
            // Tombol hapus item; memanggil hapusSub(key); type=button agar tidak submit
            '</div>' +
            // Tutup wrapper flex
            '</li>'
            // Tutup li
        );
        // Penutup append

        $("#randkey").val(makeid(10));
        // Generate key baru untuk item berikutnya
    }
    // Penutup addSub

    function hapusSub(key) {
        // Hapus item submenu berdasarkan id (key)

        $("#" + key).remove();
        // Remove elemen <li> yang id-nya sesuai key
    }
    // Penutup hapusSub
</script>
