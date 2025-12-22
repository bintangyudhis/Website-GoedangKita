<?php // Tag pembuka PHP untuk file model AksesModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (biasanya untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class AksesModel extends Model // Model untuk tabel tbl_akses (menyimpan data hak akses role terhadap menu/submenu/othermenu)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_akses'; // Menentukan nama tabel yang digunakan model ini (defaultnya akan menebak dari nama model)

    protected $primaryKey = 'akses_id'; // Menentukan primary key tabel (defaultnya 'id')

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update dengan array)
        'menu_id', // ID menu yang diakses (untuk type menu)
        'submenu_id', // ID submenu yang diakses (untuk type submenu)
        'othermenu_id', // ID othermenu yang diakses (untuk type othermenu)
        'role_id', // ID role pemilik akses
        'akses_type', // Jenis akses (mis: view, create, update, delete)
    ]; // Penutup daftar fillable
} // Penutup class AksesModel
