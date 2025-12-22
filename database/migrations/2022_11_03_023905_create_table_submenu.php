<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration untuk membuat migration database
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan struktur tabel
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk membuat/menghapus tabel

return new class extends Migration // Class anonim migration (Laravel default)
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method up dijalankan saat migrate
    {
        Schema::create('tbl_submenu', function (Blueprint $table) { // Membuat tabel tbl_submenu
            $table->increments('submenu_id'); // Primary key submenu_id, auto increment
            $table->string('menu_id'); // Menyimpan relasi ke menu (di sini tipe string sesuai kode kamu)
            $table->string('submenu_judul'); // Menyimpan judul submenu
            $table->string('submenu_slug'); // Menyimpan slug submenu (biasanya untuk URL/identitas)
            $table->string('submenu_redirect'); // Menyimpan redirect/route tujuan submenu
            $table->string('submenu_sort'); // Menyimpan urutan tampil submenu (sorting)
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        }); // Menutup callback Schema::create
    } // Menutup method up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() // Method down dijalankan saat rollback
    {
        Schema::dropIfExists('tbl_submenu'); // Menghapus tabel tbl_submenu jika ada
    } // Menutup method down
}; // Menutup class anonim migration
