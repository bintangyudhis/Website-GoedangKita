<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Admin\AppreanceModel;
use App\Models\Admin\UserModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AppreanceController extends Controller
{
    public function index(): View
    {
        /** @var UserModel|null $user */
        $user = Session::get('user');
        $userId = $user?->user_id;

        $data['title'] = 'Tampilan/Tema';
        $data['data'] = AppreanceModel::where('user_id', $userId)->first();

        return view('Master.Appreance.index', $data);
    }

    public function update(string $setting, Request $request): RedirectResponse
    {
        /** @var UserModel|null $user */
        $user = Session::get('user');
        $userId = $user?->user_id;

        $checkUser = AppreanceModel::where('user_id', $userId)->count();

        // -------------------------------
        //  IF USER SETTING EXISTS → UPDATE
        // -------------------------------
        if ($checkUser > 0) {

            if ($setting === 'layout') {
                AppreanceModel::where('user_id', $userId)->update([
                    'appreance_layout' => $request->layout,
                ]);
            } elseif ($setting === 'theme') {

                if ($request->theme === 'light-mode') {
                    AppreanceModel::where('user_id', $userId)->update([
                        'appreance_theme'  => 'light-mode',
                        'appreance_menu'   => 'light-menu',
                        'appreance_header' => 'header-light',
                    ]);
                } elseif ($request->theme === 'dark-mode') {
                    AppreanceModel::where('user_id', $userId)->update([
                        'appreance_theme'  => 'dark-mode',
                        'appreance_menu'   => 'dark-menu',
                        'appreance_header' => 'dark-header',
                    ]);
                }
            } elseif ($setting === 'menu') {
                AppreanceModel::where('user_id', $userId)->update([
                    'appreance_menu' => $request->menu,
                ]);
            } elseif ($setting === 'header') {
                AppreanceModel::where('user_id', $userId)->update([
                    'appreance_header' => $request->header,
                ]);
            } elseif ($setting === 'sidestyle') {
                AppreanceModel::where('user_id', $userId)->update([
                    'appreance_sidestyle' => $request->sidestyle,
                ]);
            }
        }

        // -------------------------------
        //  ELSE → CREATE NEW USER SETTING
        // -------------------------------
        else {

            if ($setting === 'theme') {

                if ($request->theme === 'light-mode') {
                    AppreanceModel::create([
                        'user_id'            => $userId,
                        'appreance_layout'   => 'sidebar-mini',
                        'appreance_theme'    => 'light-mode',
                        'appreance_menu'     => 'light-menu',
                        'appreance_header'   => 'header-light',
                        'appreance_sidestyle'=> 'default-menu',
                    ]);
                } elseif ($request->theme === 'dark-mode') {
                    AppreanceModel::create([
                        'user_id'            => $userId,
                        'appreance_layout'   => 'sidebar-mini',
                        'appreance_theme'    => 'dark-mode',
                        'appreance_menu'     => 'dark-menu',
                        'appreance_header'   => 'header-dark',
                        'appreance_sidestyle'=> 'default-menu',
                    ]);
                }
            } else {
                AppreanceModel::create([
                    'user_id'             => $userId,
                    'appreance_layout'    => $setting === 'layout'    ? $request->layout    : 'sidebar-mini',
                    'appreance_theme'     => $setting === 'theme'     ? $request->theme     : 'light-mode',
                    'appreance_menu'      => $setting === 'menu'      ? $request->menu      : 'light-menu',
                    'appreance_header'    => $setting === 'header'    ? $request->header    : 'header-light',
                    'appreance_sidestyle' => $setting === 'sidestyle' ? $request->sidestyle : 'default-menu',
                ]);
            }
        }

        return redirect(url('admin/appreance/'));
    }
}
