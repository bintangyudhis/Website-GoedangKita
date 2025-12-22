<?php // Tag pembuka PHP untuk file middleware PreventRequestsDuringMaintenance

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware; // Mengimpor middleware bawaan Laravel untuk mode maintenance

class PreventRequestsDuringMaintenance extends Middleware // Middleware untuk memblokir request saat aplikasi sedang maintenance
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     * (Daftar URI yang tetap boleh diakses ketika aplikasi berada dalam maintenance mode)
     *
     * @var array<int, string> // Tipe data: array berisi string (URI/path)
     */
    protected $except = [ // Properti untuk menampung URI yang dikecualikan dari pemblokiran maintenance
        //
    ]; // Jika kosong, semua route akan diblokir saat maintenance (kecuali yang diizinkan oleh Laravel default)
} // Penutup class PreventRequestsDuringMaintenance
