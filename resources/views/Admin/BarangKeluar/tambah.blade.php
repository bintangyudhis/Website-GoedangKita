<!-- MODAL TAMBAH --> <!-- Penanda: modal untuk menambah transaksi barang keluar -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8"> <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup) -->
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> <!-- Dialog modal ukuran besar dan posisi tengah -->
        <div class="modal-content modal-content-demo"> <!-- Konten utama modal -->
            <div class="modal-header"> <!-- Header modal -->
                <h6 class="modal-title">Tambah Barang Keluar</h6> <!-- Judul modal -->
                <button aria-label="Close" onclick="reset()" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol close: jalankan reset() lalu menutup modal -->
                    <span aria-hidden="true">&times;</span> <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body"> <!-- Body modal: berisi input form -->
                <div class="row"> <!-- Row untuk membagi kolom kiri dan kanan -->

                    <div class="col-md-6"> <!-- Kolom kiri -->
                        <div class="form-group"> <!-- Grup input kode barang keluar -->
                            <label for="bkkode" class="form-label">
                                Kode Barang Keluar <span class="text-danger">*</span> <!-- Tanda wajib -->
                            </label>
                            <input type="text" name="bkkode" readonly class="form-control" placeholder="">
                            <!-- Input kode BK, readonly karena di-generate otomatis -->
                        </div>

                        <div class="form-group"> <!-- Grup input tanggal keluar -->
                            <label for="tglkeluar" class="form-label">
                                Tanggal Keluar <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="tglkeluar" class="form-control datepicker-date" placeholder="">
                            <!-- Input tanggal (biasanya dipakai datepicker dari class datepicker-date) -->
                        </div>

                        <div class="form-group"> <!-- Grup input tujuan -->
                            <label for="tujuan" class="form-label">Tujuan</label> <!-- Label tujuan -->
                            <input type="text" name="tujuan" class="form-control" placeholder="">
                            <!-- Input tujuan (opsional) -->
                        </div>
                    </div>

                    <div class="col-md-6"> <!-- Kolom kanan -->
                        <div class="form-group"> <!-- Grup input kode barang -->
                            <label>
                                Kode Barang <span class="text-danger me-1">*</span> <!-- Label + tanda wajib -->

                                <input type="hidden" id="status" value="false">
                                <!-- Hidden status: penanda apakah barang yang dipilih valid (true/false) -->

                                <div class="spinner-border spinner-border-sm d-none" id="loaderkd" role="status">
                                    <!-- Loader kecil untuk proses cek barang, default disembunyikan -->
                                    <span class="visually-hidden">Loading...</span> <!-- Aksesibilitas -->
                                </div>
                            </label>

                            <div class="input-group"> <!-- Input group untuk input + tombol -->
                                <input type="text" class="form-control" autocomplete="off" name="kdbarang" placeholder="">
                                <!-- Input kode barang manual -->

                                <button class="btn btn-primary-light" onclick="searchBarang()" type="button">
                                    <!-- Tombol untuk cari barang berdasarkan kode yang diketik -->
                                    <i class="fe fe-search"></i>
                                </button>

                                <button class="btn btn-success-light" onclick="modalBarang()" type="button">
                                    <!-- Tombol untuk membuka modal daftar barang (pilih dari tabel) -->
                                    <i class="fe fe-box"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group"> <!-- Grup tampilan nama barang -->
                            <label>Nama Barang</label> <!-- Label nama barang -->
                            <input type="text" class="form-control" id="nmbarang" readonly>
                            <!-- Menampilkan nama barang hasil lookup, readonly -->
                        </div>

                        <div class="row"> <!-- Row untuk satuan dan jenis -->
                            <div class="col-md-6"> <!-- Kolom satuan -->
                                <div class="form-group">
                                    <label>Satuan</label>
                                    <input type="text" class="form-control" id="satuan" readonly>
                                    <!-- Menampilkan satuan barang -->
                                </div>
                            </div>

                            <div class="col-md-6"> <!-- Kolom jenis -->
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <input type="text" class="form-control" id="jenis" readonly>
                                    <!-- Menampilkan jenis barang -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group"> <!-- Grup input jumlah keluar -->
                            <label for="jml" class="form-label">
                                Jumlah Keluar <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="jml" value="0" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                placeholder="">
                            <!-- Input jumlah: default 0, oninput membatasi karakter hanya angka + 1 titik desimal -->
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer"> <!-- Footer modal -->
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    <!-- Tombol loader ketika proses simpan berjalan (hidden saat normal) -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkForm()" id="btnSimpan" class="btn btn-primary">
                    <!-- Tombol simpan: jalankan validasi checkForm() -->
                    Simpan <i class="fe fe-check"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">
                    <!-- Tombol batal: reset input lalu tutup modal -->
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>

        </div>
    </div>
