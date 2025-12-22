<!-- MODAL TAMBAH --> <!-- Penanda: modal untuk menambah data jenis barang -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup modal) -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal ditengah layar -->
        <div class="modal-content modal-content-demo">
            <!-- Konten utama modal -->
            <div class="modal-header">
                <!-- Header modal -->
                <h6 class="modal-title">Tambah Jenis Barang</h6>
                <!-- Judul modal -->
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol close: menutup modal (tidak memanggil reset) -->
                    <span aria-hidden="true">&times;</span>
                    <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body">
                <!-- Body modal: form input -->
                <div class="form-group">
                    <!-- Grup input jenis barang -->
                    <label for="jenisbarang" class="form-label">
                        Jenis Barang <span class="text-danger">*</span>
                        <!-- Tanda wajib -->
                    </label>
                    <input type="text" name="jenisbarang" class="form-control" placeholder="">
                    <!-- Input nama jenis barang -->
                </div>

                <div class="form-group">
                    <!-- Grup input keterangan -->
                    <label for="ket" class="form-label">Keterangan</label>
                    <!-- Label keterangan (opsional) -->
                    <textarea name="ket" class="form-control" rows="4"></textarea>
                    <!-- Textarea untuk keterangan -->
                </div>
            </div>

            <div class="modal-footer">
                <!-- Footer modal -->
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    <!-- Tombol loader saat proses simpan berjalan (default hidden) -->
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

@section('formTambahJS')
    {{-- Section Blade: JS khusus tambah jenis barang --}}
    <script>
        // Awal script JS

        function checkForm() { // Fungsi validasi sebelum submit
            const jenis = $("input[name='jenisbarang']").val(); // Ambil nilai input jenis barang

            setLoading(true); // Aktifkan loading (tampilkan loader, sembunyikan tombol simpan)
            resetValid(); // Hapus tanda invalid sebelumnya

            if (jenis == "") { // Jika jenis barang kosong
                validasi('Jenis Barang wajib di isi!', 'warning'); // Tampilkan alert warning
                $("input[name='jenisbarang']").addClass('is-invalid'); // Tandai input invalid
                setLoading(false); // Matikan loading
                return false; // Hentikan proses
            } else { // Jika valid
                submitForm(); // Lanjut kirim data ke server
            }
        }

        function submitForm() { // Fungsi AJAX simpan data jenis barang baru
            const jenis = $("input[name='jenisbarang']").val(); // Ambil nama jenis
            const ket = $("textarea[name='ket']").val(); // Ambil keterangan

            $.ajax({
                type: 'POST', // Method POST
                url: "{{ route('jenisbarang.store') }}",
                // Endpoint route untuk menyimpan data jenis barang

                enctype: 'multipart/form-data',
                // Catatan: tidak wajib bila tidak ada upload file

                data: { // Data yang dikirim ke server
                    jenisbarang: jenis, // Field jenis barang (key sesuai kebutuhan backend)
                    ket: ket // Field keterangan
                },

                success: function(data) { // Jika berhasil
                    $('#modaldemo8').modal('toggle'); // Tutup modal tambah
                    swal({ // Notifikasi sukses
                        title: "Berhasil ditambah!",
                        type: "success"
                    });
                    table.ajax.reload(null, false); // Reload DataTables tanpa reset halaman
                    reset(); // Reset input form tambah
                }
            });
        }

        function resetValid() { // Fungsi menghapus class invalid pada input
            $("input[name='jenisbarang']").removeClass('is-invalid'); // Reset invalid input jenis barang
            $("textarea[name='ket']").removeClass(
                'is-invalid'); // Reset invalid textarea ket (meski biasanya tidak divalidasi)
        };

        function reset() { // Fungsi reset semua field form tambah
            resetValid(); // Reset validasi dulu
            $("input[name='jenisbarang']").val(''); // Kosongkan input jenis
            $("textarea[name='ket']").val(''); // Kosongkan keterangan
            setLoading(false); // Matikan loading
        }

        function setLoading(bool) { // Toggle loader vs tombol simpan
            if (bool == true) { // Jika loading aktif
                $('#btnLoader').removeClass('d-none'); // Tampilkan loader
                $('#btnSimpan').addClass('d-none'); // Sembunyikan tombol simpan
            } else { // Jika loading mati
                $('#btnSimpan').removeClass('d-none'); // Tampilkan tombol simpan
                $('#btnLoader').addClass('d-none'); // Sembunyikan loader
            }
        }
    </script>
@endsection {{-- Akhir section formTambahJS --}}
