<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Master; // Namespace controller untuk modul Master (pengelolaan menu)

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\MenuModel; // Mengimpor model Menu untuk CRUD data menu di database
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\RedirectResponse; // Mengimpor RedirectResponse untuk type hint redirect
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dari form
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengambil data user login

class MenuController extends Controller // Mendefinisikan controller Menu (manajemen menu) yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman daftar menu
    {
        /** @var object|null $user */ // Anotasi tipe user dari session (di project ini user disimpan sebagai object)
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id user (nullsafe jika user null)

        // Ambil semua menu dari tbl_menu, urut berdasarkan menu_sort // Komentar penjelas pengambilan data menu
        $data = MenuModel::orderBy('menu_sort', 'ASC')->get(); // Mengambil seluruh data menu dengan urutan sort naik

        return view('Master.Menu.index', [ // Mengembalikan view master menu dengan data yang dibutuhkan
            'title'   => 'Menu', // Judul halaman
            'data'    => $data, // Data menu untuk ditampilkan
            'role_id' => $roleId, // Role id untuk kebutuhan view (misal: kontrol hak akses)
        ]); // Menutup return view
    }

    public function sortup(int $sort): RedirectResponse // Method untuk memindahkan menu ke atas berdasarkan nilai menu_sort
    {
        // Data di posisi sekarang // Komentar penanda data menu di posisi saat ini
        $data = MenuModel::where('menu_sort', $sort)->first(); // Mengambil menu yang memiliki menu_sort sesuai parameter
        // Data di posisi sebelumnya // Komentar penanda data menu di posisi sebelumnya (atasnya)
        $databack = MenuModel::where('menu_sort', $sort - 1)->first(); // Mengambil menu tepat di atasnya (sort-1)

        if ($data && $databack) { // Jika dua data tersebut ada (tidak null)
            // Tukar urutan sort // Komentar penanda proses swap sort
            MenuModel::where('menu_id', $data->menu_id)->update([ // Update menu sekarang agar pindah ke posisi atas
                'menu_sort' => $sort - 1, // Mengurangi sort 1 langkah
            ]); // Menutup update menu sekarang

            MenuModel::where('menu_id', $databack->menu_id)->update([ // Update menu sebelumnya agar pindah ke posisi sekarang
                'menu_sort' => $sort, // Mengisi sort dengan nilai sort awal
            ]); // Menutup update menu sebelumnya
        } // Menutup kondisi swap

        return redirect('/admin/menu')->with('pesan', 'Perubahan berhasil diterapkan.'); // Redirect kembali ke halaman menu dengan pesan sukses
    }

    public function sortdown(int $sort): RedirectResponse // Method untuk memindahkan menu ke bawah berdasarkan nilai menu_sort
    {
        // Data di posisi sekarang // Komentar penanda data menu di posisi saat ini
        $data = MenuModel::where('menu_sort', $sort)->first(); // Mengambil menu yang memiliki menu_sort sesuai parameter
        // Data di posisi sesudahnya // Komentar penanda data menu di posisi berikutnya (bawahnya)
        $dataforward = MenuModel::where('menu_sort', $sort + 1)->first(); // Mengambil menu tepat di bawahnya (sort+1)

        if ($data && $dataforward) { // Jika dua data tersebut ada (tidak null)
            // Tukar urutan sort // Komentar penanda proses swap sort
            MenuModel::where('menu_id', $data->menu_id)->update([ // Update menu sekarang agar pindah ke posisi bawah
                'menu_sort' => $sort + 1, // Menambah sort 1 langkah
            ]); // Menutup update menu sekarang

            MenuModel::where('menu_id', $dataforward->menu_id)->update([ // Update menu bawah agar naik ke posisi sekarang
                'menu_sort' => $sort, // Mengisi sort dengan nilai sort awal
            ]); // Menutup update menu bawah
        } // Menutup kondisi swap

        return redirect('/admin/menu')->with('pesan', 'Perubahan berhasil diterapkan.'); // Redirect kembali ke halaman menu dengan pesan sukses
    }

    public function store(Request $request): RedirectResponse // Method untuk menyimpan menu baru dari form tambah
    {
        // Form tambah biasanya pakai: judul, icon, type, redirect // Komentar penjelas field yang diharapkan
        $request->validate([ // Validasi input form tambah menu
            'judul'    => 'required', // Judul menu wajib diisi
            'redirect' => 'required', // Redirect/route menu wajib diisi
            'type'     => 'required|integer', // Type menu wajib ada dan harus integer
        ]); // Menutup validate

        $judul    = $request->input('judul'); // Mengambil input judul menu
        $redirect = $request->input('redirect'); // Mengambil input redirect/URL menu
        $icon     = $request->input('icon'); // Mengambil input icon (boleh null tergantung form)
        $type     = (int) $request->input('type'); // Mengambil type dan memaksa menjadi integer

        // Ambil menu_sort paling besar, lalu +1 // Komentar penjelasan penentuan urutan menu_sort
        $maxSort = MenuModel::max('menu_sort'); // Mengambil nilai menu_sort terbesar di tabel
        $sort    = ($maxSort ?? 0) + 1; // Menentukan sort baru: jika belum ada data maka mulai dari 1

        $slugSource = preg_replace('/[^A-Za-z0-9-]+/', '-', $judul ?? ''); // Membuat slug dasar dari judul (ganti karakter aneh dengan '-')
        $slug       = strtolower(trim($slugSource ?? '')); // Menjadikan slug lowercase dan menghapus spasi di awal/akhir

        MenuModel::create([ // Menyimpan menu baru ke database
            'menu_judul'    => $judul, // Menyimpan judul menu
            'menu_slug'     => $slug, // Menyimpan slug judul menu
            'menu_icon'     => $icon, // Menyimpan icon menu
            'menu_redirect' => $redirect, // Menyimpan redirect/route menu
            'menu_sort'     => $sort, // Menyimpan urutan menu_sort
            'menu_type'     => $type, // Menyimpan tipe menu (1 = menu, 2 = submenu)
        ]); // Menutup create

        return redirect('/admin/menu')->with('pesan', 'Data berhasil ditambah.'); // Redirect kembali ke halaman menu dengan pesan sukses tambah
    }

    public function update(Request $request): RedirectResponse // Method untuk mengubah data menu dari form ubah
    {
        // Form ubah pakai field: menu_id, ujudul, uicon, utype, uredirect // Komentar penjelasan field yang diharapkan
        $request->validate([ // Validasi input form ubah menu
            'menu_id'   => 'required|integer', // menu_id wajib ada dan integer
            'ujudul'    => 'required', // judul baru wajib diisi
            'uredirect' => 'required', // redirect baru wajib diisi
            'utype'     => 'required|integer', // type baru wajib ada dan integer
        ]); // Menutup validate

        $id       = (int) $request->input('menu_id'); // Mengambil id menu yang akan diupdate
        $judul    = $request->input('ujudul'); // Mengambil judul baru dari form ubah
        $redirect = $request->input('uredirect'); // Mengambil redirect baru dari form ubah
        $icon     = $request->input('uicon'); // Mengambil icon baru dari form ubah (boleh null)
        $type     = (int) $request->input('utype'); // Mengambil type baru dan memaksa jadi integer

        $slugSource = preg_replace('/[^A-Za-z0-9-]+/', '-', $judul ?? ''); // Membuat slug baru dari judul
        $slug       = strtolower(trim($slugSource ?? '')); // Menjadikan slug lowercase dan trim spasi

        MenuModel::where('menu_id', $id)->update([ // Update data menu berdasarkan menu_id
            'menu_judul'    => $judul, // Update judul menu
            'menu_slug'     => $slug, // Update slug menu
            'menu_icon'     => $icon, // Update icon menu
            'menu_redirect' => $redirect, // Update redirect/route menu
            'menu_type'     => $type, // Update type menu
        ]); // Menutup update

        return redirect('/admin/menu')->with('pesan', 'Data berhasil diubah.'); // Redirect kembali ke halaman menu dengan pesan sukses ubah
    }

    public function hapus(Request $request): RedirectResponse // Method untuk menghapus menu dari modal hapus
    {
        // Modal hapus isi input hidden name="idmenu" // Komentar penjelasan sumber id hapus
        $id = (int) $request->input('idmenu'); // Mengambil id menu yang akan dihapus

        if ($id > 0) { // Validasi sederhana: hanya proses jika id lebih dari 0
            MenuModel::where('menu_id', $id)->delete(); // Menghapus data menu berdasarkan menu_id
        } // Menutup kondisi id valid

        return redirect('/admin/menu')->with('pesan', 'Data berhasil dihapus.'); // Redirect kembali ke halaman menu dengan pesan sukses hapus
    }
} // Penutup class MenuController
