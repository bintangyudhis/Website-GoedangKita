<?php // Tag pembuka PHP untuk file model SatuanModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class SatuanModel extends Model // Model untuk tabel tbl_satuan (master data satuan barang, mis: pcs, box, kg)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_satuan'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'satuan_id'; // Menentukan primary key tabel satuan (satuan_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'satuan_nama', // Nama satuan (contoh: pcs, unit, kg)
        'satuan_slug', // Slug satuan (versi ramah URL)
        'satuan_keterangan', // Keterangan/penjelasan tambahan satuan
    ]; // Penutup daftar fillable
} // Penutup class SatuanModel
