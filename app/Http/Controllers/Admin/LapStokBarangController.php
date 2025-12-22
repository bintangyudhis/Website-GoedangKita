<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\BarangkeluarModel; // Mengimpor model Barang Keluar untuk menghitung jumlah keluar per barang
use App\Models\Admin\BarangmasukModel; // Mengimpor model Barang Masuk untuk menghitung jumlah masuk per barang
use App\Models\Admin\BarangModel; // Mengimpor model Barang untuk mengambil data master barang dan stok awal
use App\Models\Admin\WebModel; // Mengimpor model Web untuk mengambil informasi web (header laporan)
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil parameter filter dari user
use Symfony\Component\HttpFoundation\BinaryFileResponse; // Mengimpor BinaryFileResponse untuk type hint response download PDF
use PDF; // Mengimpor library/facade PDF untuk generate PDF dari view
use Yajra\DataTables\DataTables; // Mengimpor DataTables untuk response tabel AJAX

class LapStokBarangController extends Controller // Mendefinisikan controller Laporan Stok Barang
{
    public function index(Request $request): View // Method untuk menampilkan halaman laporan stok barang (filter/daftar)
    {
        $data['title'] = 'Lap Stok Barang'; // Menetapkan judul halaman laporan stok barang

        return view('Admin.Laporan.StokBarang.index', $data); // Mengembalikan view halaman laporan stok barang
    }

    public function print(Request $request): View // Method untuk menampilkan halaman print (HTML print) laporan stok barang
    {
        $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id') // Join barang dengan jenis barang agar data relasi tersedia
            ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id') // Join barang dengan satuan
            ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id') // Join barang dengan merk
            ->orderBy('barang_id', 'DESC') // Urutkan berdasarkan barang terbaru
            ->get(); // Ambil semua data barang untuk dicetak

        $data['title']    = 'Print Stok Barang'; // Menetapkan judul halaman print stok barang
        $data['web']      = WebModel::first(); // Mengambil data web/setting untuk header laporan
        $data['tglawal']  = $request->tglawal; // Menyimpan tanggal awal (jika ada) untuk ditampilkan di view
        $data['tglakhir'] = $request->tglakhir; // Menyimpan tanggal akhir (jika ada) untuk ditampilkan di view

        return view('Admin.Laporan.StokBarang.print', $data); // Mengembalikan view print laporan stok barang
    }

    public function pdf(Request $request): BinaryFileResponse // Method untuk generate dan download laporan stok barang dalam bentuk PDF
    {
        $data['data'] = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id') // Join barang dengan jenis barang
            ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id') // Join barang dengan satuan
            ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id') // Join barang dengan merk
            ->orderBy('barang_id', 'DESC') // Urutkan barang terbaru
            ->get(); // Ambil semua data barang untuk PDF

        $data['title']    = 'PDF Stok Barang'; // Menetapkan judul laporan untuk PDF
        $data['web']      = WebModel::first(); // Mengambil data web/setting untuk header PDF
        $data['tglawal']  = $request->tglawal; // Menyimpan tanggal awal untuk informasi periode (jika ada)
        $data['tglakhir'] = $request->tglakhir; // Menyimpan tanggal akhir untuk informasi periode (jika ada)

        $pdf = PDF::loadView('Admin.Laporan.StokBarang.pdf', $data); // Membuat file PDF dari view dan data yang disiapkan

        if ($request->tglawal) { // Jika user memilih periode tanggal, nama file mengikuti rentang tanggal
            /** @var BinaryFileResponse $download */ // Anotasi tipe agar static analyzer paham tipe response
            $download = $pdf->download('lap-stok-' . $request->tglawal . '-' . $request->tglakhir . '.pdf'); // Download PDF dengan nama file berisi tanggal awal-akhir

            return $download; // Mengembalikan response download file
        } // Menutup kondisi periode tanggal

        /** @var BinaryFileResponse $download */ // Anotasi tipe untuk response download
        $download = $pdf->download('lap-stok-semua-tanggal.pdf'); // Jika tanpa periode, download PDF dengan nama file umum

        return $download; // Mengembalikan response download file
    }

