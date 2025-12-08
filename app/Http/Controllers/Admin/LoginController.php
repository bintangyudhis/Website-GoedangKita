<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\WebModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    // menampilkan halaman login ke pengguna
    public function index(): View
    {
        $data['title'] = 'Login';
        $data['web']   = WebModel::first(); // Mengambil data pertama dari tabel web (data logo)

        return view('Admin.Login.index', $data);
    }

    public function proseslogin(Request $request): RedirectResponse
    {
        /** @var string|null $username */
        $username = $request->input('user');

        /** @var string|null $password */
        $password = $request->input('pwd');

        // Membuat array $where untuk kondisi pencarian di database
        $where = [
            // kalau null → jadi '', sama seperti sebelumnya PHP auto-cast null ke string kosong
            'tbl_user.user_nama'     => $username ?? '',
            'tbl_user.user_password' => md5($password ?? ''),
        ];

        $getCount = UserModel::where($where)->count(); // Hasilnya 0 atau lebih

        // user ditemukan
        if ($getCount > 0) {

            /** @var UserModel $query */
            $query = UserModel::leftJoin('tbl_role', 'tbl_role.role_id', '=', 'tbl_user.role_id')
                ->select()
                ->where($where)
                ->first();

            // Ambil semua data hak akses yang dimiliki oleh role user tersebut.
            $role = AksesModel::where('role_id', '=', $query->role_id)->get();

            $request->session()->put('user', $query);      // Simpan data lengkap user ke session 'user'
            $request->session()->put('user_role', $role);  // Simpan data hak akses ke session 'user_role'

            Session::flash('status', 'success');
            Session::flash('msg', 'Selamat Datang ' . $query->user_nmlengkap);

            // redirect to index
            return redirect(URL::previous());
        }

        // user tidak ditemukan / password salah
        Session::flash('status', 'error');
        Session::flash('msg', 'User password tidak cocok!');
        Session::flash('userInput', $username ?? '');

        // redirect to index
        return redirect(URL::previous());
    }

    public function logout(): RedirectResponse
    {
        Session::forget('user');
        Session::forget('user_role');

        // redirect to index
        return redirect(URL::previous());
    }
}
