<?php // Tag pembuka PHP untuk file model User (default Laravel)

namespace App\Models; // Namespace model default Laravel (bukan folder Admin)

// use Illuminate\Contracts\Auth\MustVerifyEmail; // (Opsional) Interface untuk verifikasi email, saat ini tidak dipakai
use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory (seeding/testing)
use Illuminate\Foundation\Auth\User as Authenticatable; // Base class user autentikasi Laravel
use Illuminate\Notifications\Notifiable; // Trait untuk fitur notifikasi Laravel
use Laravel\Sanctum\HasApiTokens; // Trait untuk token API (Laravel Sanctum)

class User extends Authenticatable // Model User default Laravel yang terhubung dengan sistem auth bawaan
{
    use HasApiTokens, HasFactory, Notifiable; // Mengaktifkan fitur token API, factory, dan notifikasi

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // Field yang boleh diisi lewat mass assignment (create/update dengan array)
        'name', // Nama user
        'email', // Email user
        'password', // Password user (biasanya disimpan dalam bentuk hash)
    ]; // Penutup daftar fillable

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [ // Field yang disembunyikan saat model diubah ke array/json (misalnya untuk API response)
        'password', // Password tidak ditampilkan demi keamanan
        'remember_token', // Token remember me juga tidak ditampilkan
    ]; // Penutup daftar hidden

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [ // Casting otomatis tipe data ketika diakses dari model
        'email_verified_at' => 'datetime', // Kolom email_verified_at otomatis menjadi tipe datetime
    ]; // Penutup daftar casts
} // Penutup class User
