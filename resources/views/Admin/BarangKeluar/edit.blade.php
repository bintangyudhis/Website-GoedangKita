<!-- MODAL EDIT --> <!-- Judul/penanda bagian: modal untuk edit data -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8"> <!-- Modal Bootstrap, backdrop static = klik luar tidak menutup -->
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> <!-- Ukuran besar (lg) dan posisi tengah -->
        <div class="modal-content modal-content-demo"> <!-- Konten utama modal -->
            <div class="modal-header"> <!-- Header modal -->
                <h6 class="modal-title">Ubah Barang Keluar</h6> <!-- Judul modal -->
                <button aria-label="Close" onclick="resetU()" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol close: ketika diklik menjalankan resetU() dan menutup modal -->
                    <span aria-hidden="true">&times;</span> <!-- Ikon silang -->
                </button>
            </div>

            <div class="modal-body"> <!-- Isi form modal -->
                <div class="row"> <!-- Grid row Bootstrap -->

                    <div class="col-md-6"> <!-- Kolom kiri -->
                        <input type="hidden" name="idbkU"> <!-- Hidden: menyimpan id barang keluar yang diedit -->

                        <div class="form-group"> <!-- Grup input kode barang keluar -->
                            <label for="bkkodeU" class="form-label">
                                Kode Barang Keluar <span class="text-danger">*</span>
                                <!-- Label input + tanda wajib -->
                            </label>
                            <input type="text" name="bkkodeU" readonly class="form-control" placeholder="">
                            <!-- Input kode barang keluar, readonly (tidak bisa diubah) -->
                        </div>

                        <div class="form-group"> <!-- Grup input tanggal keluar -->
                            <label for="tglkeluarU" class="form-label">
                                Tanggal Keluar <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="tglkeluarU" class="form-control datepicker-date" placeholder="">
                            <!-- Input tanggal, punya class datepicker-date (biasanya di-init plugin datepicker) -->
                        </div>

                        <div class="form-group"> <!-- Grup input tujuan -->
                            <label for="tujuanU" class="form-label">Tujuan</label> <!-- Label tujuan -->
                            <input type="text" name="tujuanU" class="form-control" placeholder="">
                            <!-- Input teks tujuan -->
                        </div>
                    </div>

                    <div class="col-md-6"> <!-- Kolom kanan -->

                        <div class="form-group"> <!-- Grup input kode barang -->
                            <label>
                                Kode Barang <span class="text-danger me-1">*</span> <!-- Label + tanda wajib -->
                                <input type="hidden" id="statusU" value="true">
                                <!-- Hidden status: penanda apakah kode barang valid atau tidak -->

                                <div class="spinner-border spinner-border-sm d-none" id="loaderkdU" role="status">
                                    <!-- Loader kecil, awalnya disembunyikan (d-none) -->
                                    <span class="visually-hidden">Loading...</span> <!-- Aksesibilitas -->
                                </div>
                            </label>

                            <div class="input-group"> <!-- Input group Bootstrap -->
                                <input type="text" class="form-control" autocomplete="off" name="kdbarangU" placeholder="">
                                <!-- Input kode barang, autocomplete off -->

                                <button class="btn btn-primary-light" onclick="searchBarangU()" type="button">
                                    <!-- Tombol cari manual (berdasarkan kode input) -->
                                    <i class="fe fe-search"></i> <!-- Ikon -->
                                </button>

                                <button class="btn btn-success-light" onclick="modalBarangU()" type="button">
                                    <!-- Tombol buka modal daftar barang -->
                                    <i class="fe fe-box"></i> <!-- Ikon -->
                                </button>
                            </div>
                        </div>

                        <div class="form-group"> <!-- Grup tampilan nama barang -->
                            <label>Nama Barang</label> <!-- Label nama barang -->
                            <input type="text" class="form-control" id="nmbarangU" readonly>
                            <!-- Menampilkan nama barang hasil lookup, readonly -->
                        </div>

                        <div class="row"> <!-- Row untuk satuan & jenis -->
                            <div class="col-md-6"> <!-- Kolom satuan -->
                                <div class="form-group">
                                    <label>Satuan</label>
                                    <input type="text" class="form-control" id="satuanU" readonly>
                                    <!-- Menampilkan satuan barang, readonly -->
                                </div>
                            </div>

                            <div class="col-md-6"> <!-- Kolom jenis -->
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <input type="text" class="form-control" id="jenisU" readonly>
                                    <!-- Menampilkan jenis barang, readonly -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group"> <!-- Grup input jumlah keluar -->
                            <label for="jmlU" class="form-label">
                                Jumlah Keluar <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="jmlU"
                                class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                placeholder=""
                            >
                            <!-- Input jumlah keluar, oninput: filter hanya angka dan satu titik desimal -->
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal-footer"> <!-- Footer modal -->
                <button class="btn btn-success d-none" id="btnLoaderU" type="button" disabled="">
                    <!-- Tombol loader saat proses simpan, default hidden -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkFormU()" id="btnSimpanU" class="btn btn-success">
                    <!-- Tombol simpan: jalankan validasi checkFormU() -->
                    Simpan Perubahan <i class="fe fe-check"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-light" onclick="resetU()" data-bs-dismiss="modal">
                    <!-- Tombol batal: reset form dan tutup modal -->
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>

        </div>
    </div>
