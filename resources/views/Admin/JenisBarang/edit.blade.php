<!-- MODAL EDIT --> <!-- Penanda: modal untuk mengubah data jenis barang -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup modal) -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal ditengah layar -->
        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal -->
            <div class="modal-header">
                <!-- Header modal -->
                <h6 class="modal-title">Ubah Jenis Barang</h6>
                <!-- Judul modal -->
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol close: menutup modal (tidak memanggil resetU) -->
                    <span aria-hidden="true">&times;</span>
                    <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body">
                <!-- Body modal: form edit -->
                <input type="hidden" name="idjenisbarangU">
                <!-- Hidden input untuk menyimpan id jenis barang yang sedang diedit -->

                <div class="form-group">
                    <!-- Grup input jenis barang -->
                    <label for="jenisbarangU" class="form-label">
                        Jenis Barang <span class="text-danger">*</span>
                        <!-- Tanda wajib -->
                    </label>
                    <input type="text" name="jenisbarangU" class="form-control" placeholder="">
                    <!-- Input nama jenis barang -->
                </div>

                <div class="form-group">
                    <!-- Grup input keterangan -->
                    <label for="ketU" class="form-label">Keterangan</label>
                    <!-- Label keterangan (opsional) -->
                    <textarea name="ketU" class="form-control" rows="4"></textarea>
                    <!-- Textarea keterangan -->
                </div>
            </div>

            <div class="modal-footer">
                <!-- Footer modal -->
                <button class="btn btn-success d-none" id="btnLoaderU" type="button" disabled="">
                    <!-- Tombol loader saat proses simpan sedang berjalan (hidden default) -->
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkFormU()" id="btnSimpanU" class="btn btn-success">
                    <!-- Tombol simpan: validasi dulu lewat checkFormU() -->
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

@section('formEditJS') {{-- Section Blade: JS khusus edit jenis barang --}}
<script> // Awal script JS

    function checkFormU() { // Fungsi validasi sebelum submit edit
        const jenis = $("input[name='jenisbarangU']").val(); // Ambil nilai input jenis barang

        setLoadingU(true); // Aktifkan loading (tampilkan loader, sembunyikan tombol simpan)
        resetValidU(); // Reset tanda invalid

        if (jenis == "") { // Jika input jenis barang kosong
            validasi('Jenis Barang wajib di isi!', 'warning'); // Notifikasi warning
            $("input[name='jenisbarangU']").addClass('is-invalid'); // Tandai input invalid
            setLoadingU(false); // Matikan loading
            return false; // Stop proses
        } else { // Jika valid
            submitFormU(); // Lanjut kirim data ke server
        }
    }

    function submitFormU() { // Fungsi AJAX untuk mengirim perubahan ke server
        const id = $("input[name='idjenisbarangU']").val(); // Ambil id jenis barang
        const jenis = $("input[name='jenisbarangU']").val(); // Ambil nama jenis barang
        const ket = $("textarea[name='ketU']").val(); // Ambil keterangan

        $.ajax({
            type: 'POST', // Method POST untuk update
            url: "{{url('admin/jenisbarang/proses_ubah')}}/" + id,
            // Endpoint proses ubah jenis barang + id

            enctype: 'multipart/form-data',
            // Catatan: tidak wajib bila tidak ada upload file

            data: { // Data yang dikirim ke server
                jenisbarang: jenis, // Nama jenis barang (key sesuai kebutuhan backend)
                ket: ket // Keterangan
            },

            success: function(data) { // Jika berhasil
                swal({ // SweetAlert sukses
                    title: "Berhasil diubah!",
                    type: "success"
                });

                $('#Umodaldemo8').modal('toggle'); // Tutup modal edit
                table.ajax.reload(null, false); // Reload DataTables utama tanpa reset halaman
                resetU(); // Reset form edit
            }
        });
    }

    function resetValidU() { // Fungsi menghapus class invalid
        $("input[name='jenisbarangU']").removeClass('is-invalid'); // Reset invalid input jenis barang
        $("textarea[name='ketU']").removeClass('is-invalid'); // Reset invalid textarea ket (meski biasanya tidak divalidasi)
    };

    function resetU() { // Fungsi reset form edit
        resetValidU(); // Reset validasi
        $("input[name='idjenisbarangU']").val(''); // Kosongkan id
        $("input[name='jenisbarangU']").val(''); // Kosongkan input jenis
        $("textarea[name='ketU']").val(''); // Kosongkan keterangan
        setLoadingU(false); // Matikan loading
    }

    function setLoadingU(bool) { // Toggle loader vs tombol simpan
        if (bool == true) { // Jika loading aktif
            $('#btnLoaderU').removeClass('d-none'); // Tampilkan loader
            $('#btnSimpanU').addClass('d-none'); // Sembunyikan tombol simpan
        } else { // Jika loading mati
            $('#btnSimpanU').removeClass('d-none'); // Tampilkan tombol simpan
            $('#btnLoaderU').addClass('d-none'); // Sembunyikan loader
        }
    }

</script>
@endsection {{-- Akhir section formEditJS --}}
