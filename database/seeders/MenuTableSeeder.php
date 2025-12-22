<?php // Tag pembuka PHP untuk file seeder MenuTableSeeder

namespace Database\Seeders; // Namespace seeder sesuai folder Database/Seeders

use Carbon\Carbon; // Mengimpor Carbon untuk mengambil waktu sekarang (timestamp created_at/updated_at)
use Illuminate\Database\Seeder; // Mengimpor base class Seeder Laravel
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk query database langsung

class MenuTableSeeder extends Seeder // Seeder untuk mengisi data awal pada tabel tbl_menu
{
    /**
     * Run the database seeds. // Method yang dijalankan saat seeding
     *
     * @return void // Tidak mengembalikan nilai
     */
    public function run() // Fungsi utama seeder untuk insert data menu
    {
        DB::table('tbl_menu')->insert( // Insert data ke tabel tbl_menu
            [
                [
                    'menu_id'       => '1667444041', // ID menu (dibuat manual, biasanya untuk mapping akses)
                    'menu_judul'    => 'Dashboard',  // Judul menu yang akan tampil di sidebar
                    'menu_slug'     => 'dashboard',  // Slug menu (biasanya untuk kebutuhan URL/identifikasi)
                    'menu_icon'     => 'home',       // Icon menu (misal nama icon dari template)
                    'menu_redirect' => '/dashboard', // URL/route tujuan saat menu diklik
                    'menu_sort'     => 1,            // Urutan tampil menu (semakin kecil, semakin atas)
                    'menu_type'     => 1,            // Tipe menu: 1=menu utama, 2=submenu (sesuai desain sistemmu)
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'), // Waktu dibuat
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s'), // Waktu diupdate
                ],
            ]
        ); // Menutup insert
    } // Menutup method run
} // Menutup class MenuTableSeeder
