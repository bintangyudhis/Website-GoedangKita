<?php // Tag pembuka PHP untuk file model UserModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class UserModel extends Model // Model untuk tabel tbl_user (menyimpan data user aplikasi dan rolenya)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_user'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'user_id'; // Menentukan primary key tabel user (user_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'role_id', // ID role user (relasi ke tbl_role)
        'user_nama', // Username untuk login
        'user_nmlengkap', // Nama lengkap user
        'user_email', // Email user
        'user_password', // Password user (biasanya disimpan dalam bentuk hash)
        'user_foto', // Nama file foto user (default atau hasil upload)
    ]; // Penutup daftar fillable
} // Penutup class UserModel
