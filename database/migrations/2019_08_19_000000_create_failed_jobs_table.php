<?php // Tag pembuka PHP untuk file migration ini

use Illuminate\Database\Migrations\Migration; // Import class Migration sebagai dasar pembuatan migration
use Illuminate\Database\Schema\Blueprint; // Import Blueprint untuk mendefinisikan struktur tabel/kolom
use Illuminate\Support\Facades\Schema; // Import Schema facade untuk operasi create/drop tabel

return new class extends Migration // Mengembalikan class anonim yang mewarisi Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() // Method up dipanggil saat menjalankan migrate (membuat perubahan ke DB)
    {
        Schema::create('failed_jobs', function (Blueprint $table) { // Membuat tabel failed_jobs dengan definisi kolom di dalam callback
            $table->id(); // Membuat kolom id sebagai primary key auto increment (bigint unsigned)
            $table->string('uuid')->unique(); // Membuat kolom uuid bertipe string dan diberi constraint unique
            $table->text('connection'); // Menyimpan nama/konfigurasi koneksi queue saat job gagal (text)
            $table->text('queue'); // Menyimpan nama queue tempat job berada (text)
            $table->longText('payload'); // Menyimpan data payload job (biasanya JSON) dengan ukuran besar (longText)
            $table->longText('exception'); // Menyimpan detail error/stacktrace exception dengan ukuran besar (longText)
            $table->timestamp('failed_at')->useCurrent(); // Waktu job gagal, default otomatis diisi waktu saat insert
        }); // Menutup callback Schema::create
    } // Menutup method up

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() // Method down dipanggil saat rollback migration (membatalkan perubahan)
    {
        Schema::dropIfExists('failed_jobs'); // Menghapus tabel failed_jobs jika tabel tersebut ada (aman untuk rollback)
    } // Menutup method down
}; // Menutup class anonim migration