    public function show(Request $request): ?JsonResponse // Method untuk menampilkan data stok barang ke DataTables via AJAX, return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request ini bukan AJAX
            return null; // Jika bukan AJAX, return null agar tidak memproses DataTables
        } // Menutup kondisi bukan AJAX

        $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id') // Join barang dengan jenis barang untuk tampilkan informasi jenis
            ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id') // Join barang dengan satuan
            ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id') // Join barang dengan merk
            ->orderBy('barang_id', 'DESC') // Urutkan barang terbaru
            ->get(); // Ambil data barang

        return DataTables::of($data) // Membuat DataTables dari data collection barang
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('stokawal', function ($row) { // Menambahkan kolom stok awal dari master barang
                $result = '<span class="">' . $row->barang_stok . '</span>'; // Membungkus stok awal dengan tag span untuk tampilan

                return $result; // Mengembalikan HTML stok awal
            }) // Menutup addColumn stokawal
            ->addColumn('jmlmasuk', function ($row) use ($request) { // Menambahkan kolom jumlah masuk (mengikuti filter tanggal jika ada)
                if ($request->tglawal == '') { // Jika tidak ada filter tanggal
                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join untuk konsistensi relasi barang masuk
                        ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join customer (tidak wajib untuk sum, tapi mengikuti query asli)
                        ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode) // Filter transaksi masuk per barang_kode
                        ->sum('tbl_barangmasuk.bm_jumlah'); // Menjumlahkan jumlah masuk
                } else { // Jika ada filter periode tanggal
                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join master barang
                        ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join customer
                        ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir]) // Filter transaksi masuk pada rentang tanggal
                        ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode) // Filter berdasarkan barang_kode
                        ->sum('tbl_barangmasuk.bm_jumlah'); // Menjumlahkan jumlah masuk periode tertentu
                } // Menutup if-else jumlah masuk

                $result = '<span class="">' . $jmlmasuk . '</span>'; // Membungkus nilai jumlah masuk dengan span

                return $result; // Mengembalikan HTML jumlah masuk
            }) // Menutup addColumn jmlmasuk
            ->addColumn('jmlkeluar', function ($row) use ($request) { // Menambahkan kolom jumlah keluar (mengikuti filter tanggal jika ada)
                if ($request->tglawal) { // Jika ada filter tanggal
                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join master barang untuk konsistensi
                        ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir]) // Filter transaksi keluar pada rentang tanggal
                        ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode) // Filter transaksi keluar per barang_kode
                        ->sum('tbl_barangkeluar.bk_jumlah'); // Menjumlahkan jumlah keluar
                } else { // Jika tidak ada filter tanggal
                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join master barang
                        ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode) // Filter per barang_kode
                        ->sum('tbl_barangkeluar.bk_jumlah'); // Menjumlahkan jumlah keluar semua tanggal
                } // Menutup if-else jumlah keluar

                $result = '<span class="">' . $jmlkeluar . '</span>'; // Membungkus nilai jumlah keluar dengan span

                return $result; // Mengembalikan HTML jumlah keluar
            }) // Menutup addColumn jmlkeluar
            ->addColumn('totalstok', function ($row) use ($request) { // Menambahkan kolom total stok = stok awal + masuk - keluar (mengikuti filter tanggal jika ada)
                if ($request->tglawal == '') { // Jika tidak ada filter tanggal untuk jumlah masuk
                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join master barang
                        ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join customer
                        ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode) // Filter per barang_kode
                        ->sum('tbl_barangmasuk.bm_jumlah'); // Sum jumlah masuk
                } else { // Jika ada filter tanggal untuk jumlah masuk
                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join master barang
                        ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join customer
                        ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir]) // Filter transaksi masuk pada rentang tanggal
                        ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode) // Filter per barang_kode
                        ->sum('tbl_barangmasuk.bm_jumlah'); // Sum jumlah masuk periode tertentu
                } // Menutup if-else jumlah masuk untuk total stok

                if ($request->tglawal) { // Jika ada filter tanggal untuk jumlah keluar
                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join master barang
                        ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir]) // Filter transaksi keluar pada rentang tanggal
                        ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode) // Filter per barang_kode
                        ->sum('tbl_barangkeluar.bk_jumlah'); // Sum jumlah keluar periode tertentu
                } else { // Jika tidak ada filter tanggal untuk jumlah keluar
                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join master barang
                        ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode) // Filter per barang_kode
                        ->sum('tbl_barangkeluar.bk_jumlah'); // Sum jumlah keluar semua tanggal
                } // Menutup if-else jumlah keluar untuk total stok

                $totalstok = $row->barang_stok + ($jmlmasuk - $jmlkeluar); // Menghitung total stok berdasarkan rumus: stok awal + masuk - keluar
                if ($totalstok == 0) { // Jika total stok sama dengan 0
                    $result = '<span class="">' . $totalstok . '</span>'; // Tampilkan stok 0 dengan style default
                } elseif ($totalstok > 0) { // Jika total stok positif
                    $result = '<span class="text-success">' . $totalstok . '</span>'; // Tampilkan stok positif dengan warna hijau
                } else { // Jika total stok negatif (indikasi data tidak konsisten)
                    $result = '<span class="text-danger">' . $totalstok . '</span>'; // Tampilkan stok negatif dengan warna merah
                } // Menutup kondisi style total stok

                return $result; // Mengembalikan HTML total stok
            }) // Menutup addColumn totalstok
            ->rawColumns(['stokawal', 'jmlmasuk', 'jmlkeluar', 'totalstok']) // Menandai kolom HTML agar tidak di-escape
            ->make(true); // Menghasilkan response JSON untuk DataTables
    }
} // Penutup class LapStokBarangController
