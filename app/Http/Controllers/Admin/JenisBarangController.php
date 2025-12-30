<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk cek hak akses user
use App\Models\Admin\JenisBarangModel; // Mengimpor model Jenis Barang untuk CRUD data jenis barang
use App\Models\Admin\UserModel; // Mengimpor model User untuk tipe data user dari session
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dan info request
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengambil data user login
use Yajra\DataTables\Facades\DataTables; // Mengimpor DataTables facade untuk membuat response tabel AJAX

class JenisBarangController extends Controller // Mendefinisikan controller Jenis Barang yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman utama modul Jenis Barang
    {
        $data['title'] = 'Jenis'; // Menetapkan judul halaman

        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id user (nullsafe jika user null)

        $data['hakTambah'] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Join akses dengan submenu untuk cek hak tambah
            ->where([ // Menambahkan kondisi filter hak akses
                'tbl_akses.role_id'         => $roleId, // Filter berdasarkan role user
                'tbl_submenu.submenu_judul' => 'Jenis', // Filter submenu berjudul Jenis
                'tbl_akses.akses_type'      => 'create', // Filter tipe akses create (tambah)
            ]) // Menutup where array
            ->count(); // Menghitung jumlah hasil (jika > 0 berarti punya hak tambah)

        return view('Admin.JenisBarang.index', $data); // Mengembalikan view halaman jenis barang dengan data yang sudah disiapkan
    }

    public function show(Request $request): ?JsonResponse // Method untuk mengambil data jenis barang via AJAX (DataTables), return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request ini bukan AJAX
            return null; // Jika bukan AJAX, return null agar tidak memproses DataTables
        } // Menutup kondisi bukan AJAX

        $data = JenisBarangModel::orderBy('jenisbarang_id', 'DESC')->get(); // Mengambil data jenis barang dan mengurutkan dari yang terbaru

        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id untuk cek hak akses edit/hapus

        return DataTables::of($data) // Membuat DataTables dari collection data jenis barang
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('ket', function ($row) { // Menambahkan kolom ket (keterangan) untuk jenis barang
                $ket = $row->jenisbarang_ket == '' ? '-' : $row->jenisbarang_ket; // Jika keterangan kosong tampil '-', jika ada tampil keterangannya

                return $ket; // Mengembalikan nilai untuk kolom ket
            }) // Menutup addColumn ket
            ->addColumn('action', function ($row) use ($roleId) { // Menambahkan kolom action untuk tombol edit/hapus berdasarkan hak akses
                /** @var string|null $nama */ // Anotasi tipe nama jenis barang
                $nama = $row->jenisbarang_nama; // Mengambil nama jenis barang dari row

                /** @var string|null $ket */ // Anotasi tipe keterangan jenis barang
                $ket = $row->jenisbarang_ket; // Mengambil keterangan jenis barang dari row

                $namaSlugSource = preg_replace( // Membuat slug aman dari nama jenis barang (untuk JS/HTML)
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter tidak valid
                    $nama ?? '' // Sumber string (jika null jadikan string kosong)
                ); // Menutup preg_replace nama
                $namaSlug = trim($namaSlugSource ?? ''); // Trim spasi hasil slug nama

                $ketSlugSource = preg_replace( // Membuat slug aman dari keterangan jenis barang
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter tidak valid
                    $ket ?? '' // Sumber string (jika null jadikan string kosong)
                ); // Menutup preg_replace ket
                $ketSlug = trim($ketSlugSource ?? ''); // Trim spasi hasil slug keterangan

                $array = [ // Menyiapkan data jenis barang untuk dikirim ke fungsi JS update/hapus
                    'jenisbarang_id'  => $row->jenisbarang_id, // ID jenis barang
                    'jenisbarang_nama' => $namaSlug, // Nama jenis barang dalam bentuk slug
                    'jenisbarang_ket' => $ketSlug, // Keterangan jenis barang dalam bentuk slug
                ]; // Menutup array data jenis barang

                $button  = ''; // Inisialisasi variabel string HTML tombol
                $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak edit
                    ->where([ // Kondisi hak edit berdasarkan role dan submenu
                        'tbl_akses.role_id'         => $roleId, // Role user
                        'tbl_submenu.submenu_judul' => 'Jenis', // Submenu Jenis
                        'tbl_akses.akses_type'      => 'update', // Tipe akses update (edit)
                    ]) // Menutup where
                    ->count(); // Hitung hasil (jika > 0 berarti boleh edit)

                $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak hapus
                    ->where([ // Kondisi hak hapus berdasarkan role dan submenu
                        'tbl_akses.role_id'         => $roleId, // Role user
                        'tbl_submenu.submenu_judul' => 'Jenis', // Submenu Jenis
                        'tbl_akses.akses_type'      => 'delete', // Tipe akses delete (hapus)
                    ]) // Menutup where
                    ->count(); // Hitung hasil (jika > 0 berarti boleh hapus)

                if ($hakEdit > 0 && $hakDelete > 0) { // Jika user punya hak edit dan hapus
                    $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span></a>
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                    '; // Menutup string HTML tombol
                } elseif ($hakEdit > 0 && $hakDelete == 0) { // Jika hanya punya hak edit
                    $button .= ' // Menambahkan tombol edit saja
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span></a>
                        </div>
                    '; // Menutup string HTML tombol edit
                } elseif ($hakEdit == 0 && $hakDelete > 0) { // Jika hanya punya hak delete
                    $button .= ' // Menambahkan tombol hapus saja
                        <div class="g-2">
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                    '; // Menutup string HTML tombol hapus
                } else { // Jika tidak punya hak edit maupun hapus
                    $button .= '-'; // Tampilkan '-' sebagai pengganti tombol
                } // Menutup kondisi hak akses

                return $button; // Mengembalikan HTML tombol untuk kolom action
            }) // Menutup addColumn action
            ->rawColumns(['action', 'ket']) // Menandai kolom yang berisi HTML agar tidak di-escape
            ->make(true); // Menghasilkan response JSON DataTables
    }

    public function proses_tambah(Request $request): JsonResponse // Method untuk menambah data jenis barang baru
    {
        /** @var string|null $rawJenis */ // Anotasi tipe input jenis barang
        $rawJenis = $request->input('jenisbarang'); // Mengambil input jenis barang dari request

        $slugSource = preg_replace( // Membuat slug dasar dari nama jenis barang
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid dengan '-'
            $rawJenis ?? '' // Sumber string (jika null pakai string kosong)
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan lowercase dan trim spasi hasil slug

        JenisBarangModel::create([ // Membuat record baru pada tabel jenis barang
            'jenisbarang_nama' => $request->jenisbarang, // Menyimpan nama jenis barang
            'jenisbarang_slug' => $slug, // Menyimpan slug jenis barang
            'jenisbarang_ket'  => $request->ket, // Menyimpan keterangan jenis barang
        ]); // Menutup create array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_ubah(Request $request, JenisBarangModel $jenisbarang): JsonResponse // Method untuk update data jenis barang melalui route model binding
    {
        /** @var string|null $rawJenis */ // Anotasi tipe input jenis barang
        $rawJenis = $request->input('jenisbarang'); // Mengambil input jenis barang dari request

        $slugSource = preg_replace( // Membuat slug dasar dari nama jenis barang
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid dengan '-'
            $rawJenis ?? '' // Sumber string (jika null pakai string kosong)
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan lowercase dan trim spasi hasil slug

        $jenisbarang->update([ // Update record jenis barang yang dipilih
            'jenisbarang_nama' => $request->jenisbarang, // Update nama jenis barang
            'jenisbarang_slug' => $slug, // Update slug jenis barang
            'jenisbarang_ket'  => $request->ket, // Update keterangan jenis barang
        ]); // Menutup update array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_hapus(Request $request, JenisBarangModel $jenisbarang): JsonResponse // Method untuk menghapus data jenis barang
    {
        $jenisbarang->delete(); // Menghapus record jenis barang dari database

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }
} // Penutup class JenisBarangController
