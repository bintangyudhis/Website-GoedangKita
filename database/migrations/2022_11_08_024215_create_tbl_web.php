<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration untuk membuat migration database
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
        Schema::create('tbl_web', function (Blueprint $table) { // Membuat tabel tbl_web untuk menyimpan pengaturan web/aplikasi
            $table->increments('web_id'); // Primary key web_id, auto increment
            $table->string('web_nama'); // Nama website/aplikasi (misal: nama sistem)
            $table->string('web_logo'); // Nama file/logo yang disimpan (path/nama file)
            $table->string('web_deskripsi')->nullable(); // Deskripsi website (opsional, boleh kosong)
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
        Schema::dropIfExists('tbl_web'); // Menghapus tabel tbl_web jika ada
    } // Menutup method down
}; // Menutup class anonim migration
