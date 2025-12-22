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
        Schema::create('tbl_satuan', function (Blueprint $table) { // Membuat tabel tbl_satuan untuk menyimpan master satuan barang
            $table->increments('satuan_id'); // Primary key satuan_id, auto increment
            $table->string('satuan_nama'); // Nama satuan (misal: pcs, box, kg, liter, dll)
            $table->string('satuan_slug'); // Slug satuan (versi aman untuk URL/pencarian)
            $table->string('satuan_keterangan')->nullable(); // Keterangan satuan (opsional, boleh kosong)
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
        Schema::dropIfExists('tbl_satuan'); // Menghapus tabel tbl_satuan jika ada
    } // Menutup method down
}; // Menutup class anonim migration
