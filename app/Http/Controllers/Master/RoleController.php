<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\RoleModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('Master.Role.index', [
            'title' => 'Role',
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        if ($request->ajax()) {
            $data = RoleModel::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    /** @var string|null $roleTitleSlug */
                    $roleTitleSlug = preg_replace(
                        '/[^A-Za-z0-9-]+/',
                        '_',
                        (string) $row->role_title
                    );

                    /** @var string|null $roleDescSlug */
                    $roleDescSlug = preg_replace(
                        '/[^A-Za-z0-9-]+/',
                        '_',
                        (string) $row->role_desc
                    );

                    $array = [
                        'role_id'   => $row->role_id,
                        'role_title'=> trim($roleTitleSlug ?? ''),
                        'role_desc' => trim($roleDescSlug ?? ''),
                    ];

                    if ($row->role_id != 1) {
                        return '
                            <div class="g-2">
                                <a class="btn modal-effect text-primary btn-sm"
                                   data-bs-effect="effect-super-scaled"
                                   data-bs-toggle="modal" href="#Umodaldemo8"
                                   onclick=\'update(' . json_encode($array) . ')\'>
                                   <span class="fe fe-edit text-success fs-14"></span>
                                </a>

                                <a class="btn modal-effect text-danger btn-sm"
                                   data-bs-effect="effect-super-scaled"
                                   data-bs-toggle="modal" href="#Hmodaldemo8"
                                   onclick=\'hapus(' . json_encode($array) . ')\'>
                                   <span class="fe fe-trash-2 fs-14"></span>
                                </a>
                            </div>
                        ';
                    }

                    return '<span class="badge bg-success">Locked</span>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([]);
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var string|null $rawTitle */
        $rawTitle = $request->input('title');

        /** @var string|null $slugSource */
        $slugSource = preg_replace(
            '/[^A-Za-z0-9-]+/',
            '-',
            $rawTitle ?? ''
        );

        $slug = strtolower(trim($slugSource ?? ''));

        RoleModel::create([
            'role_title' => $request->title,
            'role_slug'  => $slug,
            'role_desc'  => $request->desc,
        ]);

        Session::flash('status', 'success');
        Session::flash('msg', 'Berhasil ditambah!');

        return redirect()->route('role.index');
    }

    public function update(Request $request, RoleModel $role): RedirectResponse
    {
        // Role Super Admin tidak boleh diubah
        if ($role->role_id == 1) {
            Session::flash('status', 'error');
            Session::flash('msg', 'Role Super Admin tidak boleh diubah!');

            return redirect()->route('role.index');
        }

        /** @var string|null $rawTitle */
        $rawTitle = $request->input('utitle');

        /** @var string|null $slugSource */
        $slugSource = preg_replace(
            '/[^A-Za-z0-9-]+/',
            '-',
            $rawTitle ?? ''
        );

        $slug = strtolower(trim($slugSource ?? ''));

        $role->update([
            'role_title' => $request->utitle,
            'role_slug'  => $slug,
            'role_desc'  => $request->udesc,
        ]);

        Session::flash('status', 'success');
        Session::flash('msg', 'Berhasil diubah!');

        return redirect()->route('role.index');
    }

    public function hapus(Request $request): RedirectResponse
    {
        // Role Super Admin tidak boleh dihapus
        if ($request->idrole == 1) {
            Session::flash('status', 'error');
            Session::flash('msg', 'Role Super Admin tidak boleh dihapus!');

            return redirect()->route('role.index');
        }

        // findOrFail bisa return model atau collection → paksa ambil first()
        $role = RoleModel::where('role_id', $request->idrole)->firstOrFail();
        $role->delete();

        AksesModel::where('role_id', $request->idrole)->delete();

        Session::flash('status', 'success');
        Session::flash('msg', 'Berhasil dihapus!');

        return redirect()->route('role.index');
    }
}
