<?php
// Tag pembuka PHP

use App\Http\Controllers\Admin\BarangController;
// Import controller untuk modul Barang (admin)

use App\Http\Controllers\Admin\BarangkeluarController;
// Import controller untuk modul Barang Keluar (admin)

use App\Http\Controllers\Admin\BarangmasukController;
// Import controller untuk modul Barang Masuk (admin)

use App\Http\Controllers\Admin\CustomerController;
// Import controller untuk modul Customer (admin)

use App\Http\Controllers\Admin\DashboardController;
// Import controller untuk Dashboard (admin)

use App\Http\Controllers\Admin\JenisBarangController;
// Import controller untuk modul Jenis Barang (admin)

use App\Http\Controllers\Admin\LapBarangKeluarController;
// Import controller untuk modul Laporan Barang Keluar (admin)

use App\Http\Controllers\Admin\LapBarangMasukController;
// Import controller untuk modul Laporan Barang Masuk (admin)

use App\Http\Controllers\Admin\LapStokBarangController;
// Import controller untuk modul Laporan Stok Barang (admin)

use App\Http\Controllers\Admin\LoginController;
// Import controller untuk login/logout admin

use App\Http\Controllers\Admin\MerkController;
// Import controller untuk modul Merk (admin)

use App\Http\Controllers\Admin\SatuanController;
// Import controller untuk modul Satuan (admin)

use App\Http\Controllers\Master\AksesController;
// Import controller untuk pengaturan akses per role (master)

use App\Http\Controllers\Master\AppreanceController;
// Import controller untuk pengaturan tampilan/appearance (master)

use App\Http\Controllers\Master\MenuController;
// Import controller untuk manajemen menu (master)

use App\Http\Controllers\Master\RoleController;
// Import controller untuk manajemen role (master)

use App\Http\Controllers\Master\UserController;
// Import controller untuk manajemen user (master)

use Illuminate\Support\Facades\Route;
// Import facade Route untuk mendefinisikan web routes

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Komentar bawaan Laravel: file ini untuk definisi route web (session, CSRF, cookie, dll)

// login admin
// Penanda section route untuk fitur login admin

Route::middleware(['preventBackHistory'])->group(function () {
    // Grup route yang memakai middleware preventBackHistory
    // Biasanya untuk mencegah halaman auth tampil saat tombol back browser setelah logout

    Route::get('/admin/login', [LoginController::class, 'index'])->middleware('useractive');
    // GET /admin/login -> tampilkan halaman login
    // tambahan middleware useractive (cek user aktif / status tertentu sebelum login)

    Route::post('/admin/proseslogin', [LoginController::class, 'proseslogin'])->middleware('useractive');
    // POST /admin/proseslogin -> proses autentikasi login
    // middleware useractive juga diterapkan

    Route::get('/admin/logout', [LoginController::class, 'logout']);
    // GET /admin/logout -> proses logout (hapus session/token)
});
// Penutup grup preventBackHistory

// admin
// Penanda section route admin (harus sudah login)

