<!-- MODAL EDIT -->
<div class="modal fade" data-bs-backdrop="static" id="Umodaldemo8">
    {{-- Modal Bootstrap untuk mengubah data satuan barang --}}
    {{-- data-bs-backdrop="static" → modal tidak tertutup saat klik area luar --}}

    <div class="modal-dialog modal-dialog-centered" role="document">
        {{-- Dialog modal ditampilkan di tengah layar --}}

        <div class="modal-content modal-content-demo">
            {{-- Konten utama modal --}}

            <div class="modal-header">
                {{-- Header modal --}}

                <h6 class="modal-title">Ubah Satuan Barang</h6>
                {{-- Judul modal edit satuan barang --}}

                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal">
                    {{-- Tombol untuk menutup modal --}}
                    <span aria-hidden="true">&times;</span>
                    {{-- Ikon close --}}
                </button>
            </div>

            <div class="modal-body">
                {{-- Body modal: form edit satuan --}}

                <input type="hidden" name="idsatuanU">
                {{-- Hidden input untuk menyimpan ID satuan yang sedang diedit --}}

                <div class="form-group">
                    {{-- Grup input satuan barang --}}

                    <label for="satuanU" class="form-label">
                        Satuan Barang <span class="text-danger">*</span>
                        {{-- Label dengan tanda wajib --}}
                    </label>

                    <input type="text" name="satuanU" class="form-control" placeholder="">
                    {{-- Input teks untuk nama satuan --}}
                </div>

                <div class="form-group">
                    {{-- Grup input keterangan --}}

                    <label for="ketU" class="form-label">Keterangan</label>
                    {{-- Label keterangan (opsional) --}}

                    <textarea name="ketU" class="form-control" rows="4"></textarea>
                    {{-- Textarea untuk keterangan satuan --}}
                </div>
            </div>

            <div class="modal-footer">
                {{-- Footer modal --}}

                <button class="btn btn-success d-none" id="btnLoaderU" type="button" disabled="">
                    {{-- Tombol loader saat proses update berlangsung --}}
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Loading...
                </button>

                <a href="javascript:void(0)" onclick="checkFormU()" id="btnSimpanU" class="btn btn-success">
                    {{-- Tombol simpan perubahan, memanggil fungsi checkFormU() --}}
                    Simpan Perubahan <i class="fe fe-check"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-light"
                   onclick="resetU()" data-bs-dismiss="modal">
                    {{-- Tombol batal: reset form lalu tutup modal --}}
                    Batal <i class="fe fe-x"></i>
                </a>
            </div>

        </div>
    </div>
</div>


@section('formEditJS')
{{-- Section Blade untuk JavaScript khusus form edit satuan --}}
<script>

    function checkFormU() {
        // Fungsi validasi sebelum mengirim data edit

        const satuan = $("input[name='satuanU']").val();
        // Ambil nilai input satuan

        setLoadingU(true);
        // Aktifkan loading (spinner tampil, tombol simpan disembunyikan)

        resetValidU();
        // Reset status validasi sebelumnya

        if (satuan == "") {
            // Jika input satuan kosong

            validasi('Satuan Barang wajib di isi!', 'warning');
            // Tampilkan notifikasi peringatan

            $("input[name='satuanU']").addClass('is-invalid');
            // Tandai input satuan sebagai invalid

            setLoadingU(false);
            // Matikan loading

            return false;
            // Hentikan proses submit
        } else {
            // Jika valid
            submitFormU();
            // Lanjutkan proses kirim data ke server
        }
    }

    function submitFormU() {
        // Fungsi AJAX untuk mengirim data edit satuan ke server

        const id = $("input[name='idsatuanU']").val();
        // Ambil ID satuan

        const satuan = $("input[name='satuanU']").val();
        // Ambil nama satuan

        const ket = $("textarea[name='ketU']").val();
        // Ambil keterangan

        $.ajax({
            type: 'POST',
            // Method POST untuk update data

            url: "{{url('admin/satuan/proses_ubah')}}/" + id,
            // URL proses ubah satuan + id

            enctype: 'multipart/form-data',
            // Tidak wajib, tapi aman untuk form Laravel

            data: {
                satuan: satuan,
                ket: ket
            },
            // Data yang dikirim ke backend

            success: function(data) {
                // Jika proses berhasil

                swal({
                    title: "Berhasil diubah!",
                    type: "success"
                });
                // Tampilkan notifikasi sukses

                $('#Umodaldemo8').modal('toggle');
                // Tutup modal edit

                table.ajax.reload(null, false);
                // Reload DataTables tanpa reset halaman

                resetU();
                // Reset form edit
            }
        });
    }

    function resetValidU() {
        // Menghapus status invalid pada input

        $("input[name='satuanU']").removeClass('is-invalid');
        // Reset input satuan

        $("textarea[name='ketU']").removeClass('is-invalid');
        // Reset textarea keterangan
    };

    function resetU() {
        // Fungsi reset form edit satuan

        resetValidU();
        // Reset validasi

        $("input[name='idsatuanU']").val('');
        // Kosongkan ID satuan

        $("input[name='satuanU']").val('');
        // Kosongkan input satuan

        $("textarea[name='ketU']").val('');
        // Kosongkan keterangan

        setLoadingU(false);
        // Matikan loading
    }

    function setLoadingU(bool) {
        // Fungsi toggle antara loader dan tombol simpan

        if (bool == true) {
            // Jika loading aktif
            $('#btnLoaderU').removeClass('d-none');
            // Tampilkan loader
            $('#btnSimpanU').addClass('d-none');
            // Sembunyikan tombol simpan
        } else {
            // Jika loading tidak aktif
            $('#btnSimpanU').removeClass('d-none');
            // Tampilkan tombol simpan
            $('#btnLoaderU').addClass('d-none');
            // Sembunyikan loader
        }
    }

</script>
@endsection
{{-- Akhir section formEditJS --}}