</div>

@section('formTambahJS') {{-- Section Blade: menaruh JS khusus form tambah --}}
<script> // Awal script JS

    $('input[name="kdbarang"]').keypress(function(event) { // Event keypress pada input kode barang
        var keycode = (event.keyCode ? event.keyCode : event.which); // Ambil kode tombol (kompatibel beberapa browser)
        if (keycode == '13') { // Jika tombol Enter ditekan
            getbarangbyid($('input[name="kdbarang"]').val()); // Cari barang berdasarkan kode yang diketik
        }
    });

    function modalBarang() { // Fungsi buka modal daftar barang
        $('#modalBarang').modal('show'); // Tampilkan modal daftar barang
        $('#modaldemo8').addClass('d-none'); // Sembunyikan modal tambah (dengan d-none, bukan toggle standar)
        $('input[name="param"]').val('tambah'); // Set parameter mode menjadi "tambah"
        resetValid(); // Hilangkan semua validasi merah
        table2.ajax.reload(); // Reload datatable daftar barang (table2)
    }

    function searchBarang() { // Fungsi tombol cari barang (ikon search)
        getbarangbyid($('input[name="kdbarang"]').val()); // Lookup barang berdasarkan kode
        resetValid(); // Reset validasi
    }

    function getbarangbyid(id) { // Fungsi AJAX untuk mengambil data barang dari server
        $("#loaderkd").removeClass('d-none'); // Tampilkan loader
        $.ajax({ // Mulai request AJAX
            type: 'GET', // Method GET
            url: "{{ url('admin/barang/getbarang') }}/" + id, // Endpoint getbarang + id/kode
            processData: false, // Tidak proses data (umumnya untuk upload; untuk GET tidak wajib)
            contentType: false, // Tidak set contentType (juga tidak wajib di GET)
            dataType: 'json', // Response berupa JSON
            success: function(data) { // Callback sukses
                if (data.length > 0) { // Jika barang ditemukan
                    $("#loaderkd").addClass('d-none'); // Sembunyikan loader
                    $("#status").val("true"); // Status valid
                    $("#nmbarang").val(data[0].barang_nama); // Isi nama barang
                    $("#satuan").val(data[0].satuan_nama); // Isi satuan
                    $("#jenis").val(data[0].jenisbarang_nama); // Isi jenis
                } else { // Jika barang tidak ditemukan
                    $("#loaderkd").addClass('d-none'); // Sembunyikan loader
                    $("#status").val("false"); // Status invalid
                    $("#nmbarang").val(''); // Kosongkan nama
                    $("#satuan").val(''); // Kosongkan satuan
                    $("#jenis").val(''); // Kosongkan jenis
                }
            }
        });
    }

    function checkForm() { // Fungsi validasi form sebelum submit
        const tglkeluar = $("input[name='tglkeluar']").val(); // Ambil tanggal keluar
        const status = $("#status").val(); // Ambil status valid barang
        const jml = $("input[name='jml']").val(); // Ambil jumlah keluar

        setLoading(true); // Aktifkan loading (tampilkan spinner)
        resetValid(); // Bersihkan is-invalid

        if (tglkeluar == "") { // Jika tanggal kosong
            validasi('Tanggal Keluar wajib di isi!', 'warning'); // Alert warning
            $("input[name='tglkeluar']").addClass('is-invalid'); // Tandai tanggal invalid
            setLoading(false); // Matikan loading
            return false; // Stop
        } else if (status == "false") { // Jika barang belum valid (belum dipilih/lookup gagal)
            validasi('Barang wajib di pilih!', 'warning'); // Alert warning
            $("input[name='kdbarang']").addClass('is-invalid'); // Tandai kode barang invalid
            setLoading(false); // Matikan loading
            return false; // Stop
        } else if (jml == "" || jml == "0") { // Jika jumlah kosong atau 0
            validasi('Jumlah Keluar wajib di isi!', 'warning'); // Alert warning
            $("input[name='jml']").addClass('is-invalid'); // Tandai jumlah invalid
            setLoading(false); // Matikan loading
            return false; // Stop
        } else { // Jika semua valid
            submitForm(); // Lanjut submit
        }
    }

    function submitForm() { // Fungsi untuk mengirim data tambah ke server
        const bkkode = $("input[name='bkkode']").val(); // Ambil kode BK
        const tglkeluar = $("input[name='tglkeluar']").val(); // Ambil tanggal keluar
        const kdbarang = $("input[name='kdbarang']").val(); // Ambil kode barang
        const tujuan = $("input[name='tujuan']").val(); // Ambil tujuan
        const jml = $("input[name='jml']").val(); // Ambil jumlah

        $.ajax({ // Mulai AJAX POST
            type: 'POST', // Method POST
            url: "{{ route('barang-keluar.store') }}", // Endpoint route store (Laravel)
            enctype: 'multipart/form-data', // Catatan: tidak wajib jika tidak upload file
            data: { // Data dikirim ke controller
                bkkode: bkkode, // Field kode BK
                tglkeluar: tglkeluar, // Field tanggal
                barang: kdbarang, // Field barang (kode barang)
                tujuan: tujuan, // Field tujuan
                jml: jml // Field jumlah
            },
            success: function(data) { // Callback ketika berhasil
                $('#modaldemo8').modal('toggle'); // Tutup modal tambah
                swal({ // SweetAlert success
                    title: "Berhasil ditambah!",
                    type: "success"
                });
                table.ajax.reload(null, false); // Reload datatable utama tanpa reset page
                reset(); // Reset seluruh input form tambah
            }
        });
    }

    function resetValid() { // Fungsi menghapus class invalid dari semua input
        $("input[name='tglkeluar']").removeClass('is-invalid'); // Hapus invalid tanggal
        $("input[name='kdbarang']").removeClass('is-invalid'); // Hapus invalid kode barang
        $("input[name='tujuan']").removeClass('is-invalid'); // Hapus invalid tujuan
        $("input[name='jml']").removeClass('is-invalid'); // Hapus invalid jumlah
    };

    function reset() { // Fungsi reset form tambah ke kondisi awal
        resetValid(); // Bersihkan validasi
        $("input[name='bkkode']").val(''); // Kosongkan kode BK
        $("input[name='tglkeluar']").val(''); // Kosongkan tanggal
        $("input[name='kdbarang']").val(''); // Kosongkan kode barang
        $("input[name='tujuan']").val(''); // Kosongkan tujuan
        $("input[name='jml']").val('0'); // Set jumlah jadi 0
        $("#nmbarang").val(''); // Kosongkan nama barang
        $("#satuan").val(''); // Kosongkan satuan
        $("#jenis").val(''); // Kosongkan jenis
        $("#status").val('false'); // Set status invalid (wajib pilih barang lagi)
        setLoading(false); // Matikan loading (tampilkan tombol simpan)
    }

    function setLoading(bool) { // Fungsi toggle tombol simpan vs spinner
        if (bool == true) { // Jika loading aktif
            $('#btnLoader').removeClass('d-none'); // Tampilkan tombol loader
            $('#btnSimpan').addClass('d-none'); // Sembunyikan tombol simpan
        } else { // Jika loading tidak aktif
            $('#btnSimpan').removeClass('d-none'); // Tampilkan tombol simpan
            $('#btnLoader').addClass('d-none'); // Sembunyikan loader
        }
    }

</script>
@endsection {{-- Akhir section formTambahJS --}}
