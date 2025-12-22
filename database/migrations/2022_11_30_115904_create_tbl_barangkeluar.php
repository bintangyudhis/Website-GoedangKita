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
        Schema::create('tbl_barangkeluar', function (Blueprint $table) { // Membuat tabel tbl_barangkeluar untuk mencatat transaksi barang keluar
            $table->increments('bk_id'); // Primary key bk_id auto increment

            $table->string('bk_kode'); // Kode transaksi barang keluar
            $table->string('barang_kode'); // Kode barang yang keluar (relasi ke tbl_barang.barang_kode)
            $table->string('bk_tanggal'); // Tanggal barang keluar (disimpan sebagai string sesuai desain awal)
            $table->string('bk_tujuan')->nullable(); // Tujuan pengeluaran barang (boleh kosong)
            $table->string('bk_jumlah'); // Jumlah barang keluar (disimpan sebagai string sesuai desain awal)

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
        Schema::dropIfExists('tbl_barangkeluar'); // Menghapus tabel tbl_barangkeluar jika ada
    } // Menutup method down
}; // Menutup class anonim migration
