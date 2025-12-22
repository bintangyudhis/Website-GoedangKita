<?php // Tag pembuka PHP untuk file seeder ini

namespace Database\Seeders; // Namespace seeder sesuai folder Database/Seeders

use Carbon\Carbon; // Import Carbon untuk membuat timestamp created_at/updated_at
use Illuminate\Database\Seeder; // Import base Seeder Laravel
use Illuminate\Support\Facades\DB; // Import DB facade untuk operasi query insert langsung

class AksesTableSeeder extends Seeder // Seeder untuk mengisi data awal (seed) tabel tbl_akses
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() // Method utama yang akan dipanggil saat php artisan db:seed --class=AksesTableSeeder
    {
        // ==========================================================
        // INSERT 1: AKSES MENU "DASHBOARD" (menu_id = 1667444041)
        // Dibuat untuk role: 1 (Super Admin), 2 (Admin), 3 (Operator)
        // Setiap role diberi 4 hak: view, create, update, delete
        // ==========================================================
        DB::table('tbl_akses')->insert(
            [
                // -----------------------------
                // Dashboard akses role Super Admin (role_id = 1)
                // -----------------------------
                [
                    'menu_id'     => '1667444041', // ID menu dashboard (diambil dari tbl_menu)
                    'role_id'     => 1,            // Role Super Admin
                    'akses_type'  => 'view',       // Hak akses melihat
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp dibuat
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'), // Timestamp diupdate
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 1,            // Role Super Admin
                    'akses_type'  => 'create',     // Hak akses tambah
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 1,            // Role Super Admin
                    'akses_type'  => 'update',     // Hak akses ubah
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 1,            // Role Super Admin
                    'akses_type'  => 'delete',     // Hak akses hapus
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],

                // -----------------------------
                // Dashboard akses role Admin (role_id = 2)
                // -----------------------------
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 2,            // Role Admin
                    'akses_type'  => 'view',       // Hak akses melihat
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 2,            // Role Admin
                    'akses_type'  => 'create',     // Hak akses tambah
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 2,            // Role Admin
                    'akses_type'  => 'update',     // Hak akses ubah
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 2,            // Role Admin
                    'akses_type'  => 'delete',     // Hak akses hapus
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],

                // -----------------------------
                // Dashboard akses role Operator (role_id = 3)
                // -----------------------------
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 3,            // Role Operator
                    'akses_type'  => 'view',       // Hak akses melihat
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 3,            // Role Operator
                    'akses_type'  => 'create',     // Hak akses tambah
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 3,            // Role Operator
                    'akses_type'  => 'update',     // Hak akses ubah
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'menu_id'     => '1667444041', // ID menu dashboard
                    'role_id'     => 3,            // Role Operator
                    'akses_type'  => 'delete',     // Hak akses hapus
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ] // Menutup array utama insert pertama
        ); // Menutup DB::table()->insert pertama

        // ==========================================================
        // INSERT 2: AKSES "OTHERMENU" (menu pengaturan/master)
        // othermenu_id 1..6 adalah menu khusus (bukan tbl_menu / tbl_submenu)
        // ==========================================================
        DB::table('tbl_akses')->insert([
            // -----------------------------
            // Settings (othermenu_id = 1)
            // -----------------------------
            [
                'othermenu_id' => 1,        // ID menu othermenu "Settings"
                'role_id'      => 1,        // Super Admin
                'akses_type'   => 'view',   // Hanya hak akses melihat
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 1,        // ID menu othermenu "Settings"
                'role_id'      => 2,        // Admin
                'akses_type'   => 'view',   // Hanya hak akses melihat
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            // -----------------------------
            // Menu (othermenu_id = 2)
            // Super Admin dan Admin punya hak lengkap: view/create/update/delete
            // -----------------------------
            [
                'othermenu_id' => 2,
                'role_id'      => 1,        // Super Admin
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 2,
                'role_id'      => 1,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 2,
                'role_id'      => 1,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 2,
                'role_id'      => 1,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            [
                'othermenu_id' => 2,
                'role_id'      => 2,        // Admin
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 2,
                'role_id'      => 2,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 2,
                'role_id'      => 2,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 2,
                'role_id'      => 2,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            // -----------------------------
            // Role (othermenu_id = 3)
            // Super Admin dan Admin punya hak lengkap
            // -----------------------------
            [
                'othermenu_id' => 3,
                'role_id'      => 1,
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 3,
                'role_id'      => 1,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 3,
                'role_id'      => 1,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 3,
                'role_id'      => 1,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            [
                'othermenu_id' => 3,
                'role_id'      => 2,
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 3,
                'role_id'      => 2,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 3,
                'role_id'      => 2,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 3,
                'role_id'      => 2,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            // -----------------------------
            // User (othermenu_id = 4)
            // Super Admin dan Admin punya hak lengkap
            // -----------------------------
            [
                'othermenu_id' => 4,
                'role_id'      => 1,
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 4,
                'role_id'      => 1,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 4,
                'role_id'      => 1,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 4,
                'role_id'      => 1,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            [
                'othermenu_id' => 4,
                'role_id'      => 2,
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 4,
                'role_id'      => 2,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 4,
                'role_id'      => 2,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 4,
                'role_id'      => 2,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            // -----------------------------
            // Menu Akses (othermenu_id = 5)
            // Hanya terlihat pada Super Admin (hak lengkap)
            // -----------------------------
            [
                'othermenu_id' => 5,
                'role_id'      => 1,
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 5,
                'role_id'      => 1,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 5,
                'role_id'      => 1,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 5,
                'role_id'      => 1,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            // -----------------------------
            // Web (othermenu_id = 6)
            // Super Admin dan Admin punya hak lengkap
            // -----------------------------
            [
                'othermenu_id' => 6,
                'role_id'      => 1,
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 6,
                'role_id'      => 1,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 6,
                'role_id'      => 1,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 6,
                'role_id'      => 1,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],

            [
                'othermenu_id' => 6,
                'role_id'      => 2,
                'akses_type'   => 'view',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 6,
                'role_id'      => 2,
                'akses_type'   => 'create',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 6,
                'role_id'      => 2,
                'akses_type'   => 'update',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'othermenu_id' => 6,
                'role_id'      => 2,
                'akses_type'   => 'delete',
                'created_at'   => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'   => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]); // Menutup DB insert kedua (othermenu)
    } // Menutup method run
} // Menutup class AksesTableSeeder
;
