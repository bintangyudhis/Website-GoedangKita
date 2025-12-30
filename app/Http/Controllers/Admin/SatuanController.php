<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk cek hak akses user berdasarkan role
use App\Models\Admin\SatuanModel; // Mengimpor model Satuan untuk CRUD data satuan
use App\Models\Admin\UserModel; // Mengimpor model User untuk tipe data user dari session
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON (AJAX)
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dan info request
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengambil data user login
use Yajra\DataTables\Facades\DataTables; // Mengimpor DataTables facade untuk membuat response DataTables

class SatuanController extends Controller // Mendefinisikan controller Satuan yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman utama modul Satuan
    {
        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id user (nullsafe jika user null)

        $data['title']     = 'Satuan'; // Menetapkan judul halaman
        $data['hakTambah'] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Join akses dengan submenu untuk cek hak tambah
            ->where([ // Filter hak akses berdasarkan role, judul submenu, dan tipe akses
                'tbl_akses.role_id'         => $roleId, // Role user
                'tbl_submenu.submenu_judul' => 'Satuan', // Submenu Satuan
                'tbl_akses.akses_type'      => 'create', // Tipe akses create (tambah)
            ]) // Menutup where array
            ->count(); // Menghitung jumlah hasil (jika > 0 berarti punya hak tambah)

        return view('Admin.Satuan.index', $data); // Mengembalikan view halaman Satuan dengan data yang sudah disiapkan
    }

    public function show(Request $request): ?JsonResponse // Method untuk mengambil data satuan via AJAX (DataTables), return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request ini bukan AJAX
            return null; // Jika bukan AJAX, return null agar tidak memproses DataTables
        } // Menutup kondisi bukan AJAX

        $data = SatuanModel::orderBy('satuan_id', 'DESC')->get(); // Mengambil semua data satuan dan mengurutkan dari yang terbaru

        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id untuk cek hak akses edit/hapus

        return DataTables::of($data) // Membuat DataTables dari collection data satuan
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('ket', function ($row) { // Menambahkan kolom ket (keterangan) untuk satuan
                $ket = $row->satuan_keterangan == '' ? '-' : $row->satuan_keterangan; // Jika keterangan kosong tampil '-', jika ada tampil keterangannya

                return $ket; // Mengembalikan nilai kolom ket
            }) // Menutup addColumn ket
            ->addColumn('action', function ($row) use ($roleId) { // Menambahkan kolom action untuk tombol edit/hapus berdasarkan hak akses
                /** @var string|null $nama */ // Anotasi tipe nama satuan
                $nama = $row->satuan_nama; // Mengambil nama satuan dari row

                /** @var string|null $ket */ // Anotasi tipe keterangan satuan
                $ket = $row->satuan_keterangan; // Mengambil keterangan satuan dari row

                $namaSlugSource = preg_replace( // Membuat slug aman dari nama satuan (untuk dikirim ke JS/HTML)
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter tidak valid
                    $nama ?? '' // Sumber string (jika null jadi string kosong)
                ); // Menutup preg_replace nama
                $namaSlug = trim($namaSlugSource ?? ''); // Trim spasi hasil slug nama

                $ketSlugSource = preg_replace( // Membuat slug aman dari keterangan satuan
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter tidak valid
                    $ket ?? '' // Sumber string (jika null jadi string kosong)
                ); // Menutup preg_replace ket
                $ketSlug = trim($ketSlugSource ?? ''); // Trim spasi hasil slug keterangan

                $array = [ // Menyiapkan data satuan untuk dikirim ke fungsi JS update/hapus
                    'satuan_id'        => $row->satuan_id, // ID satuan
                    'satuan_nama'      => $namaSlug, // Nama satuan dalam bentuk slug
                    'satuan_keterangan'=> $ketSlug, // Keterangan satuan dalam bentuk slug
                ]; // Menutup array data satuan

                $button  = ''; // Inisialisasi variabel string HTML tombol
                $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak edit/update
                    ->where([ // Kondisi hak edit berdasarkan role dan submenu
                        'tbl_akses.role_id'         => $roleId, // Role user
                        'tbl_submenu.submenu_judul' => 'Satuan', // Submenu Satuan
                        'tbl_akses.akses_type'      => 'update', // Tipe akses update (edit)
                    ]) // Menutup where
                    ->count(); // Hitung hasil (jika > 0 berarti boleh edit)

                $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak delete/hapus
                    ->where([ // Kondisi hak delete berdasarkan role dan submenu
                        'tbl_akses.role_id'         => $roleId, // Role user
                        'tbl_submenu.submenu_judul' => 'Satuan', // Submenu Satuan
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

    public function proses_tambah(Request $request): JsonResponse // Method untuk menambah data satuan baru
    {
        /** @var string|null $rawSatuan */ // Anotasi tipe input satuan
        $rawSatuan = $request->input('satuan'); // Mengambil input satuan dari request

        $slugSource = preg_replace( // Membuat slug dasar dari nama satuan
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid dengan '-'
            $rawSatuan ?? '' // Sumber string (jika null jadi string kosong)
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan lowercase dan trim spasi hasil slug

        // insert data // Komentar penanda proses insert
        SatuanModel::create([ // Membuat record baru pada tabel satuan
            'satuan_nama'       => $request->satuan, // Menyimpan nama satuan
            'satuan_slug'       => $slug, // Menyimpan slug satuan
            'satuan_keterangan' => $request->ket, // Menyimpan keterangan satuan
        ]); // Menutup create array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_ubah(Request $request, SatuanModel $satuan): JsonResponse // Method untuk update data satuan melalui route model binding
    {
        /** @var string|null $rawSatuan */ // Anotasi tipe input satuan
        $rawSatuan = $request->input('satuan'); // Mengambil input satuan dari request

        $slugSource = preg_replace( // Membuat slug dasar dari nama satuan
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid dengan '-'
            $rawSatuan ?? '' // Sumber string (jika null jadi string kosong)
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan lowercase dan trim spasi hasil slug

        // update data // Komentar penanda proses update
        $satuan->update([ // Update record satuan yang dipilih
            'satuan_nama'       => $request->satuan, // Update nama satuan
            'satuan_slug'       => $slug, // Update slug satuan
            'satuan_keterangan' => $request->ket, // Update keterangan satuan
        ]); // Menutup update array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_hapus(Request $request, SatuanModel $satuan): JsonResponse // Method untuk menghapus data satuan
    {
        // delete // Komentar penanda proses delete
        $satuan->delete(); // Menghapus record satuan dari database

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }
} // Penutup class SatuanController
