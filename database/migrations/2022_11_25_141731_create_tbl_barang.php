<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration (kerangka dasar migration)
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan struktur tabel/kolom
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk create/drop tabel

return new class extends Migration // Class anonim migration (format standar Laravel)
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method up dijalankan saat perintah php artisan migrate
    {
        Schema::create('tbl_barang', function (Blueprint $table) { // Membuat tabel tbl_barang untuk menyimpan data master barang
            $table->increments('barang_id'); // Primary key barang_id, auto increment

            $table->string('jenisbarang_id')->nullable(); // Menyimpan id jenis barang (relasi ke tbl_jenisbarang), boleh null
            $table->string('satuan_id')->nullable(); // Menyimpan id satuan (relasi ke tbl_satuan), boleh null
            $table->string('merk_id')->nullable(); // Menyimpan id merk (relasi ke tbl_merk), boleh null

            $table->string('barang_kode'); // Kode unik barang (biasanya dipakai untuk transaksi masuk/keluar)
            $table->string('barang_nama'); // Nama barang
            $table->string('barang_slug'); // Slug nama barang (untuk URL/identifikasi aman)
            $table->string('barang_harga'); // Harga barang (disimpan string sesuai desain awal project)
            $table->string('barang_stok'); // Stok awal barang (disimpan string sesuai desain awal project)
            $table->string('barang_gambar'); // Nama file gambar barang (default atau hasil upload)

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
        Schema::dropIfExists('tbl_barang'); // Menghapus tabel tbl_barang jika ada
    } // Menutup method down
}; // Menutup class anonim migration
