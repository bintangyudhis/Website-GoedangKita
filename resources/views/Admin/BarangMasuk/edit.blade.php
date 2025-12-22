<!-- MODAL EDIT --> <!-- Penanda: modal untuk mengubah data barang masuk -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8"> <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup) -->
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> <!-- Dialog modal ukuran besar dan ditengah -->
        <div class="modal-content modal-content-demo"> <!-- Konten modal -->
            <div class="modal-header"> <!-- Header modal -->
                <h6 class="modal-title">Ubah Barang Masuk</h6> <!-- Judul modal (edit barang masuk) -->
                <button aria-label="Close" onclick="resetU()" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol close: panggil resetU() lalu tutup modal -->
                    <span aria-hidden="true">&times;</span> <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body"> <!-- Body modal -->
                <div class="row"> <!-- Row untuk layout 2 kolom -->

                    <div class="col-md-6"> <!-- Kolom kiri -->
                        <input type="hidden" name="idbmU"> <!-- Hidden id barang masuk yang diedit -->

                        <div class="form-group"> <!-- Grup input kode barang masuk -->
                            <label for="bmkodeU" class="form-label">
                                Kode Barang Masuk <span class="text-danger">*</span> <!-- Wajib -->
                            </label>
                            <input type="text" name="bmkodeU" readonly class="form-control" placeholder="">
                            <!-- Input kode BM, readonly (biasanya tidak boleh diubah) -->
                        </div>

                        <div class="form-group"> <!-- Grup input tanggal masuk -->
                            <label for="tglmasukU" class="form-label">
                                Tanggal Masuk <span class="text-danger">*</span> <!-- Wajib -->
                            </label>
                            <input type="text" name="tglmasukU" class="form-control datepicker-date" placeholder="">
                            <!-- Input tanggal masuk, class datepicker-date untuk plugin datepicker -->
                        </div>

                        <div class="form-group"> <!-- Grup select customer -->
                            <label for="customerU" class="form-label">
                                Pilih Customer <span class="text-danger">*</span> <!-- Wajib -->
                            </label>
                            <select name="customerU" id="customerU" class="form-control">
                                <!-- Dropdown customer -->
                                <option value="">-- Pilih Customer --</option> <!-- Default kosong -->
                                @foreach ($customer as $c) {{-- Loop data customer dari controller --}}
                                <option value="{{ $c->customer_id }}">{{ $c->customer_nama }}</option>
                                {{-- Option customer: value=id, teks=nama --}}
                                @endforeach {{-- Akhir loop --}}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6"> <!-- Kolom kanan -->
                        <div class="form-group"> <!-- Grup input kode barang -->
                            <label>
                                Kode Barang <span class="text-danger me-1">*</span> <!-- Wajib -->
                                <input type="hidden" id="statusU" value="true">
                                <!-- Hidden statusU: flag valid/tidaknya barang yang dipilih -->

                                <div class="spinner-border spinner-border-sm d-none" id="loaderkdU" role="status">
                                    <!-- Loader kecil saat cek barang -->
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </label>

                            <div class="input-group"> <!-- Input group untuk kode barang + tombol -->
                                <input type="text" class="form-control" autocomplete="off" name="kdbarangU" placeholder="">
                                <!-- Input kode barang untuk edit -->

                                <button class="btn btn-primary-light" onclick="searchBarangU()" type="button">
                                    <!-- Tombol cari barang berdasarkan kode -->
                                    <i class="fe fe-search"></i>
                                </button>

                                <button class="btn btn-success-light" onclick="modalBarangU()" type="button">
                                    <!-- Tombol buka modal daftar barang -->
                                    <i class="fe fe-box"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group"> <!-- Grup nama barang -->
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" id="nmbarangU" readonly>
                            <!-- Menampilkan nama barang hasil lookup -->
                        </div>

                        <div class="row"> <!-- Row satuan & jenis -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Satuan</label>
                                    <input type="text" class="form-control" id="satuanU" readonly>
                                    <!-- Menampilkan satuan -->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <input type="text" class="form-control" id="jenisU" readonly>
                                    <!-- Menampilkan jenis -->
                                </div>
                            </div>
                        </div>

                        <div class="form-group"> <!-- Grup jumlah masuk -->
                            <label for="jmlU" class="form-label">
                                Jumlah Masuk <span class="text-danger">*</span> <!-- Wajib -->
                            </label>
                            <input type="text" name="jmlU" class="form-control"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');"
                                placeholder="">
                            <!-- Input jumlah masuk, filter input hanya angka dan 1 titik desimal -->
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer"> <!-- Footer modal -->
                <button class="btn btn-success d-none" id="btnLoaderU" type="button" disabled="">
                    <!-- Tombol loader saat submit -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkFormU()" id="btnSimpanU" class="btn btn-success">
                    <!-- Tombol simpan: validasi via checkFormU() -->
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

