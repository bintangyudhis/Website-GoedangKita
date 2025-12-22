<?php // Tag pembuka PHP untuk file model BarangkeluarModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class BarangkeluarModel extends Model // Model untuk tabel tbl_barangkeluar (mencatat transaksi barang keluar)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_barangkeluar'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'bk_id'; // Menentukan primary key tabel barang keluar (bk_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'bk_kode', // Kode transaksi barang keluar
        'barang_kode', // Kode barang yang dikeluarkan (relasi ke tbl_barang via barang_kode)
        'bk_tanggal', // Tanggal barang keluar
        'bk_tujuan', // Tujuan/keperluan barang keluar
        'bk_jumlah', // Jumlah barang yang keluar
    ]; // Penutup daftar fillable
} // Penutup class BarangkeluarModel
