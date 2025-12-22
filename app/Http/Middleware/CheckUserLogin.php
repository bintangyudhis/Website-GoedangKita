<?php // Tag pembuka PHP untuk file middleware CheckUserLogin

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Closure; // Mengimpor Closure untuk parameter $next (pipeline middleware)
use Illuminate\Http\Request; // Mengimpor Request untuk menangani request yang masuk
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengecek apakah user sudah login (tersimpan di session)
use Symfony\Component\HttpFoundation\Response; // Mengimpor Response untuk type hint return dari middleware

class CheckUserLogin // Middleware untuk memastikan user sudah login sebelum mengakses halaman admin tertentu
{
    /**
     * @param  \Illuminate\Http\Request  $request // Request yang sedang diproses
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next // Closure untuk meneruskan request ke proses berikutnya
     */
    public function handle(Request $request, Closure $next): Response // Method utama middleware untuk memproses request masuk
    {
        if (Session::get('user') == '') { // Jika session 'user' kosong (artinya belum login)
            return redirect('/admin/login'); // Redirect ke halaman login admin
        }

        return $next($request); // Jika sudah login, lanjutkan request ke proses berikutnya (controller/middleware lain)
    }
} // Penutup class CheckUserLogin
