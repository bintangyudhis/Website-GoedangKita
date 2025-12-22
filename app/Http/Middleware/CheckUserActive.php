<?php // Tag pembuka PHP untuk file middleware CheckUserActive

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Closure; // Mengimpor Closure untuk parameter $next (pipeline middleware)
use Illuminate\Http\Request; // Mengimpor Request untuk menangani request yang masuk
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengecek apakah user sudah tersimpan di session (login)
use Symfony\Component\HttpFoundation\Response; // Mengimpor Response untuk type hint return dari middleware

class CheckUserActive // Middleware untuk mengecek apakah user sudah login (aktif) di session
{
    /**
     * @param  \Illuminate\Http\Request  $request // Request yang sedang diproses
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next // Closure untuk meneruskan request ke proses berikutnya
     */
    public function handle(Request $request, Closure $next): Response // Method utama middleware untuk memproses request masuk
    {
        if (! Session::get('user') == '') { // Jika session 'user' tidak kosong (user sudah login), maka...
            return redirect('/admin/dashboard'); // Redirect user ke dashboard (supaya tidak bisa akses halaman login lagi)
        }

        return $next($request); // Jika belum login, lanjutkan request ke proses berikutnya (misalnya halaman login)
    }
} // Penutup class CheckUserActive
