<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration untuk membuat/mengelola migration
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan struktur kolom tabel
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk operasi create/drop tabel

return new class extends Migration // Class anonim migration (format standar Laravel)
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method up dijalankan saat php artisan migrate
    {
        Schema::create('tbl_appreance', function (Blueprint $table) { // Membuat tabel tbl_appreance untuk menyimpan pengaturan tampilan user
            $table->increments('appreance_id'); // Primary key appreance_id, auto increment
            $table->string('user_id'); // Menyimpan id user pemilik setting (relasi ke tbl_user.user_id)
            $table->string('appreance_layout')->nullable(); // Setting layout (misal: sidebar-mini), boleh kosong
            $table->string('appreance_theme')->nullable(); // Setting tema (misal: light-mode/dark-mode), boleh kosong
            $table->string('appreance_menu')->nullable(); // Setting warna menu (misal: light-menu/dark-menu), boleh kosong
            $table->string('appreance_header')->nullable(); // Setting header (misal: header-light/header-dark), boleh kosong
            $table->string('appreance_sidestyle')->nullable(); // Style sidebar (misal: default-menu), boleh kosong
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        }); // Menutup callback Schema::create
    } // Menutup method up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() // Method down dijalankan saat rollback migration
    {
        Schema::dropIfExists('tbl_appreance'); // Menghapus tabel tbl_appreance jika ada
    } // Menutup method down
}; // Menutup class anonim migration
