<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Menentukan namespace controller untuk area Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk cek hak akses user
use App\Models\Admin\BarangmasukModel; // Mengimpor model Barang Masuk untuk CRUD dan query data
use App\Models\Admin\CustomerModel; // Mengimpor model Customer untuk mengambil daftar customer
use Carbon\Carbon; // Mengimpor Carbon untuk parsing dan format tanggal
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dari user
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengambil data user login dari session
use Illuminate\View\View; // Mengimpor View untuk type hint response view
use Yajra\DataTables\DataTables; // Mengimpor DataTables untuk membuat response tabel via AJAX

class BarangmasukController extends Controller // Mendefinisikan controller Barang Masuk yang mewarisi Controller
{
    public function index(): View // Method untuk menampilkan halaman utama modul Barang Masuk
    {
        /** @var \App\Models\Admin\UserModel|null $user */ // Anotasi tipe data user dari session
        $user = Session::get('user'); // Mengambil data user login dari session

        $data['title'] = 'Barang Masuk'; // Menetapkan judul halaman

        $data['hakTambah'] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Join akses dengan submenu untuk cek hak tambah
            ->where([ // Menambahkan kondisi filter hak akses
                'tbl_akses.role_id' => $user?->role_id, // Filter berdasarkan role_id user (nullsafe)
                'tbl_submenu.submenu_judul' => 'Barang Masuk', // Filter submenu yang berjudul Barang Masuk
                'tbl_akses.akses_type' => 'create', // Filter tipe akses create (tambah)
            ]) // Menutup where array
            ->count(); // Menghitung jumlah data (jika > 0 berarti punya hak tambah)

        $data['customer'] = CustomerModel::orderBy('customer_id', 'DESC')->get(); // Mengambil daftar customer untuk dropdown, urut terbaru

        return view('Admin.BarangMasuk.index', $data); // Mengembalikan view halaman Barang Masuk beserta data
    }

