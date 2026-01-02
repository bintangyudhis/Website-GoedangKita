<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\MenuModel;
use App\Models\Admin\RoleModel;
use App\Models\Admin\SubmenuModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AksesController extends Controller
{
    public function index(string $role): View
    {
        $data['title']      = 'Akses';
        $data['roleid']     = $role === 'role' ? '' : $role;
        $data['detailrole'] = $role === 'role' ? '' : RoleModel::where('role_id', '=', $role)->first();
        $data['role']       = RoleModel::where('role_id', '!=', 1)->latest()->get(); // tidak mengambil super admin
        $data['menu']       = MenuModel::where('menu_type', '=', '1')->orderBy('menu_sort', 'ASC')->get();
        $data['menusub']    = MenuModel::where('menu_type', '=', '2')->orderBy('menu_sort', 'ASC')->get();

        return view('Master.Akses.index', $data);
    }

    // Memberikan satu izin spesifik. Fungsi ini biasanya dipanggil oleh AJAX ketika admin mencentang sebuah checkbox di halaman hak akses.
    public function addAkses(int $idmenu, int $idrole, string $type, string $akses): RedirectResponse
    {
        if ($type === 'menu') {
            // create input menu
            AksesModel::create([
                'menu_id'    => $idmenu,
                'role_id'    => $idrole,
                'akses_type' => $akses,
            ]);
        } elseif ($type === 'submenu') {
            // create input submenu
            AksesModel::create([
                'submenu_id' => $idmenu,
                'role_id'    => $idrole,
                'akses_type' => $akses,
            ]);
        } elseif ($type === 'othermenu') {
            // create input othermenu
            AksesModel::create([
                'othermenu_id' => $idmenu,
                'role_id'      => $idrole,
                'akses_type'   => $akses,
            ]);
        }

        $data['title'] = 'Akses';

        // redirect to index
        return redirect(url('admin/akses/' . $idrole))->with($data);
    }

    // Mencabut satu izin spesifik. Fungsi ini dipanggil ketika admin menghilangkan centang pada sebuah checkbox.
    public function removeAkses(int $idmenu, int $idrole, string $type, string $akses): RedirectResponse
    {
        if ($type === 'menu') {
            AksesModel::where([
                'menu_id'    => $idmenu,
                'role_id'    => $idrole,
                'akses_type' => $akses,
            ])->delete();
        } elseif ($type === 'submenu') {
            AksesModel::where([
                'submenu_id' => $idmenu,
                'role_id'    => $idrole,
                'akses_type' => $akses,
            ])->delete();
        } elseif ($type === 'othermenu') {
            AksesModel::where([
                'othermenu_id' => $idmenu,
                'role_id'      => $idrole,
                'akses_type'   => $akses,
            ])->delete();
        }

        $data['title'] = 'Akses';

        // redirect to index
        return redirect(url('admin/akses/' . $idrole))->with($data);
    }

    public function setAllAkses(int $idrole): RedirectResponse
    {
        AksesModel::where(['role_id' => $idrole])->delete();

        $object1 = [];
        $object2 = [];
        $object3 = [];

        $menu = MenuModel::orderBy('menu_sort', 'ASC')->get();
        foreach ($menu as $m) {
            $object1[] = [
                'menu_id'    => $m->menu_id,
                'role_id'    => $idrole,
                'akses_type' => 'view',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $object1[] = [
                'menu_id'    => $m->menu_id,
                'role_id'    => $idrole,
                'akses_type' => 'create',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $object1[] = [
                'menu_id'    => $m->menu_id,
                'role_id'    => $idrole,
                'akses_type' => 'update',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $object1[] = [
                'menu_id'    => $m->menu_id,
                'role_id'    => $idrole,
                'akses_type' => 'delete',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $submenu = SubmenuModel::orderBy('submenu_sort', 'ASC')->get();
        foreach ($submenu as $sb) {
            $object2[] = [
                'submenu_id' => $sb->submenu_id,
                'role_id'    => $idrole,
                'akses_type' => 'view',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $object2[] = [
                'submenu_id' => $sb->submenu_id,
                'role_id'    => $idrole,
                'akses_type' => 'create',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $object2[] = [
                'submenu_id' => $sb->submenu_id,
                'role_id'    => $idrole,
                'akses_type' => 'update',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $object2[] = [
                'submenu_id' => $sb->submenu_id,
                'role_id'    => $idrole,
                'akses_type' => 'delete',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        for ($i = 1; $i <= 6; $i++) {
            $object3[] = [
                'othermenu_id' => $i,
                'role_id'      => $idrole,
                'akses_type'   => 'view',
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }
        for ($i = 1; $i <= 6; $i++) {
            $object3[] = [
                'othermenu_id' => $i,
                'role_id'      => $idrole,
                'akses_type'   => 'create',
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }
        for ($i = 1; $i <= 6; $i++) {
            $object3[] = [
                'othermenu_id' => $i,
                'role_id'      => $idrole,
                'akses_type'   => 'update',
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }
        for ($i = 1; $i <= 6; $i++) {
            $object3[] = [
                'othermenu_id' => $i,
                'role_id'      => $idrole,
                'akses_type'   => 'delete',
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        AksesModel::insert($object1);
        AksesModel::insert($object2);
        AksesModel::insert($object3);

        $data['title'] = 'Akses';

        // redirect to index
        return redirect(url('admin/akses/' . $idrole))->with($data);
    }

    public function unsetAllAkses(int $idrole): RedirectResponse
    {
        AksesModel::where(['role_id' => $idrole])->delete();

        $data['title'] = 'Akses';

        // redirect to index
        return redirect(url('admin/akses/' . $idrole))->with($data);
    }
}
