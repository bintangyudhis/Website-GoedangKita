<?php // Tag pembuka PHP untuk file model CustomerModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class CustomerModel extends Model // Model untuk tabel tbl_customer (menyimpan data customer/supplier terkait transaksi barang masuk)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_customer'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'customer_id'; // Menentukan primary key tabel customer (customer_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'customer_nama', // Nama customer
        'customer_slug', // Slug customer (biasanya untuk URL/identifikasi ramah teks)
        'customer_alamat', // Alamat customer
        'customer_notelp', // Nomor telepon customer
    ]; // Penutup daftar fillable
} // Penutup class CustomerModel
