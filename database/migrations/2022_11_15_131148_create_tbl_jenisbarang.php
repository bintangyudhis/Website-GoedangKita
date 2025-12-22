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
        Schema::create('tbl_jenisbarang', function (Blueprint $table) { // Membuat tabel tbl_jenisbarang untuk menyimpan master jenis barang
            $table->increments('jenisbarang_id'); // Primary key jenisbarang_id, auto increment
            $table->string('jenisbarang_nama'); // Nama jenis barang (misal: Elektronik, ATK, dll)
            $table->string('jenisbarang_slug'); // Slug jenis barang (versi aman untuk URL/pencarian)
            $table->string('jenisbarang_keterangan')->nullable(); // Keterangan jenis barang (opsional, boleh kosong)
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
        Schema::dropIfExists('tbl_jenisbarang'); // Menghapus tabel tbl_jenisbarang jika ada
    } // Menutup method down
}; // Menutup class anonim migration
