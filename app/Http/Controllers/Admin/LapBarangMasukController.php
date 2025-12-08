<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\WebModel;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDF;
use Yajra\DataTables\DataTables;

class LapBarangMasukController extends Controller
{
    public function index(Request $request): View
    {
        $data['title'] = 'Lap Barang Masuk';

        return view('Admin.Laporan.BarangMasuk.index', $data);
    }

    public function print(Request $request): View
    {
        if ($request->tglawal) {
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])
                ->orderBy('bm_id', 'DESC')
                ->get();
        } else {
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                ->orderBy('bm_id', 'DESC')
                ->get();
        }

        $data['title']   = 'Print Barang Masuk';
        $data['web']     = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;

        return view('Admin.Laporan.BarangMasuk.print', $data);
    }

    public function pdf(Request $request): Response
    {
        if ($request->tglawal) {
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])
                ->orderBy('bm_id', 'DESC')
                ->get();
        } else {
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                ->orderBy('bm_id', 'DESC')
                ->get();
        }

        $data['title']   = 'PDF Barang Masuk';
        $data['web']     = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;

        $pdf = PDF::loadView('Admin.Laporan.BarangMasuk.pdf', $data);

        if ($request->tglawal) {
            return $pdf->download('lap-bm-' . $request->tglawal . '-' . $request->tglakhir . '.pdf');
        }

        return $pdf->download('lap-bm-semua-tanggal.pdf');
    }

    public function show(Request $request): ?JsonResponse
    {
        if (! $request->ajax()) {
            return null;
        }

        if ($request->tglawal == '') {
            $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                ->orderBy('bm_id', 'DESC')
                ->get();
        } else {
            $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])
                ->orderBy('bm_id', 'DESC')
                ->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('tgl', function ($row) {
                $tgl = $row->bm_tanggal == '' ? '-' : Carbon::parse($row->bm_tanggal)->translatedFormat('d F Y');

                return $tgl;
            })
            ->addColumn('customer', function ($row) {
                $customer = $row->customer_id == '' ? '-' : $row->customer_nama;

                return $customer;
            })
            ->addColumn('barang', function ($row) {
                $barang = $row->barang_id == '' ? '-' : $row->barang_nama;

                return $barang;
            })
            ->rawColumns(['tgl', 'customer', 'barang'])
            ->make(true);
    }
}
