<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\BarangkeluarModel; // Mengimpor model Barang Keluar untuk mengambil data laporan
use App\Models\Admin\WebModel; // Mengimpor model Web untuk mengambil informasi web (profil/setting laporan)
use Carbon\Carbon; // Mengimpor Carbon untuk parsing dan format tanggal
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input/filter dari user
use Illuminate\Http\Response; // Mengimpor Response untuk type hint response download PDF
use PDF; // Mengimpor facade/library PDF untuk generate file PDF dari view
use Yajra\DataTables\DataTables; // Mengimpor DataTables untuk response tabel AJAX

class LapBarangKeluarController extends Controller // Mendefinisikan controller Laporan Barang Keluar
{
    public function index(): View // Method untuk menampilkan halaman filter/daftar laporan barang keluar
    {
        $data['title'] = 'Lap Barang Keluar'; // Menetapkan judul halaman laporan barang keluar

        return view('Admin.Laporan.BarangKeluar.index', $data); // Mengembalikan view halaman laporan barang keluar
    }

    public function print(Request $request): View // Method untuk menampilkan halaman print laporan (HTML print) berdasarkan filter tanggal
    {
        if ($request->tglawal) { // Mengecek apakah user mengisi tanggal awal (berarti memakai filter tanggal)
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan master barang untuk dapat nama barang
                ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir]) // Filter data berdasarkan rentang tanggal keluar
                ->orderBy('bk_id', 'DESC') // Urutkan data berdasarkan id terbaru
                ->get(); // Ambil semua data hasil query
        } else { // Jika tidak ada tanggal awal (berarti ambil semua data)
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan master barang
                ->orderBy('bk_id', 'DESC') // Urutkan data berdasarkan id terbaru
                ->get(); // Ambil semua data tanpa filter tanggal
        } // Menutup if-else filter print

        $data['title']   = 'Print Barang Masuk'; // Menetapkan judul halaman print (catatan: teksnya "Barang Masuk" sesuai kode asli)
        $data['web']     = WebModel::first(); // Mengambil data web/setting pertama (misal nama instansi, alamat, dsb.)
        $data['tglawal'] = $request->tglawal; // Menyimpan nilai tanggal awal untuk ditampilkan di view
        $data['tglakhir'] = $request->tglakhir; // Menyimpan nilai tanggal akhir untuk ditampilkan di view

        return view('Admin.Laporan.BarangKeluar.print', $data); // Mengembalikan view print laporan barang keluar
    }

    public function pdf(Request $request): Response // Method untuk generate dan download laporan barang keluar dalam bentuk PDF
    {
        if ($request->tglawal) { // Mengecek apakah user mengisi tanggal awal (pakai filter tanggal)
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan master barang
                ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir]) // Filter data sesuai rentang tanggal
                ->orderBy('bk_id', 'DESC') // Urutkan data terbaru
                ->get(); // Ambil data hasil query
        } else { // Jika tidak ada filter tanggal
            $data['data'] = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan master barang
                ->orderBy('bk_id', 'DESC') // Urutkan data terbaru
                ->get(); // Ambil semua data tanpa filter tanggal
        } // Menutup if-else filter pdf

        $data['title']   = 'PDF Barang Masuk'; // Menetapkan judul halaman PDF (catatan: teksnya "Barang Masuk" sesuai kode asli)
        $data['web']     = WebModel::first(); // Mengambil data web/setting untuk header laporan PDF
        $data['tglawal'] = $request->tglawal; // Menyimpan tanggal awal untuk ditampilkan di PDF
        $data['tglakhir'] = $request->tglakhir; // Menyimpan tanggal akhir untuk ditampilkan di PDF

        $pdf = PDF::loadView('Admin.Laporan.BarangKeluar.pdf', $data); // Membuat file PDF dari view dengan data yang sudah disiapkan

        if ($request->tglawal) { // Jika pakai filter tanggal, nama file dibuat berdasarkan rentang tanggal
            return $pdf->download('lap-bk-' . $request->tglawal . '-' . $request->tglakhir . '.pdf'); // Download PDF dengan nama file berisi tanggal awal-akhir
        } // Menutup kondisi nama file berdasarkan tanggal

        return $pdf->download('lap-bk-semua-tanggal.pdf'); // Jika tanpa filter, download PDF dengan nama file umum
    }

    public function show(Request $request): ?JsonResponse // Method untuk menampilkan data laporan ke DataTables via AJAX, return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request ini bukan AJAX
            return null; // Jika bukan AJAX, return null agar tidak memproses DataTables
        } // Menutup kondisi bukan AJAX

        if ($request->tglawal == '') { // Jika tanggal awal kosong (tidak filter tanggal)
            $data = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan master barang
                ->orderBy('bk_id', 'DESC') // Urutkan data terbaru
                ->get(); // Ambil semua data
        } else { // Jika tanggal awal diisi (pakai filter rentang tanggal)
            $data = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan master barang
                ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir]) // Filter data berdasarkan rentang tanggal
                ->orderBy('bk_id', 'DESC') // Urutkan data terbaru
                ->get(); // Ambil data hasil filter
        } // Menutup if-else filter show

        return DataTables::of($data) // Membuat DataTables dari data collection
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('tgl', function ($row) { // Menambahkan kolom tgl untuk menampilkan tanggal keluar yang diformat
                $tgl = $row->bk_tanggal == '' ? '-' : Carbon::parse($row->bk_tanggal)->translatedFormat('d F Y'); // Jika kosong tampil '-', jika ada format tanggal Indonesia

                return $tgl; // Mengembalikan nilai kolom tgl
            }) // Menutup addColumn tgl
            ->addColumn('tujuan', function ($row) { // Menambahkan kolom tujuan untuk menampilkan tujuan barang keluar
                $tujuan = $row->bk_tujuan == '' ? '-' : $row->bk_tujuan; // Jika kosong tampil '-', jika ada tampil tujuan

                return $tujuan; // Mengembalikan nilai kolom tujuan
            }) // Menutup addColumn tujuan
            ->addColumn('barang', function ($row) { // Menambahkan kolom barang untuk menampilkan nama barang
                $barang = $row->barang_id == '' ? '-' : $row->barang_nama; // Jika barang tidak ditemukan tampil '-', jika ada tampil nama barang

                return $barang; // Mengembalikan nilai kolom barang
            }) // Menutup addColumn barang
            ->rawColumns(['tgl', 'tujuan', 'barang']) // Menandai kolom (jika ada HTML) agar tidak di-escape (meski di sini hanya teks)
            ->make(true); // Menghasilkan response JSON untuk DataTables
    }
} // Penutup class LapBarangKeluarController
