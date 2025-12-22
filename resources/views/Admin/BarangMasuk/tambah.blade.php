<!-- MODAL TAMBAH --> <!-- Penanda: modal untuk menambah transaksi barang masuk -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup) -->
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <!-- Dialog modal ukuran besar, posisi tengah -->
        <div class="modal-content modal-content-demo">
            <!-- Konten modal -->
            <div class="modal-header">
                <!-- Header modal -->
                <h6 class="modal-title">Tambah Barang Masuk</h6>
                <!-- Judul modal -->
                <button onclick="reset()" aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol close: jalankan reset() lalu menutup modal -->
                    <span aria-hidden="true">&times;</span>
                    <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body">
                <!-- Isi form modal -->
                <div class="row">
                    <!-- Layout 2 kolom -->

                    <div class="col-md-6">
                        <!-- Kolom kiri -->
                        <div class="form-group">
                            <!-- Grup input kode barang masuk -->
                            <label for="bmkode" class="form-label">
                                Kode Barang Masuk <span class="text-danger">*</span>
                                <!-- Tanda wajib -->
                            </label>
                            <input type="text" name="bmkode" readonly class="form-control" placeholder="">
                            <!-- Input kode BM, readonly karena di-generate otomatis -->
                        </div>

                        <div class="form-group">
                            <!-- Grup input tanggal masuk -->
                            <label for="tglmasuk" class="form-label">
                                Tanggal Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="tglmasuk" class="form-control datepicker-date" placeholder="">
                            <!-- Input tanggal, class datepicker-date biasanya dihubungkan ke plugin datepicker -->
                        </div>

                        <div class="form-group">
                            <!-- Grup select customer -->
                            <label for="customer" class="form-label">
                                Pilih Customer <span class="text-danger">*</span>
                            </label>
                            <select name="customer" id="customer" class="form-control">
                                <!-- Dropdown customer -->
                                <option value="">-- Pilih Customer --</option>
                                <!-- Default kosong -->
                                @foreach ($customer as $c)
                                {{-- Loop daftar customer dari controller --}}
                                <option value="{{ $c->customer_id }}">{{ $c->customer_nama }}</option>
                                {{-- Option customer: value=id, text=nama --}}
                                @endforeach
                                {{-- Akhir loop --}}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Kolom kanan -->
                        <div class="form-group">
                            <!-- Grup input kode barang -->
                            <label>
                                Kode Barang <span class="text-danger me-1">*</span>
                                <!-- Tanda wajib -->
                                <input type="hidden" id="status" value="false">
                                <!-- Hidden status: flag validasi barang (false sebelum barang dipilih/terdeteksi) -->
                                <div class="spinner-border spinner-border-sm d-none" id="loaderkd" role="status">
                                    <!-- Loader kecil untuk proses cek barang, default hidden -->
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </label>

                            <div class="input-group">
                                <!-- Input group: input kode + tombol aksi -->
                                <input type="text" class="form-control" autocomplete="off" name="kdbarang" placeholder="">
                                <!-- Input kode barang -->
                                <button class="btn btn-primary-light" onclick="searchBarang()" type="button">
                                    <!-- Tombol cari barang berdasarkan kode -->
                                    <i class="fe fe-search"></i>
                                </button>
                                <button class="btn btn-success-light" onclick="modalBarang()" type="button">
                                    <!-- Tombol buka modal daftar barang -->
                                    <i class="fe fe-box"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- Grup tampilan nama barang -->
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" id="nmbarang" readonly>
                            <!-- Menampilkan nama barang hasil lookup -->
                        </div>

                        <div class="row">
                            <!-- Row untuk satuan dan jenis -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Satuan</label>
                                    <input type="text" class="form-control" id="satuan" readonly>
                                    <!-- Menampilkan satuan barang -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <input type="text" class="form-control" id="jenis" readonly>
                                    <!-- Menampilkan jenis barang -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <!-- Grup input jumlah masuk -->
                            <label for="jml" class="form-label">
                                Jumlah Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="jml" value="0" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                placeholder="">
                            <!-- Input jumlah, default 0, oninput membatasi hanya angka dan 1 titik -->
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <!-- Footer modal -->
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    <!-- Tombol loader saat submit -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkForm()" id="btnSimpan" class="btn btn-primary">
                    <!-- Tombol simpan: jalankan validasi checkForm() -->
                    Simpan <i class="fe fe-check"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-light" onclick="reset()" data-bs-dismiss="modal">
                    <!-- Tombol batal: reset form lalu tutup modal -->
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>

        </div>
    </div>
