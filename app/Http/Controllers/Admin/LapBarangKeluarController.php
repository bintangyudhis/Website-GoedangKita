<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BarangkeluarModel;
use App\Models\Admin\WebModel;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDF;
use Yajra\DataTables\DataTables;

class LapBarangKeluarController extends Controller
{
    public function index(): View
    {
        $data['title'] = 'Lap Barang Keluar';

        return view('Admin.Laporan.BarangKeluar.index', $data);
    }

    public function print(Request $request): View
    {
        if ($request->tglawal) {
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                ->orderBy('bk_id', 'DESC')
                ->get();
        } else {
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->orderBy('bk_id', 'DESC')
                ->get();
        }

        $data['title']   = 'Print Barang Masuk';
        $data['web']     = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;

        return view('Admin.Laporan.BarangKeluar.print', $data);
    }

    public function pdf(Request $request): Response
    {
        if ($request->tglawal) {
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                ->orderBy('bk_id', 'DESC')
                ->get();
        } else {
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->orderBy('bk_id', 'DESC')
                ->get();
        }

        $data['title']   = 'PDF Barang Masuk';
        $data['web']     = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;

        $pdf = PDF::loadView('Admin.Laporan.BarangKeluar.pdf', $data);

        if ($request->tglawal) {
            return $pdf->download('lap-bk-' . $request->tglawal . '-' . $request->tglakhir . '.pdf');
        }

        return $pdf->download('lap-bk-semua-tanggal.pdf');
    }

    public function show(Request $request): ?JsonResponse
    {
        if (! $request->ajax()) {
            return null;
        }

        if ($request->tglawal == '') {
            $data = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->orderBy('bk_id', 'DESC')
                ->get();
        } else {
            $data = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir])
                ->orderBy('bk_id', 'DESC')
                ->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tgl', function ($row) {
                $tgl = $row->bk_tanggal == '' ? '-' : Carbon::parse($row->bk_tanggal)->translatedFormat('d F Y');

                return $tgl;
            })
            ->addColumn('tujuan', function ($row) {
                $tujuan = $row->bk_tujuan == '' ? '-' : $row->bk_tujuan;

                return $tujuan;
            })
            ->addColumn('barang', function ($row) {
                $barang = $row->barang_id == '' ? '-' : $row->barang_nama;

                return $barang;
            })
            ->rawColumns(['tgl', 'tujuan', 'barang'])
            ->make(true);
    }
}
