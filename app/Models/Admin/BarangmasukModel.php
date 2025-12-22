<?php // Tag pembuka PHP untuk file model BarangmasukModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class BarangmasukModel extends Model // Model untuk tabel tbl_barangmasuk (mencatat transaksi barang masuk)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_barangmasuk'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'bm_id'; // Menentukan primary key tabel barang masuk (bm_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'bm_kode', // Kode transaksi barang masuk
        'barang_kode', // Kode barang yang masuk (relasi ke tbl_barang via barang_kode)
        'customer_id', // ID customer/sumber barang (relasi ke tbl_customer)
        'bm_tanggal', // Tanggal barang masuk
        'bm_jumlah', // Jumlah barang yang masuk
    ]; // Penutup daftar fillable
} // Penutup class BarangmasukModel
