<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Master; // Namespace controller untuk modul Master (manajemen role)

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk menghapus akses terkait role saat role dihapus
use App\Models\Admin\RoleModel; // Mengimpor model Role untuk CRUD data role di database
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON (AJAX)
use Illuminate\Http\RedirectResponse; // Mengimpor RedirectResponse untuk type hint redirect
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input form
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk flash message
use Yajra\DataTables\Facades\DataTables; // Mengimpor DataTables facade untuk membuat response DataTables

class RoleController extends Controller // Mendefinisikan controller Role yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman utama Role
    {
        return view('Master.Role.index', [ // Mengembalikan view Master.Role.index
            'title' => 'Role', // Mengirimkan judul halaman ke view
        ]); // Menutup return view
    }

    public function show(Request $request): JsonResponse // Method untuk menampilkan data role via AJAX DataTables
    {
        if ($request->ajax()) { // Mengecek apakah request berasal dari AJAX (DataTables)
            $data = RoleModel::latest()->get(); // Mengambil semua role terbaru (urut desc created_at)

            return DataTables::of($data) // Membuat DataTables dari collection role
                ->addIndexColumn() // Menambahkan kolom index/nomor urut
                ->addColumn('action', function ($row) { // Menambahkan kolom action (tombol edit/hapus atau locked)
                    /** @var string|null $roleTitleSlug */ // Anotasi tipe variabel slug title
                    $roleTitleSlug = preg_replace( // Membuat versi slug aman untuk role_title
                        '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                        '_', // Ganti karakter tidak valid menjadi underscore
                        (string) $row->role_title // Sumber string role_title dipaksa string
                    ); // Menutup preg_replace title

                    /** @var string|null $roleDescSlug */ // Anotasi tipe variabel slug desc
                    $roleDescSlug = preg_replace( // Membuat versi slug aman untuk role_desc
                        '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                        '_', // Ganti karakter tidak valid menjadi underscore
                        (string) $row->role_desc // Sumber string role_desc dipaksa string
                    ); // Menutup preg_replace desc

                    $array = [ // Menyiapkan data role untuk dikirim ke fungsi JS (update/hapus)
                        'role_id'   => $row->role_id, // ID role
                        'role_title'=> trim($roleTitleSlug ?? ''), // Title role dalam format aman + trim
                        'role_desc' => trim($roleDescSlug ?? ''), // Desc role dalam format aman + trim
                    ]; // Menutup array role

                    if ($row->role_id != 1) { // Jika bukan role Super Admin (id 1), izinkan edit dan hapus
                        return ' // Mengembalikan HTML tombol action edit/hapus
                            <div class="g-2">
                                <a class="btn modal-effect text-primary btn-sm"
                                   data-bs-effect="effect-super-scaled"
                                   data-bs-toggle="modal" href="#Umodaldemo8"
                                   onclick=\'update(' . json_encode($array) . ')\'>
                                   <span class="fe fe-edit text-success fs-14"></span>
                                </a>

                                <a class="btn modal-effect text-danger btn-sm"
                                   data-bs-effect="effect-super-scaled"
                                   data-bs-toggle="modal" href="#Hmodaldemo8"
                                   onclick=\'hapus(' . json_encode($array) . ')\'>
                                   <span class="fe fe-trash-2 fs-14"></span>
                                </a>
                            </div>
                        '; // Menutup string HTML
                    } // Menutup kondisi bukan super admin

                    return '<span class="badge bg-success">Locked</span>'; // Jika role super admin, tampilkan badge locked
                }) // Menutup addColumn action
                ->rawColumns(['action']) // Mengizinkan kolom action berisi HTML (tidak di-escape)
                ->make(true); // Menghasilkan response JSON DataTables
        } // Menutup kondisi AJAX

        return response()->json([]); // Jika bukan request AJAX, kembalikan JSON kosong agar aman
    }

    public function store(Request $request): RedirectResponse // Method untuk menyimpan role baru (tambah)
    {
        /** @var string|null $rawTitle */ // Anotasi tipe judul role dari input
        $rawTitle = $request->input('title'); // Mengambil input judul role

        /** @var string|null $slugSource */ // Anotasi tipe slugSource
        $slugSource = preg_replace( // Membuat slug dasar dari title
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid menjadi '-'
            $rawTitle ?? '' // Sumber string (jika null jadi string kosong)
        ); // Menutup preg_replace

        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan slug lowercase dan trim spasi

        RoleModel::create([ // Menambahkan role baru ke database
            'role_title' => $request->title, // Menyimpan title role dari form
            'role_slug'  => $slug, // Menyimpan slug role
            'role_desc'  => $request->desc, // Menyimpan deskripsi role dari form
        ]); // Menutup create

        Session::flash('status', 'success'); // Menyimpan status flash untuk notifikasi
        Session::flash('msg', 'Berhasil ditambah!'); // Menyimpan pesan flash sukses tambah

        return redirect()->route('role.index'); // Redirect kembali ke halaman index role
    }

    public function update(Request $request, RoleModel $role): RedirectResponse // Method untuk update role (route model binding)
    {
        // Role Super Admin tidak boleh diubah // Komentar penanda aturan super admin
        if ($role->role_id == 1) { // Jika role yang akan diupdate adalah role_id 1
            Session::flash('status', 'error'); // Set flash status error
            Session::flash('msg', 'Role Super Admin tidak boleh diubah!'); // Set flash pesan error

            return redirect()->route('role.index'); // Redirect kembali tanpa melakukan update
        } // Menutup kondisi super admin

        /** @var string|null $rawTitle */ // Anotasi tipe judul role dari input update
        $rawTitle = $request->input('utitle'); // Mengambil input judul role baru dari form update

        /** @var string|null $slugSource */ // Anotasi tipe slugSource
        $slugSource = preg_replace( // Membuat slug dasar dari title update
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid menjadi '-'
            $rawTitle ?? '' // Sumber string (jika null jadi string kosong)
        ); // Menutup preg_replace

        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan slug lowercase dan trim spasi

        $role->update([ // Update data role yang dipilih
            'role_title' => $request->utitle, // Update title role
            'role_slug'  => $slug, // Update slug role
            'role_desc'  => $request->udesc, // Update deskripsi role
        ]); // Menutup update

        Session::flash('status', 'success'); // Set flash status sukses
        Session::flash('msg', 'Berhasil diubah!'); // Set flash pesan sukses update

        return redirect()->route('role.index'); // Redirect kembali ke halaman index role
    }

    public function hapus(Request $request): RedirectResponse // Method untuk menghapus role
    {
        // Role Super Admin tidak boleh dihapus // Komentar penanda aturan super admin
        if ($request->idrole == 1) { // Jika role_id yang ingin dihapus adalah 1
            Session::flash('status', 'error'); // Set flash status error
            Session::flash('msg', 'Role Super Admin tidak boleh dihapus!'); // Set flash pesan error

            return redirect()->route('role.index'); // Redirect kembali tanpa menghapus
        } // Menutup kondisi super admin

        // findOrFail bisa return model atau collection → paksa ambil first() // Komentar penjelas pemilihan method query
        $role = RoleModel::where('role_id', $request->idrole)->firstOrFail(); // Mengambil role berdasarkan role_id, jika tidak ada maka 404
        $role->delete(); // Menghapus record role dari database

        AksesModel::where('role_id', $request->idrole)->delete(); // Menghapus semua data akses yang terkait role tersebut

        Session::flash('status', 'success'); // Set flash status sukses
        Session::flash('msg', 'Berhasil dihapus!'); // Set flash pesan sukses hapus

        return redirect()->route('role.index'); // Redirect kembali ke halaman index role
    }
} // Penutup class RoleController
