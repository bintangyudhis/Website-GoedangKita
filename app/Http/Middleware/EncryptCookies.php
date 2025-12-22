<?php // Tag pembuka PHP untuk file middleware EncryptCookies

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware; // Mengimpor middleware bawaan Laravel untuk enkripsi cookie

class EncryptCookies extends Middleware // Middleware yang bertugas mengenkripsi nilai cookie saat dikirim/diterima
{
    /**
     * The names of the cookies that should not be encrypted.
     * (Daftar nama cookie yang dikecualikan dari proses enkripsi)
     *
     * @var array<int, string> // Tipe data: array berisi string (nama cookie)
     */
    protected $except = [ // Properti untuk menampung daftar cookie yang tidak perlu dienkripsi
        //
    ]; // Saat kosong artinya semua cookie akan dienkripsi oleh middleware ini
} // Penutup class EncryptCookies
