<?php // Tag pembuka PHP untuk file model JenisBarangModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class JenisBarangModel extends Model // Model untuk tabel tbl_jenisbarang (master data kategori/jenis barang)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_jenisbarang'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'jenisbarang_id'; // Menentukan primary key tabel jenis barang (jenisbarang_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'jenisbarang_nama', // Nama jenis/kategori barang
        'jenisbarang_slug', // Slug jenis barang (biasanya untuk URL/identifikasi ramah teks)
        'jenisbarang_ket', // Keterangan/deskipsi jenis barang
    ]; // Penutup daftar fillable
} // Penutup class JenisBarangModel
