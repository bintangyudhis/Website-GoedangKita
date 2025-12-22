<?php // Tag pembuka PHP untuk file BroadcastServiceProvider

namespace App\Providers; // Namespace untuk service provider aplikasi

use Illuminate\Support\Facades\Broadcast; // Facade Broadcast untuk mendaftarkan route broadcasting
use Illuminate\Support\ServiceProvider; // Base class ServiceProvider Laravel

class BroadcastServiceProvider extends ServiceProvider // Service provider untuk fitur broadcasting (event real-time)
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() // Method yang dijalankan saat aplikasi booting untuk konfigurasi broadcasting
    {
        Broadcast::routes(); // Mendaftarkan route broadcasting (biasanya /broadcasting/auth untuk private channel)

        require base_path('routes/channels.php'); // Memuat file definisi channel broadcasting (authorization channel)
    } // Penutup method boot
} // Penutup class BroadcastServiceProvider
