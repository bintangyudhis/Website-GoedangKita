<?php // Tag pembuka PHP untuk file model MenuModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class MenuModel extends Model // Model untuk tabel tbl_menu (menyimpan data menu & submenu yang tampil di aplikasi)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_menu'; // Menentukan nama tabel yang digunakan model ini

    protected $primaryKey = 'menu_id'; // Menentukan primary key tabel menu (menu_id)

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment (create/update)
        'menu_id', // ID menu (biasanya auto-increment; dimasukkan ke fillable jika memang diisi manual)
        'menu_judul', // Judul/nama menu yang tampil
        'menu_slug', // Slug menu (versi ramah URL)
        'menu_icon', // Icon menu (misalnya class icon)
        'menu_redirect', // Redirect/route/url tujuan menu
        'menu_sort', // Urutan menu untuk sorting tampilan
        'menu_type', // Tipe menu (contoh: 1 = menu, 2 = submenu)
    ]; // Penutup daftar fillable
} // Penutup class MenuModel
