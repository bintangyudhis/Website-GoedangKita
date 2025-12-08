<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\MerkModel;
use App\Models\Admin\UserModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class MerkController extends Controller
{
    public function index(): View
    {
        /** @var UserModel|null $user */
        $user   = Session::get('user');
        $roleId = $user?->role_id;

        $data['title']     = 'Merk';
        $data['hakTambah'] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
            ->where([
                'tbl_akses.role_id'         => $roleId,
                'tbl_submenu.submenu_judul' => 'Merk',
                'tbl_akses.akses_type'      => 'create',
            ])
            ->count();

        return view('Admin.Merk.index', $data);
    }

    public function show(Request $request): ?JsonResponse
    {
        if (! $request->ajax()) {
            return null;
        }

        $data = MerkModel::orderBy('merk_id', 'DESC')->get();

        /** @var UserModel|null $user */
        $user   = Session::get('user');
        $roleId = $user?->role_id;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('ket', function ($row) {
                $ket = $row->merk_keterangan == '' ? '-' : $row->merk_keterangan;

                return $ket;
            })
            ->addColumn('action', function ($row) use ($roleId) {
                /** @var string|null $nama */
                $nama = $row->merk_nama;

                /** @var string|null $ket */
                $ket = $row->merk_keterangan;

                $namaSlugSource = preg_replace(
                    '/[^A-Za-z0-9-]+/',
                    '_',
                    $nama ?? ''
                );
                $namaSlug = trim($namaSlugSource ?? '');

                $ketSlugSource = preg_replace(
                    '/[^A-Za-z0-9-]+/',
                    '_',
                    $ket ?? ''
                );
                $ketSlug = trim($ketSlugSource ?? '');

                $array = [
                    'merk_id'        => $row->merk_id,
                    'merk_nama'      => $namaSlug,
                    'merk_keterangan'=> $ketSlug,
                ];

                $button  = '';
                $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
                    ->where([
                        'tbl_akses.role_id'         => $roleId,
                        'tbl_submenu.submenu_judul' => 'Merk',
                        'tbl_akses.akses_type'      => 'update',
                    ])
                    ->count();

                $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
                    ->where([
                        'tbl_akses.role_id'         => $roleId,
                        'tbl_submenu.submenu_judul' => 'Merk',
                        'tbl_akses.akses_type'      => 'delete',
                    ])
                    ->count();

                if ($hakEdit > 0 && $hakDelete > 0) {
                    $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip"
                           data-bs-original-title="Edit"
                           onclick=update(' . json_encode($array) . ')>
                           <span class="fe fe-edit text-success fs-14"></span>
                        </a>
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                        </div>
                    ';
                } elseif ($hakEdit > 0 && $hakDelete == 0) {
                    $button .= '
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled"
                               data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip"
                               data-bs-original-title="Edit"
                               onclick=update(' . json_encode($array) . ')>
                               <span class="fe fe-edit text-success fs-14"></span>
                            </a>
                        </div>
                    ';
                } elseif ($hakEdit == 0 && $hakDelete > 0) {
                    $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                        </div>
                    ';
                } else {
                    $button .= '-';
                }

                return $button;
            })
            ->rawColumns(['action', 'ket'])
            ->make(true);
    }

    public function proses_tambah(Request $request): JsonResponse
    {
        /** @var string|null $rawMerk */
        $rawMerk = $request->input('merk');

        $slugSource = preg_replace(
            '/[^A-Za-z0-9-]+/',
            '-',
            $rawMerk ?? ''
        );
        $slug = strtolower(trim($slugSource ?? ''));

        // insert data
        MerkModel::create([
            'merk_nama'       => $request->merk,
            'merk_slug'       => $slug,
            'merk_keterangan' => $request->ket,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_ubah(Request $request, MerkModel $merk): JsonResponse
    {
        /** @var string|null $rawMerk */
        $rawMerk = $request->input('merk');

        $slugSource = preg_replace(
            '/[^A-Za-z0-9-]+/',
            '-',
            $rawMerk ?? ''
        );
        $slug = strtolower(trim($slugSource ?? ''));

        // update data
        $merk->update([
            'merk_nama'       => $request->merk,
            'merk_slug'       => $slug,
            'merk_keterangan' => $request->ket,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_hapus(Request $request, MerkModel $merk): JsonResponse
    {
        // delete
        $merk->delete();

        return response()->json(['success' => 'Berhasil']);
    }
}
