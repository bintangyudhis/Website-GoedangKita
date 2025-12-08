<?php

namespace App\Http\Middleware;

use App\Models\Admin\AksesModel;
use App\Models\Admin\UserModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckRoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $menu
     * @param  string  $type
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $menu, string $type)
    {
        /** @var UserModel|null $user */
        $user   = Session::get('user');
        $roleId = $user?->role_id;

        $getMenu = 1;

        if ($type === 'othermenu') {
            $getMenu = AksesModel::where([
                'role_id'      => $roleId,
                'othermenu_id' => $menu,
                'akses_type'   => 'view',
            ])->count();
        } elseif ($type === 'menu') {
            $getMenu = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id')
                ->where([
                    'tbl_akses.role_id'     => $roleId,
                    'tbl_menu.menu_redirect'=> $menu,
                    'tbl_akses.akses_type'  => 'view',
                ])
                ->count();
        } elseif ($type === 'submenu') {
            $getMenu = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
                ->where([
                    'tbl_akses.role_id'        => $roleId,
                    'tbl_submenu.submenu_redirect' => $menu,
                    'tbl_akses.akses_type'     => 'view',
                ])
                ->count();
        }

        if ($getMenu === 0) {
            return abort(404);
        }

        return $next($request);
    }
}
