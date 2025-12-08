<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\UserModel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class BarangkeluarController extends Controller
{
    public function index(): View
    {
        /** @var UserModel|null $user */
        $user = Session::get('user');

        $data['title'] = 'Barang Keluar';
        $data['hakTambah'] = AksesModel::leftJoin(
            'tbl_submenu',
            'tbl_submenu.submenu_id',
            '=',
            'tbl_akses.submenu_id'
        )->where([
            'tbl_akses.role_id' => $user?->role_id,
            'tbl_submenu.submenu_judul' => 'Barang Keluar',
            'tbl_akses.akses_type' => 'create',
        ])->count();

        return view('Admin.BarangKeluar.index', $data);
    }

    public function show(Request $request): JsonResponse|View
    {
        if ($request->ajax()) {

            $data = BarangkeluarModel::leftJoin(
                'tbl_barang',
                'tbl_barang.barang_kode',
                '=',
                'tbl_barangkeluar.barang_kode'
            )->orderBy('bk_id', 'DESC')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn(
                    'tgl',
                    fn ($row) => $row->bk_tanggal == ''
                        ? '-'
                        : Carbon::parse($row->bk_tanggal)->translatedFormat('d F Y')
                )
                ->addColumn(
                    'tujuan',
                    fn ($row) => $row->bk_tujuan == '' ? '-' : $row->bk_tujuan
                )
                ->addColumn(
                    'barang',
                    fn ($row) => $row->barang_id == '' ? '-' : $row->barang_nama
                )
                ->addColumn('action', function ($row) {

                    /** @var UserModel|null $user */
                    $user = Session::get('user');

                    $array = [
                        'bk_id' => $row->bk_id,
                        'bk_kode' => $row->bk_kode,
                        'barang_kode' => $row->barang_kode,
                        'bk_tanggal' => $row->bk_tanggal,
                        'bk_tujuan' => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->bk_tujuan)),
                        'bk_jumlah' => $row->bk_jumlah,
                    ];

                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
                        ->where([
                            'tbl_akses.role_id' => $user?->role_id,
                            'tbl_submenu.submenu_judul' => 'Barang Keluar',
                            'tbl_akses.akses_type' => 'update',
                        ])->count();

                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
                        ->where([
                            'tbl_akses.role_id' => $user?->role_id,
                            'tbl_submenu.submenu_judul' => 'Barang Keluar',
                            'tbl_akses.akses_type' => 'delete',
                        ])->count();

                    if ($hakEdit > 0 && $hakDelete > 0) {
                        return '
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Umodaldemo8"
                                onclick=update('.json_encode($array).')>
                                <span class="fe fe-edit text-success fs-14"></span>
                            </a>
                            <a class="btn modal-effect text-danger btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Hmodaldemo8"
                                onclick=hapus('.json_encode($array).')>
                                <span class="fe fe-trash-2 fs-14"></span>
                            </a>
                        </div>';
                    }

                    if ($hakEdit > 0) {
                        return '
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Umodaldemo8"
                                onclick=update('.json_encode($array).')>
                                <span class="fe fe-edit text-success fs-14"></span>
                            </a>
                        </div>';
                    }

                    if ($hakDelete > 0) {
                        return '
                        <div class="g-2">
                            <a class="btn modal-effect text-danger btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Hmodaldemo8"
                                onclick=hapus('.json_encode($array).')>
                                <span class="fe fe-trash-2 fs-14"></span>
                            </a>
                        </div>';
                    }

                    return '-';
                })
                ->rawColumns(['action', 'tgl', 'tujuan', 'barang'])
                ->make(true);
        }

        return view('Admin.BarangKeluar.index');
    }

    public function proses_tambah(Request $request): JsonResponse
    {
        BarangkeluarModel::create([
            'bk_tanggal' => $request->tglkeluar,
            'bk_kode' => $request->bkkode,
            'barang_kode' => $request->barang,
            'bk_tujuan' => $request->tujuan,
            'bk_jumlah' => $request->jml,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_ubah(Request $request, BarangkeluarModel $barangkeluar): JsonResponse
    {
        $barangkeluar->update([
            'bk_tanggal' => $request->tglkeluar,
            'bk_kode' => $request->bkkode,
            'barang_kode' => $request->barang,
            'bk_tujuan' => $request->tujuan,
            'bk_jumlah' => $request->jml,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_hapus(Request $request, BarangkeluarModel $barangkeluar): JsonResponse
    {
        $barangkeluar->delete();

        return response()->json(['success' => 'Berhasil']);
    }
}
