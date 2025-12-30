<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk cek hak akses berdasarkan role user
use App\Models\Admin\MerkModel; // Mengimpor model Merk untuk CRUD data merk
use App\Models\Admin\UserModel; // Mengimpor model User untuk tipe data user dari session
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON (AJAX)
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dan info request
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengambil data user login
use Yajra\DataTables\Facades\DataTables; // Mengimpor DataTables facade untuk membuat response DataTables

class MerkController extends Controller // Mendefinisikan controller Merk yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman utama modul Merk
    {
        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id user (nullsafe jika user null)

        $data['title']     = 'Merk'; // Menetapkan judul halaman
        $data['hakTambah'] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Join akses dengan submenu untuk cek hak tambah
            ->where([ // Filter hak akses berdasarkan role, judul submenu, dan tipe akses
                'tbl_akses.role_id'         => $roleId, // Role user
                'tbl_submenu.submenu_judul' => 'Merk', // Submenu Merk
                'tbl_akses.akses_type'      => 'create', // Tipe akses create (tambah)
            ]) // Menutup where array
            ->count(); // Menghitung jumlah hasil (jika > 0 berarti punya hak tambah)

        return view('Admin.Merk.index', $data); // Mengembalikan view halaman Merk dengan data yang sudah disiapkan
    }

    public function show(Request $request): ?JsonResponse // Method untuk mengambil data merk via AJAX (DataTables), return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request ini bukan AJAX
            return null; // Jika bukan AJAX, return null agar tidak memproses DataTables
        } // Menutup kondisi bukan AJAX

        $data = MerkModel::orderBy('merk_id', 'DESC')->get(); // Mengambil semua data merk dan mengurutkan dari yang terbaru

        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id user untuk cek hak akses edit/hapus

        return DataTables::of($data) // Membuat DataTables dari collection data merk
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('ket', function ($row) { // Menambahkan kolom ket (keterangan) untuk merk
                $ket = $row->merk_keterangan == '' ? '-' : $row->merk_keterangan; // Jika keterangan kosong tampil '-', jika ada tampil keterangannya

                return $ket; // Mengembalikan nilai kolom ket
            }) // Menutup addColumn ket
            ->addColumn('action', function ($row) use ($roleId) { // Menambahkan kolom action untuk tombol edit/hapus berdasarkan hak akses
                /** @var string|null $nama */ // Anotasi tipe nama merk
                $nama = $row->merk_nama; // Mengambil nama merk dari row

                /** @var string|null $ket */ // Anotasi tipe keterangan merk
                $ket = $row->merk_keterangan; // Mengambil keterangan merk dari row

                $namaSlugSource = preg_replace( // Membuat slug aman dari nama merk (untuk dikirim ke JS/HTML)
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter tidak valid
                    $nama ?? '' // Sumber string (jika null jadi string kosong)
                ); // Menutup preg_replace nama
                $namaSlug = trim($namaSlugSource ?? ''); // Trim spasi hasil slug nama merk

                $ketSlugSource = preg_replace( // Membuat slug aman dari keterangan merk
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter tidak valid
                    $ket ?? '' // Sumber string (jika null jadi string kosong)
                ); // Menutup preg_replace ket
                $ketSlug = trim($ketSlugSource ?? ''); // Trim spasi hasil slug keterangan merk

                $array = [ // Menyiapkan data merk untuk dikirim ke fungsi JS update/hapus
                    'merk_id'        => $row->merk_id, // ID merk
                    'merk_nama'      => $namaSlug, // Nama merk dalam bentuk slug
                    'merk_keterangan' => $ketSlug, // Keterangan merk dalam bentuk slug
                ]; // Menutup array data merk

                $button  = ''; // Inisialisasi variabel string HTML tombol
                $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak edit/update
                    ->where([ // Kondisi hak edit berdasarkan role dan submenu
                        'tbl_akses.role_id'         => $roleId, // Role user
                        'tbl_submenu.submenu_judul' => 'Merk', // Submenu Merk
                        'tbl_akses.akses_type'      => 'update', // Tipe akses update (edit)
                    ]) // Menutup where
                    ->count(); // Hitung hasil (jika > 0 berarti boleh edit)

                $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak delete/hapus
                    ->where([ // Kondisi hak delete berdasarkan role dan submenu
                        'tbl_akses.role_id'         => $roleId, // Role user
                        'tbl_submenu.submenu_judul' => 'Merk', // Submenu Merk
                        'tbl_akses.akses_type'      => 'delete', // Tipe akses delete (hapus)
                    ]) // Menutup where
                    ->count(); // Hitung hasil (jika > 0 berarti boleh hapus)

                if ($hakEdit > 0 && $hakDelete > 0) { // Jika user punya hak edit dan hapus
                    $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip"
                           data-bs-original-title="Edit"
                           onclick=update(' . json_encode($array) . ')>
                           <span class="fe fe-edit text-success fs-14"></span>
                        </a>
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                        </div>
                    '; // Menutup string HTML tombol
                } elseif ($hakEdit > 0 && $hakDelete == 0) { // Jika hanya punya hak edit
                    $button .= ' // Menambahkan tombol edit saja
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled"
                               data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip"
                               data-bs-original-title="Edit"
                               onclick=update(' . json_encode($array) . ')>
                               <span class="fe fe-edit text-success fs-14"></span>
                            </a>
                        </div>
                    '; // Menutup string HTML tombol edit
                } elseif ($hakEdit == 0 && $hakDelete > 0) { // Jika hanya punya hak delete
                    $button .= ' // Menambahkan tombol hapus saja
                        <div class="g-2">
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
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

    public function proses_tambah(Request $request): JsonResponse // Method untuk menambah data merk baru
    {
        /** @var string|null $rawMerk */ // Anotasi tipe input merk
        $rawMerk = $request->input('merk'); // Mengambil input merk dari request

        $slugSource = preg_replace( // Membuat slug dasar dari nama merk
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid dengan '-'
            $rawMerk ?? '' // Sumber string (jika null jadi string kosong)
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan lowercase dan trim spasi hasil slug

        // insert data // Komentar penanda proses insert data
        MerkModel::create([ // Membuat record baru pada tabel merk
            'merk_nama'       => $request->merk, // Menyimpan nama merk
            'merk_slug'       => $slug, // Menyimpan slug merk
            'merk_keterangan' => $request->ket, // Menyimpan keterangan merk
        ]); // Menutup create array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_ubah(Request $request, MerkModel $merk): JsonResponse // Method untuk update data merk melalui route model binding
    {
        /** @var string|null $rawMerk */ // Anotasi tipe input merk
        $rawMerk = $request->input('merk'); // Mengambil input merk dari request

        $slugSource = preg_replace( // Membuat slug dasar dari nama merk
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid dengan '-'
            $rawMerk ?? '' // Sumber string (jika null jadi string kosong)
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan lowercase dan trim spasi hasil slug

        // update data // Komentar penanda proses update data
        $merk->update([ // Update record merk yang dipilih
            'merk_nama'       => $request->merk, // Update nama merk
            'merk_slug'       => $slug, // Update slug merk
            'merk_keterangan' => $request->ket, // Update keterangan merk
        ]); // Menutup update array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_hapus(Request $request, MerkModel $merk): JsonResponse // Method untuk menghapus data merk
    {
        // delete // Komentar penanda proses delete
        $merk->delete(); // Menghapus record merk dari database

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }
} // Penutup class MerkController