</div>

@section('formEditJS') {{-- Section Blade untuk menaruh JS khusus form edit --}}
<script> // Awal script JS/jQuery

    $('input[name="kdbarangU"]').keypress(function(event) { // Event keypress pada input kdbarangU
        var keycode = (event.keyCode ? event.keyCode : event.which); // Ambil kode tombol yang ditekan
        if (keycode == '13') { // Jika tombol Enter (13)
            getbarangbyidU($('input[name="kdbarangU"]').val()); // Panggil fungsi ambil barang berdasarkan id/kode
        }
    });

    function modalBarangU() { // Fungsi untuk membuka modal daftar barang
        $('#modalBarang').modal('show'); // Tampilkan modalBarang (modal daftar barang)
        $('#Umodaldemo8').addClass('d-none'); // Sembunyikan modal edit dengan class d-none (catatan: ini bukan cara standar bootstrap toggle)
        $('input[name="param"]').val('ubah'); // Set parameter hidden 'param' menjadi 'ubah' (dipakai untuk membedakan mode)
        resetValidU(); // Hilangkan tanda invalid pada semua input
        table2.ajax.reload(); // Reload datatable table2 (list barang) agar data fresh
    }

    function searchBarangU() { // Fungsi tombol cari kode barang
        getbarangbyidU($('input[name="kdbarangU"]').val()); // Panggil lookup barang sesuai input kode
        resetValidU(); // Reset validasi (hapus is-invalid)
    }

    function getbarangbyidU(id) { // Fungsi AJAX ambil data barang berdasarkan id/kode
        $("#loaderkdU").removeClass('d-none'); // Tampilkan loader
        $.ajax({ // Mulai AJAX jQuery
            type: 'GET', // Method GET
            url: "{{ url('admin/barang/getbarang') }}/" + id, // URL endpoint + id barang (Blade url())
            processData: false, // Tidak memproses data (sebenarnya untuk GET ini tidak terlalu perlu)
            contentType: false, // Tidak set contentType (juga tidak terlalu perlu untuk GET)
            dataType: 'json', // Ekspektasi response JSON
            success: function(data) { // Callback jika sukses
                if (data.length > 0) { // Jika ada data barang ditemukan
                    $("#loaderkdU").addClass('d-none'); // Sembunyikan loader
                    $("#statusU").val("true"); // Status valid
                    $("#nmbarangU").val(data[0].barang_nama); // Isi nama barang
                    $("#satuanU").val(data[0].satuan_nama); // Isi satuan
                    $("#jenisU").val(data[0].jenisbarang_nama); // Isi jenis barang
                } else { // Jika data kosong (barang tidak ditemukan)
                    $("#loaderkdU").addClass('d-none'); // Sembunyikan loader
                    $("#statusU").val("false"); // Status invalid
                    $("#nmbarangU").val(''); // Kosongkan nama
                    $("#satuanU").val(''); // Kosongkan satuan
                    $("#jenisU").val(''); // Kosongkan jenis
                }
            }
        });
    }

    function checkFormU() { // Fungsi validasi sebelum submit
        const tglkeluar = $("input[name='tglkeluarU']").val(); // Ambil nilai tanggal keluar
        const status = $("#statusU").val(); // Ambil status validasi barang
        const kdbarang = $("input[name='kdbarangU").val();
        // BUG: selector kurang penutup quote dan kurung -> harusnya $("input[name='kdbarangU']").val();

        const tujuan = $("input[name='tujuanU']").val(); // Ambil tujuan
        const jml = $("input[name='jmlU']").val(); // Ambil jumlah
        setLoadingU(true); // Aktifkan tampilan loading (tombol loader muncul)
        resetValidU(); // Bersihkan class is-invalid

        if (tglkeluar == "") { // Jika tanggal keluar kosong
            validasi('Tanggal Keluar wajib di isi!', 'warning'); // Tampilkan notifikasi (fungsi validasi custom)
            $("input[name='tglkeluarU']").addClass('is-invalid'); // Tandai input invalid
            setLoading(Ufalse);
            // BUG: harusnya setLoadingU(false); dan Ufalse tidak ada
            return false; // Stop proses
        } else if (status == "false" || kdbarang == '') { // Jika barang tidak valid atau kdbarang kosong
            validasi('Barang wajib di pilih!', 'warning'); // Notifikasi
            $("input[name='kdbarangU']").addClass('is-invalid'); // Tandai invalid
            setLoadingU(false); // Matikan loading
            return false; // Stop
        }  else if (jml == "" || jml == "0") { // Jika jumlah kosong atau nol
            validasi('Jumlah Masuk wajib di isi!', 'warning');
            // CATATAN: teks seharusnya "Jumlah Keluar", bukan "Jumlah Masuk" (biar konsisten)
            $("input[name='jmlU']").addClass('is-invalid'); // Tandai invalid
            setLoadingU(false); // Matikan loading
            return false; // Stop
        } else { // Jika semua valid
            submitFormU(); // Kirim data ke server
        }
    }

    function submitFormU() { // Fungsi submit AJAX edit
        const id = $("input[name='idbkU']").val(); // Ambil id barang keluar yang diedit
        const bkkode = $("input[name='bkkodeU']").val(); // Ambil kode barang keluar
        const tglkeluar = $("input[name='tglkeluarU']").val(); // Ambil tanggal keluar
        const kdbarang = $("input[name='kdbarangU']").val(); // Ambil kode barang
        const tujuan = $("input[name='tujuanU']").val(); // Ambil tujuan
        const jml = $("input[name='jmlU']").val(); // Ambil jumlah keluar

        $.ajax({ // Mulai AJAX POST
            type: 'POST', // Method POST
            url: "{{ url('admin/barang-keluar/proses_ubah') }}/" + id, // Endpoint ubah + id
            enctype: 'multipart/form-data', // Atribut enctype (tidak diperlukan jika tidak upload file)
            data: { // Data yang dikirim ke server
                bkkode: bkkode, // Kirim kode barang keluar
                tglkeluar: tglkeluar, // Kirim tanggal keluar
                barang: kdbarang, // Kirim kode barang (field bernama barang)
                tujuan: tujuan, // Kirim tujuan
                jml: jml // Kirim jumlah keluar
            },
            success: function(data) { // Jika sukses
                swal({ // SweetAlert notifikasi
                    title: "Berhasil diubah!", // Judul alert
                    type: "success" // Tipe sukses (versi swal tertentu memakai icon: 'success')
                });
                $('#Umodaldemo8').modal('toggle'); // Tutup modal edit
                table.ajax.reload(null, false); // Reload datatable utama tanpa reset pagination
                resetU(); // Reset form edit
            }
        });
    }

    function resetValidU() { // Fungsi menghapus status invalid pada semua input
        $("input[name='tglkeluarU']").removeClass('is-invalid'); // Hapus invalid tglkeluar
        $("input[name='kdbarangU']").removeClass('is-invalid'); // Hapus invalid kdbarang
        $("input[name='tujuanU']").removeClass('is-invalid'); // Hapus invalid tujuan
        $("input[name='jmlU']").removeClass('is-invalid'); // Hapus invalid jumlah
    };

    function resetU() { // Fungsi reset seluruh nilai form edit
        resetValidU(); // Reset validasi dulu
        $("input[name='idbkU']").val(''); // Kosongkan id
        $("input[name='bkkodeU']").val(''); // Kosongkan kode barang keluar
        $("input[name='tglkeluarU']").val(''); // Kosongkan tanggal
        $("input[name='kdbarangU']").val(''); // Kosongkan kode barang
        $("input[name='tujuanU']").val(''); // Kosongkan tujuan
        $("input[name='jmlU']").val('0'); // Set jumlah default 0
        $("#nmbarangU").val(''); // Kosongkan nama barang
        $("#satuanU").val(''); // Kosongkan satuan
        $("#jenisU").val(''); // Kosongkan jenis
        $("#statusU").val('false'); // Set status invalid default (agar wajib pilih barang lagi)
        setLoadingU(false); // Matikan loading (tampilkan tombol simpan)
    }

    function setLoadingU(bool) { // Fungsi toggle tombol simpan vs loader
        if (bool == true) { // Jika sedang loading
            $('#btnLoaderU').removeClass('d-none'); // Tampilkan tombol loader
            $('#btnSimpanU').addClass('d-none'); // Sembunyikan tombol simpan
        } else { // Jika tidak loading
            $('#btnSimpanU').removeClass('d-none'); // Tampilkan tombol simpan
            $('#btnLoaderU').addClass('d-none'); // Sembunyikan tombol loader
        }
    }
</script>
@endsection {{-- Akhir section --}}
