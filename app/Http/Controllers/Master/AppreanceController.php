<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Master; // Namespace controller untuk modul Master (pengaturan tampilan/tema)

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AppreanceModel; // Mengimpor model Appreance untuk menyimpan pengaturan tampilan per user
use App\Models\Admin\UserModel; // Mengimpor model User untuk tipe data user dari session
use Illuminate\Http\RedirectResponse; // Mengimpor RedirectResponse untuk type hint redirect
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input setting dari form
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengambil data user login
use Illuminate\View\View; // Mengimpor View untuk type hint return view

class AppreanceController extends Controller // Mendefinisikan controller Appreance (tampilan/tema) yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman pengaturan tampilan/tema
    {
        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user = Session::get('user'); // Mengambil data user login dari session
        $userId = $user?->user_id; // Mengambil user_id (nullsafe jika user null)

        $data['title'] = 'Tampilan/Tema'; // Menetapkan judul halaman pengaturan tampilan
        $data['data'] = AppreanceModel::where('user_id', $userId)->first(); // Mengambil setting tampilan milik user (jika ada)

        return view('Master.Appreance.index', $data); // Mengembalikan view pengaturan tampilan beserta data setting user
    }

    public function update(string $setting, Request $request): RedirectResponse // Method untuk mengubah setting tertentu (layout/theme/menu/header/sidestyle)
    {
        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user = Session::get('user'); // Mengambil data user login dari session
        $userId = $user?->user_id; // Mengambil user_id (nullsafe jika user null)

        $checkUser = AppreanceModel::where('user_id', $userId)->count(); // Mengecek apakah user sudah punya record setting (0 atau >0)

        // -------------------------------
        //  IF USER SETTING EXISTS → UPDATE
        // -------------------------------
        if ($checkUser > 0) { // Jika setting user sudah ada, maka hanya update field yang relevan

            if ($setting === 'layout') { // Jika yang diubah adalah layout (misal: sidebar-mini, dll)
                AppreanceModel::where('user_id', $userId)->update([ // Update record setting milik user
                    'appreance_layout' => $request->layout, // Mengisi layout sesuai input form
                ]); // Menutup update layout
            } elseif ($setting === 'theme') { // Jika yang diubah adalah theme (light-mode / dark-mode)

                if ($request->theme === 'light-mode') { // Jika theme yang dipilih adalah light
                    AppreanceModel::where('user_id', $userId)->update([ // Update setting untuk mode light
                        'appreance_theme'  => 'light-mode', // Set theme ke light-mode
                        'appreance_menu'   => 'light-menu', // Set menu jadi light-menu agar konsisten dengan theme
                        'appreance_header' => 'header-light', // Set header jadi header-light
                    ]); // Menutup update theme light
                } elseif ($request->theme === 'dark-mode') { // Jika theme yang dipilih adalah dark
                    AppreanceModel::where('user_id', $userId)->update([ // Update setting untuk mode dark
                        'appreance_theme'  => 'dark-mode', // Set theme ke dark-mode
                        'appreance_menu'   => 'dark-menu', // Set menu jadi dark-menu agar konsisten dengan theme
                        'appreance_header' => 'dark-header', // Set header jadi dark-header
                    ]); // Menutup update theme dark
                } // Menutup kondisi theme
            } elseif ($setting === 'menu') { // Jika yang diubah adalah style menu
                AppreanceModel::where('user_id', $userId)->update([ // Update menu setting
                    'appreance_menu' => $request->menu, // Mengisi menu sesuai input form
                ]); // Menutup update menu
            } elseif ($setting === 'header') { // Jika yang diubah adalah style header
                AppreanceModel::where('user_id', $userId)->update([ // Update header setting
                    'appreance_header' => $request->header, // Mengisi header sesuai input form
                ]); // Menutup update header
            } elseif ($setting === 'sidestyle') { // Jika yang diubah adalah gaya sidebar (sidestyle)
                AppreanceModel::where('user_id', $userId)->update([ // Update sidestyle setting
                    'appreance_sidestyle' => $request->sidestyle, // Mengisi sidestyle sesuai input form
                ]); // Menutup update sidestyle
            } // Menutup kondisi pilihan setting
        }

        // -------------------------------
        //  ELSE → CREATE NEW USER SETTING
        // -------------------------------
        else { // Jika user belum punya setting, maka buat record baru

            if ($setting === 'theme') { // Jika setting pertama yang diset adalah theme (special case karena theme mempengaruhi menu dan header)

                if ($request->theme === 'light-mode') { // Jika theme yang dipilih light
                    AppreanceModel::create([ // Membuat record setting baru untuk user
                        'user_id'            => $userId, // Menyimpan user_id pemilik setting
                        'appreance_layout'   => 'sidebar-mini', // Default layout ketika pertama kali dibuat
                        'appreance_theme'    => 'light-mode', // Set theme ke light-mode
                        'appreance_menu'     => 'light-menu', // Set menu default untuk light
                        'appreance_header'   => 'header-light', // Set header default untuk light
                        'appreance_sidestyle'=> 'default-menu', // Set gaya sidebar default
                    ]); // Menutup create theme light
                } elseif ($request->theme === 'dark-mode') { // Jika theme yang dipilih dark
                    AppreanceModel::create([ // Membuat record setting baru untuk user
                        'user_id'            => $userId, // Menyimpan user_id pemilik setting
                        'appreance_layout'   => 'sidebar-mini', // Default layout ketika pertama kali dibuat
                        'appreance_theme'    => 'dark-mode', // Set theme ke dark-mode
                        'appreance_menu'     => 'dark-menu', // Set menu default untuk dark
                        'appreance_header'   => 'header-dark', // Set header default untuk dark
                        'appreance_sidestyle'=> 'default-menu', // Set gaya sidebar default
                    ]); // Menutup create theme dark
                } // Menutup kondisi theme
            } else { // Jika setting pertama bukan theme, tetap buat record baru dengan default untuk field lainnya
                AppreanceModel::create([ // Membuat record setting baru untuk user
                    'user_id'             => $userId, // Menyimpan user_id pemilik setting
                    'appreance_layout'    => $setting === 'layout'    ? $request->layout    : 'sidebar-mini', // Jika setting layout, pakai input; jika tidak, pakai default
                    'appreance_theme'     => $setting === 'theme'     ? $request->theme     : 'light-mode', // Jika setting theme, pakai input; jika tidak, pakai default
                    'appreance_menu'      => $setting === 'menu'      ? $request->menu      : 'light-menu', // Jika setting menu, pakai input; jika tidak, pakai default
                    'appreance_header'    => $setting === 'header'    ? $request->header    : 'header-light', // Jika setting header, pakai input; jika tidak, pakai default
                    'appreance_sidestyle' => $setting === 'sidestyle' ? $request->sidestyle : 'default-menu', // Jika setting sidestyle, pakai input; jika tidak, pakai default
                ]); // Menutup create record setting
            } // Menutup else (bukan theme)
        } // Menutup else (create setting)

        return redirect(url('admin/appreance/')); // Redirect kembali ke halaman pengaturan tampilan
    }
} // Penutup class AppreanceController
