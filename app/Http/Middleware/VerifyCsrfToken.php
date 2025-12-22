<?php // Tag pembuka PHP untuk file middleware VerifyCsrfToken

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware; // Mengimpor middleware bawaan Laravel untuk proteksi CSRF

class VerifyCsrfToken extends Middleware // Middleware untuk memverifikasi token CSRF pada request (biasanya POST/PUT/PATCH/DELETE)
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * (Daftar URI yang dikecualikan dari pengecekan CSRF, biasanya untuk webhook atau API tertentu)
     *
     * @var array<int, string> // Tipe data: array berisi string (URI/path)
     */
    protected $except = [ // Properti untuk menampung URI yang tidak wajib membawa token CSRF
        //
    ]; // Jika kosong, semua route web akan wajib validasi CSRF sesuai aturan Laravel
} // Penutup class VerifyCsrfToken
