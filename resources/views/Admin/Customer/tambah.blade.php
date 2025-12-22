<!-- MODAL TAMBAH --> <!-- Penanda: modal untuk menambah data customer -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    <!-- Modal Bootstrap, backdrop static (klik luar tidak menutup) -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Dialog modal ditengah -->
        <div class="modal-content modal-content-demo">
            <!-- Konten modal -->
            <div class="modal-header">
                <!-- Header modal -->
                <h6 class="modal-title">Tambah Customer</h6>
                <!-- Judul modal -->
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                    <!-- Tombol close: menutup modal (tidak memanggil reset) -->
                    <span aria-hidden="true">&times;</span>
                    <!-- Ikon close -->
                </button>
            </div>

            <div class="modal-body">
                <!-- Body modal: form input customer -->
                <div class="form-group">
                    <!-- Grup input nama customer -->
                    <label for="customer" class="form-label">
                        Nama Customer <span class="text-danger">*</span>
                        <!-- Tanda wajib -->
                    </label>
                    <input type="text" name="customer" class="form-control" placeholder="">
                    <!-- Input nama customer -->
                </div>

                <div class="form-group">
                    <!-- Grup input nomor telepon -->
                    <label for="notelp" class="form-label">No Telepon</label>
                    <!-- Label nomor telepon (opsional) -->
                    <input type="text" name="notelp" class="form-control" placeholder="">
                    <!-- Input nomor telepon -->
                </div>

                <div class="form-group">
                    <!-- Grup input alamat -->
                    <label for="alamat" class="form-label">Alamat</label>
                    <!-- Label alamat (opsional) -->
                    <textarea name="alamat" class="form-control" rows="4"></textarea>
                    <!-- Textarea alamat -->
                </div>
            </div>

            <div class="modal-footer">
                <!-- Footer modal -->
                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    <!-- Tombol loader saat proses simpan -->
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

@section('formTambahJS') {{-- Section Blade: JS khusus tambah customer --}}
    <script> // Awal script JS

        function checkForm() { // Fungsi validasi sebelum submit
            const customer = $("input[name='customer']").val(); // Ambil nilai nama customer

            setLoading(true); // Aktifkan loading (tampilkan loader)
            resetValid(); // Reset tanda invalid

            if (customer == "") { // Jika nama customer kosong
                validasi('Nama Customer wajib di isi!', 'warning'); // Tampilkan warning
                $("input[name='customer']").addClass('is-invalid'); // Tandai input invalid
                setLoading(false); // Matikan loading
                return false; // Hentikan proses
            } else { // Jika valid
                submitForm(); // Lanjut submit
            }
        }

        function submitForm() { // Fungsi AJAX untuk menyimpan data customer baru
            const customer = $("input[name='customer']").val(); // Ambil nama customer
            const notelp = $("input[name='notelp']").val(); // Ambil nomor telepon
            const alamat = $("textarea[name='alamat']").val(); // Ambil alamat

            $.ajax({
                type: 'POST', // Method POST
                url: "{{ route('customer.store') }}", // Endpoint route store customer
                enctype: 'multipart/form-data',
                // Catatan: tidak wajib jika tidak ada upload file

                data: { // Data yang dikirim ke server
                    customer: customer,
                    notelp: notelp,
                    alamat: alamat
                },

                success: function(data) { // Callback jika berhasil
                    $('#modaldemo8').modal('toggle'); // Tutup modal tambah
                    swal({ // Notifikasi sukses
                        title: "Berhasil ditambah!",
                        type: "success"
                    });
                    table.ajax.reload(null, false); // Reload DataTables tanpa reset halaman
                    reset(); // Reset form input
                }
            });
        }

        function resetValid() { // Menghapus status invalid
            $("input[name='customer']").removeClass('is-invalid'); // Hapus invalid nama customer
        };

        function reset() { // Reset seluruh field form tambah
            resetValid(); // Reset validasi
            $("input[name='customer']").val(''); // Kosongkan nama customer
            $("input[name='notelp']").val(''); // Kosongkan no telp
            $("textarea[name='ket']").val('');
            // BUG: textarea yang benar bernama 'alamat', bukan 'ket'
            // Seharusnya: $("textarea[name='alamat']").val('');

            setLoading(false); // Matikan loading
        }

        function setLoading(bool) { // Toggle tombol loader dan tombol simpan
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
