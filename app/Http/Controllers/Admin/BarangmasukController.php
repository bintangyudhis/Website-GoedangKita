<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesModel;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\CustomerModel;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class BarangmasukController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\Admin\UserModel|null $user */
        $user = Session::get('user');

        $data['title'] = 'Barang Masuk';

        $data['hakTambah'] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
            ->where([
                'tbl_akses.role_id' => $user?->role_id,
                'tbl_submenu.submenu_judul' => 'Barang Masuk',
                'tbl_akses.akses_type' => 'create',
            ])
            ->count();

        $data['customer'] = CustomerModel::orderBy('customer_id', 'DESC')->get();

        return view('Admin.BarangMasuk.index', $data);
    }

    public function show(Request $request): JsonResponse
    {
        if ($request->ajax()) {

            /** @var \App\Models\Admin\UserModel|null $user */
            $user = Session::get('user');

            $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                ->orderBy('bm_id', 'DESC')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    return $row->bm_tanggal == '' ? '-' : Carbon::parse($row->bm_tanggal)->translatedFormat('d F Y');
                })
                ->addColumn('customer', function ($row) {
                    return $row->customer_id == '' ? '-' : $row->customer_nama;
                })
                ->addColumn('barang', function ($row) {
                    return $row->barang_id == '' ? '-' : $row->barang_nama;
                })
                ->addColumn('action', function ($row) use ($user) {

                    $array = [
                        'bm_id' => $row->bm_id,
                        'bm_kode' => $row->bm_kode,
                        'barang_kode' => $row->barang_kode,
                        'customer_id' => $row->customer_id,
                        'bm_tanggal' => $row->bm_tanggal,
                        'bm_jumlah' => $row->bm_jumlah,
                    ];

                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
                        ->where([
                            'tbl_akses.role_id' => $user?->role_id,
                            'tbl_submenu.submenu_judul' => 'Barang Masuk',
                            'tbl_akses.akses_type' => 'update',
                        ])
                        ->count();

                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id')
                        ->where([
                            'tbl_akses.role_id' => $user?->role_id,
                            'tbl_submenu.submenu_judul' => 'Barang Masuk',
                            'tbl_akses.akses_type' => 'delete',
                        ])
                        ->count();

                    $button = '';

                    if ($hakEdit > 0 && $hakDelete > 0) {
                        $button .= '
                            <div class="g-2">
                                <a class="btn modal-effect text-primary btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Umodaldemo8"
                                onclick=update('.json_encode($array).')>
                                <span class="fe fe-edit text-success fs-14"></span></a>

                                <a class="btn modal-effect text-danger btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Hmodaldemo8"
                                onclick=hapus('.json_encode($array).')>
                                <span class="fe fe-trash-2 fs-14"></span></a>
                            </div>';
                    } elseif ($hakEdit > 0) {
                        $button .= '
                            <div class="g-2">
                                <a class="btn modal-effect text-primary btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Umodaldemo8"
                                onclick=update('.json_encode($array).')>
                                <span class="fe fe-edit text-success fs-14"></span></a>
                            </div>';
                    } elseif ($hakDelete > 0) {
                        $button .= '
                            <div class="g-2">
                                <a class="btn modal-effect text-danger btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Hmodaldemo8"
                                onclick=hapus('.json_encode($array).')>
                                <span class="fe fe-trash-2 fs-14"></span></a>
                            </div>';
                    } else {
                        $button = '-';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'tgl', 'customer', 'barang'])
                ->make(true);
        }

        return response()->json([]);
    }

    public function proses_tambah(Request $request): JsonResponse
    {
        BarangmasukModel::create([
            'bm_tanggal' => $request->tglmasuk,
            'bm_kode' => $request->bmkode,
            'barang_kode' => $request->barang,
            'customer_id' => $request->customer,
            'bm_jumlah' => $request->jml,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_ubah(Request $request, BarangmasukModel $barangmasuk): JsonResponse
    {
        $barangmasuk->update([
            'bm_tanggal' => $request->tglmasuk,
            'barang_kode' => $request->barang,
            'customer_id' => $request->customer,
            'bm_jumlah' => $request->jml,
        ]);

        return response()->json(['success' => 'Berhasil']);
    }

    public function proses_hapus(Request $request, BarangmasukModel $barangmasuk): JsonResponse
    {
        $barangmasuk->delete();

        return response()->json(['success' => 'Berhasil']);
    }
}
