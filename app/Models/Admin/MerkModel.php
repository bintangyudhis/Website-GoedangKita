<?php // Tag pembuka PHP untuk file model MerkModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class MerkModel extends Model // Model untuk tabel tbl_merk (master data merk/brand barang)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_merk'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'merk_id'; // Menentukan primary key tabel merk (merk_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'merk_nama', // Nama merk/brand
        'merk_slug', // Slug merk (biasanya untuk URL/identifikasi ramah teks)
        'merk_keterangan', // Keterangan/penjelasan tambahan merk
    ]; // Penutup daftar fillable
} // Penutup class MerkModel
