<?php // Tag pembuka PHP untuk file seeder utama

namespace Database\Seeders; // Namespace seeder sesuai folder Database/Seeders

// use Illuminate\Database\Console\Seeds\WithoutModelEvents; // (Opsional) Trait untuk menonaktifkan event model saat seeding
use Illuminate\Database\Seeder; // Mengimpor class Seeder Laravel sebagai base seeder

class DatabaseSeeder extends Seeder // Class seeder utama yang dijalankan saat `php artisan db:seed`
{
    /**
     * Seed the application's database. // Fungsi utama untuk menjalankan proses seeding
     *
     * @return void // Tidak mengembalikan nilai apa pun
     */
    public function run() // Method yang akan dipanggil oleh Laravel saat seeding
    {
        $this->call([ // Memanggil seeder-seeder lain secara berurutan
            RoleTableSeeder::class,  // Menjalankan seeder untuk data role (Super Admin, Admin, dll.)
            MenuTableSeeder::class,  // Menjalankan seeder untuk data menu utama
            UsersTableSeeder::class, // Menjalankan seeder untuk data user awal (akun default)
            AksesTableSeeder::class, // Menjalankan seeder untuk hak akses tiap role (menu/submenu/othermenu)
            WebTableSeeder::class,   // Menjalankan seeder untuk data web (nama, logo, deskripsi)
        ]); // Menutup array daftar seeder
    } // Menutup method run
} // Menutup class DatabaseSeeder
