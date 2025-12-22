<?php // Tag pembuka PHP untuk file middleware PreventBackHistory

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Closure; // Mengimpor Closure untuk parameter $next (pipeline middleware)
use Illuminate\Http\Request; // Mengimpor Request untuk menangani request yang masuk

class PreventBackHistory // Middleware untuk mencegah halaman admin tersimpan di cache (agar tombol Back tidak menampilkan halaman lama setelah logout)
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next // Closure untuk meneruskan request ke proses berikutnya
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse // Response yang sudah ditambahkan header cache-control
     */
    public function handle(Request $request, Closure $next) // Method utama middleware untuk memproses request masuk
    {
        $response = $next($request); // Menjalankan request ke middleware berikutnya / controller dan menyimpan responsenya

        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate') // Header untuk melarang browser menyimpan cache halaman
            ->header('Pragma', 'no-cache') // Header tambahan (kompatibilitas HTTP lama) untuk melarang caching
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT'); // Memaksa cache dianggap kadaluarsa sejak tanggal lama
    } // Penutup method handle
} // Penutup class PreventBackHistory
