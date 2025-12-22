<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk mengambil hak akses berdasarkan role
use App\Models\Admin\UserModel; // Mengimpor model User untuk proses login (cek username/password)
use App\Models\Admin\WebModel; // Mengimpor model Web untuk mengambil data web (misalnya logo di halaman login)
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\RedirectResponse; // Mengimpor RedirectResponse untuk type hint return redirect
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dari form login
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk menyimpan/menghapus session user
use Illuminate\Support\Facades\URL; // Mengimpor URL untuk redirect ke halaman sebelumnya

class LoginController extends Controller // Mendefinisikan controller Login yang mewarisi Controller Laravel
{
    // menampilkan halaman login ke pengguna // Komentar penjelas fungsi method index
    public function index(): View // Method untuk menampilkan halaman login
    {
        $data['title'] = 'Login'; // Menetapkan judul halaman login
        $data['web']   = WebModel::first(); // Mengambil data pertama dari tabel web (biasanya untuk logo/identitas web)

        return view('Admin.Login.index', $data); // Mengembalikan view login beserta data yang dibutuhkan
    }

    public function proseslogin(Request $request): RedirectResponse // Method untuk memproses login dari form (validasi user/password)
    {
        /** @var string|null $username */ // Anotasi tipe username agar aman untuk static analyzer
        $username = $request->input('user'); // Mengambil input username dari field 'user'

        /** @var string|null $password */ // Anotasi tipe password agar aman untuk static analyzer
        $password = $request->input('pwd'); // Mengambil input password dari field 'pwd'

        // Membuat array $where untuk kondisi pencarian di database // Komentar penanda kondisi query login
        $where = [ // Array kondisi untuk query UserModel::where()
            // kalau null → jadi '', sama seperti sebelumnya PHP auto-cast null ke string kosong // Komentar penjelasan fallback null
            'tbl_user.user_nama'     => $username ?? '', // Kondisi username sesuai input (jika null jadi string kosong)
            'tbl_user.user_password' => md5($password ?? ''), // Kondisi password yang sudah di-hash MD5 (jika null jadi hash dari string kosong)
        ]; // Menutup array where

        $getCount = UserModel::where($where)->count(); // Menghitung jumlah user yang cocok dengan username+password (hasilnya 0 atau lebih)

        // user ditemukan // Komentar penanda jika login berhasil
        if ($getCount > 0) { // Jika data user ditemukan (berarti username/password cocok)

            /** @var UserModel $query */ // Anotasi tipe $query agar jelas merupakan object user
            $query = UserModel::leftJoin('tbl_role', 'tbl_role.role_id', '=', 'tbl_user.role_id') // Join user dengan role untuk mendapatkan info role
                ->select() // Mengambil semua kolom (default)
                ->where($where) // Mencari user sesuai kondisi username+password
                ->first(); // Mengambil 1 data user pertama yang cocok

            // Ambil semua data hak akses yang dimiliki oleh role user tersebut. // Komentar penjelasan ambil hak akses role
            $role = AksesModel::where('role_id', '=', $query->role_id)->get(); // Mengambil daftar hak akses berdasarkan role_id user

            $request->session()->put('user', $query);      // Simpan data lengkap user ke session 'user' (dipakai untuk identitas login)
            $request->session()->put('user_role', $role);  // Simpan data hak akses ke session 'user_role' (dipakai untuk cek permission)

            Session::flash('status', 'success'); // Membuat flash session status sukses untuk ditampilkan sekali
            Session::flash('msg', 'Selamat Datang ' . $query->user_nmlengkap); // Membuat flash session pesan selamat datang

            // redirect to index // Komentar penanda redirect setelah login
            return redirect(URL::previous()); // Redirect ke halaman sebelumnya (biasanya kembali ke halaman yang diminta)
        } // Menutup kondisi user ditemukan

        // user tidak ditemukan / password salah // Komentar penanda jika login gagal
        Session::flash('status', 'error'); // Membuat flash session status error
        Session::flash('msg', 'User password tidak cocok!'); // Membuat flash session pesan error login
        Session::flash('userInput', $username ?? ''); // Menyimpan input username agar form bisa diisi kembali otomatis

        // redirect to index // Komentar penanda redirect setelah login gagal
        return redirect(URL::previous()); // Redirect kembali ke halaman sebelumnya (halaman login)
    }

    public function logout(): RedirectResponse // Method untuk proses logout user
    {
        Session::forget('user'); // Menghapus session 'user' agar user dianggap logout
        Session::forget('user_role'); // Menghapus session 'user_role' agar hak akses juga hilang

        // redirect to index // Komentar penanda redirect setelah logout
        return redirect(URL::previous()); // Redirect ke halaman sebelumnya setelah logout
    }
} // Penutup class LoginController