</div>

@section('formTambahJS') {{-- Section Blade: JS khusus form tambah barang masuk --}}
<script> // Awal script

    $('input[name="kdbarang"]').keypress(function(event) { // Event ketika user mengetik di input kdbarang
        var keycode = (event.keyCode ? event.keyCode : event.which); // Ambil kode tombol
        if (keycode == '13') { // Jika Enter ditekan
            getbarangbyid($('input[name="kdbarang"]').val()); // Cari barang berdasarkan kode yang diketik
        }
    });

    function modalBarang() { // Fungsi membuka modal daftar barang
        $('#modalBarang').modal('show'); // Tampilkan modal daftar barang
        $('#modaldemo8').addClass('d-none'); // Sembunyikan modal tambah (dengan class d-none)
        $('input[name="param"]').val('tambah'); // Set param = tambah agar modal barang tahu konteksnya
        resetValid(); // Reset validasi
        table2.ajax.reload(); // Reload DataTable daftar barang (table2)
    }

    function searchBarang() { // Fungsi tombol search barang
        getbarangbyid($('input[name="kdbarang"]').val()); // Lookup barang berdasarkan kode
        resetValid(); // Reset validasi
    }

    function getbarangbyid(id) { // Fungsi AJAX untuk mengambil detail barang
        $("#loaderkd").removeClass('d-none'); // Tampilkan loader
        $.ajax({
            type: 'GET', // Method GET
            url: "{{ url('admin/barang/getbarang') }}/" + id, // Endpoint + kode/id barang
            processData: false, // Tidak wajib untuk GET, tapi tidak masalah
            contentType: false, // Tidak wajib untuk GET, tapi tidak masalah
            dataType: 'json', // Response JSON
            success: function(data) { // Jika sukses
                if (data.length > 0) { // Jika barang ditemukan
                    $("#loaderkd").addClass('d-none'); // Sembunyikan loader
                    $("#status").val("true"); // Set status valid
                    $("#nmbarang").val(data[0].barang_nama); // Isi nama barang
                    $("#satuan").val(data[0].satuan_nama); // Isi satuan
                    $("#jenis").val(data[0].jenisbarang_nama); // Isi jenis
                } else { // Jika barang tidak ditemukan
                    $("#loaderkd").addClass('d-none'); // Sembunyikan loader
                    $("#status").val("false"); // Set status invalid
                    $("#nmbarang").val(''); // Kosongkan nama
                    $("#satuan").val(''); // Kosongkan satuan
                    $("#jenis").val(''); // Kosongkan jenis
                }
            }
        });
    }

    function checkForm() { // Fungsi validasi sebelum submit tambah
        const tglmasuk = $("input[name='tglmasuk']").val(); // Ambil tanggal masuk
        const status = $("#status").val(); // Ambil status valid barang
        const customer = $("select[name='customer']").val(); // Ambil customer
        const jml = $("input[name='jml']").val(); // Ambil jumlah masuk

        setLoading(true); // Aktifkan loading
        resetValid(); // Reset validasi

        if (tglmasuk == "") { // Jika tanggal kosong
            validasi('Tanggal Masuk wajib di isi!', 'warning'); // Notifikasi
            $("input[name='tglmasuk']").addClass('is-invalid'); // Tandai invalid
            setLoading(false); // Matikan loading
            return false; // Stop
        } else if (customer == "") { // Jika customer belum dipilih
            validasi('Customer wajib di pilih!', 'warning'); // Notifikasi
            $("select[name='customer']").addClass('is-invalid'); // Tandai invalid
            setLoading(false); // Matikan loading
            return false; // Stop
        } else if (status == "false") { // Jika barang belum valid
            validasi('Barang wajib di pilih!', 'warning'); // Notifikasi
            $("input[name='kdbarang']").addClass('is-invalid'); // Tandai invalid kdbarang
            setLoading(false); // Matikan loading
            return false; // Stop
        } else if (jml == "" || jml == "0") { // Jika jumlah kosong atau 0
            validasi('Jumlah Masuk wajib di isi!', 'warning'); // Notifikasi
            $("input[name='jml']").addClass('is-invalid'); // Tandai invalid jumlah
            setLoading(false); // Matikan loading
            return false; // Stop
        } else { // Jika semua valid
            submitForm(); // Lanjut submit ke server
        }
    }

    function submitForm() { // Fungsi AJAX submit tambah
        const bmkode = $("input[name='bmkode']").val(); // Ambil kode BM
        const tglmasuk = $("input[name='tglmasuk']").val(); // Ambil tanggal masuk
        const kdbarang = $("input[name='kdbarang']").val(); // Ambil kode barang
        const customer = $("select[name='customer']").val(); // Ambil customer id
        const jml = $("input[name='jml']").val(); // Ambil jumlah

        $.ajax({
            type: 'POST', // Method POST
            url: "{{ route('barang-masuk.store') }}", // Endpoint store barang masuk
            enctype: 'multipart/form-data', // Tidak wajib jika tidak ada upload file
            data: { // Data yang dikirim
                bmkode: bmkode,
                tglmasuk: tglmasuk,
                barang: kdbarang,
                customer: customer,
                jml: jml
            },
            success: function(data) { // Jika berhasil
                $('#modaldemo8').modal('toggle'); // Tutup modal tambah
                swal({ // SweetAlert sukses
                    title: "Berhasil ditambah!",
                    type: "success"
                });
                table.ajax.reload(null, false); // Reload tabel utama tanpa reset page
                reset(); // Reset form
            }
        });
    }

    function resetValid() { // Menghapus class invalid pada input/seleksi
        $("input[name='tglmasuk']").removeClass('is-invalid'); // Reset tanggal
        $("input[name='kdbarang']").removeClass('is-invalid'); // Reset kdbarang
        $("select[name='customer']").removeClass('is-invalid'); // Reset customer
        $("input[name='jml']").removeClass('is-invalid'); // Reset jumlah
    };

    function reset() { // Reset seluruh field form tambah
        resetValid(); // Reset validasi
        $("input[name='bmkode']").val(''); // Kosongkan kode BM
        $("input[name='tglmasuk']").val(''); // Kosongkan tanggal
        $("input[name='kdbarang']").val(''); // Kosongkan kode barang
        $("select[name='customer']").val(''); // Reset pilihan customer
        $("input[name='jml']").val('0'); // Set jumlah default 0
        $("#nmbarang").val(''); // Kosongkan nama barang
        $("#satuan").val(''); // Kosongkan satuan
        $("#jenis").val(''); // Kosongkan jenis
        $("#status").val('false'); // Set status invalid default
        setLoading(false); // Matikan loading
    }

    function setLoading(bool) { // Toggle loader dan tombol simpan
        if (bool == true) { // Jika loading aktif
            $('#btnLoader').removeClass('d-none'); // Tampilkan loader
            $('#btnSimpan').addClass('d-none'); // Sembunyikan tombol simpan
        } else { // Jika loading tidak aktif
            $('#btnSimpan').removeClass('d-none'); // Tampilkan tombol simpan
            $('#btnLoader').addClass('d-none'); // Sembunyikan loader
        }
    }

</script>
@endsection {{-- Akhir section formTambahJS --}}
