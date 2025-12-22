<?php // Tag pembuka PHP untuk file AppServiceProvider

namespace App\Providers; // Namespace untuk service provider aplikasi

use Illuminate\Support\Facades\URL; // Facade URL untuk memanipulasi scheme/host URL
use Illuminate\Support\ServiceProvider; // Base class ServiceProvider Laravel

class AppServiceProvider extends ServiceProvider // Service provider bawaan Laravel untuk service umum aplikasi
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() // Method untuk mendaftarkan service/binding ke container (biasanya untuk singleton, config, dll)
    {
        // Tidak ada service tambahan yang didaftarkan di sini
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() // Method yang dijalankan saat aplikasi booting (tempat konfigurasi global/initialization)
    {
        // Jika environment bukan "local" (misalnya production/staging), paksa semua URL menggunakan https
        if (env(key: 'APP_ENV') !== 'local') { // Mengecek nilai APP_ENV dari file .env
            URL::forceScheme(scheme: 'https'); // Memaksa scheme URL menjadi https untuk semua generated URL
        } // Penutup kondisi if
    } // Penutup method boot
} // Penutup class AppServiceProvider
