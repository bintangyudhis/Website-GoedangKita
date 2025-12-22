<?php // Tag pembuka PHP untuk file middleware TrustHosts

namespace App\Http\Middleware; // Namespace middleware aplikasi

use Illuminate\Http\Middleware\TrustHosts as Middleware; // Mengimpor middleware bawaan Laravel untuk menentukan host yang dipercaya

class TrustHosts extends Middleware // Middleware untuk mendefinisikan host/domain yang dipercaya oleh aplikasi
{
    /**
     * Get the host patterns that should be trusted.
     * (Mengembalikan pola host yang dianggap aman/terpercaya)
     *
     * @return array<int, string|null> // Array berisi pola host (regex/pattern) atau null
     */
    public function hosts() // Method untuk menentukan daftar host yang di-trust
    {
        return [ // Mengembalikan array host yang dipercaya
            $this->allSubdomainsOfApplicationUrl(), // Mempercayai semua subdomain dari APP_URL (mis: *.example.com)
        ]; // Penutup array host
    } // Penutup method hosts
} // Penutup class TrustHosts