    public function show(Request $request): JsonResponse // Method untuk menampilkan data Barang Masuk ke DataTables via AJAX (return JSON)
    {
        if ($request->ajax()) { // Mengecek apakah request berasal dari AJAX

            /** @var \App\Models\Admin\UserModel|null $user */ // Anotasi tipe user dari session
            $user = Session::get('user'); // Mengambil data user login dari session untuk cek role/hak akses

            $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan tabel barang untuk dapat nama barang
                ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join barang masuk dengan tabel customer untuk dapat nama customer
                ->orderBy('bm_id', 'DESC') // Urutkan berdasarkan id barang masuk terbaru
                ->get(); // Ambil semua data hasil query

            return DataTables::of($data) // Membuat DataTables dari data collection
                ->addIndexColumn() // Menambahkan kolom index/nomor urut
                ->addColumn('tgl', function ($row) { // Menambahkan kolom tgl untuk menampilkan tanggal masuk yang diformat
                    return $row->bm_tanggal == '' ? '-' : Carbon::parse($row->bm_tanggal)->translatedFormat('d F Y'); // Jika kosong tampil '-', jika ada format tanggal Indonesia
                }) // Menutup addColumn tgl
                ->addColumn('customer', function ($row) { // Menambahkan kolom customer untuk menampilkan nama customer
                    return $row->customer_id == '' ? '-' : $row->customer_nama; // Jika customer kosong tampil '-', jika ada tampil nama customer
                }) // Menutup addColumn customer
                ->addColumn('barang', function ($row) { // Menambahkan kolom barang untuk menampilkan nama barang
                    return $row->barang_id == '' ? '-' : $row->barang_nama; // Jika barang kosong/tidak ada tampil '-', jika ada tampil nama barang
                }) // Menutup addColumn barang
                ->addColumn('action', function ($row) use ($user) { // Menambahkan kolom action untuk tombol edit/hapus berdasarkan hak akses

                    $array = [ // Menyiapkan data untuk dikirim ke fungsi JS update/hapus
                        'bm_id' => $row->bm_id, // ID barang masuk untuk identifikasi record
                        'bm_kode' => $row->bm_kode, // Kode transaksi barang masuk
                        'barang_kode' => $row->barang_kode, // Kode barang yang masuk
                        'customer_id' => $row->customer_id, // ID customer yang terkait
                        'bm_tanggal' => $row->bm_tanggal, // Tanggal barang masuk
                        'bm_jumlah' => $row->bm_jumlah, // Jumlah barang masuk
                    ]; // Menutup array

                    $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak edit
                        ->where([ // Filter hak edit berdasarkan role dan submenu
                            'tbl_akses.role_id' => $user?->role_id, // Role id user
                            'tbl_submenu.submenu_judul' => 'Barang Masuk', // Judul submenu yang dicek
                            'tbl_akses.akses_type' => 'update', // Tipe akses update (edit)
                        ]) // Menutup where
                        ->count(); // Hitung hasil (jika > 0 berarti boleh edit)

                    $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak hapus
                        ->where([ // Filter hak hapus berdasarkan role dan submenu
                            'tbl_akses.role_id' => $user?->role_id, // Role id user
                            'tbl_submenu.submenu_judul' => 'Barang Masuk', // Judul submenu yang dicek
                            'tbl_akses.akses_type' => 'delete', // Tipe akses delete (hapus)
                        ]) // Menutup where
                        ->count(); // Hitung hasil (jika > 0 berarti boleh hapus)

                    $button = ''; // Inisialisasi string HTML tombol action

                    if ($hakEdit > 0 && $hakDelete > 0) { // Jika user punya hak edit dan delete
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
                            </div>'; // Menutup string HTML tombol
                    } elseif ($hakEdit > 0) { // Jika hanya punya hak edit
                        $button .= ' // Menambahkan tombol edit saja
                            <div class="g-2">
                                <a class="btn modal-effect text-primary btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Umodaldemo8"
                                onclick=update('.json_encode($array).')>
                                <span class="fe fe-edit text-success fs-14"></span></a>
                            </div>'; // Menutup string HTML tombol edit
                    } elseif ($hakDelete > 0) { // Jika hanya punya hak delete
                        $button .= ' // Menambahkan tombol hapus saja
                            <div class="g-2">
                                <a class="btn modal-effect text-danger btn-sm"
                                data-bs-effect="effect-super-scaled"
                                data-bs-toggle="modal"
                                href="#Hmodaldemo8"
                                onclick=hapus('.json_encode($array).')>
                                <span class="fe fe-trash-2 fs-14"></span></a>
                            </div>'; // Menutup string HTML tombol hapus
                    } else { // Jika tidak punya hak edit maupun hapus
                        $button = '-'; // Menampilkan '-' sebagai pengganti tombol
                    } // Menutup kondisi hak akses

                    return $button; // Mengembalikan HTML tombol untuk kolom action
                }) // Menutup addColumn action
                ->rawColumns(['action', 'tgl', 'customer', 'barang']) // Menandai kolom yang berisi HTML agar tidak di-escape
                ->make(true); // Menghasilkan response JSON untuk DataTables
        } // Menutup kondisi AJAX

        return response()->json([]); // Jika bukan AJAX, kembalikan JSON kosong agar aman
    }

    public function proses_tambah(Request $request): JsonResponse // Method untuk menyimpan data barang masuk baru
    {
        BarangmasukModel::create([ // Membuat record baru pada tabel barang masuk
            'bm_tanggal' => $request->tglmasuk, // Menyimpan tanggal barang masuk dari form
            'bm_kode' => $request->bmkode, // Menyimpan kode transaksi barang masuk
            'barang_kode' => $request->barang, // Menyimpan kode barang yang masuk
            'customer_id' => $request->customer, // Menyimpan id customer
            'bm_jumlah' => $request->jml, // Menyimpan jumlah barang masuk
        ]); // Menutup create array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_ubah(Request $request, BarangmasukModel $barangmasuk): JsonResponse // Method untuk update data barang masuk melalui model binding
    {
        $barangmasuk->update([ // Update record barang masuk yang dipilih
            'bm_tanggal' => $request->tglmasuk, // Update tanggal barang masuk
            'barang_kode' => $request->barang, // Update kode barang
            'customer_id' => $request->customer, // Update customer
            'bm_jumlah' => $request->jml, // Update jumlah barang masuk
        ]); // Menutup update array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_hapus(Request $request, BarangmasukModel $barangmasuk): JsonResponse // Method untuk menghapus data barang masuk
    {
        $barangmasuk->delete(); // Menghapus record dari database

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }
} // Penutup class BarangmasukController
