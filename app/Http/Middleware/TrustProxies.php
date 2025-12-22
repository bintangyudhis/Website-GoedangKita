<?php // Tag pembuka PHP untuk file middleware TrustProxies

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Illuminate\Http\Middleware\TrustProxies as Middleware; // Mengimpor middleware bawaan Laravel untuk menangani proxy/load balancer
use Illuminate\Http\Request; // Mengimpor Request untuk konstanta header X-Forwarded-*

class TrustProxies extends Middleware // Middleware untuk menentukan proxy yang dipercaya dan header apa yang dipakai
{
    /**
     * The trusted proxies for this application.
     * (Daftar IP proxy/load balancer yang dipercaya oleh aplikasi)
     *
     * @var array<int, string>|string|null // Bisa berupa array IP, string IP, atau null (mengikuti default)
     */
    protected $proxies; // Properti untuk menyimpan daftar proxy yang dipercaya

    /**
     * The headers that should be used to detect proxies.
     * (Header mana yang dipakai untuk membaca informasi asli client saat melewati proxy)
     *
     * @var int // Nilai bitmask gabungan konstanta HEADER_X_FORWARDED_*
     */
    protected $headers = // Properti untuk menentukan kombinasi header yang digunakan
        Request::HEADER_X_FORWARDED_FOR | // Membaca IP client asli (X-Forwarded-For)
        Request::HEADER_X_FORWARDED_HOST | // Membaca host asli (X-Forwarded-Host)
        Request::HEADER_X_FORWARDED_PORT | // Membaca port asli (X-Forwarded-Port)
        Request::HEADER_X_FORWARDED_PROTO | // Membaca protokol asli http/https (X-Forwarded-Proto)
        Request::HEADER_X_FORWARDED_AWS_ELB; // Dukungan header dari AWS Elastic Load Balancer (ELB)
} // Penutup class TrustProxies
