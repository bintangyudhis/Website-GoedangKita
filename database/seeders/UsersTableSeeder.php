<?php // Tag pembuka PHP untuk file seeder UsersTableSeeder

namespace Database\Seeders; // Namespace seeder sesuai struktur folder Database/Seeders

use Carbon\Carbon; // Mengimpor Carbon untuk mengambil waktu sekarang (timestamp created_at/updated_at)
use Illuminate\Database\Seeder; // Mengimpor base class Seeder Laravel
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk melakukan operasi insert ke database

class UsersTableSeeder extends Seeder // Seeder untuk mengisi data awal pada tabel tbl_user
{
    /**
     * Run the database seeds. // Method utama yang dieksekusi saat seeding
     *
     * @return void // Tidak mengembalikan nilai
     */
    public function run() // Menjalankan proses insert user default
    {
        DB::table('tbl_user')->insert([ // Insert beberapa user default ke tabel tbl_user
            [
                'role_id'        => 1, // Role 1 = Super Admin
                'user_nmlengkap' => 'Super Administrator', // Nama lengkap user
                'user_nama'      => 'superadmin', // Username untuk login
                'user_email'     => 'superadmin@gmail.com', // Email user
                'user_foto'      => 'undraw_profile.svg', // Foto default user
                'user_password'  => md5('12345678'), // Password di-hash dengan md5 (mengikuti sistem lama)
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp dibuat
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp update
            ],
            [
                'role_id'        => 1, // Role 1 = Super Admin
                'user_nmlengkap' => 'Bintang Yudhistira', // Nama lengkap user
                'user_nama'      => 'bintangyudhis', // Username untuk login
                'user_email'     => 'superadmin@gmail.com', // Email user (saat ini sama dengan user pertama)
                'user_foto'      => 'undraw_profile.svg', // Foto default
                'user_password'  => md5('12345678'), // Password default (md5)
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp dibuat
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp update
            ],
            [
                'role_id'        => 2, // Role 2 = Admin
                'user_nmlengkap' => 'Administrator', // Nama lengkap admin
                'user_nama'      => 'admin', // Username admin
                'user_email'     => 'admin@gmail.com', // Email admin
                'user_foto'      => 'undraw_profile.svg', // Foto default
                'user_password'  => md5('12345678'), // Password default (md5)
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp dibuat
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp update
            ],
            [
                'role_id'        => 3, // Role 3 = Operator
                'user_nmlengkap' => 'Operator', // Nama lengkap operator
                'user_nama'      => 'operator', // Username operator
                'user_email'     => 'operator@gmail.com', // Email operator
                'user_foto'      => 'undraw_profile.svg', // Foto default
                'user_password'  => md5('12345678'), // Password default (md5)
                'created_at'     => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp dibuat
                'updated_at'     => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp update
            ],
        ]); // Menutup insert array
    } // Menutup method run
} // Menutup class UsersTableSeeder
