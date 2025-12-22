<?php // Tag pembuka PHP untuk file Http Kernel (pengaturan middleware global, grup, dan route)

namespace App\Http; // Namespace untuk komponen HTTP aplikasi (Kernel, request, response, dll)

use Illuminate\Foundation\Http\Kernel as HttpKernel; // Mengimpor Kernel bawaan Laravel sebagai parent class

class Kernel extends HttpKernel // Kernel aplikasi: tempat mendaftarkan seluruh middleware yang akan dijalankan
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     * (Middleware global: selalu berjalan untuk setiap request ke aplikasi)
     *
     * @var array<int, class-string|string> // Array berisi class middleware atau string middleware
     */
    protected $middleware = [ // Daftar middleware global (selalu aktif)
        // \App\Http\Middleware\TrustHosts::class, // (Opsional) Membatasi host yang dipercaya (saat ini dinonaktifkan)
        \App\Http\Middleware\TrustProxies::class, // Mengatur proxy/load balancer yang dipercaya dan header X-Forwarded-*
        \Illuminate\Http\Middleware\HandleCors::class, // Menangani CORS (Cross-Origin Resource Sharing)
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class, // Memblokir request saat aplikasi maintenance
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // Memastikan ukuran POST tidak melebihi batas server
        \App\Http\Middleware\TrimStrings::class, // Menghapus spasi depan/belakang input string (kecuali field tertentu)
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // Mengubah string kosong "" menjadi null
    ]; // Penutup middleware global

    /**
     * The application's route middleware groups.
     * (Grup middleware: kumpulan middleware yang bisa dipakai untuk route web/api)
     *
     * @var array<string, array<int, class-string|string>> // Key grup -> list middleware
     */
    protected $middlewareGroups = [ // Daftar grup middleware
        'web' => [ // Grup middleware untuk route web (menggunakan session, cookies, CSRF, view)
            \App\Http\Middleware\EncryptCookies::class, // Mengenkripsi cookie agar aman
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // Menambahkan cookie yang diantrikan ke response
            \Illuminate\Session\Middleware\StartSession::class, // Memulai session (akses Session::)
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Membagikan error validasi dari session ke view
            \App\Http\Middleware\VerifyCsrfToken::class, // Memverifikasi token CSRF untuk request stateful (POST/PUT/DELETE)
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Mengaktifkan route model binding (mis. {user} -> UserModel)
        ], // Penutup grup web

        'api' => [ // Grup middleware untuk route API (umumnya stateless)
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // (Opsional) Untuk Sanctum SPA (dinonaktifkan)
            'throttle:api', // Rate limiting untuk API (mencegah spam request)
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Route model binding untuk API juga
        ], // Penutup grup api
    ]; // Penutup middlewareGroups

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     * (Middleware route: bisa dipakai per-route atau dipanggil lewat alias)
     *
     * @var array<string, class-string|string> // Alias => class middleware
     */
    protected $routeMiddleware = [ // Daftar alias middleware yang dapat digunakan pada routes
        'auth' => \App\Http\Middleware\Authenticate::class, // Middleware auth bawaan (redirect ke login jika belum login)
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // Basic Auth (username/password via header)
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class, // Menjaga session auth tetap valid
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class, // Mengatur header cache pada response
        'can' => \Illuminate\Auth\Middleware\Authorize::class, // Authorization policy/gate (cek permission)
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // Jika sudah login, redirect dari halaman guest (login/register)
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // Meminta konfirmasi password untuk aksi sensitif
        'signed' => \App\Http\Middleware\ValidateSignature::class, // Memvalidasi signed URL
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // Rate limit request berdasarkan aturan throttle
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // Memastikan email sudah diverifikasi
        'userlogin' => \App\Http\Middleware\CheckUserLogin::class, // Custom: redirect ke /admin/login jika session user kosong
        'useractive' => \App\Http\Middleware\CheckUserActive::class, // Custom: redirect ke dashboard jika sudah login (hindari akses login page)
        'preventBackHistory' => \App\Http\Middleware\PreventBackHistory::class, // Custom: menambahkan header anti-cache agar tombol back tidak menampilkan halaman lama
        'checkRoleUser' => \App\Http\Middleware\CheckRoleUser::class, // Custom: cek hak akses role terhadap menu/submenu/othermenu
    ]; // Penutup routeMiddleware
} // Penutup class Kernel
