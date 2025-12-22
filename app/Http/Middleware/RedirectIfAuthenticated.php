<?php // Tag pembuka PHP untuk file middleware RedirectIfAuthenticated

namespace App\Http\Middleware; // Namespace middleware aplikasi

use App\Providers\RouteServiceProvider; // Mengimpor RouteServiceProvider untuk konstanta HOME (tujuan redirect setelah login)
use Closure; // Mengimpor Closure untuk parameter $next (pipeline middleware)
use Illuminate\Http\Request; // Mengimpor Request untuk menangani request yang masuk
use Illuminate\Support\Facades\Auth; // Mengimpor Auth facade untuk mengecek status autentikasi user

class RedirectIfAuthenticated // Middleware untuk mencegah user yang sudah login mengakses halaman guest (misalnya login/register)
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next // Closure untuk meneruskan request ke proses berikutnya
     * @param  string|null  ...$guards // Daftar guard yang ingin dicek (bisa kosong, bisa multi guard)
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse // Response hasil middleware (redirect atau lanjut)
     */
    public function handle(Request $request, Closure $next, ...$guards) // Method utama middleware untuk memproses request masuk
    {
        $guards = empty($guards) ? [null] : $guards; // Jika guard tidak dikirim, gunakan guard default (null)

        foreach ($guards as $guard) { // Loop setiap guard yang ingin dicek
            if (Auth::guard($guard)->check()) { // Jika pada guard tersebut user sudah terautentikasi (sudah login)
                return redirect(RouteServiceProvider::HOME); // Redirect ke halaman HOME (biasanya dashboard)
            }
        }

        return $next($request); // Jika belum login, lanjutkan request ke proses berikutnya (misalnya tampilkan halaman login)
    } // Penutup method handle
} // Penutup class RedirectIfAuthenticated
