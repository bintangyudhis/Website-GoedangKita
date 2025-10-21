<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\WebModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    // menampilkan halaman login ke pengguna
    public function index()
    {
        $data["title"] = "Login";
        $data["web"] = WebModel::first(); // Mengambil data pertama dari tabel web (data logo)
        return view('Admin.Login.index', $data);
    }

    public function proseslogin(Request $request)
    {
        // Membuat array $where untuk kondisi pencarian di database
        $where = array(
            'tbl_user.user_nama' => $request->user, // Mencari user_nama yang cocok dengan input 'user' dari form
            'tbl_user.user_password' => md5($request->pwd) // Mencari user_password yang cocok dengan input 'pwd', SETELAH di-hash dengan md5()
        );
        $getCount = UserModel::where($where)->count(); // Menghitung jumlah user yang cocok dengan kondisi di atas. Hasilnya 0 atau 1.

        // user ditemukan
        if ($getCount > 0) {

            // Ambil data lengkap user tersebut, gabungkan dengan data rolenya.
            $query = UserModel::leftJoin('tbl_role', 'tbl_role.role_id', '=', 'tbl_user.role_id')->select()->where($where)->first();

            // Ambil semua data hak akses yang dimiliki oleh role user tersebut.
            $role = AksesModel::where('role_id', '=', $query->role_id)->get();

            $request->session()->put('user', $query); // Simpan data lengkap user ke dalam session bernama 'user'.
            $request->session()->put('user_role', $role); // Simpan data hak akses user ke dalam session bernama 'user_role'.

            Session::flash('status', 'success');
            Session::flash('msg', 'Selamat Datang ' . $query->user_nmlengkap);

            //redirect to index
            return redirect(URL::previous());
        } else {
            Session::flash('status', 'error');
            Session::flash('msg', 'User password tidak cocok!');
            Session::flash('userInput', $request->user);

            //redirect to index
            return redirect(URL::previous());
        }
    }

    public function logout()
    {
        Session::forget('user');
        Session::forget('user_role');

        //redirect to index
        return redirect(URL::previous());
    }
}
