<?php // Tag pembuka PHP untuk file model SubmenuModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class SubmenuModel extends Model // Model untuk tabel tbl_submenu (menyimpan data submenu yang berada di bawah menu tertentu)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_submenu'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'submenu_id'; // Menentukan primary key tabel submenu (submenu_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'menu_id', // ID menu induk (relasi ke tbl_menu)
        'submenu_judul', // Judul/nama submenu yang tampil
        'submenu_slug', // Slug submenu (versi ramah URL)
        'submenu_redirect', // Redirect/route/url tujuan submenu
        'submenu_sort', // Urutan submenu untuk sorting tampilan
    ]; // Penutup daftar fillable
} // Penutup class SubmenuModel
