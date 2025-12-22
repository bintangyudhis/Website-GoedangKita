<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Master; // Namespace controller untuk modul Master (pengaturan akses)

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk CRUD data hak akses di database
use App\Models\Admin\MenuModel; // Mengimpor model Menu untuk mengambil daftar menu yang bisa diberi akses
use App\Models\Admin\RoleModel; // Mengimpor model Role untuk mengambil daftar role dan detail role
use App\Models\Admin\SubmenuModel; // Mengimpor model Submenu untuk mengambil daftar submenu yang bisa diberi akses
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\RedirectResponse; // Mengimpor RedirectResponse untuk type hint return redirect

class AksesController extends Controller // Mendefinisikan controller Akses (manajemen hak akses) yang mewarisi Controller Laravel
{
    public function index(string $role): View // Method untuk menampilkan halaman manajemen akses untuk role tertentu
    {
        $data['title']      = 'Akses'; // Menetapkan judul halaman
        $data['roleid']     = $role === 'role' ? '' : $role; // Menentukan roleid: jika param 'role' maka kosong, kalau tidak pakai role yang dikirim
        $data['detailrole'] = $role === 'role' ? '' : RoleModel::where('role_id', '=', $role)->first(); // Mengambil detail role jika role bukan 'role'
        $data['role']       = RoleModel::where('role_id', '!=', 1)->latest()->get(); // Mengambil daftar role selain super admin (role_id 1)
        $data['menu']       = MenuModel::where('menu_type', '=', '1')->orderBy('menu_sort', 'ASC')->get(); // Mengambil daftar menu utama (type 1) urut berdasarkan sort
        $data['menusub']    = MenuModel::where('menu_type', '=', '2')->orderBy('menu_sort', 'ASC')->get(); // Mengambil daftar menu sub/kelompok (type 2) urut berdasarkan sort

        return view('Master.Akses.index', $data); // Mengembalikan view halaman akses beserta data yang dibutuhkan
    }

    // Memberikan satu izin spesifik. Fungsi ini biasanya dipanggil oleh AJAX ketika admin mencentang sebuah checkbox di halaman hak akses.
    public function addAkses(int $idmenu, int $idrole, string $type, string $akses): RedirectResponse // Method untuk menambahkan akses tertentu untuk role tertentu
    {
        if ($type === 'menu') { // Jika yang diberi akses adalah menu utama
            // create input menu // Komentar penanda create akses menu
            AksesModel::create([ // Menambahkan record akses baru ke tabel akses
                'menu_id'    => $idmenu, // Menyimpan id menu
                'role_id'    => $idrole, // Menyimpan id role
                'akses_type' => $akses, // Menyimpan jenis akses (view/create/update/delete)
            ]); // Menutup create
        } elseif ($type === 'submenu') { // Jika yang diberi akses adalah submenu
            // create input submenu // Komentar penanda create akses submenu
            AksesModel::create([ // Menambahkan record akses baru untuk submenu
                'submenu_id' => $idmenu, // Menyimpan id submenu (menggunakan variabel $idmenu sebagai id submenu)
                'role_id'    => $idrole, // Menyimpan id role
                'akses_type' => $akses, // Menyimpan jenis akses
            ]); // Menutup create
        } elseif ($type === 'othermenu') { // Jika yang diberi akses adalah othermenu (menu tambahan/khusus)
            // create input othermenu // Komentar penanda create akses othermenu
            AksesModel::create([ // Menambahkan record akses baru untuk othermenu
                'othermenu_id' => $idmenu, // Menyimpan id othermenu
                'role_id'      => $idrole, // Menyimpan id role
                'akses_type'   => $akses, // Menyimpan jenis akses
            ]); // Menutup create
        } // Menutup kondisi tipe akses

        $data['title'] = 'Akses'; // Menetapkan title untuk data yang dikirim saat redirect (dipakai untuk view)

        // redirect to index // Komentar penanda redirect setelah menambah akses
        return redirect(url('admin/akses/' . $idrole))->with($data); // Redirect kembali ke halaman akses role tersebut dan membawa data title
    }

