<?php // Tag pembuka PHP untuk file model WebModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class WebModel extends Model // Model untuk tabel tbl_web (menyimpan pengaturan/identitas website seperti nama, logo, deskripsi)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_web'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'web_id'; // Menentukan primary key tabel web (web_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'web_nama', // Nama website/aplikasi
        'web_logo', // Nama file logo website (default atau hasil upload)
        'web_deskripsi', // Deskripsi singkat tentang website/aplikasi
    ]; // Penutup daftar fillable
} // Penutup class WebModel
