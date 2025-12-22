<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai dasar pembuatan migration
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan struktur tabel/kolom
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk create/drop tabel di database

return new class extends Migration // Mengembalikan class anonim yang mewarisi Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method up dijalankan saat migrate (membuat tabel / perubahan DB)
    {
        Schema::create('tbl_menu', function (Blueprint $table) { // Membuat tabel tbl_menu dengan struktur kolom di dalam callback
            $table->increments('menu_id'); // Membuat kolom menu_id sebagai primary key auto increment (tipe int)
            $table->string('menu_judul'); // Kolom judul menu (string/varchar)
            $table->string('menu_slug'); // Kolom slug menu (biasanya versi URL-friendly dari judul)
            $table->string('menu_icon'); // Kolom icon menu (misal nama class icon)
            $table->string('menu_redirect'); // Kolom redirect/route tujuan menu saat diklik
            $table->string('menu_sort'); // Kolom urutan menu (dipakai untuk sorting tampilan menu)
            $table->string('menu_type'); // Kolom tipe menu (misal 1 = menu utama, 2 = submenu)
            $table->timestamps(); // Kolom created_at dan updated_at otomatis dari Laravel
        }); // Menutup callback Schema::create
    } // Menutup method up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() // Method down dijalankan saat rollback (membatalkan perubahan migration)
    {
        Schema::dropIfExists('menu'); // Menghapus tabel jika ada (CATATAN: ini akan menghapus tabel 'menu', bukan 'tbl_menu')
    } // Menutup method down
}; // Menutup class anonim migration
