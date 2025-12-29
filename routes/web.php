<?php

// =======================
// IMPORT CONTROLLERS
// =======================

// Controller untuk modul Barang
use App\Http\Controllers\Admin\BarangController;

// Controller untuk modul Barang Keluar
use App\Http\Controllers\Admin\BarangkeluarController;

// Controller untuk modul Barang Masuk
use App\Http\Controllers\Admin\BarangmasukController;

// Controller untuk modul Customer
use App\Http\Controllers\Admin\CustomerController;

// Controller untuk Dashboard admin
use App\Http\Controllers\Admin\DashboardController;

// Controller untuk Jenis Barang
use App\Http\Controllers\Admin\JenisBarangController;

// Controller laporan Barang Keluar
use App\Http\Controllers\Admin\LapBarangKeluarController;

// Controller laporan Barang Masuk
use App\Http\Controllers\Admin\LapBarangMasukController;

// Controller laporan Stok Barang
use App\Http\Controllers\Admin\LapStokBarangController;

// Controller Login & Logout
use App\Http\Controllers\Admin\LoginController;

// Controller Merk Barang
use App\Http\Controllers\Admin\MerkController;

// Controller Satuan Barang
use App\Http\Controllers\Admin\SatuanController;

// Controller Master Akses
use App\Http\Controllers\Master\AksesController;

// Controller pengaturan tampilan (appearance)
use App\Http\Controllers\Master\AppreanceController;

// Controller Master Menu
use App\Http\Controllers\Master\MenuController;

// Controller Master Role
use App\Http\Controllers\Master\RoleController;

// Controller Master User
use App\Http\Controllers\Master\UserController;

// Facade Route untuk mendefinisikan routing
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| File ini berisi semua route web (menggunakan session, CSRF, dll)
| Route di sini akan otomatis menggunakan middleware "web"
|--------------------------------------------------------------------------
*/

// =======================
// ROUTE LOGIN ADMIN
// =======================

// Group middleware untuk mencegah akses halaman setelah logout (prevent back history)
Route::middleware(['preventBackHistory'])->group(function () {

    // Halaman login admin (GET)
    Route::get('/admin/login', [LoginController::class, 'index'])
        ->middleware('useractive'); // Cek status user aktif

    // Proses login admin (POST)
    Route::post('/admin/proseslogin', [LoginController::class, 'proseslogin'])
        ->middleware('useractive'); // Cek status user aktif

    // Logout admin
    Route::get('/admin/logout', [LoginController::class, 'logout']);
});

// =======================
// ROUTE ADMIN (HARUS LOGIN)
// =======================

