<?php // Tag pembuka PHP untuk file middleware Authenticate

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Illuminate\Auth\Middleware\Authenticate as Middleware; // Mengimpor middleware Authenticate bawaan Laravel (dan memberi alias Middleware)

class Authenticate extends Middleware // Middleware untuk memastikan user sudah login sebelum mengakses route tertentu
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * (Menentukan ke mana user diarahkan jika belum login)
     *
     * @param  \Illuminate\Http\Request  $request  // Request yang sedang diproses
     * @return string|null                        // Mengembalikan URL redirect atau null jika tidak perlu redirect
     */
    protected function redirectTo($request) // Method bawaan Laravel yang dipanggil saat user belum terautentikasi
    {
        if (! $request->expectsJson()) { // Jika request BUKAN mengharapkan JSON (bukan API/AJAX tertentu)
            return route('login'); // Arahkan user ke route bernama 'login'
        } // Jika request expects JSON, Laravel biasanya akan mengembalikan response 401 tanpa redirect
    } // Penutup method redirectTo
} // Penutup class Authenticate
