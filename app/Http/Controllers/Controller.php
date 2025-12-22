<?php // Tag pembuka PHP untuk file controller dasar (base controller)

namespace App\Http\Controllers; // Namespace utama untuk semua controller aplikasi

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Trait untuk fitur authorization (policy/gate) pada controller
use Illuminate\Foundation\Bus\DispatchesJobs; // Trait untuk menjalankan/dispatch job (queue) dari controller
use Illuminate\Foundation\Validation\ValidatesRequests; // Trait untuk memvalidasi request (validate()) di controller
use Illuminate\Routing\Controller as BaseController; // Mengimpor base controller Laravel dan memberi alias BaseController

class Controller extends BaseController // Kelas controller dasar yang akan diwarisi oleh semua controller lain
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests; // Mengaktifkan fitur authorize, dispatch job, dan validasi request
} // Penutup class Controller
