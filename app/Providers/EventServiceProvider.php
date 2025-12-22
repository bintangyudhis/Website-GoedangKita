<?php // Tag pembuka PHP untuk file EventServiceProvider

namespace App\Providers; // Namespace untuk service provider aplikasi

use Illuminate\Auth\Events\Registered; // Event bawaan Laravel yang dipicu saat user berhasil registrasi
use Illuminate\Auth\Listeners\SendEmailVerificationNotification; // Listener untuk mengirim email verifikasi
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider; // Base EventServiceProvider Laravel
use Illuminate\Support\Facades\Event; // Facade Event (disediakan untuk registrasi event secara manual, di file ini belum dipakai)

class EventServiceProvider extends ServiceProvider // Service provider untuk mendaftarkan event dan listener aplikasi
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [ // Mapping event -> listener yang akan dijalankan saat event terjadi
        Registered::class => [ // Saat event Registered (user registrasi)
            SendEmailVerificationNotification::class, // Jalankan listener kirim email verifikasi
        ],
    ]; // Penutup properti listen

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() // Method boot untuk konfigurasi event tambahan saat aplikasi berjalan
    {
        // Saat ini tidak ada konfigurasi event tambahan di sini
    } // Penutup method boot

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents() // Mengatur apakah Laravel akan auto-discover event & listener dari folder tertentu
    {
        return false; // false = tidak auto-discover, hanya menggunakan mapping manual di $listen
    } // Penutup method shouldDiscoverEvents
} // Penutup class EventServiceProvider
