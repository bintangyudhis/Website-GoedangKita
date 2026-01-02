<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Admin\MenuModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    public function index(): View
    {
        /** @var object|null $user */
        $user   = Session::get('user');
        $roleId = $user?->role_id;

        // Ambil semua menu dari tbl_menu, urut berdasarkan menu_sort
        $data = MenuModel::orderBy('menu_sort', 'ASC')->get();

        return view('Master.Menu.index', [
            'title'   => 'Menu',
            'data'    => $data,
            'role_id' => $roleId,
        ]);
    }

    public function sortup(int $sort): RedirectResponse
    {
        // Data di posisi sekarang
        $data = MenuModel::where('menu_sort', $sort)->first();
        // Data di posisi sebelumnya
        $databack = MenuModel::where('menu_sort', $sort - 1)->first();

        if ($data && $databack) {
            // Tukar urutan sort
            MenuModel::where('menu_id', $data->menu_id)->update([
                'menu_sort' => $sort - 1,
            ]);

            MenuModel::where('menu_id', $databack->menu_id)->update([
                'menu_sort' => $sort,
            ]);
        }

        return redirect('/admin/menu')->with('pesan', 'Perubahan berhasil diterapkan.');
    }

    public function sortdown(int $sort): RedirectResponse
    {
        // Data di posisi sekarang
        $data = MenuModel::where('menu_sort', $sort)->first();
        // Data di posisi sesudahnya
        $dataforward = MenuModel::where('menu_sort', $sort + 1)->first();

        if ($data && $dataforward) {
            // Tukar urutan sort
            MenuModel::where('menu_id', $data->menu_id)->update([
                'menu_sort' => $sort + 1,
            ]);

            MenuModel::where('menu_id', $dataforward->menu_id)->update([
                'menu_sort' => $sort,
            ]);
        }

        return redirect('/admin/menu')->with('pesan', 'Perubahan berhasil diterapkan.');
    }

    public function store(Request $request): RedirectResponse
    {
        // Form tambah biasanya pakai: judul, icon, type, redirect
        $request->validate([
            'judul'    => 'required',
            'redirect' => 'required',
            'type'     => 'required|integer',
        ]);

        $judul    = $request->input('judul');
        $redirect = $request->input('redirect');
        $icon     = $request->input('icon');
        $type     = (int) $request->input('type');

        // Ambil menu_sort paling besar, lalu +1
        $maxSort = MenuModel::max('menu_sort');
        $sort    = ($maxSort ?? 0) + 1;

        $slugSource = preg_replace('/[^A-Za-z0-9-]+/', '-', $judul ?? '');
        $slug       = strtolower(trim($slugSource ?? ''));

        MenuModel::create([
            'menu_judul'    => $judul,
            'menu_slug'     => $slug,
            'menu_icon'     => $icon,
            'menu_redirect' => $redirect,
            'menu_sort'     => $sort,
            'menu_type'     => $type, // 1 = menu, 2 = submenu
        ]);

        return redirect('/admin/menu')->with('pesan', 'Data berhasil ditambah.');
    }

    public function update(Request $request): RedirectResponse
    {
        // Form ubah pakai field: menu_id, ujudul, uicon, utype, uredirect
        $request->validate([
            'menu_id'   => 'required|integer',
            'ujudul'    => 'required',
            'uredirect' => 'required',
            'utype'     => 'required|integer',
        ]);

        $id       = (int) $request->input('menu_id');
        $judul    = $request->input('ujudul');
        $redirect = $request->input('uredirect');
        $icon     = $request->input('uicon');
        $type     = (int) $request->input('utype');

        $slugSource = preg_replace('/[^A-Za-z0-9-]+/', '-', $judul ?? '');
        $slug       = strtolower(trim($slugSource ?? ''));

        MenuModel::where('menu_id', $id)->update([
            'menu_judul'    => $judul,
            'menu_slug'     => $slug,
            'menu_icon'     => $icon,
            'menu_redirect' => $redirect,
            'menu_type'     => $type,
        ]);

        return redirect('/admin/menu')->with('pesan', 'Data berhasil diubah.');
    }

    public function hapus(Request $request): RedirectResponse
    {
        // Modal hapus isi input hidden name="idmenu"
        $id = (int) $request->input('idmenu');

        if ($id > 0) {
            MenuModel::where('menu_id', $id)->delete();
        }

        return redirect('/admin/menu')->with('pesan', 'Data berhasil dihapus.');
    }
}
