<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai dasar pembuatan migration
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan struktur tabel/kolom
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk membuat/menghapus tabel

return new class extends Migration // Mengembalikan class anonim yang mewarisi Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method up dijalankan saat migrate (membuat tabel / perubahan DB)
    {
        Schema::create('tbl_role', function (Blueprint $table) { // Membuat tabel tbl_role dengan struktur kolom di dalam callback
            $table->increments('role_id'); // Membuat kolom role_id sebagai primary key auto increment
            $table->string('role_title'); // Kolom nama/judul role (contoh: Admin, Staff, dll.)
            $table->string('role_slug'); // Kolom slug role (versi URL-friendly dari role_title)
            $table->text('role_desc')->nullable(); // Kolom deskripsi role (text) dan boleh kosong (nullable)
            $table->timestamps(); // Kolom created_at dan updated_at otomatis dari Laravel
        }); // Menutup callback Schema::create
    } // Menutup method up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() // Method down dijalankan saat rollback (membatalkan migration)
    {
        Schema::dropIfExists('tbl_role'); // Menghapus tabel tbl_role jika ada
    } // Menutup method down
}; // Menutup class anonim migration
