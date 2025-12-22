<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk area Admin sesuai struktur folder

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model akses untuk cek hak akses user
use App\Models\Admin\BarangkeluarModel; // Mengimpor model Barang Keluar untuk CRUD dan query data
use App\Models\Admin\UserModel; // Mengimpor model User untuk tipe data session user
use Carbon\Carbon; // Mengimpor Carbon untuk manipulasi/format tanggal
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dan info request
use Illuminate\Support\Facades\Session; // Mengimpor facade Session untuk mengambil data session
use Illuminate\View\View; // Mengimpor View untuk type hint response view
use Yajra\DataTables\DataTables; // Mengimpor DataTables untuk membuat response tabel AJAX

class BarangkeluarController extends Controller // Mendefinisikan controller Barang Keluar yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman utama modul Barang Keluar
    {
        /** @var UserModel|null $user */ // Anotasi tipe user agar IDE/static analyzer paham tipe data
        $user = Session::get('user'); // Mengambil data user yang sedang login dari session

        $data['title'] = 'Barang Keluar'; // Menetapkan judul halaman untuk ditampilkan pada view
        $data['hakTambah'] = AksesModel::leftJoin( // Menghitung hak tambah berdasarkan akses role pada submenu
            'tbl_submenu', // Nama tabel yang akan di-join
            'tbl_submenu.submenu_id', // Kolom join dari tabel submenu
            '=', // Operator join
            'tbl_akses.submenu_id' // Kolom join dari tabel akses
        )->where([ // Menambahkan kondisi filter hak akses
            'tbl_akses.role_id' => $user?->role_id, // Filter berdasarkan role_id user (nullsafe jika user null)
            'tbl_submenu.submenu_judul' => 'Barang Keluar', // Filter submenu yang berjudul Barang Keluar
            'tbl_akses.akses_type' => 'create', // Filter tipe akses create untuk tambah data
        ])->count(); // Menghitung jumlah hasil (jika > 0 berarti boleh tambah)

        return view('Admin.BarangKeluar.index', $data); // Mengembalikan view halaman Barang Keluar dengan data yang sudah disiapkan
    }

    public function show(Request $request): JsonResponse|View // Method untuk menampilkan data ke DataTables (AJAX) atau mengembalikan view jika non-AJAX
    {
        if ($request->ajax()) { // Mengecek apakah request berasal dari AJAX

            $data = BarangkeluarModel::leftJoin( // Mengambil data barang keluar dan join dengan data barang untuk mendapatkan nama barang
                'tbl_barang', // Nama tabel barang
                'tbl_barang.barang_kode', // Kolom join dari tabel barang
                '=', // Operator join
                'tbl_barangkeluar.barang_kode' // Kolom join dari tabel barangkeluar
            )->orderBy('bk_id', 'DESC')->get(); // Mengurutkan data dari yang terbaru lalu mengambil semua hasil

            return DataTables::of($data) // Membuat DataTables dari data collection
                ->addIndexColumn() // Menambahkan kolom index/nomor urut
                ->addColumn( // Menambahkan kolom tgl dengan format tanggal Indonesia
                    'tgl', // Nama kolom baru pada DataTables
                    fn ($row) => $row->bk_tanggal == '' // Jika tanggal kosong
                        ? '-' // Tampilkan tanda '-' jika tidak ada tanggal
                        : Carbon::parse($row->bk_tanggal)->translatedFormat('d F Y') // Format tanggal (contoh: 12 Desember 2025) dengan bahasa terjemahan
                ) // Menutup addColumn tgl
                ->addColumn( // Menambahkan kolom tujuan untuk menampilkan tujuan barang keluar
                    'tujuan', // Nama kolom baru pada DataTables
                    fn ($row) => $row->bk_tujuan == '' ? '-' : $row->bk_tujuan // Jika tujuan kosong tampil '-', jika ada tampil tujuan
                ) // Menutup addColumn tujuan
                ->addColumn( // Menambahkan kolom barang untuk menampilkan nama barang
                    'barang', // Nama kolom baru pada DataTables
                    fn ($row) => $row->barang_id == '' ? '-' : $row->barang_nama // Jika barang tidak ditemukan tampil '-', jika ada tampil nama barang
                ) // Menutup addColumn barang
                ->addColumn('action', function ($row) { // Menambahkan kolom action untuk tombol edit/hapus sesuai hak akses

                    /** @var UserModel|null $user */ // Anotasi tipe user dari session
                    $user = Session::get('user'); // Mengambil user dari session untuk cek role/hak akses

                    $array = [ // Menyiapkan data yang akan dikirim ke fungsi JS update/hapus
                        'bk_id' => $row->bk_id, // ID barang keluar untuk identifikasi record
                        'bk_kode' => $row->bk_kode, // Kode transaksi barang keluar
                        'barang_kode' => $row->barang_kode, // Kode barang yang keluar
                        'bk_tanggal' => $row->bk_tanggal, // Tanggal barang keluar
                        'bk_tujuan' => trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $row->bk_tujuan)), // Tujuan disanitasi agar aman dibawa ke JS/HTML
                        'bk_jumlah' => $row->bk_jumlah, // Jumlah barang keluar
                    ]; // Menutup array data untuk JS

                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query untuk cek hak edit
                        ->where([ // Kondisi hak edit berdasarkan role dan submenu
                            'tbl_akses.role_id' => $user?->role_id, // Role id user
                            'tbl_submenu.submenu_judul' => 'Barang Keluar', // Judul submenu yang dicek
                            'tbl_akses.akses_type' => 'update', // Tipe akses update (edit)
                        ])->count(); // Hitung hasil (jika > 0 berarti boleh edit)

                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query untuk cek hak hapus
                        ->where([ // Kondisi hak hapus berdasarkan role dan submenu
                            'tbl_akses.role_id' => $user?->role_id, // Role id user
                            'tbl_submenu.submenu_judul' => 'Barang Keluar', // Judul submenu yang dicek
                            'tbl_akses.akses_type' => 'delete', // Tipe akses delete (hapus)
                        ])->count(); // Hitung hasil (jika > 0 berarti boleh hapus)

                    if ($hakEdit > 0 && $hakDelete > 0) { // Jika user punya hak edit dan hak delete
                        return ' // Return HTML tombol edit dan hapus
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
                        </div>'; // Menutup string HTML
                    } // Menutup kondisi hak edit+delete

                    if ($hakEdit > 0) { // Jika hanya punya hak edit
                        return ' // Return HTML tombol edit saja
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Umodaldemo8"
                                onclick=update('.json_encode($array).')>
                                <span class="fe fe-edit text-success fs-14"></span>
                            </a>
                        </div>'; // Menutup string HTML
                    } // Menutup kondisi hak edit

                    if ($hakDelete > 0) { // Jika hanya punya hak hapus
                        return ' // Return HTML tombol hapus saja
                        <div class="g-2">
                            <a class="btn modal-effect text-danger btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Hmodaldemo8"
                                onclick=hapus('.json_encode($array).')>
                                <span class="fe fe-trash-2 fs-14"></span>
                            </a>
                        </div>'; // Menutup string HTML
                    } // Menutup kondisi hak delete

                    return '-'; // Jika tidak punya hak edit dan hapus, tampilkan '-'
                }) // Menutup addColumn action
                ->rawColumns(['action', 'tgl', 'tujuan', 'barang']) // Menandai kolom berisi HTML agar tidak di-escape
                ->make(true); // Menghasilkan response JSON DataTables
        } // Menutup kondisi AJAX

        return view('Admin.BarangKeluar.index'); // Jika request bukan AJAX, tampilkan halaman index Barang Keluar
    }

    public function proses_tambah(Request $request): JsonResponse // Method untuk menyimpan data barang keluar baru ke database
    {
        BarangkeluarModel::create([ // Membuat record baru di tabel barang keluar
            'bk_tanggal' => $request->tglkeluar, // Menyimpan tanggal barang keluar dari input form
            'bk_kode' => $request->bkkode, // Menyimpan kode transaksi barang keluar
            'barang_kode' => $request->barang, // Menyimpan kode barang yang keluar
            'bk_tujuan' => $request->tujuan, // Menyimpan tujuan barang keluar
            'bk_jumlah' => $request->jml, // Menyimpan jumlah barang yang keluar
        ]); // Menutup create array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_ubah(Request $request, BarangkeluarModel $barangkeluar): JsonResponse // Method untuk update data barang keluar berdasarkan model binding
    {
        $barangkeluar->update([ // Update record barang keluar yang dipilih
            'bk_tanggal' => $request->tglkeluar, // Update tanggal barang keluar
            'bk_kode' => $request->bkkode, // Update kode transaksi
            'barang_kode' => $request->barang, // Update kode barang
            'bk_tujuan' => $request->tujuan, // Update tujuan
            'bk_jumlah' => $request->jml, // Update jumlah barang keluar
        ]); // Menutup update array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_hapus(Request $request, BarangkeluarModel $barangkeluar): JsonResponse // Method untuk menghapus record barang keluar
    {
        $barangkeluar->delete(); // Menghapus data barang keluar dari database

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }
} // Penutup class BarangkeluarController