    // Mencabut satu izin spesifik. Fungsi ini dipanggil ketika admin menghilangkan centang pada sebuah checkbox.
    public function removeAkses(int $idmenu, int $idrole, string $type, string $akses): RedirectResponse // Method untuk menghapus/mencabut akses tertentu
    {
        if ($type === 'menu') { // Jika akses yang dicabut adalah menu utama
            AksesModel::where([ // Mencari record akses yang sesuai kondisi
                'menu_id'    => $idmenu, // Cocokkan menu_id
                'role_id'    => $idrole, // Cocokkan role_id
                'akses_type' => $akses, // Cocokkan jenis akses
            ])->delete(); // Hapus record yang cocok
        } elseif ($type === 'submenu') { // Jika akses yang dicabut adalah submenu
            AksesModel::where([ // Mencari record akses submenu yang sesuai
                'submenu_id' => $idmenu, // Cocokkan submenu_id (variabel $idmenu dipakai sebagai id submenu)
                'role_id'    => $idrole, // Cocokkan role_id
                'akses_type' => $akses, // Cocokkan jenis akses
            ])->delete(); // Hapus record yang cocok
        } elseif ($type === 'othermenu') { // Jika akses yang dicabut adalah othermenu
            AksesModel::where([ // Mencari record akses othermenu yang sesuai
                'othermenu_id' => $idmenu, // Cocokkan othermenu_id
                'role_id'      => $idrole, // Cocokkan role_id
                'akses_type'   => $akses, // Cocokkan jenis akses
            ])->delete(); // Hapus record yang cocok
        } // Menutup kondisi tipe akses

        $data['title'] = 'Akses'; // Menetapkan title untuk data redirect

        // redirect to index // Komentar penanda redirect setelah mencabut akses
        return redirect(url('admin/akses/' . $idrole))->with($data); // Redirect kembali ke halaman akses role tersebut
    }

