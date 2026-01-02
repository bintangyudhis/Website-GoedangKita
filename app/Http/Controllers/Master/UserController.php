<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Admin\RoleModel;
use App\Models\Admin\UserModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(): View
    {
        $data['title'] = 'User';
        $data['role']  = RoleModel::where('role_id', '!=', 1)->latest()->get(); // tidak mengambil role super admin

        return view('Master.User.index', $data);
    }

    public function profile(UserModel $user): View
    {
        $data['title'] = 'Profile';
        $data['data']  = UserModel::leftJoin('tbl_role', 'tbl_role.role_id', '=', 'tbl_user.role_id')
            ->select()
            ->where('tbl_user.user_id', '=', $user->user_id)
            ->first();

        return view('Master.User.profile', $data);
    }

    public function show(Request $request): ?JsonResponse
    {
        if (! $request->ajax()) {
            return null;
        }

        $data = UserModel::leftJoin('tbl_role', 'tbl_role.role_id', '=', 'tbl_user.role_id')
            ->select()
            ->orderBy('user_id', 'DESC')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('img', function ($row) {
                if ($row->user_foto == 'undraw_profile.svg') {
                    $img = '<span class="avatar avatar-lg brround cover-image" data-bs-image-src="' . url('assets/images/users/14.jpg') . '" style="background: url(&quot;' . url('/assets/default/users') . '/' . $row->user_foto . '&quot;) center center;"></span>';
                } else {
                    $img = '<span class="avatar avatar-lg brround cover-image" data-bs-image-src="' . url('assets/images/users/14.jpg') . '" style="background: url(&quot;' . asset('storage/users/' . $row->user_foto) . '&quot;) center center;"></span>';
                }

                return $img;
            })
            ->addColumn('role', function ($row) {
                $badge = '<span class="badge bg-primary badge-sm  me-1 mb-1 mt-1">' . $row->role_title . '</span>';

                return $badge;
            })
            ->addColumn('action', function ($row) {
                // jaga supaya preg_replace subject selalu string
                $userNamaSource = is_string($row->user_nama) ? $row->user_nama : '';
                $userNamaClean  = preg_replace('/[^A-Za-z0-9-]+/', '_', $userNamaSource);
                $userNamaSlug   = trim($userNamaClean ?? '');

                $userNmLengkapSource = is_string($row->user_nmlengkap) ? $row->user_nmlengkap : '';
                $userNmLengkapClean  = preg_replace('/[^A-Za-z0-9-]+/', '_', $userNmLengkapSource);
                $userNmLengkapSlug   = trim($userNmLengkapClean ?? '');

                $array = [
                    'user_id'        => $row->user_id,
                    'user_nama'      => $userNamaSlug,
                    'user_nmlengkap' => $userNmLengkapSlug,
                    'user_foto'      => $row->user_foto,
                    'role_id'        => $row->role_id,
                    'user_email'     => $row->user_email,
                ];

                $button  = '';
                $button .= '
                    <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm"
                           data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                           href="#Umodaldemo8" data-bs-toggle="tooltip"
                           data-bs-original-title="Edit"
                           onclick=update(' . json_encode($array) . ')>
                           <span class="fe fe-edit text-success fs-14"></span>
                        </a>
                        <a class="btn modal-effect text-danger btn-sm"
                           data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                           href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                    </div>
                ';

                return $button;
            })
            ->rawColumns(['action', 'img', 'role'])
            ->make(true);
    }

    public function store(Request $request): RedirectResponse
    {
        // Validasi agar memastikan itu bukan menambahkan user admin
        if ($request->role == 1) {
            Session::flash('status', 'error');
            Session::flash('msg', 'Tidak dapat menambahkan Super Admin baru!');

            return redirect()->route('user.index');
        }

        $img = '';

        // upload image
        if ($request->file('photo') === null) {
            $img = 'undraw_profile.svg';
        } else {
            $image = $request->file('photo');
            $image->storeAs('public/users/', $image->hashName());
            $img = $image->hashName();
        }

        // jaga supaya password selalu string
        $pwdRaw    = $request->input('pwd');
        $pwdString = is_string($pwdRaw) ? $pwdRaw : '';

        // create post
        UserModel::create([
            'user_foto'     => $img,
            'user_nmlengkap' => $request->nmlengkap,
            'user_nama'     => $request->username,
            'user_email'    => $request->email,
            'role_id'       => $request->role,
            'user_password' => md5($pwdString),
        ]);

        $data['title'] = 'User';
        Session::flash('status', 'success');
        Session::flash('msg', 'Berhasil ditambah!');

        // redirect to index
        return redirect()->route('user.index')->with($data);
    }

    public function update(Request $request, UserModel $user): RedirectResponse
    {
        // 1. Mencegah user lain dipromosikan menjadi Super Admin
        if ($user->role_id != 1 && $request->roleU == 1) {
            Session::flash('status', 'error');
            Session::flash('msg', 'Tidak dapat mengubah user lain menjadi Super Admin!');

            return redirect()->route('user.index');
        }

        // 2. Mencegah Super Admin diturunkan rolenya
        if ($user->role_id == 1 && $request->roleU != 1) {
            Session::flash('status', 'error');
            Session::flash('msg', 'Role Super Admin tidak boleh diubah!');

            return redirect()->route('user.index');
        }

        // siapkan password baru (kalau diisi)
        $pwdURaw    = $request->input('pwdU');
        $pwdUString = is_string($pwdURaw) ? $pwdURaw : '';

        // check if image is uploaded
        if ($request->hasFile('photoU')) {

            // upload new image
            $image = $request->file('photoU');
            $image->storeAs('public/users', $image->hashName());

            // delete old image
            Storage::delete('public/users/' . $user->user_foto);

            if ($request->pwd == '') {
                // update post with new image tanpa ubah password
                $user->update([
                    'user_foto'     => $image->hashName(),
                    'user_nmlengkap' => $request->nmlengkapU,
                    'user_nama'     => $request->usernameU,
                    'user_email'    => $request->emailU,
                    'role_id'       => $request->roleU,
                ]);
            } else {
                // update post dengan new image + password baru
                $user->update([
                    'user_foto'     => $image->hashName(),
                    'user_nmlengkap' => $request->nmlengkapU,
                    'user_nama'     => $request->usernameU,
                    'user_email'    => $request->emailU,
                    'role_id'       => $request->roleU,
                    'user_password' => md5($pwdUString),
                ]);
            }
        } else {
            if ($request->pwd == '') {
                // update post without image & tanpa ubah password
                $user->update([
                    'user_nmlengkap' => $request->nmlengkapU,
                    'user_nama'     => $request->usernameU,
                    'user_email'    => $request->emailU,
                    'role_id'       => $request->roleU,
                ]);
            } else {
                // update post tanpa image tapi dengan password baru
                $user->update([
                    'user_nmlengkap' => $request->nmlengkapU,
                    'user_nama'     => $request->usernameU,
                    'user_email'    => $request->emailU,
                    'role_id'       => $request->roleU,
                    'user_password' => md5($pwdUString),
                ]);
            }
        }

        $data['title'] = 'User';
        Session::flash('status', 'success');
        Session::flash('msg', 'Berhasil diubah!');

        // redirect to index
        return redirect()->route('user.index')->with($data);
    }

    public function updatePassword(Request $request, UserModel $user): RedirectResponse
    {
        $currentRaw    = $request->input('currentpassword');
        $currentString = is_string($currentRaw) ? $currentRaw : '';

        $newRaw    = $request->input('newpassword');
        $newString = is_string($newRaw) ? $newRaw : '';

        $checkPassword = UserModel::where([
            'user_id'       => $user->user_id,
            'user_password' => md5($currentString),
        ])->count();

        if ($checkPassword > 0) {
            $user->update([
                'user_password' => md5($newString),
            ]);
            Session::flash('status', 'success');
            Session::flash('msg', 'Password berhasil di ubah!');
        } else {
            Session::flash('status', 'error');
            Session::flash('msg', 'Password saat ini tidak sama dengan password lama!');
            Session::flash('currentpassword', $request->currentpassword);
            Session::flash('newpassword', $request->newpassword);
            Session::flash('confirmpassword', $request->confirmpassword);
        }

        $data['title'] = 'Profile';

        // redirect to index
        return redirect(url('admin/profile/' . $user->user_id))->with($data);
    }

    public function updateProfile(Request $request, UserModel $user): RedirectResponse
    {
        // check if image is uploaded
        if ($request->hasFile('photoU')) {

            // upload new image
            $image = $request->file('photoU');
            $image->storeAs('public/users', $image->hashName());

            // delete old image
            Storage::delete('public/users/' . $user->user_foto);

            // update post with new image
            $user->update([
                'user_foto'     => $image->hashName(),
                'user_nmlengkap' => $request->nmlengkap,
                'user_nama'     => $request->username,
                'user_email'    => $request->email,
            ]);
        } else {
            // update post without image
            $user->update([
                'user_nmlengkap' => $request->nmlengkap,
                'user_nama'     => $request->username,
                'user_email'    => $request->email,
            ]);
        }

        $data['title'] = 'Profile';
        Session::flash('status', 'success');
        Session::flash('msg', 'Profile Berhasil diubah!');

        // redirect to index
        return redirect(url('admin/profile/' . $user->user_id))->with($data);
    }

    public function hapus(Request $request): RedirectResponse
    {
        // pastikan ambil satu baris UserModel, bukan Collection
        $id = $request->input('iduser');

        $detail = UserModel::where('user_id', $id)->firstOrFail();

        // biar tidak bisa hapus super admin
        if ($detail->role_id == 1) {
            Session::flash('status', 'error');
            Session::flash('msg', 'Super Admin tidak dapat dihapus');

            return redirect()->route('user.index');
        }

        // delete image
        Storage::delete('public/users/' . $detail->user_foto);

        // delete post
        $detail->delete();

        $data['title'] = 'User';
        Session::flash('status', 'success');
        Session::flash('msg', 'Berhasil dihapus!');

        // redirect to index
        return redirect()->route('user.index')->with($data);
    }
}
