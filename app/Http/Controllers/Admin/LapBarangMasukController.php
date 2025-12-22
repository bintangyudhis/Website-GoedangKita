<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\BarangmasukModel; // Mengimpor model Barang Masuk untuk mengambil data laporan
use App\Models\Admin\WebModel; // Mengimpor model Web untuk data informasi web (header laporan, dll)
use Carbon\Carbon; // Mengimpor Carbon untuk parsing dan format tanggal
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input/filter dari user
use Illuminate\Http\Response; // Mengimpor Response untuk type hint response download PDF
use PDF; // Mengimpor library/facade PDF untuk generate PDF dari view
use Yajra\DataTables\DataTables; // Mengimpor DataTables untuk response tabel AJAX

class LapBarangMasukController extends Controller // Mendefinisikan controller Laporan Barang Masuk
{
    public function index(Request $request): View // Method untuk menampilkan halaman laporan barang masuk (filter/daftar)
    {
        $data['title'] = 'Lap Barang Masuk'; // Menetapkan judul halaman laporan barang masuk

        return view('Admin.Laporan.BarangMasuk.index', $data); // Mengembalikan view halaman laporan barang masuk
    }

    public function print(Request $request): View // Method untuk menampilkan halaman print laporan (HTML print) berdasarkan filter tanggal
    {
        if ($request->tglawal) { // Mengecek apakah user mengisi tanggal awal (berarti memakai filter tanggal)
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan master barang untuk dapat nama barang
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join barang masuk dengan customer untuk dapat nama customer
                ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir]) // Filter berdasarkan rentang tanggal masuk
                ->orderBy('bm_id', 'DESC') // Urutkan berdasarkan id terbaru
                ->get(); // Ambil data hasil query
        } else { // Jika tidak ada tanggal awal (ambil semua data)
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan master barang
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join barang masuk dengan customer
                ->orderBy('bm_id', 'DESC') // Urutkan id terbaru
                ->get(); // Ambil semua data tanpa filter tanggal
        } // Menutup if-else filter print

        $data['title']   = 'Print Barang Masuk'; // Menetapkan judul halaman print laporan
        $data['web']     = WebModel::first(); // Mengambil data web/setting untuk header laporan
        $data['tglawal'] = $request->tglawal; // Menyimpan tanggal awal untuk ditampilkan di view
        $data['tglakhir'] = $request->tglakhir; // Menyimpan tanggal akhir untuk ditampilkan di view

        return view('Admin.Laporan.BarangMasuk.print', $data); // Mengembalikan view print laporan barang masuk
    }

    public function pdf(Request $request): Response // Method untuk generate dan download laporan barang masuk dalam bentuk PDF
    {
        if ($request->tglawal) { // Mengecek apakah user mengisi tanggal awal (pakai filter tanggal)
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan master barang
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join barang masuk dengan customer
                ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir]) // Filter berdasarkan rentang tanggal masuk
                ->orderBy('bm_id', 'DESC') // Urutkan id terbaru
                ->get(); // Ambil data hasil filter
        } else { // Jika tidak memakai filter tanggal
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan master barang
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join barang masuk dengan customer
                ->orderBy('bm_id', 'DESC') // Urutkan id terbaru
                ->get(); // Ambil semua data tanpa filter tanggal
        } // Menutup if-else filter pdf

        $data['title']   = 'PDF Barang Masuk'; // Menetapkan judul untuk file PDF
        $data['web']     = WebModel::first(); // Mengambil data web/setting untuk header PDF
        $data['tglawal'] = $request->tglawal; // Menyimpan tanggal awal untuk ditampilkan di PDF
        $data['tglakhir'] = $request->tglakhir; // Menyimpan tanggal akhir untuk ditampilkan di PDF

        $pdf = PDF::loadView('Admin.Laporan.BarangMasuk.pdf', $data); // Membuat PDF dari view dan data yang sudah disiapkan

        if ($request->tglawal) { // Jika memakai filter tanggal, buat nama file berdasarkan rentang tanggal
            return $pdf->download('lap-bm-' . $request->tglawal . '-' . $request->tglakhir . '.pdf'); // Download PDF dengan nama file berisi tanggal awal-akhir
        } // Menutup kondisi nama file berdasarkan tanggal

        return $pdf->download('lap-bm-semua-tanggal.pdf'); // Jika tanpa filter, download PDF dengan nama file umum
    }

    public function show(Request $request): ?JsonResponse // Method untuk menampilkan data laporan ke DataTables via AJAX, return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request ini bukan AJAX
            return null; // Jika bukan AJAX, return null agar tidak memproses DataTables
        } // Menutup kondisi bukan AJAX

        if ($request->tglawal == '') { // Jika tanggal awal kosong (tidak filter tanggal)
            $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan master barang
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join barang masuk dengan customer
                ->orderBy('bm_id', 'DESC') // Urutkan data terbaru
                ->get(); // Ambil semua data
        } else { // Jika tanggal awal diisi (pakai filter rentang tanggal)
            $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan master barang
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join barang masuk dengan customer
                ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir]) // Filter data berdasarkan rentang tanggal
                ->orderBy('bm_id', 'DESC') // Urutkan data terbaru
                ->get(); // Ambil data hasil filter
        } // Menutup if-else filter show

        return DataTables::of($data) // Membuat DataTables dari data collection
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('tgl', function ($row) { // Menambahkan kolom tgl untuk menampilkan tanggal masuk yang diformat
                $tgl = $row->bm_tanggal == '' ? '-' : Carbon::parse($row->bm_tanggal)->translatedFormat('d F Y'); // Jika kosong tampil '-', jika ada format tanggal Indonesia

                return $tgl; // Mengembalikan nilai kolom tgl
            }) // Menutup addColumn tgl
            ->addColumn('customer', function ($row) { // Menambahkan kolom customer untuk menampilkan nama customer
                $customer = $row->customer_id == '' ? '-' : $row->customer_nama; // Jika customer kosong tampil '-', jika ada tampil nama customer

                return $customer; // Mengembalikan nilai kolom customer
            }) // Menutup addColumn customer
            ->addColumn('barang', function ($row) { // Menambahkan kolom barang untuk menampilkan nama barang
                $barang = $row->barang_id == '' ? '-' : $row->barang_nama; // Jika barang tidak ditemukan tampil '-', jika ada tampil nama barang

                return $barang; // Mengembalikan nilai kolom barang
            }) // Menutup addColumn barang
            ->rawColumns(['tgl', 'customer', 'barang']) // Menandai kolom (jika ada HTML) agar tidak di-escape (di sini hanya teks)
            ->make(true); // Menghasilkan response JSON untuk DataTables
    }
} // Penutup class LapBarangMasukController
