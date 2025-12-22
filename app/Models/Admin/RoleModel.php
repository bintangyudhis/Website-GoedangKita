<?php // Tag pembuka PHP untuk file model RoleModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class RoleModel extends Model // Model untuk tabel tbl_role (menyimpan data role/level user)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_role'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'role_id'; // Menentukan primary key tabel role (role_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'role_title', // Nama/judul role (mis: Admin, Staff, dll)
        'role_slug', // Slug role (versi ramah URL)
        'role_desc', // Deskripsi role (keterangan singkat hak/peran)
    ]; // Penutup daftar fillable
} // Penutup class RoleModel
