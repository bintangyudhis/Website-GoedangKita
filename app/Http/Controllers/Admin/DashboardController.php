<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\BarangkeluarModel; // Mengimpor model Barang Keluar untuk mengambil data transaksi keluar
use App\Models\Admin\BarangmasukModel; // Mengimpor model Barang Masuk untuk mengambil data transaksi masuk
use App\Models\Admin\BarangModel; // Mengimpor model Barang untuk mengambil data master barang dan stok awal
use App\Models\Admin\CustomerModel; // Mengimpor model Customer untuk menghitung jumlah customer
use App\Models\Admin\JenisBarangModel; // Mengimpor model Jenis Barang untuk menghitung jumlah jenis barang
use App\Models\Admin\MerkModel; // Mengimpor model Merk untuk menghitung jumlah merk
use App\Models\Admin\SatuanModel; // Mengimpor model Satuan untuk menghitung jumlah satuan
use App\Models\Admin\UserModel; // Mengimpor model User untuk menghitung jumlah user
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view

class DashboardController extends Controller // Mendefinisikan controller Dashboard yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman dashboard (ringkasan data sistem)
    {
        $data['title']    = 'Dashboard'; // Menetapkan judul halaman dashboard
        $data['jenis']    = JenisBarangModel::orderBy('jenisbarang_id', 'DESC')->count(); // Menghitung jumlah jenis barang (urut terbaru, lalu count)
        $data['satuan']   = SatuanModel::orderBy('satuan_id', 'DESC')->count(); // Menghitung jumlah satuan (urut terbaru, lalu count)
        $data['merk']     = MerkModel::orderBy('merk_id', 'DESC')->count(); // Menghitung jumlah merk (urut terbaru, lalu count)
        $data['barang']   = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id') // Join barang dengan jenis barang untuk konsistensi data relasi
            ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id') // Join barang dengan satuan untuk konsistensi data relasi
            ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id') // Join barang dengan merk untuk konsistensi data relasi
            ->orderBy('barang_id', 'DESC') // Mengurutkan berdasarkan barang terbaru
            ->count(); // Menghitung jumlah data barang
        $data['customer'] = CustomerModel::orderBy('customer_id', 'DESC')->count(); // Menghitung jumlah customer (urut terbaru, lalu count)
        $data['bm']       = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join transaksi barang masuk dengan master barang
            ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join transaksi barang masuk dengan customer
            ->orderBy('bm_id', 'DESC') // Mengurutkan transaksi masuk terbaru
            ->count(); // Menghitung jumlah transaksi barang masuk
        $data['bk']       = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join transaksi barang keluar dengan master barang
            ->orderBy('bk_id', 'DESC') // Mengurutkan transaksi keluar terbaru
            ->count(); // Menghitung jumlah transaksi barang keluar
        $data['user']     = UserModel::leftJoin('tbl_role', 'tbl_role.role_id', '=', 'tbl_user.role_id') // Join user dengan role untuk konsistensi relasi role
            ->select() // Memilih semua kolom (default) untuk query user
            ->orderBy('user_id', 'DESC') // Mengurutkan user berdasarkan yang terbaru
            ->count(); // Menghitung jumlah user

        // tambahan untuk menampilkan stok barang // Komentar penanda bagian perhitungan stok total di dashboard
        $stokAwal          = BarangModel::sum('barang_stok'); // Menghitung total stok awal dari tabel barang (sum semua stok)
        $totalMasuk        = BarangmasukModel::sum('bm_jumlah'); // Menghitung total jumlah barang masuk dari seluruh transaksi
        $totalKeluar       = BarangkeluarModel::sum('bk_jumlah'); // Menghitung total jumlah barang keluar dari seluruh transaksi
        $data['total_stok'] = $stokAwal + $totalMasuk - $totalKeluar; // Menghitung stok total = stok awal + masuk - keluar

        return view('Admin.Dashboard.index', $data); // Mengembalikan view dashboard dengan data ringkasan yang sudah disiapkan
    }
} // Penutup class DashboardController