Route::group(['middleware' => 'userlogin'], function () {
    // Grup besar route admin yang dilindungi middleware userlogin
    // userlogin biasanya mengecek apakah session user/admin sudah login

    // Profile
    // Sub-bagian untuk profile dan pengaturan user

    Route::get('/admin/profile/{user}', [UserController::class, 'profile']);
    // GET /admin/profile/{user} -> halaman profile berdasarkan parameter {user}

    Route::post('/admin/updatePassword/{user}', [UserController::class, 'updatePassword']);
    // POST /admin/updatePassword/{user} -> proses update password user tertentu

    Route::post('/admin/updateProfile/{user}', [UserController::class, 'updateProfile']);
    // POST /admin/updateProfile/{user} -> proses update data profil user tertentu

    Route::get('/admin/appreance/', [AppreanceController::class, 'index']);
    // GET /admin/appreance -> halaman pengaturan tampilan (appearance)

    Route::post('/admin/appreance/{setting}', [AppreanceController::class, 'update']);
    // POST /admin/appreance/{setting} -> update setting appearance tertentu

    Route::middleware(['checkRoleUser:/dashboard,menu'])->group(function () {
        // Grup route dashboard yang dibatasi role/akses
        // checkRoleUser:/dashboard,menu kemungkinan mengecek izin akses menu "/dashboard" tipe "menu"

        Route::get('/', [DashboardController::class, 'index']);
        // GET / -> redirect/halaman dashboard

        Route::get('/admin', [DashboardController::class, 'index']);
        // GET /admin -> dashboard

        Route::get('/admin/dashboard', [DashboardController::class, 'index']);
        // GET /admin/dashboard -> dashboard
    });
    // Penutup grup akses dashboard

    Route::middleware(['checkRoleUser:/jenisbarang,submenu'])->group(function () {
        // Grup route Jenis Barang dengan pembatas akses submenu "/jenisbarang"

        // Jenis Barang
        Route::get('/admin/jenisbarang', [JenisBarangController::class, 'index']);
        // Halaman list Jenis Barang

        Route::get('/admin/jenisbarang/show/', [JenisBarangController::class, 'show'])->name('jenisbarang.getjenisbarang');
        // Endpoint data (umumnya untuk DataTables/AJAX) + diberi nama route

        Route::post('/admin/jenisbarang/proses_tambah/', [JenisBarangController::class, 'proses_tambah'])->name('jenisbarang.store');
        // Proses tambah Jenis Barang + route name untuk pemanggilan via route()

        Route::post('/admin/jenisbarang/proses_ubah/{jenisbarang}', [JenisBarangController::class, 'proses_ubah']);
        // Proses ubah Jenis Barang berdasarkan parameter {jenisbarang}

        Route::post('/admin/jenisbarang/proses_hapus/{jenisbarang}', [JenisBarangController::class, 'proses_hapus']);
        // Proses hapus Jenis Barang berdasarkan parameter {jenisbarang}
    });
    // Penutup grup Jenis Barang

    Route::middleware(['checkRoleUser:/satuan,submenu'])->group(function () {
        // Grup route Satuan dengan pembatas akses submenu "/satuan"

        // Satuan
        Route::resource('/admin/satuan', \App\Http\Controllers\Admin\SatuanController::class);
        // Membuat route resource standar (index, create, store, show, edit, update, destroy) untuk satuan

        Route::get('/admin/satuan/show/', [SatuanController::class, 'show'])->name('satuan.getsatuan');
        // Endpoint show khusus (biasanya untuk datatables)

        Route::post('/admin/satuan/proses_tambah/', [SatuanController::class, 'proses_tambah'])->name('satuan.store');
        // Proses tambah (custom), diberi nama 'satuan.store' (perlu hati-hati: bisa overlap dengan resource store)

        Route::post('/admin/satuan/proses_ubah/{satuan}', [SatuanController::class, 'proses_ubah']);
        // Proses ubah satuan

        Route::post('/admin/satuan/proses_hapus/{satuan}', [SatuanController::class, 'proses_hapus']);
        // Proses hapus satuan
    });
    // Penutup grup Satuan

    Route::middleware(['checkRoleUser:/merk,submenu'])->group(function () {
        // Grup route Merk dengan pembatas akses submenu "/merk"

        // Merk
        Route::resource('/admin/merk', \App\Http\Controllers\Admin\MerkController::class);
        // Route resource untuk merk

        Route::get('/admin/merk/show/', [MerkController::class, 'show'])->name('merk.getmerk');
        // Endpoint data merk untuk AJAX

        Route::post('/admin/merk/proses_tambah/', [MerkController::class, 'proses_tambah'])->name('merk.store');
        // Proses tambah merk (custom) + name (bisa overlap store resource)

        Route::post('/admin/merk/proses_ubah/{merk}', [MerkController::class, 'proses_ubah']);
        // Proses ubah merk

        Route::post('/admin/merk/proses_hapus/{merk}', [MerkController::class, 'proses_hapus']);
        // Proses hapus merk
    });
    // Penutup grup Merk

    Route::middleware(['checkRoleUser:/barang,submenu'])->group(function () {
        // Grup route Barang dengan pembatas akses submenu "/barang"

        // Barang
        Route::resource('/admin/barang', \App\Http\Controllers\Admin\BarangController::class);
        // Route resource barang

        Route::get('/admin/barang/show/', [BarangController::class, 'show'])->name('barang.getbarang');
        // Endpoint data barang

        Route::post('/admin/barang/proses_tambah/', [BarangController::class, 'proses_tambah'])->name('barang.store');
        // Proses tambah barang (custom) + name (potensi overlap dengan store bawaan resource)

        Route::post('/admin/barang/proses_ubah/{barang}', [BarangController::class, 'proses_ubah']);
        // Proses ubah barang

        Route::post('/admin/barang/proses_hapus/{barang}', [BarangController::class, 'proses_hapus']);
        // Proses hapus barang
    });
    // Penutup grup Barang

    Route::middleware(['checkRoleUser:/customer,menu'])->group(function () {
        // Grup route Customer dengan pembatas akses menu "/customer"

        // Customer
        Route::resource('/admin/customer', \App\Http\Controllers\Admin\CustomerController::class);
        // Route resource customer

        Route::get('/admin/customer/show/', [CustomerController::class, 'show'])->name('customer.getcustomer');
        // Endpoint data customer

        Route::post('/admin/customer/proses_tambah/', [CustomerController::class, 'proses_tambah'])->name('customer.store');
        // Proses tambah customer (custom) + name (potensi overlap store resource)

        Route::post('/admin/customer/proses_ubah/{customer}', [CustomerController::class, 'proses_ubah']);
        // Proses ubah customer

        Route::post('/admin/customer/proses_hapus/{customer}', [CustomerController::class, 'proses_hapus']);
        // Proses hapus customer
    });
    // Penutup grup Customer

    Route::middleware(['checkRoleUser:/barang-masuk,submenu'])->group(function () {
        // Grup route Barang Masuk dengan pembatas akses submenu "/barang-masuk"

        // Barang Masuk
        Route::resource('/admin/barang-masuk', \App\Http\Controllers\Admin\BarangmasukController::class);
        // Route resource barang masuk

        Route::get('/admin/barang-masuk/show/', [BarangmasukController::class, 'show'])->name('barang-masuk.getbarang-masuk');
        // Endpoint data barang masuk (perhatikan name memakai dash)

        Route::post('/admin/barang-masuk/proses_tambah/', [BarangmasukController::class, 'proses_tambah'])->name('barang-masuk.store');
        // Proses tambah barang masuk (custom) + name (potensi overlap store resource)

        Route::post('/admin/barang-masuk/proses_ubah/{barangmasuk}', [BarangmasukController::class, 'proses_ubah']);
        // Proses ubah barang masuk

        Route::post('/admin/barang-masuk/proses_hapus/{barangmasuk}', [BarangmasukController::class, 'proses_hapus']);
        // Proses hapus barang masuk

        Route::get('/admin/barang/getbarang/{id}', [BarangController::class, 'getbarang']);
        // Endpoint helper untuk ambil detail barang berdasarkan id (dipakai di form barang masuk)

        Route::get('/admin/barang/listbarang/{param}', [BarangController::class, 'listbarang']);
        // Endpoint helper untuk list barang (kemungkinan untuk autocomplete/filter) berdasarkan param
    });
    // Penutup grup Barang Masuk

    Route::middleware(['checkRoleUser:/lap-barang-masuk,submenu'])->group(function () {
        // Grup route barang keluar (CATATAN: middleware-nya tertulis lap-barang-masuk, tapi isinya barang-keluar)
        // Ini bisa jadi typo penamaan, tapi aku tidak mengubah kode (hanya catatan)

        // Barang Keluar
        Route::resource('/admin/barang-keluar', \App\Http\Controllers\Admin\BarangkeluarController::class);
        // Route resource barang keluar

        Route::get('/admin/barang-keluar/show/', [BarangkeluarController::class, 'show'])->name('barang-keluar.getbarang-keluar');
        // Endpoint data barang keluar

        Route::post('/admin/barang-keluar/proses_tambah/', [BarangkeluarController::class, 'proses_tambah'])->name('barang-keluar.store');
        // Proses tambah barang keluar (custom) + name (potensi overlap store resource)

        Route::post('/admin/barang-keluar/proses_ubah/{barangkeluar}', [BarangkeluarController::class, 'proses_ubah']);
        // Proses ubah barang keluar

        Route::post('/admin/barang-keluar/proses_hapus/{barangkeluar}', [BarangkeluarController::class, 'proses_hapus']);
        // Proses hapus barang keluar
    });
    // Penutup grup Barang Keluar

    Route::middleware(['checkRoleUser:/lap-barang-masuk,submenu'])->group(function () {
        // Grup route Laporan Barang Masuk dengan pembatas akses submenu "/lap-barang-masuk"

        // Laporan Barang Masuk
        Route::resource('/admin/lap-barang-masuk', \App\Http\Controllers\Admin\LapBarangMasukController::class);
        // Route resource laporan barang masuk

        Route::get('/admin/lapbarangmasuk/print/', [LapBarangMasukController::class, 'print'])->name('lap-bm.print');
        // Endpoint cetak (print view) laporan barang masuk

        Route::get('/admin/lapbarangmasuk/pdf/', [LapBarangMasukController::class, 'pdf'])->name('lap-bm.pdf');
        // Endpoint generate PDF laporan barang masuk

        Route::get('/admin/lap-barang-masuk/show/', [LapBarangMasukController::class, 'show'])->name('lap-bm.getlap-bm');
        // Endpoint data laporan barang masuk (AJAX/datatable)
    });
    // Penutup grup Laporan Barang Masuk

    Route::middleware(['checkRoleUser:/lap-barang-keluar,submenu'])->group(function () {
        // Grup route Laporan Barang Keluar dengan pembatas akses submenu "/lap-barang-keluar"

        // Laporan Barang Keluar
        Route::resource('/admin/lap-barang-keluar', \App\Http\Controllers\Admin\LapBarangKeluarController::class);
        // Route resource laporan barang keluar

        Route::get('/admin/lapbarangkeluar/print/', [LapBarangKeluarController::class, 'print'])->name('lap-bk.print');
        // Endpoint print laporan barang keluar

        Route::get('/admin/lapbarangkeluar/pdf/', [LapBarangKeluarController::class, 'pdf'])->name('lap-bk.pdf');
        // Endpoint PDF laporan barang keluar

        Route::get('/admin/lap-barang-keluar/show/', [LapBarangKeluarController::class, 'show'])->name('lap-bk.getlap-bk');
        // Endpoint data laporan barang keluar
    });
    // Penutup grup Laporan Barang Keluar

    Route::middleware(['checkRoleUser:/lap-stok-barang,submenu'])->group(function () {
        // Grup route Laporan Stok Barang dengan pembatas akses submenu "/lap-stok-barang"

        // Laporan Stok Barang
        Route::resource('/admin/lap-stok-barang', \App\Http\Controllers\Admin\LapStokBarangController::class);
        // Route resource laporan stok

        Route::get('/admin/lapstokbarang/print/', [LapStokBarangController::class, 'print'])->name('lap-sb.print');
        // Endpoint print laporan stok

        Route::get('/admin/lapstokbarang/pdf/', [LapStokBarangController::class, 'pdf'])->name('lap-sb.pdf');
        // Endpoint PDF laporan stok

        Route::get('/admin/lap-stok-barang/show/', [LapStokBarangController::class, 'show'])->name('lap-sb.getlap-sb');
        // Endpoint data laporan stok
    });
    // Penutup grup Laporan Stok

    Route::middleware(['checkRoleUser:1,othermenu'])->group(function () {
        // Grup "othermenu" level 1 (kemungkinan master/admin utama)
        // checkRoleUser:1,othermenu kemungkinan mengecek akses berbasis id menu/role tertentu

        Route::middleware(['checkRoleUser:2,othermenu'])->group(function () {
            // Sub-grup othermenu level 2: Menu management

            // Menu
            Route::resource('/admin/menu', \App\Http\Controllers\Master\MenuController::class);
            // Route resource untuk menu

            Route::post('/admin/menu/hapus', [MenuController::class, 'hapus']);
            // Endpoint hapus menu via POST (custom), bukan destroy resource default

            Route::get('/admin/menu/sortup/{sort}', [MenuController::class, 'sortup']);
            // Endpoint untuk naikkan urutan menu berdasarkan parameter sort

            Route::get('/admin/menu/sortdown/{sort}', [MenuController::class, 'sortdown']);
            // Endpoint untuk turunkan urutan menu berdasarkan parameter sort
        });
        // Penutup sub-grup Menu

        Route::middleware(['checkRoleUser:3,othermenu'])->group(function () {
            // Sub-grup othermenu level 3: Role management

            // Role
            Route::resource('/admin/role', \App\Http\Controllers\Master\RoleController::class);
            // Route resource role

            Route::get('/admin/role/show/', [RoleController::class, 'show'])->name('role.getrole');
            // Endpoint data role (AJAX/datatable)

            Route::post('/admin/role/hapus', [RoleController::class, 'hapus']);
            // Endpoint hapus role via POST (custom)
        });
        // Penutup sub-grup Role

        Route::middleware(['checkRoleUser:4,othermenu'])->group(function () {
            // Sub-grup othermenu level 4: User list management

            // List User
            Route::resource('/admin/user', \App\Http\Controllers\Master\UserController::class);
            // Route resource user (CRUD)

            Route::get('/admin/user/show/', [UserController::class, 'show'])->name('user.getuser');
            // Endpoint data user untuk datatable

            Route::post('/admin/user/hapus', [UserController::class, 'hapus']);
            // Endpoint hapus user via POST (custom)
        });
        // Penutup sub-grup User

        Route::middleware(['checkRoleUser:5,othermenu'])->group(function () {
            // Sub-grup othermenu level 5: Akses (permission) management

            // Akses
            Route::get('/admin/akses/{role}', [AksesController::class, 'index']);
            // Halaman pengaturan akses untuk role tertentu

            Route::get('/admin/akses/addAkses/{idmenu}/{idrole}/{type}/{akses}', [AksesController::class, 'addAkses']);
            // Menambah hak akses: parameter idmenu, idrole, type, akses (pakai GET)

            Route::get('/admin/akses/removeAkses/{idmenu}/{idrole}/{type}/{akses}', [AksesController::class, 'removeAkses']);
            // Menghapus hak akses

            Route::get('/admin/akses/setAll/{role}', [AksesController::class, 'setAllAkses']);
            // Memberi semua akses untuk role tertentu

            Route::get('/admin/akses/unsetAll/{role}', [AksesController::class, 'unsetAllAkses']);
            // Mencabut semua akses untuk role tertentu
        });
        // Penutup sub-grup Akses

        Route::middleware(['checkRoleUser:6,othermenu'])->group(function () {
            // Sub-grup othermenu level 6: Web setting management

            // Web
            Route::resource('/admin/web', \App\Http\Controllers\Master\WebController::class);
            // Route resource untuk pengaturan website (logo, nama, deskripsi, dsb)
        });
        // Penutup sub-grup Web
    });
    // Penutup grup othermenu
});
// Penutup grup admin userlogin
