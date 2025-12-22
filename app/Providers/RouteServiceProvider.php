<?php // Tag pembuka PHP untuk file RouteServiceProvider

namespace App\Providers; // Namespace untuk service provider aplikasi

use Illuminate\Cache\RateLimiting\Limit; // Class untuk mendefinisikan aturan rate limiting
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider; // Base RouteServiceProvider Laravel
use Illuminate\Http\Request; // Class Request untuk membaca data request (user/ip)
use Illuminate\Support\Facades\RateLimiter; // Facade RateLimiter untuk mendaftarkan limiter
use Illuminate\Support\Facades\Route; // Facade Route untuk mendaftarkan file route

class RouteServiceProvider extends ServiceProvider // Service provider untuk konfigurasi routing aplikasi
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home'; // Konstanta path default tujuan redirect setelah login (dipakai oleh auth bawaan Laravel)

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot() // Method boot: tempat registrasi route dan konfigurasi rate limiting
    {
        $this->configureRateLimiting(); // Memanggil konfigurasi limiter (batas request per menit)

        $this->routes(function () { // Mendefinisikan grup route untuk API dan Web
            Route::middleware('api') // Route API menggunakan middleware group "api"
                ->prefix('api') // Semua endpoint API diawali prefix /api
                ->group(base_path('routes/api.php')); // Memuat definisi route dari routes/api.php

            Route::middleware('web') // Route web (browser) menggunakan middleware group "web"
                ->group(base_path('routes/web.php')); // Memuat definisi route dari routes/web.php
        }); // Penutup routes closure
    } // Penutup method boot

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting() // Method untuk mendefinisikan aturan rate limiting
    {
        RateLimiter::for('api', function (Request $request) { // Mendaftarkan limiter bernama "api"
            // Batas 60 request/menit per user yang login; jika tidak login, gunakan IP sebagai identitas pembatas
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        }); // Penutup definisi limiter
    } // Penutup method configureRateLimiting
} // Penutup class RouteServiceProvider
