<?php // Tag pembuka PHP untuk file model BarangModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class BarangModel extends Model // Model untuk tabel tbl_barang (master data barang/inventaris)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_barang'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'barang_id'; // Menentukan primary key tabel barang (barang_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'jenisbarang_id', // ID jenis barang (relasi ke tbl_jenisbarang)
        'satuan_id', // ID satuan barang (relasi ke tbl_satuan)
        'merk_id', // ID merk barang (relasi ke tbl_merk)
        'barang_kode', // Kode unik barang (sering dipakai untuk join/transaksi)
        'barang_nama', // Nama barang
        'barang_slug', // Slug nama barang (biasanya untuk URL/identifikasi ramah teks)
        'barang_harga', // Harga barang (misalnya dalam Rupiah)
        'barang_stok', // Stok awal/tersimpan pada master barang
        'barang_gambar', // Nama file gambar barang (default atau hasil upload)
    ]; // Penutup daftar fillable
} // Penutup class BarangModel
