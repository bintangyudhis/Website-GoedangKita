<?php // Tag pembuka PHP untuk file middleware ValidateSignature

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Illuminate\Routing\Middleware\ValidateSignature as Middleware; // Mengimpor middleware bawaan Laravel untuk validasi URL bertanda tangan (signed URL)

class ValidateSignature extends Middleware // Middleware untuk memastikan signed URL valid (tidak diubah dan belum kedaluwarsa)
{
    /**
     * The names of the query string parameters that should be ignored.
     * (Daftar parameter query string yang diabaikan saat memvalidasi signature)
     *
     * @var array<int, string> // Tipe data: array berisi string (nama parameter)
     */
    protected $except = [ // Properti berisi parameter query yang tidak ikut dihitung dalam signature
        // 'fbclid', // Contoh: parameter tracking Facebook (jika ingin diabaikan, hapus komentar)
        // 'utm_campaign', // Contoh: UTM tracking campaign
        // 'utm_content', // Contoh: UTM tracking content
        // 'utm_medium', // Contoh: UTM tracking medium
        // 'utm_source', // Contoh: UTM tracking source
        // 'utm_term', // Contoh: UTM tracking term
    ]; // Saat kosong, semua parameter query akan ikut dipertimbangkan dalam signature (kecuali signature & expires bawaan Laravel)
} // Penutup class ValidateSignature
