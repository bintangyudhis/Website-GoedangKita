<?php // Tag pembuka PHP untuk file model AppreanceModel

namespace App\Models\Admin; // Namespace model pada folder Admin

use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (biasanya untuk seeding/testing)
use Illuminate\Database\Eloquent\Model; // Base class Eloquent Model Laravel

class AppreanceModel extends Model // Model untuk tabel tbl_appreance (menyimpan preferensi tampilan/tema per user)
{
    use HasFactory; // Mengaktifkan fitur factory pada model ini

    protected $table = 'tbl_appreance'; // Menentukan nama tabel yang dipakai model ini

    protected $primaryKey = 'appreance_id'; // Menentukan primary key tabel (bukan default 'id')

    protected $fillable = [ // Daftar kolom yang boleh diisi melalui mass assignment
        'user_id', // ID user pemilik pengaturan tampilan
        'appreance_layout', // Tipe layout (mis: sidebar-mini, dll)
        'appreance_theme', // Tema (mis: light-mode / dark-mode)
        'appreance_menu', // Gaya/warna menu (mis: light-menu / dark-menu)
        'appreance_header', // Gaya/warna header (mis: header-light / header-dark)
        'appreance_sidestyle', // Style sidebar (mis: default-menu)
    ]; // Penutup daftar fillable
} // Penutup class AppreanceModel
