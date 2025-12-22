<!-- MODAL TAMBAH -->
<div class="modal fade" data-bs-backdrop="static" id="modaldemo8">
    {{-- Modal Bootstrap untuk tambah merk --}}
    {{-- data-bs-backdrop="static" → klik di luar modal tidak menutup modal --}}

    <div class="modal-dialog modal-dialog-centered" role="document">
        {{-- Dialog modal ditampilkan di tengah layar --}}

        <div class="modal-content modal-content-demo">
            {{-- Konten utama modal --}}

            <div class="modal-header">
                {{-- Header modal --}}

                <h6 class="modal-title">Tambah Merk</h6>
                {{-- Judul modal --}}

                <button aria-label="Close" class="btn-close"
                    data-bs-dismiss="modal">
                    {{-- Tombol untuk menutup modal --}}
                    <span aria-hidden="true">&times;</span>
                    {{-- Ikon close --}}
                </button>
            </div>

            <div class="modal-body">
                {{-- Body modal: berisi form input --}}

                <div class="form-group">
                    {{-- Grup input nama merk --}}

                    <label for="merk" class="form-label">
                        Nama Merk <span class="text-danger">*</span>
                        {{-- Label input dengan tanda wajib --}}
                    </label>

                    <input type="text" name="merk" class="form-control" placeholder="">
                    {{-- Input teks untuk nama merk --}}
                </div>

                <div class="form-group">
                    {{-- Grup input keterangan --}}

                    <label for="ket" class="form-label">Keterangan</label>
                    {{-- Label keterangan (opsional) --}}

                    <textarea name="ket" class="form-control" rows="4"></textarea>
                    {{-- Textarea untuk keterangan merk --}}
                </div>
            </div>

            <div class="modal-footer">
                {{-- Footer modal --}}

                <button class="btn btn-primary d-none" id="btnLoader" type="button" disabled="">
                    {{-- Tombol loader (spinner) saat proses simpan --}}
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkForm()" id="btnSimpan" class="btn btn-primary">
                    {{-- Tombol simpan, memanggil fungsi checkForm() --}}
                    Simpan <i class="fe fe-check"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-light"
                    onclick="reset()" data-bs-dismiss="modal">
                    {{-- Tombol batal: reset form lalu tutup modal --}}
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>

        </div>
    </div>
</div>


@section('formTambahJS')
{{-- Section Blade untuk JavaScript khusus form tambah --}}
<script>

        function checkForm() {
            // Fungsi validasi sebelum submit data

            const merk = $("input[name='merk']").val();
            // Ambil nilai input nama merk

            setLoading(true);
            // Aktifkan loading (spinner muncul, tombol simpan disembunyikan)

            resetValid();
            // Reset validasi sebelumnya

            if (merk == "") {
                // Jika nama merk kosong

                validasi('Nama Merk wajib di isi!', 'warning');
                // Tampilkan notifikasi warning

                $("input[name='merk']").addClass('is-invalid');
                // Tandai input sebagai invalid

                setLoading(false);
                // Matikan loading

                return false;
                // Hentikan proses
            } else {
                // Jika valid
                submitForm();
                // Lanjut kirim data ke server
            }

        }

        function submitForm() {
            // Fungsi AJAX untuk menyimpan data merk baru

            const merk = $("input[name='merk']").val();
            // Ambil nama merk

            const ket = $("textarea[name='ket']").val();
            // Ambil keterangan merk

            $.ajax({
                type: 'POST',
                // Method POST untuk simpan data

                url: "{{ route('merk.store') }}",
                // Route Laravel untuk simpan merk

                enctype: 'multipart/form-data',
                // Tidak wajib, tapi aman jika nanti ada upload

                data: {
                    merk: merk,
                    ket: ket
                },
                // Data yang dikirim ke backend

                success: function(data) {
                    // Jika berhasil

                    $('#modaldemo8').modal('toggle');
                    // Tutup modal tambah

                    swal({
                        title: "Berhasil ditambah!",
                        type: "success"
                    });
                    // Tampilkan notifikasi sukses

                    table.ajax.reload(null, false);
                    // Reload DataTables tanpa reset halaman

                    reset();
                    // Reset form tambah
                }
            });
        }

        function resetValid() {
            // Menghapus status invalid pada input

            $("input[name='merk']").removeClass('is-invalid');
        };

        function reset() {
            // Reset seluruh form tambah

            resetValid();
            // Reset validasi

            $("input[name='merk']").val('');
            // Kosongkan input nama merk

            $("textarea[name='ket']").val('');
            // Kosongkan textarea keterangan

            setLoading(false);
            // Matikan loading
        }

        function setLoading(bool) {
            // Fungsi toggle loading spinner dan tombol simpan

            if (bool == true) {
                // Jika loading aktif
                $('#btnLoader').removeClass('d-none');
                // Tampilkan loader
                $('#btnSimpan').addClass('d-none');
                // Sembunyikan tombol simpan
            } else {
                // Jika loading mati
                $('#btnSimpan').removeClass('d-none');
                // Tampilkan tombol simpan
                $('#btnLoader').addClass('d-none');
                // Sembunyikan loader
            }
        }
    </script>
@endsection
{{-- Akhir section formTambahJS --}}
