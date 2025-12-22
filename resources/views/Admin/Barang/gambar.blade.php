{{-- ============================= --}}
{{-- MODAL GAMBAR (PREVIEW FOTO BARANG) --}}
{{-- ============================= --}}
<div class="modal fade" id="Gmodaldemo8">
    {{-- Dialog modal (posisi tengah) --}}
    <div class="modal-dialog modal-dialog-centered" role="document">
        {{-- Konten modal dibuat transparan + tanpa border + tanpa shadow (biar fokus ke gambar) --}}
        <div class="modal-content modal-content-demo bg-transparent border-0 shadow-none">

            {{-- Body modal: menampilkan gambar di tengah --}}
            <div class="modal-body text-center p-4 pb-5">

                {{-- Tombol close (X) untuk menutup modal --}}
                <button type="reset"
                        aria-label="Close"
                        class="btn-close position-absolute"
                        data-bs-dismiss="modal">
                    <span aria-hidden="true">×</span>
                </button>

                {{-- Gambar preview --}}
                {{-- Default: image.png (akan diganti via JS ketika user klik thumbnail) --}}
                {{-- id="outputImgG" dipakai untuk mengganti src dari JavaScript --}}
                <img src="{{url('/assets/default/barang/image.png')}}"
                     width="100%"
                     alt="profile-user"
                     id="outputImgG"
                     class="">
            </div>
        </div>
    </div>
</div>
