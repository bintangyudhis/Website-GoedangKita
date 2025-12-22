<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration (kerangka dasar migration Laravel)
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan struktur tabel/kolom
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk operasi create/drop tabel

return new class extends Migration // Class anonim migration (format standar Laravel)
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Dijalankan saat php artisan migrate
    {
        Schema::create('tbl_barangmasuk', function (Blueprint $table) { // Membuat tabel tbl_barangmasuk untuk mencatat transaksi barang masuk
            $table->increments('bm_id'); // Primary key bm_id auto increment

            $table->string('bm_kode'); // Kode transaksi barang masuk
            $table->string('barang_kode'); // Kode barang yang masuk (relasi ke tbl_barang.barang_kode)
            $table->string('customer_id'); // ID customer terkait (relasi ke tbl_customer.customer_id)
            $table->string('bm_tanggal'); // Tanggal barang masuk (disimpan sebagai string sesuai desain awal)
            $table->string('bm_jumlah'); // Jumlah barang masuk (disimpan sebagai string sesuai desain awal)

            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        }); // Menutup callback Schema::create
    } // Menutup method up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() // Dijalankan saat php artisan migrate:rollback
    {
        Schema::dropIfExists('tbl_barangmasuk'); // Menghapus tabel tbl_barangmasuk jika ada
    } // Menutup method down
}; // Menutup class anonim migration
