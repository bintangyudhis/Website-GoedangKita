<?php // Tag pembuka PHP untuk file seeder WebTableSeeder

namespace Database\Seeders; // Namespace seeder sesuai folder Database/Seeders

use Carbon\Carbon; // Mengimpor Carbon untuk mengambil waktu sekarang (created_at/updated_at)
use Illuminate\Database\Seeder; // Mengimpor base class Seeder Laravel
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk menjalankan query insert ke database

class WebTableSeeder extends Seeder // Seeder untuk mengisi data awal pada tabel tbl_web
{
    /**
     * Run the database seeds. // Method yang akan dijalankan saat php artisan db:seed
     *
     * @return void // Tidak mengembalikan nilai
     */
    public function run() // Menjalankan proses insert data web
    {
        DB::table('tbl_web')->insert( // Insert data awal identitas web/aplikasi
            [
                [
                    'web_nama'      => 'GoedangKita', // Nama aplikasi/website yang ditampilkan di sistem
                    'web_logo'      => 'laravel.svg', // Nama file logo (biasanya ada di storage/public/web atau assets)
                    'web_deskripsi' => null, // Deskripsi web (null berarti belum diisi)
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp dibuat
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp update
                ],
            ]
        ); // Menutup insert
    } // Menutup method run
} // Menutup class WebTableSeeder
