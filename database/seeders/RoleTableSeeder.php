<?php // Tag pembuka PHP untuk file seeder RoleTableSeeder

namespace Database\Seeders; // Namespace seeder sesuai folder Database/Seeders

use Carbon\Carbon; // Mengimpor Carbon untuk mengambil waktu sekarang (timestamp created_at/updated_at)
use Illuminate\Database\Seeder; // Mengimpor base class Seeder Laravel
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk melakukan insert ke database

class RoleTableSeeder extends Seeder // Seeder untuk mengisi data awal pada tabel tbl_role
{
    /**
     * Run the database seeds. // Method yang dijalankan saat seeding
     *
     * @return void // Tidak mengembalikan nilai
     */
    public function run() // Fungsi utama seeder untuk insert role default
    {
        DB::table('tbl_role')->insert( // Insert data ke tabel tbl_role
            [
                [
                    'role_title' => 'Super Admin', // Nama role untuk akses tertinggi
                    'role_slug'  => 'super-admin', // Slug role (biasanya untuk identifikasi yang aman)
                    'role_desc'  => '-',           // Deskripsi role (diisi default "-")
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'), // Waktu dibuat
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), // Waktu terakhir diupdate
                ],
                [
                    'role_title' => 'Admin', // Nama role admin (hak akses menengah)
                    'role_slug'  => 'admin', // Slug role admin
                    'role_desc'  => '-',     // Deskripsi default
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'), // Waktu dibuat
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), // Waktu diupdate
                ],
                [
                    'role_title' => 'Operator', // Nama role operator (biasanya hak akses lebih terbatas)
                    'role_slug'  => 'operator', // Slug role operator
                    'role_desc'  => '-',        // Deskripsi default
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'), // Waktu dibuat
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), // Waktu diupdate
                ],
            ]
        ); // Menutup insert
    } // Menutup method run
} // Menutup class RoleTableSeeder
