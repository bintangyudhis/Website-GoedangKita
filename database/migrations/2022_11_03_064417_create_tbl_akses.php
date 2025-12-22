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
        Schema::create('tbl_akses', function (Blueprint $table) { // Membuat tabel tbl_akses
            $table->increments('akses_id'); // Primary key akses_id, auto increment

            $table->string('menu_id')->nullable(); // ID menu (boleh kosong jika akses untuk submenu/othermenu)
            $table->string('submenu_id')->nullable(); // ID submenu (boleh kosong jika akses untuk menu/othermenu)
            $table->string('othermenu_id')->nullable(); // ID othermenu (boleh kosong jika akses untuk menu/submenu)

            $table->string('role_id'); // ID role yang memiliki akses ini
            $table->string('akses_type'); // Jenis akses (misal: view/create/update/delete)

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
        Schema::dropIfExists('tbl_akses'); // Menghapus tabel tbl_akses jika ada
    } // Menutup method down
}; // Menutup class anonim migration
