<?php // Tag pembuka PHP untuk file middleware CheckRoleUser

namespace App\Http\Middleware; // Namespace middleware aplikasi

use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk mengecek hak akses (role -> menu/submenu/othermenu)
use App\Models\Admin\UserModel; // Mengimpor model User untuk type hint data user dari session
use Closure; // Mengimpor Closure untuk parameter $next (pipeline middleware)
use Illuminate\Http\Request; // Mengimpor Request untuk menangani request yang masuk
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengambil data user yang sedang login

class CheckRoleUser // Middleware custom untuk mengecek apakah role user punya akses ke menu tertentu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next // Closure untuk meneruskan request ke middleware berikutnya / controller
     * @param  string  $menu // Parameter nama menu/redirect/ID yang akan dicek (diambil dari route middleware parameter)
     * @param  string  $type // Jenis akses yang dicek: othermenu | menu | submenu
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse // Response hasil proses middleware
     */
    public function handle(Request $request, Closure $next, string $menu, string $type) // Method utama middleware untuk memproses request
    {
        /** @var UserModel|null $user */ // Type hint agar jelas data session yang diambil adalah UserModel atau null
        $user   = Session::get('user'); // Mengambil data user yang tersimpan di session saat login
        $roleId = $user?->role_id; // Mengambil role_id user (jika user null maka hasilnya null)

        $getMenu = 1; // Default nilai akses (diasumsikan boleh), akan diubah jika dilakukan pengecekan

        if ($type === 'othermenu') { // Jika akses yang dicek adalah othermenu
            $getMenu = AksesModel::where([ // Query cek akses berdasarkan othermenu_id
                'role_id'      => $roleId, // Cocokkan role user
                'othermenu_id' => $menu, // Cocokkan ID othermenu (dari parameter middleware)
                'akses_type'   => 'view', // Hanya cek akses type 'view' (hak lihat)
            ])->count(); // Hitung jumlah record yang cocok (0 = tidak punya akses)
        } elseif ($type === 'menu') { // Jika akses yang dicek adalah menu utama
            $getMenu = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id') // Join akses dengan tabel menu
                ->where([ // Filter berdasarkan role dan redirect menu
                    'tbl_akses.role_id'     => $roleId, // Cocokkan role user
                    'tbl_menu.menu_redirect'=> $menu, // Cocokkan menu berdasarkan menu_redirect (url/route redirect)
                    'tbl_akses.akses_type'  => 'view', // Cek akses view
                ])
                ->count(); // Hitung jumlah akses yang cocok
        } elseif ($type === 'submenu') { // Jika akses yang dicek adalah submenu
            $getMenu = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Join akses dengan tabel submenu
                ->where([ // Filter berdasarkan role dan redirect submenu
                    'tbl_akses.role_id'        => $roleId, // Cocokkan role user
                    'tbl_submenu.submenu_redirect' => $menu, // Cocokkan submenu berdasarkan submenu_redirect (url/route redirect)
                    'tbl_akses.akses_type'     => 'view', // Cek akses view
                ])
                ->count(); // Hitung jumlah akses yang cocok
        } // Penutup percabangan jenis menu

        if ($getMenu === 0) { // Jika hasil pengecekan 0 berarti user tidak punya akses
            return abort(404); // Tampilkan halaman 404 (disamarkan seolah route tidak ada)
        } // Jika punya akses, request boleh lanjut

        return $next($request); // Melanjutkan request ke proses berikutnya (controller atau middleware lain)
    } // Penutup method handle
} // Penutup class CheckRoleUser
