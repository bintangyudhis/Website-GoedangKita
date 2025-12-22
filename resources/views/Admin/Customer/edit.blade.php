<!-- MODAL EDIT --> <!-- Penanda: modal untuk mengubah data customer -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup) -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal ditengah -->
        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal -->
            <div class="modal-header">
                <!-- Header modal -->
                <h6 class="modal-title">Ubah Customer Barang</h6>
                <!-- Judul modal -->
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol close: langsung tutup modal (tidak memanggil reset) -->
                    <span aria-hidden="true">&times;</span>
                    <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body">
                <!-- Body modal: form edit customer -->
                <input type="hidden" name="idcustomerU">
                <!-- Hidden input: menyimpan id customer yang sedang diedit -->

                <div class="form-group">
                    <!-- Grup input nama customer -->
                    <label for="customerU" class="form-label">
                        Customer Barang <span class="text-danger">*</span>
                        <!-- Nama customer wajib -->
                    </label>
                    <input type="text" name="customerU" class="form-control" placeholder="">
                    <!-- Input teks nama customer -->
                </div>

                <div class="form-group">
                    <!-- Grup input nomor telepon -->
                    <label for="notelpU" class="form-label">No Telepon</label>
                    <!-- Label nomor telepon (opsional) -->
                    <input type="text" name="notelpU" class="form-control" placeholder="">
                    <!-- Input nomor telepon -->
                </div>

                <div class="form-group">
                    <!-- Grup input alamat -->
                    <label for="alamatU" class="form-label">Alamat</label>
                    <!-- Label alamat (opsional) -->
                    <textarea name="alamatU" class="form-control" rows="4"></textarea>
                    <!-- Textarea alamat, 4 baris -->
                </div>
            </div>

            <div class="modal-footer">
                <!-- Footer modal -->
                <button class="btn btn-success d-none" id="btnLoaderU" type="button" disabled="">
                    <!-- Tombol loader ketika proses submit berlangsung (hidden default) -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkFormU()" id="btnSimpanU" class="btn btn-success">
                    <!-- Tombol simpan: jalankan validasi checkFormU() -->
                    Simpan Perubahan <i class="fe fe-check"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-light" onclick="resetU()" data-bs-dismiss="modal">
                    <!-- Tombol batal: reset input lalu tutup modal -->
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>

        </div>
    </div>
</div>

@section('formEditJS') {{-- Section Blade untuk JS khusus edit customer --}}
<script> // Awal script JS

    function checkFormU() { // Fungsi validasi sebelum submit
        const customer = $("input[name='customerU']").val(); // Ambil nilai nama customer

        setLoadingU(true); // Aktifkan loading (tampilkan tombol loader)
        resetValidU(); // Hapus semua tanda invalid

        if (customer == "") { // Jika nama customer kosong
            validasi('Nama Customer wajib di isi!', 'warning'); // Tampilkan alert warning
            $("input[name='customerU']").addClass('is-invalid'); // Tandai input customer invalid
            setLoadingU(false); // Matikan loading
            return false; // Stop proses
        } else { // Jika valid
            submitFormU(); // Lanjut submit ke server
        }
    }

    function submitFormU() { // Fungsi AJAX submit edit customer
        const id = $("input[name='idcustomerU']").val(); // Ambil id customer
        const customer = $("input[name='customerU']").val(); // Ambil nama customer
        const notelp = $("input[name='notelpU']").val(); // Ambil nomor telepon
        const alamat = $("textarea[name='alamatU']").val(); // Ambil alamat

        $.ajax({
            type: 'POST', // Method POST untuk update
            url: "{{url('admin/customer/proses_ubah')}}/" + id,
            // Endpoint proses ubah customer + id

            enctype: 'multipart/form-data',
            // Catatan: tidak wajib jika tidak ada upload file, bisa dihilangkan

            data: { // Data yang dikirim ke server
                customer: customer, // Nama customer
                notelp: notelp, // Nomor telepon
                alamat: alamat // Alamat
            },

            success: function(data) { // Jika sukses
                swal({ // SweetAlert sukses
                    title: "Berhasil diubah!",
                    type: "success"
                });

                $('#Umodaldemo8').modal('toggle'); // Tutup modal edit
                table.ajax.reload(null, false); // Reload DataTables utama tanpa reset halaman
                resetU(); // Reset isi form + matikan loading
            }
        });
    }

    function resetValidU() { // Fungsi menghapus status invalid
        $("input[name='customerU']").removeClass('is-invalid'); // Hapus invalid nama customer
        $("input[name='notelpU']").removeClass('is-invalid'); // Hapus invalid notelp
        $("textarea[name='alamatU']").removeClass('is-invalid'); // Hapus invalid alamat
    };

    function resetU() { // Fungsi reset seluruh input modal edit
        resetValidU(); // Reset validasi dulu
        $("input[name='idcustomerU']").val(''); // Kosongkan id
        $("input[name='customerU']").val(''); // Kosongkan nama customer
        $("input[name='notelpU']").val(''); // Kosongkan nomor telepon
        $("textarea[name='alamatU']").val(''); // Kosongkan alamat
        setLoadingU(false); // Matikan loading (tampilkan tombol simpan)
    }

    function setLoadingU(bool) { // Toggle loader vs tombol simpan
        if (bool == true) { // Jika loading aktif
            $('#btnLoaderU').removeClass('d-none'); // Tampilkan loader
            $('#btnSimpanU').addClass('d-none'); // Sembunyikan tombol simpan
        } else { // Jika loading nonaktif
            $('#btnSimpanU').removeClass('d-none'); // Tampilkan tombol simpan
            $('#btnLoaderU').addClass('d-none'); // Sembunyikan loader
        }
    }

</script>
@endsection {{-- Akhir section formEditJS --}}
