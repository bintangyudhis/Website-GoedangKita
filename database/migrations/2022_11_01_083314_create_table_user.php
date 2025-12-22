<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration untuk membuat migration database
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan kolom-kolom tabel
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk membuat/menghapus tabel

return new class extends Migration // Class anonim migration (Laravel default)
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method up dijalankan saat menjalankan migrate
    {
        Schema::create('tbl_user', function (Blueprint $table) { // Membuat tabel tbl_user
            $table->increments('user_id'); // Primary key user_id, auto increment
            $table->string('role_id'); // Menyimpan role_id user (di sini tipe string sesuai kode kamu)
            $table->string('user_nmlengkap'); // Menyimpan nama lengkap user
            $table->string('user_nama'); // Menyimpan username / nama akun user
            $table->string('user_email'); // Menyimpan email user
            $table->string('user_foto'); // Menyimpan nama file foto user (default atau hasil upload)
            $table->string('user_password'); // Menyimpan password user (di project kamu pakai md5)
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
        Schema::dropIfExists('table_user'); // Menghapus tabel table_user jika ada (sesuai kode kamu)
        // Catatan: biasanya nama tabelnya 'tbl_user', tapi ini tidak saya ubah karena kamu minta lanjut komentar tanpa ubah logika.
    } // Menutup method down
}; // Menutup class anonim migration