@section('formEditJS') {{-- Section Blade untuk JS khusus edit --}}
<script> // Awal script JS edit

    $('input[name="kdbarangU"]').keypress(function(event) { // Event saat mengetik di input kdbarangU
        var keycode = (event.keyCode ? event.keyCode : event.which); // Ambil kode tombol
        if (keycode == '13') { // Jika tombol Enter
            getbarangbyidU($('input[name="kdbarangU"]').val()); // Cari barang berdasarkan kode
        }
    });

    function modalBarangU() { // Buka modal daftar barang untuk mode edit
        $('#modalBarang').modal('show'); // Tampilkan modal barang
        $('#Umodaldemo8').addClass('d-none'); // Sembunyikan modal edit agar tidak tabrakan tampilan
        $('input[name="param"]').val('ubah'); // Set param jadi "ubah" agar modal barang tahu ini mode edit
        resetValidU(); // Reset validasi
        table2.ajax.reload(); // Reload table2 daftar barang
    }

    function searchBarangU() { // Fungsi tombol search barang
        getbarangbyidU($('input[name="kdbarangU"]').val()); // Lookup barang dari input kode
        resetValidU(); // Reset validasi
    }

    function getbarangbyidU(id) { // Fungsi AJAX ambil detail barang berdasarkan kode
        $("#loaderkdU").removeClass('d-none'); // Tampilkan loader
        $.ajax({
            type: 'GET', // Method GET
            url: "{{ url('admin/barang/getbarang') }}/" + id, // Endpoint getbarang + id
            processData: false, // Tidak wajib untuk GET, tapi tidak masalah
            contentType: false, // Tidak wajib untuk GET, tapi tidak masalah
            dataType: 'json', // Response JSON
            success: function(data) { // Jika sukses
                if (data.length > 0) { // Jika barang ditemukan
                    $("#loaderkdU").addClass('d-none'); // Sembunyikan loader
                    $("#statusU").val("true"); // Barang valid
                    $("#nmbarangU").val(data[0].barang_nama); // Isi nama barang
                    $("#satuanU").val(data[0].satuan_nama); // Isi satuan
                    $("#jenisU").val(data[0].jenisbarang_nama); // Isi jenis
                } else { // Jika tidak ditemukan
                    $("#loaderkdU").addClass('d-none'); // Sembunyikan loader
                    $("#statusU").val("false"); // Barang invalid
                    $("#nmbarangU").val(''); // Kosongkan nama
                    $("#satuanU").val(''); // Kosongkan satuan
                    $("#jenisU").val(''); // Kosongkan jenis
                }
            }
        });
    }

    function checkFormU() { // Fungsi validasi sebelum submit edit
        const tglmasuk = $("input[name='tglmasukU']").val(); // Ambil tanggal masuk
        const status = $("#statusU").val(); // Ambil status valid barang
        const kdbarang = $("input[name='kdbarangU").val();
        // BUG: selector kurang penutup -> harusnya $("input[name='kdbarangU']").val();

        const customer = $("select[name='customerU']").val(); // Ambil customer terpilih
        const jml = $("input[name='jmlU']").val(); // Ambil jumlah masuk

        setLoadingU(true); // Aktifkan loading
        resetValidU(); // Reset validasi

        if (tglmasuk == "") { // Jika tanggal masuk kosong
            validasi('Tanggal Masuk wajib di isi!', 'warning'); // Notifikasi
            $("input[name='tglmasukU']").addClass('is-invalid'); // Tandai invalid
            setLoading(Ufalse);
            // BUG: harusnya setLoadingU(false); dan Ufalse tidak ada
            return false; // Stop proses
        } else if (customer == "") { // Jika customer belum dipilih
            validasi('Customer wajib di pilih!', 'warning'); // Notifikasi
            $("select[name='customerU']").addClass('is-invalid'); // Tandai invalid
            setLoadingU(false); // Matikan loading
            return false; // Stop
        } else if (status == "false" || kdbarang == '') { // Jika barang invalid atau kosong
            validasi('Barang wajib di pilih!', 'warning'); // Notifikasi
            $("input[name='kdbarangU']").addClass('is-invalid'); // Tandai invalid
            setLoadingU(false); // Matikan loading
            return false; // Stop
        } else if (jml == "" || jml == "0") { // Jika jumlah kosong atau 0
            validasi('Jumlah Masuk wajib di isi!', 'warning'); // Notifikasi
            $("input[name='jmlU']").addClass('is-invalid'); // Tandai invalid
            setLoadingU(false); // Matikan loading
            return false; // Stop
        } else { // Jika semua valid
            submitFormU(); // Lanjut submit edit
        }
    }

    function submitFormU() { // Fungsi AJAX submit edit ke server
        const id = $("input[name='idbmU']").val(); // Ambil id barang masuk
        const bmkode = $("input[name='bmkodeU']").val(); // Ambil kode BM
        const tglmasuk = $("input[name='tglmasukU']").val(); // Ambil tanggal
        const kdbarang = $("input[name='kdbarangU']").val(); // Ambil kode barang
        const customer = $("select[name='customerU']").val(); // Ambil customer
        const jml = $("input[name='jmlU']").val(); // Ambil jumlah

        $.ajax({
            type: 'POST', // Method POST
            url: "{{ url('admin/barang-masuk/proses_ubah') }}/" + id, // Endpoint ubah + id
            enctype: 'multipart/form-data', // Tidak wajib jika tidak upload file
            data: { // Data dikirim
                bmkode: bmkode, // Kode BM
                tglmasuk: tglmasuk, // Tanggal masuk
                barang: kdbarang, // Kode barang
                customer: customer, // Customer id
                jml: jml // Jumlah
            },
            success: function(data) { // Jika sukses
                swal({ // SweetAlert sukses
                    title: "Berhasil diubah!",
                    type: "success"
                });
                $('#Umodaldemo8').modal('toggle'); // Tutup modal edit
                table.ajax.reload(null, false); // Reload datatable utama
                resetU(); // Reset form edit
            }
        });
    }

    function resetValidU() { // Menghapus tanda invalid
        $("input[name='tglmasukU']").removeClass('is-invalid'); // Reset invalid tanggal
        $("input[name='kdbarangU']").removeClass('is-invalid'); // Reset invalid kdbarang
        $("select[name='customerU']").removeClass('is-invalid'); // Reset invalid customer
        $("input[name='jmlU']").removeClass('is-invalid'); // Reset invalid jumlah
    };

    function resetU() { // Reset semua input form edit ke default
        resetValidU(); // Reset validasi
        $("input[name='idbmU']").val(''); // Kosongkan id
        $("input[name='bmkodeU']").val(''); // Kosongkan kode
        $("input[name='tglmasukU']").val(''); // Kosongkan tanggal
        $("input[name='kdbarangU']").val(''); // Kosongkan kode barang
        $("select[name='customerU']").val(''); // Reset pilihan customer
        $("input[name='jmlU']").val('0'); // Set jumlah ke 0
        $("#nmbarangU").val(''); // Kosongkan nama
        $("#satuanU").val(''); // Kosongkan satuan
        $("#jenisU").val(''); // Kosongkan jenis
        $("#statusU").val('false'); // Set status invalid default
        setLoadingU(false); // Matikan loading (tampilkan tombol simpan)
    }

    function setLoadingU(bool) { // Toggle loader dan tombol simpan
        if (bool == true) { // Jika loading aktif
            $('#btnLoaderU').removeClass('d-none'); // Tampilkan loader
            $('#btnSimpanU').addClass('d-none'); // Sembunyikan tombol simpan
        } else { // Jika loading tidak aktif
            $('#btnSimpanU').removeClass('d-none'); // Tampilkan tombol simpan
            $('#btnLoaderU').addClass('d-none'); // Sembunyikan loader
        }
    }

</script>
@endsection {{-- Akhir section formEditJS --}}