// Semua route di dalam group ini wajib login
Route::group(['middleware' => 'userlogin'], function () {

    // =======================
    // PROFILE USER
    // =======================

    // Halaman profile user
    Route::get('/admin/profile/{user}', [UserController::class, 'profile']);

    // Update password user
    Route::post('/admin/updatePassword/{user}', [UserController::class, 'updatePassword']);

    // Update data profile user
    Route::post('/admin/updateProfile/{user}', [UserController::class, 'updateProfile']);

    // Halaman pengaturan tampilan (appearance)
    Route::get('/admin/appreance/', [AppreanceController::class, 'index']);

    // Update pengaturan tampilan
    Route::post('/admin/appreance/{setting}', [AppreanceController::class, 'update']);

    // =======================
    // DASHBOARD
    // =======================

    // Route dashboard dengan pengecekan akses menu dashboard
    Route::middleware(['checkRoleUser:/dashboard,menu'])->group(function () {

        // Route root ke dashboard
        Route::get('/', [DashboardController::class, 'index']);

        // Route /admin ke dashboard
        Route::get('/admin', [DashboardController::class, 'index']);

        // Route dashboard utama
        Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    });

    // =======================
    // JENIS BARANG
    // =======================

    // Route jenis barang dengan akses submenu
    Route::middleware(['checkRoleUser:/jenisbarang,submenu'])->group(function () {

        // Halaman list jenis barang
        Route::get('/admin/jenisbarang', [JenisBarangController::class, 'index']);

        // Endpoint data jenis barang (AJAX/DataTable)
        Route::get('/admin/jenisbarang/show/', [JenisBarangController::class, 'show'])
            ->name('jenisbarang.getjenisbarang');

        // Proses tambah jenis barang
        Route::post('/admin/jenisbarang/proses_tambah/', [JenisBarangController::class, 'proses_tambah'])
            ->name('jenisbarang.store');

        // Proses ubah jenis barang
        Route::post('/admin/jenisbarang/proses_ubah/{jenisbarang}', [JenisBarangController::class, 'proses_ubah']);

        // Proses hapus jenis barang
        Route::post('/admin/jenisbarang/proses_hapus/{jenisbarang}', [JenisBarangController::class, 'proses_hapus']);
    });

    // =======================
    // SATUAN
    // =======================

    Route::middleware(['checkRoleUser:/satuan,submenu'])->group(function () {

        // Route resource CRUD satuan
        Route::resource('/admin/satuan', \App\Http\Controllers\Admin\SatuanController::class);

        // Endpoint data satuan
        Route::get('/admin/satuan/show/', [SatuanController::class, 'show'])
            ->name('satuan.getsatuan');

        // Proses tambah satuan
        Route::post('/admin/satuan/proses_tambah/', [SatuanController::class, 'proses_tambah'])
            ->name('satuan.store');

        // Proses ubah satuan
        Route::post('/admin/satuan/proses_ubah/{satuan}', [SatuanController::class, 'proses_ubah']);

        // Proses hapus satuan
        Route::post('/admin/satuan/proses_hapus/{satuan}', [SatuanController::class, 'proses_hapus']);
    });

    // =======================
    // MERK
    // =======================

    Route::middleware(['checkRoleUser:/merk,submenu'])->group(function () {

        // Route resource CRUD merk
        Route::resource('/admin/merk', \App\Http\Controllers\Admin\MerkController::class);

        // Endpoint data merk
        Route::get('/admin/merk/show/', [MerkController::class, 'show'])
            ->name('merk.getmerk');

        // Proses tambah merk
        Route::post('/admin/merk/proses_tambah/', [MerkController::class, 'proses_tambah'])
            ->name('merk.store');

        // Proses ubah merk
        Route::post('/admin/merk/proses_ubah/{merk}', [MerkController::class, 'proses_ubah']);

        // Proses hapus merk
        Route::post('/admin/merk/proses_hapus/{merk}', [MerkController::class, 'proses_hapus']);
    });

    // =======================
    // BARANG
    // =======================

    Route::middleware(['checkRoleUser:/barang,submenu'])->group(function () {

        // Route resource CRUD barang
        Route::resource('/admin/barang', \App\Http\Controllers\Admin\BarangController::class);

        // Endpoint data barang
        Route::get('/admin/barang/show/', [BarangController::class, 'show'])
            ->name('barang.getbarang');

        // Proses tambah barang
        Route::post('/admin/barang/proses_tambah/', [BarangController::class, 'proses_tambah'])
            ->name('barang.store');

        // Proses ubah barang
        Route::post('/admin/barang/proses_ubah/{barang}', [BarangController::class, 'proses_ubah']);

        // Proses hapus barang
        Route::post('/admin/barang/proses_hapus/{barang}', [BarangController::class, 'proses_hapus']);
    });

    // =======================
    // CUSTOMER
    // =======================

    Route::middleware(['checkRoleUser:/customer,menu'])->group(function () {

        // Route resource CRUD customer
        Route::resource('/admin/customer', \App\Http\Controllers\Admin\CustomerController::class);

        // Endpoint data customer
        Route::get('/admin/customer/show/', [CustomerController::class, 'show'])
            ->name('customer.getcustomer');

        // Proses tambah customer
        Route::post('/admin/customer/proses_tambah/', [CustomerController::class, 'proses_tambah'])
            ->name('customer.store');

        // Proses ubah customer
        Route::post('/admin/customer/proses_ubah/{customer}', [CustomerController::class, 'proses_ubah']);

        // Proses hapus customer
        Route::post('/admin/customer/proses_hapus/{customer}', [CustomerController::class, 'proses_hapus']);
    });

    // =======================
    // BARANG MASUK
    // =======================

    Route::middleware(['checkRoleUser:/barang-masuk,submenu'])->group(function () {

        // Route resource CRUD barang masuk
        Route::resource('/admin/barang-masuk', \App\Http\Controllers\Admin\BarangmasukController::class);

        // Endpoint data barang masuk
        Route::get('/admin/barang-masuk/show/', [BarangmasukController::class, 'show'])
            ->name('barang-masuk.getbarang-masuk');

        // Proses tambah barang masuk
        Route::post('/admin/barang-masuk/proses_tambah/', [BarangmasukController::class, 'proses_tambah'])
            ->name('barang-masuk.store');

        // Proses ubah barang masuk
        Route::post('/admin/barang-masuk/proses_ubah/{barangmasuk}', [BarangmasukController::class, 'proses_ubah']);

        // Proses hapus barang masuk
        Route::post('/admin/barang-masuk/proses_hapus/{barangmasuk}', [BarangmasukController::class, 'proses_hapus']);

        // Helper ambil data barang
        Route::get('/admin/barang/getbarang/{id}', [BarangController::class, 'getbarang']);

        // Helper list barang
        Route::get('/admin/barang/listbarang/{param}', [BarangController::class, 'listbarang']);
    });

    // =======================
    // BARANG KELUAR
    // =======================

    Route::middleware(['checkRoleUser:/lap-barang-masuk,submenu'])->group(function () {

        // Route resource CRUD barang keluar
        Route::resource('/admin/barang-keluar', \App\Http\Controllers\Admin\BarangkeluarController::class);

        // Endpoint data barang keluar
        Route::get('/admin/barang-keluar/show/', [BarangkeluarController::class, 'show'])
            ->name('barang-keluar.getbarang-keluar');

        // Proses tambah barang keluar
        Route::post('/admin/barang-keluar/proses_tambah/', [BarangkeluarController::class, 'proses_tambah'])
            ->name('barang-keluar.store');

        // Proses ubah barang keluar
        Route::post('/admin/barang-keluar/proses_ubah/{barangkeluar}', [BarangkeluarController::class, 'proses_ubah']);

        // Proses hapus barang keluar
        Route::post('/admin/barang-keluar/proses_hapus/{barangkeluar}', [BarangkeluarController::class, 'proses_hapus']);
    });

    // =======================
    // LAPORAN BARANG MASUK
    // =======================

    Route::middleware(['checkRoleUser:/lap-barang-masuk,submenu'])->group(function () {

        // Route resource laporan barang masuk
        Route::resource('/admin/lap-barang-masuk', \App\Http\Controllers\Admin\LapBarangMasukController::class);

        // Cetak laporan
        Route::get('/admin/lapbarangmasuk/print/', [LapBarangMasukController::class, 'print'])
            ->name('lap-bm.print');

        // Export PDF
        Route::get('/admin/lapbarangmasuk/pdf/', [LapBarangMasukController::class, 'pdf'])
            ->name('lap-bm.pdf');

        // Endpoint data laporan
        Route::get('/admin/lap-barang-masuk/show/', [LapBarangMasukController::class, 'show'])
            ->name('lap-bm.getlap-bm');
    });

    // =======================
    // LAPORAN BARANG KELUAR
    // =======================

    Route::middleware(['checkRoleUser:/lap-barang-keluar,submenu'])->group(function () {

        // Route resource laporan barang keluar
        Route::resource('/admin/lap-barang-keluar', \App\Http\Controllers\Admin\LapBarangKeluarController::class);

        // Cetak laporan
        Route::get('/admin/lapbarangkeluar/print/', [LapBarangKeluarController::class, 'print'])
            ->name('lap-bk.print');

        // Export PDF
        Route::get('/admin/lapbarangkeluar/pdf/', [LapBarangKeluarController::class, 'pdf'])
            ->name('lap-bk.pdf');

        // Endpoint data laporan
        Route::get('/admin/lap-barang-keluar/show/', [LapBarangKeluarController::class, 'show'])
            ->name('lap-bk.getlap-bk');
    });

    // =======================
    // LAPORAN STOK BARANG
    // =======================

    Route::middleware(['checkRoleUser:/lap-stok-barang,submenu'])->group(function () {

        // Route resource laporan stok
        Route::resource('/admin/lap-stok-barang', \App\Http\Controllers\Admin\LapStokBarangController::class);

        // Cetak laporan
        Route::get('/admin/lapstokbarang/print/', [LapStokBarangController::class, 'print'])
            ->name('lap-sb.print');

        // Export PDF
        Route::get('/admin/lapstokbarang/pdf/', [LapStokBarangController::class, 'pdf'])
            ->name('lap-sb.pdf');

        // Endpoint data laporan
        Route::get('/admin/lap-stok-barang/show/', [LapStokBarangController::class, 'show'])
            ->name('lap-sb.getlap-sb');
    });

    // =======================
    // MASTER / OTHER MENU
    // =======================

    Route::middleware(['checkRoleUser:1,othermenu'])->group(function () {

        // Menu
        Route::middleware(['checkRoleUser:2,othermenu'])->group(function () {
            Route::resource('/admin/menu', \App\Http\Controllers\Master\MenuController::class);
            Route::post('/admin/menu/hapus', [MenuController::class, 'hapus']);
            Route::get('/admin/menu/sortup/{sort}', [MenuController::class, 'sortup']);
            Route::get('/admin/menu/sortdown/{sort}', [MenuController::class, 'sortdown']);
        });

        // Role
        Route::middleware(['checkRoleUser:3,othermenu'])->group(function () {
            Route::resource('/admin/role', \App\Http\Controllers\Master\RoleController::class);
            Route::get('/admin/role/show/', [RoleController::class, 'show'])->name('role.getrole');
            Route::post('/admin/role/hapus', [RoleController::class, 'hapus']);
        });

        // User
        Route::middleware(['checkRoleUser:4,othermenu'])->group(function () {
            Route::resource('/admin/user', \App\Http\Controllers\Master\UserController::class);
            Route::get('/admin/user/show/', [UserController::class, 'show'])->name('user.getuser');
            Route::post('/admin/user/hapus', [UserController::class, 'hapus']);
        });

        // Akses
        Route::middleware(['checkRoleUser:5,othermenu'])->group(function () {
            Route::get('/admin/akses/{role}', [AksesController::class, 'index']);
            Route::get('/admin/akses/addAkses/{idmenu}/{idrole}/{type}/{akses}', [AksesController::class, 'addAkses']);
            Route::get('/admin/akses/removeAkses/{idmenu}/{idrole}/{type}/{akses}', [AksesController::class, 'removeAkses']);
            Route::get('/admin/akses/setAll/{role}', [AksesController::class, 'setAllAkses']);
            Route::get('/admin/akses/unsetAll/{role}', [AksesController::class, 'unsetAllAkses']);
        });

        // Web
        Route::middleware(['checkRoleUser:6,othermenu'])->group(function () {
            Route::resource('/admin/web', \App\Http\Controllers\Master\WebController::class);
        });
    });
});
