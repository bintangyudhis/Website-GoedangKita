<?php // Tag pembuka PHP untuk file AuthServiceProvider

namespace App\Providers; // Namespace untuk service provider aplikasi

// use Illuminate\Support\Facades\Gate; // (Opsional) Facade Gate untuk mendefinisikan aturan otorisasi manual, saat ini tidak dipakai
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider; // Base AuthServiceProvider Laravel

class AuthServiceProvider extends ServiceProvider // Service provider untuk konfigurasi policy dan otorisasi (authorization)
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [ // Mapping model ke policy (aturan otorisasi berbasis class)
        // 'App\Models\Model' => 'App\Policies\ModelPolicy', // Contoh: hubungkan Model tertentu ke Policy-nya
    ]; // Penutup properti policies

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() // Method yang dijalankan saat aplikasi booting untuk mendaftarkan policy dan aturan auth
    {
        $this->registerPolicies(); // Mendaftarkan semua policy yang ada di properti $policies

        // Tempat untuk menambahkan definisi Gate atau rule authorization lain jika diperlukan
    } // Penutup method boot
} // Penutup class AuthServiceProvider
