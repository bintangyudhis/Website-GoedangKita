<?php // Tag pembuka PHP untuk file middleware TrimStrings

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware; // Mengimpor middleware bawaan Laravel untuk memangkas spasi pada input

class TrimStrings extends Middleware // Middleware yang otomatis meng-trim (hapus spasi depan/belakang) semua input string dari request
{
    /**
     * The names of the attributes that should not be trimmed.
     * (Daftar field/input yang tidak boleh di-trim, biasanya untuk keamanan dan konsistensi password)
     *
     * @var array<int, string> // Tipe data: array berisi string (nama field request)
     */
    protected $except = [ // Properti daftar pengecualian field yang tidak dipangkas spasinya
        'current_password', // Field password saat ini (biarkan apa adanya)
        'password', // Field password baru (biarkan apa adanya)
        'password_confirmation', // Field konfirmasi password (biarkan apa adanya)
    ]; // Penutup daftar pengecualian
} // Penutup class TrimStrings
