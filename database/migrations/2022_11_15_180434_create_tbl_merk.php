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
        Schema::create('tbl_merk', function (Blueprint $table) { // Membuat tabel tbl_merk untuk menyimpan master merk barang
            $table->increments('merk_id'); // Primary key merk_id, auto increment
            $table->string('merk_nama'); // Nama merk (misal: Epson, Canon, Logitech, dll)
            $table->string('merk_slug'); // Slug merk (versi aman untuk URL/pencarian)
            $table->string('merk_keterangan')->nullable(); // Keterangan merk (opsional, boleh kosong)
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
        Schema::dropIfExists('tbl_merk'); // Menghapus tabel tbl_merk jika ada
    } // Menutup method down
}; // Menutup class anonim migration