    public function setAllAkses(int $idrole): RedirectResponse // Method untuk memberikan semua akses (view/create/update/delete) ke sebuah role
    {
        AksesModel::where(['role_id' => $idrole])->delete(); // Menghapus semua akses role tersebut terlebih dahulu agar tidak duplikat

        $object1 = []; // Menyiapkan array batch insert untuk akses menu
        $object2 = []; // Menyiapkan array batch insert untuk akses submenu
        $object3 = []; // Menyiapkan array batch insert untuk akses othermenu

        $menu = MenuModel::orderBy('menu_sort', 'ASC')->get(); // Mengambil semua menu berdasarkan urutan sort
        foreach ($menu as $m) { // Loop setiap menu
            $object1[] = [ // Menambahkan akses view untuk menu
                'menu_id'    => $m->menu_id, // ID menu
                'role_id'    => $idrole, // Role yang diberi akses
                'akses_type' => 'view', // Jenis akses view
                'created_at' => now(), // Timestamp create
                'updated_at' => now(), // Timestamp update
            ]; // Menutup array akses view menu
            $object1[] = [ // Menambahkan akses create untuk menu
                'menu_id'    => $m->menu_id, // ID menu
                'role_id'    => $idrole, // Role yang diberi akses
                'akses_type' => 'create', // Jenis akses create
                'created_at' => now(), // Timestamp create
                'updated_at' => now(), // Timestamp update
            ]; // Menutup array akses create menu
            $object1[] = [ // Menambahkan akses update untuk menu
                'menu_id'    => $m->menu_id, // ID menu
                'role_id'    => $idrole, // Role yang diberi akses
                'akses_type' => 'update', // Jenis akses update
                'created_at' => now(), // Timestamp create
                'updated_at' => now(), // Timestamp update
            ]; // Menutup array akses update menu
            $object1[] = [ // Menambahkan akses delete untuk menu
                'menu_id'    => $m->menu_id, // ID menu
                'role_id'    => $idrole, // Role yang diberi akses
                'akses_type' => 'delete', // Jenis akses delete
                'created_at' => now(), // Timestamp create
                'updated_at' => now(), // Timestamp update
            ]; // Menutup array akses delete menu
        } // Menutup foreach menu

        $submenu = SubmenuModel::orderBy('submenu_sort', 'ASC')->get(); // Mengambil semua submenu berdasarkan urutan sort
        foreach ($submenu as $sb) { // Loop setiap submenu
            $object2[] = [ // Menambahkan akses view untuk submenu
                'submenu_id' => $sb->submenu_id, // ID submenu
                'role_id'    => $idrole, // Role yang diberi akses
                'akses_type' => 'view', // Jenis akses view
                'created_at' => now(), // Timestamp create
                'updated_at' => now(), // Timestamp update
            ]; // Menutup array akses view submenu
            $object2[] = [ // Menambahkan akses create untuk submenu
                'submenu_id' => $sb->submenu_id, // ID submenu
                'role_id'    => $idrole, // Role yang diberi akses
                'akses_type' => 'create', // Jenis akses create
                'created_at' => now(), // Timestamp create
                'updated_at' => now(), // Timestamp update
            ]; // Menutup array akses create submenu
            $object2[] = [ // Menambahkan akses update untuk submenu
                'submenu_id' => $sb->submenu_id, // ID submenu
                'role_id'    => $idrole, // Role yang diberi akses
                'akses_type' => 'update', // Jenis akses update
                'created_at' => now(), // Timestamp create
                'updated_at' => now(), // Timestamp update
            ]; // Menutup array akses update submenu
            $object2[] = [ // Menambahkan akses delete untuk submenu
                'submenu_id' => $sb->submenu_id, // ID submenu
                'role_id'    => $idrole, // Role yang diberi akses
                'akses_type' => 'delete', // Jenis akses delete
                'created_at' => now(), // Timestamp create
                'updated_at' => now(), // Timestamp update
            ]; // Menutup array akses delete submenu
        } // Menutup foreach submenu

        for ($i = 1; $i <= 6; $i++) { // Loop untuk othermenu id 1 sampai 6
            $object3[] = [ // Menambahkan akses view untuk each othermenu
                'othermenu_id' => $i, // ID othermenu
                'role_id'      => $idrole, // Role yang diberi akses
                'akses_type'   => 'view', // Jenis akses view
                'created_at'   => now(), // Timestamp create
                'updated_at'   => now(), // Timestamp update
            ]; // Menutup array akses view othermenu
        } // Menutup loop view othermenu
        for ($i = 1; $i <= 6; $i++) { // Loop untuk akses create othermenu
            $object3[] = [ // Menambahkan akses create untuk each othermenu
                'othermenu_id' => $i, // ID othermenu
                'role_id'      => $idrole, // Role yang diberi akses
                'akses_type'   => 'create', // Jenis akses create
                'created_at'   => now(), // Timestamp create
                'updated_at'   => now(), // Timestamp update
            ]; // Menutup array akses create othermenu
        } // Menutup loop create othermenu
        for ($i = 1; $i <= 6; $i++) { // Loop untuk akses update othermenu
            $object3[] = [ // Menambahkan akses update untuk each othermenu
                'othermenu_id' => $i, // ID othermenu
                'role_id'      => $idrole, // Role yang diberi akses
                'akses_type'   => 'update', // Jenis akses update
                'created_at'   => now(), // Timestamp create
                'updated_at'   => now(), // Timestamp update
            ]; // Menutup array akses update othermenu
        } // Menutup loop update othermenu
        for ($i = 1; $i <= 6; $i++) { // Loop untuk akses delete othermenu
            $object3[] = [ // Menambahkan akses delete untuk each othermenu
                'othermenu_id' => $i, // ID othermenu
                'role_id'      => $idrole, // Role yang diberi akses
                'akses_type'   => 'delete', // Jenis akses delete
                'created_at'   => now(), // Timestamp create
                'updated_at'   => now(), // Timestamp update
            ]; // Menutup array akses delete othermenu
        } // Menutup loop delete othermenu

        AksesModel::insert($object1); // Insert batch seluruh akses menu ke database
        AksesModel::insert($object2); // Insert batch seluruh akses submenu ke database
        AksesModel::insert($object3); // Insert batch seluruh akses othermenu ke database

        $data['title'] = 'Akses'; // Menetapkan title untuk redirect

        // redirect to index // Komentar penanda redirect setelah set semua akses
        return redirect(url('admin/akses/' . $idrole))->with($data); // Redirect kembali ke halaman akses role tersebut
    }

    public function unsetAllAkses(int $idrole): RedirectResponse // Method untuk mencabut semua akses dari role tertentu
    {
        AksesModel::where(['role_id' => $idrole])->delete(); // Menghapus semua record akses untuk role tersebut

        $data['title'] = 'Akses'; // Menetapkan title untuk redirect

        // redirect to index // Komentar penanda redirect setelah unset semua akses
        return redirect(url('admin/akses/' . $idrole))->with($data); // Redirect kembali ke halaman akses role tersebut
    }
} // Penutup class AksesController
