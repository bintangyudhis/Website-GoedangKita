<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration (kerangka dasar migration)
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan struktur tabel/kolom
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk membuat/menghapus tabel

return new class extends Migration // Class anonim migration (format standar Laravel)
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method up dijalankan saat php artisan migrate
    {
        Schema::create('tbl_customer', function (Blueprint $table) { // Membuat tabel tbl_customer untuk menyimpan data customer
            $table->increments('customer_id'); // Primary key customer_id auto increment

            $table->string('customer_nama'); // Nama customer
            $table->string('customer_slug'); // Slug nama customer (aman untuk URL/identifikasi)
            $table->text('customer_alamat')->nullable(); // Alamat customer (text karena bisa panjang), boleh kosong
            $table->string('customer_notelp')->nullable(); // Nomor telepon customer, boleh kosong

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
        Schema::dropIfExists('tbl_customer'); // Menghapus tabel tbl_customer jika ada
    } // Menutup method down
}; // Menutup class anonim migration
