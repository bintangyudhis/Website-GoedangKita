<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Menentukan namespace agar class tidak bentrok dan sesuai struktur folder

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk cek hak akses user
use App\Models\Admin\BarangkeluarModel; // Mengimpor model Barang Keluar untuk perhitungan stok keluar
use App\Models\Admin\BarangmasukModel; // Mengimpor model Barang Masuk untuk perhitungan stok masuk
use App\Models\Admin\BarangModel; // Mengimpor model Barang (tabel barang)
use App\Models\Admin\JenisBarangModel; // Mengimpor model Jenis Barang untuk dropdown/filter
use App\Models\Admin\MerkModel; // Mengimpor model Merk untuk dropdown/filter
use App\Models\Admin\SatuanModel; // Mengimpor model Satuan untuk dropdown/filter
use App\Models\Admin\UserModel; // Mengimpor model User untuk tipe data session user
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint return JSON
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dan info request
use Illuminate\Support\Facades\Session; // Mengimpor facade Session untuk ambil data session
use Illuminate\Support\Facades\Storage; // Mengimpor facade Storage untuk operasi file (hapus/upload)
use Yajra\DataTables\Facades\DataTables; // Mengimpor DataTables untuk response tabel AJAX

class BarangController extends Controller // Mendefinisikan class controller Barang yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman utama modul Barang, return berupa View
    {
        $data['title'] = 'Barang'; // Menetapkan judul halaman yang akan dikirim ke view

        /** @var UserModel|null $user */ // Memberi anotasi tipe data agar IDE/static analyzer paham
        $user   = Session::get('user'); // Mengambil data user dari session
        $roleId = $user?->role_id; // Mengambil role_id user (pakai nullsafe jika user null)

        $data['hakTambah'] = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Join akses dengan submenu untuk cek hak akses berdasarkan judul submenu
            ->where([ // Filter berdasarkan kondisi akses
                'tbl_akses.role_id'         => $roleId, // Cocokkan role user saat ini
                'tbl_submenu.submenu_judul' => 'Barang', // Cocokkan submenu yang bernama Barang
                'tbl_akses.akses_type'      => 'create', // Cek jenis akses create (tambah)
            ]) // Menutup where array
            ->count(); // Hitung jumlah data (jika > 0 berarti punya hak tambah)

        $data['jenisbarang'] = JenisBarangModel::orderBy('jenisbarang_id', 'DESC')->get(); // Ambil daftar jenis barang untuk dropdown, urut terbaru
        $data['satuan']      = SatuanModel::orderBy('satuan_id', 'DESC')->get(); // Ambil daftar satuan untuk dropdown, urut terbaru
        $data['merk']        = MerkModel::orderBy('merk_id', 'DESC')->get(); // Ambil daftar merk untuk dropdown, urut terbaru

        return view('Admin.Barang.index', $data); // Mengembalikan view halaman barang dengan data yang sudah disiapkan
    }

    // Mengambil data detail dari satu barang spesifik berdasarkan kodenya. // Komentar penjelasan fungsi method
    public function getbarang(string $id): string // Method untuk ambil data barang berdasarkan kode, return string JSON
    {
        $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id') // Join tabel barang dengan jenis barang
            ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id') // Join tabel barang dengan satuan
            ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id') // Join tabel barang dengan merk
            ->where('tbl_barang.barang_kode', '=', $id) // Filter berdasarkan kode barang yang dikirim lewat parameter
            ->get(); // Ambil hasil query dalam bentuk collection

        // json_encode bisa return false, jadi dipaksa jadi string supaya cocok dengan return type // Komentar alasan casting string
        return (string) json_encode($data); // Encode data menjadi JSON dan pastikan return berupa string
    }

    public function show(Request $request): ?JsonResponse // Method untuk menampilkan data barang ke DataTables via AJAX, return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request ini berasal dari AJAX
            return null; // Jika bukan AJAX, kembalikan null agar tidak memproses DataTables
        } // Menutup kondisi ajax

        $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id') // Join tabel barang dengan jenis barang
            ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id') // Join tabel barang dengan satuan
            ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id') // Join tabel barang dengan merk
            ->orderBy('barang_id', 'DESC') // Urutkan data berdasarkan id barang terbaru
            ->get(); // Ambil semua data hasil query

        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil user dari session
        $roleId = $user?->role_id; // Mengambil role_id untuk kebutuhan cek hak akses

        return DataTables::of($data) // Membuat DataTables dari data collection
            ->addIndexColumn() // Menambahkan kolom index (nomor urut)
            ->addColumn('img', function ($row) { // Menambahkan kolom custom bernama img untuk menampilkan gambar barang
                $array = [ // Membuat array data yang akan dikirim ke JS saat klik gambar
                    'barang_gambar' => $row->barang_gambar, // Menyimpan nama file gambar dari database
                ]; // Menutup array
                if ($row->barang_gambar == 'image.png') { // Jika gambar default (tidak upload)
                    $img = '<a data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Gmodaldemo8" onclick=gambar(' . json_encode($array) . ')><span class="avatar avatar-lg cover-image" style="background: url(&quot;' . url('/assets/default/barang') . '/' . $row->barang_gambar . '&quot;) center center;"></span></a>'; // HTML untuk menampilkan avatar dengan gambar default dan membuka modal
                } else { // Jika gambar adalah hasil upload user
                    $img = '<a data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Gmodaldemo8" onclick=gambar(' . json_encode($array) . ')><span class="avatar avatar-lg cover-image" style="background: url(&quot;' . asset('storage/barang/' . $row->barang_gambar) . '&quot;) center center;"></span></a>'; // HTML untuk menampilkan avatar dari storage dan membuka modal
                } // Menutup if-else gambar

                return $img; // Mengembalikan HTML gambar untuk kolom img
            }) // Menutup addColumn img
            ->addColumn('jenisbarang', function ($row) { // Menambahkan kolom jenisbarang untuk menampilkan nama jenis barang
                $jenisbarang = $row->jenisbarang_id == '' ? '-' : $row->jenisbarang_nama; // Jika jenisbarang kosong tampil '-', jika ada tampil namanya

                return $jenisbarang; // Mengembalikan nilai untuk kolom jenisbarang
            }) // Menutup addColumn jenisbarang
            ->addColumn('satuan', function ($row) { // Menambahkan kolom satuan untuk menampilkan nama satuan
                $satuan = $row->satuan_id == '' ? '-' : $row->satuan_nama; // Jika satuan kosong tampil '-', jika ada tampil namanya

                return $satuan; // Mengembalikan nilai untuk kolom satuan
            }) // Menutup addColumn satuan
            ->addColumn('merk', function ($row) { // Menambahkan kolom merk untuk menampilkan nama merk
                $merk = $row->merk_id == '' ? '-' : $row->merk_nama; // Jika merk kosong tampil '-', jika ada tampil namanya

                return $merk; // Mengembalikan nilai untuk kolom merk
            }) // Menutup addColumn merk
            ->addColumn('currency', function ($row) { // Menambahkan kolom currency untuk format harga dalam rupiah
                $currency = $row->barang_harga == '' ? '-' : 'Rp ' . number_format($row->barang_harga, 0); // Jika harga kosong tampil '-', jika ada format ribuan

                return $currency; // Mengembalikan harga yang sudah diformat
            }) // Menutup addColumn currency
            ->addColumn('totalstok', function ($row) use ($request) { // Menambahkan kolom totalstok dengan menghitung stok total berdasarkan filter tanggal
                if ($request->tglawal == '') { // Jika tidak ada filter tanggal awal (tidak filter rentang waktu)
                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan barang untuk validasi kode
                        ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join customer (opsional untuk data terkait)
                        ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode) // Filter berdasarkan kode barang saat ini
                        ->sum('tbl_barangmasuk.bm_jumlah'); // Menjumlahkan jumlah barang masuk
                } else { // Jika ada filter tanggal (gunakan rentang tanggal)
                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan barang
                        ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join customer
                        ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir]) // Filter berdasarkan rentang tanggal masuk
                        ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode) // Filter berdasarkan kode barang
                        ->sum('tbl_barangmasuk.bm_jumlah'); // Menjumlahkan barang masuk sesuai filter
                } // Menutup if-else perhitungan barang masuk

                if ($request->tglawal) { // Jika tglawal ada (artinya filter tanggal digunakan)
                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan barang
                        ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir]) // Filter berdasarkan rentang tanggal keluar
                        ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode) // Filter berdasarkan kode barang
                        ->sum('tbl_barangkeluar.bk_jumlah'); // Menjumlahkan barang keluar sesuai filter
                } else { // Jika tidak ada filter tanggal
                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan barang
                        ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode) // Filter berdasarkan kode barang
                        ->sum('tbl_barangkeluar.bk_jumlah'); // Menjumlahkan seluruh barang keluar
                } // Menutup if-else perhitungan barang keluar

                $totalstok = $row->barang_stok + ($jmlmasuk - $jmlkeluar); // Menghitung stok total = stok awal + (masuk - keluar)
                if ($totalstok == 0) { // Jika stok total sama dengan 0
                    $result = '<span class="">' . $totalstok . '</span>'; // Tampilkan stok tanpa warna khusus
                } elseif ($totalstok > 0) { // Jika stok total lebih besar dari 0
                    $result = '<span class="text-success">' . $totalstok . '</span>'; // Tampilkan stok dengan warna hijau
                } else { // Jika stok total kurang dari 0 (indikasi data tidak konsisten/defisit)
                    $result = '<span class="text-danger">' . $totalstok . '</span>'; // Tampilkan stok dengan warna merah
                } // Menutup if-elseif-else tampilan stok

                return $result; // Mengembalikan HTML stok untuk kolom totalstok
            }) // Menutup addColumn totalstok
            ->addColumn('action', function ($row) use ($roleId) { // Menambahkan kolom action (tombol edit/hapus) berdasarkan hak akses
                $barangNamaSlug = preg_replace( // Mengubah nama barang menjadi slug aman untuk dibawa ke JS/HTML
                    '/[^A-Za-z0-9-]+/', // Pola karakter yang tidak diizinkan
                    '_', // Pengganti karakter yang tidak diizinkan
                    (string) $row->barang_nama // Sumber string dari nama barang
                ); // Menutup preg_replace
                $barangNamaSlug = trim($barangNamaSlug ?? ''); // Menghapus spasi di awal/akhir hasil slug

                $array = [ // Menyiapkan data barang untuk dikirim ke fungsi JS update/hapus
                    'barang_id'       => $row->barang_id, // Id barang untuk identifikasi
                    'jenisbarang_id'  => $row->jenisbarang_id, // Id jenis barang
                    'satuan_id'       => $row->satuan_id, // Id satuan
                    'merk_id'         => $row->merk_id, // Id merk
                    'barang_kode'     => $row->barang_kode, // Kode barang
                    'barang_nama'     => $barangNamaSlug, // Nama barang (slug)
                    'barang_harga'    => $row->barang_harga, // Harga barang
                    'barang_stok'     => $row->barang_stok, // Stok barang
                    'barang_gambar'   => $row->barang_gambar, // Nama file gambar
                ]; // Menutup array data barang

                $button  = ''; // Inisialisasi variabel HTML tombol
                $hakEdit = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak edit
                    ->where([ // Filter hak akses edit
                        'tbl_akses.role_id'         => $roleId, // Berdasarkan role user
                        'tbl_submenu.submenu_judul' => 'Barang', // Submenu Barang
                        'tbl_akses.akses_type'      => 'update', // Tipe akses update/edit
                    ]) // Menutup where
                    ->count(); // Hitung apakah user punya hak edit

                $hakDelete = AksesModel::leftJoin('tbl_submenu', 'tbl_submenu.submenu_id', '=', 'tbl_akses.submenu_id') // Query cek hak delete
                    ->where([ // Filter hak akses delete
                        'tbl_akses.role_id'         => $roleId, // Berdasarkan role user
                        'tbl_submenu.submenu_judul' => 'Barang', // Submenu Barang
                        'tbl_akses.akses_type'      => 'delete', // Tipe akses delete/hapus
                    ]) // Menutup where
                    ->count(); // Hitung apakah user punya hak delete

                if ($hakEdit > 0 && $hakDelete > 0) { // Jika punya hak edit dan delete
                    $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span></a>
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                    '; // Menutup string HTML tombol
                } elseif ($hakEdit > 0 && $hakDelete == 0) { // Jika hanya punya hak edit
                    $button .= '
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip" data-bs-original-title="Edit" onclick=update(' . json_encode($array) . ')><span class="fe fe-edit text-success fs-14"></span></a>
                        </div>
                    '; // Menutup string HTML tombol edit
                } elseif ($hakEdit == 0 && $hakDelete > 0) { // Jika hanya punya hak delete
                    $button .= '
                        <div class="g-2">
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled" data-bs-toggle="modal" href="#Hmodaldemo8" onclick=hapus(' . json_encode($array) . ')><span class="fe fe-trash-2 fs-14"></span></a>
                        </div>
                    '; // Menutup string HTML tombol hapus
                } else { // Jika tidak punya hak edit maupun delete
                    $button .= '-'; // Tampilkan tanda '-' sebagai pengganti tombol
                } // Menutup kondisi hak akses

                return $button; // Mengembalikan HTML tombol untuk kolom action
            }) // Menutup addColumn action
            ->rawColumns(['action', 'img', 'jenisbarang', 'satuan', 'merk', 'currency', 'totalstok']) // Menandai kolom yang berisi HTML agar tidak di-escape
            ->make(true); // Menghasilkan response JSON untuk DataTables
    }

    public function listbarang(Request $request): ?JsonResponse // Method untuk list barang versi pemilihan (misal modal pilih barang), return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request berasal dari AJAX
            return null; // Jika bukan AJAX, return null
        } // Menutup kondisi ajax

        $data = BarangModel::leftJoin('tbl_jenisbarang', 'tbl_jenisbarang.jenisbarang_id', '=', 'tbl_barang.jenisbarang_id') // Join barang dengan jenis barang
            ->leftJoin('tbl_satuan', 'tbl_satuan.satuan_id', '=', 'tbl_barang.satuan_id') // Join barang dengan satuan
            ->leftJoin('tbl_merk', 'tbl_merk.merk_id', '=', 'tbl_barang.merk_id') // Join barang dengan merk
            ->orderBy('barang_id', 'DESC') // Urutkan berdasarkan id terbaru
            ->get(); // Ambil semua data

        return DataTables::of($data) // Membuat DataTables dari collection data
            ->addIndexColumn() // Tambah kolom index
            ->addColumn('img', function ($row) { // Kolom untuk menampilkan gambar tanpa link modal
                if ($row->barang_gambar == 'image.png') { // Jika gambar default
                    $img = '<span class="avatar avatar-lg cover-image" style="background: url(&quot;' . url('/assets/default/barang') . '/' . $row->barang_gambar . '&quot;) center center;"></span>'; // HTML gambar default
                } else { // Jika gambar upload
                    $img = '<span class="avatar avatar-lg cover-image" style="background: url(&quot;' . asset('storage/barang/' . $row->barang_gambar) . '&quot;) center center;"></span>'; // HTML gambar dari storage
                } // Menutup if-else gambar

                return $img; // Return HTML gambar
            }) // Menutup addColumn img
            ->addColumn('jenisbarang', function ($row) { // Kolom jenis barang
                $jenisbarang = $row->jenisbarang_id == '' ? '-' : $row->jenisbarang_nama; // Jika kosong tampil '-', jika ada tampil nama

                return $jenisbarang; // Return nilai jenis barang
            }) // Menutup addColumn jenisbarang
            ->addColumn('satuan', function ($row) { // Kolom satuan
                $satuan = $row->satuan_id == '' ? '-' : $row->satuan_nama; // Jika kosong tampil '-', jika ada tampil nama

                return $satuan; // Return nilai satuan
            }) // Menutup addColumn satuan
            ->addColumn('merk', function ($row) { // Kolom merk
                $merk = $row->merk_id == '' ? '-' : $row->merk_nama; // Jika kosong tampil '-', jika ada tampil nama

                return $merk; // Return nilai merk
            }) // Menutup addColumn merk
            ->addColumn('currency', function ($row) { // Kolom harga format rupiah
                $currency = $row->barang_harga == '' ? '-' : 'Rp ' . number_format($row->barang_harga, 0); // Jika kosong tampil '-', jika ada format ribuan

                return $currency; // Return nilai harga yang diformat
            }) // Menutup addColumn currency
            ->addColumn('totalstok', function ($row) use ($request) { // Kolom stok total dihitung berdasarkan barang masuk/keluar (dengan filter tanggal jika ada)
                if ($request->tglawal == '') { // Jika tidak ada filter tanggal
                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan barang
                        ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join customer
                        ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode) // Filter berdasarkan kode barang
                        ->sum('tbl_barangmasuk.bm_jumlah'); // Sum barang masuk
                } else { // Jika ada filter tanggal
                    $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode') // Join barang masuk dengan barang
                        ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id') // Join customer
                        ->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir]) // Filter rentang tanggal masuk
                        ->where('tbl_barangmasuk.barang_kode', '=', $row->barang_kode) // Filter kode barang
                        ->sum('tbl_barangmasuk.bm_jumlah'); // Sum barang masuk sesuai rentang
                } // Menutup if-else barang masuk

                if ($request->tglawal) { // Jika ada filter tanggal
                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan barang
                        ->whereBetween('bk_tanggal', [$request->tglawal, $request->tglakhir]) // Filter rentang tanggal keluar
                        ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode) // Filter kode barang
                        ->sum('tbl_barangkeluar.bk_jumlah'); // Sum barang keluar sesuai rentang
                } else { // Jika tidak filter tanggal
                    $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode') // Join barang keluar dengan barang
                        ->where('tbl_barangkeluar.barang_kode', '=', $row->barang_kode) // Filter kode barang
                        ->sum('tbl_barangkeluar.bk_jumlah'); // Sum seluruh barang keluar
                } // Menutup if-else barang keluar

                $totalstok = $row->barang_stok + ($jmlmasuk - $jmlkeluar); // Hitung stok total
                if ($totalstok == 0) { // Jika stok 0
                    $result = '<span class="">' . $totalstok . '</span>'; // Tampilkan normal
                } elseif ($totalstok > 0) { // Jika stok positif
                    $result = '<span class="text-success">' . $totalstok . '</span>'; // Tampilkan hijau
                } else { // Jika stok negatif
                    $result = '<span class="text-danger">' . $totalstok . '</span>'; // Tampilkan merah
                } // Menutup kondisi tampilan stok

                return $result; // Return HTML stok
            }) // Menutup addColumn totalstok
            ->addColumn('action', function ($row) use ($request) { // Kolom action untuk tombol pilih barang (tambah/ubah)
                $barangNamaSlug = preg_replace( // Membuat slug aman dari nama barang
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter
                    (string) $row->barang_nama // Sumber: nama barang
                ); // Menutup preg_replace
                $barangNamaSlug = trim($barangNamaSlug ?? ''); // Trim hasil slug

                $satuanNamaSlug = preg_replace( // Membuat slug aman dari nama satuan
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter
                    (string) $row->satuan_nama // Sumber: nama satuan
                ); // Menutup preg_replace
                $satuanNamaSlug = trim($satuanNamaSlug ?? ''); // Trim hasil slug satuan

                $jenisbarangNamaSlug = preg_replace( // Membuat slug aman dari nama jenis barang
                    '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
                    '_', // Pengganti karakter
                    (string) $row->jenisbarang_nama // Sumber: nama jenis barang
                ); // Menutup preg_replace
                $jenisbarangNamaSlug = trim($jenisbarangNamaSlug ?? ''); // Trim hasil slug jenis barang

                $array = [ // Data barang yang akan dikirim ke JS pilihBarang/pilihBarangU
                    'barang_kode'      => $row->barang_kode, // Kode barang
                    'barang_nama'      => $barangNamaSlug, // Nama barang slug
                    'satuan_nama'      => $satuanNamaSlug, // Nama satuan slug
                    'jenisbarang_nama' => $jenisbarangNamaSlug, // Nama jenis barang slug
                ]; // Menutup array

                $button = ''; // Inisialisasi string tombol
                if ($request->get('param') == 'tambah') { // Jika konteks tombol untuk form tambah
                    $button .= ' // Tombol pilih untuk tambah
                        <div class="g-2">
                            <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick=pilihBarang(' . json_encode($array) . ')>Pilih</a>
                        </div>
                    '; // Menutup string tombol tambah
                } else { // Jika konteks tombol untuk form ubah
                    $button .= ' // Tombol pilih untuk ubah
                        <div class="g-2">
                            <a class="btn btn-success btn-sm" href="javascript:void(0)" onclick=pilihBarangU(' . json_encode($array) . ')>Pilih</a>
                        </div>
                    '; // Menutup string tombol ubah
                } // Menutup if-else param

                return $button; // Return HTML tombol pilih
            }) // Menutup addColumn action
            ->rawColumns(['action', 'img', 'jenisbarang', 'satuan', 'merk', 'currency', 'totalstok']) // Kolom yang mengandung HTML
            ->make(true); // Return JSON DataTables
    }

    public function proses_tambah(Request $request): JsonResponse // Method untuk proses tambah barang (insert ke database), return JSON
    {
        $img = ''; // Inisialisasi variabel nama gambar

        /** @var string|null $rawNama */ // Anotasi tipe input nama
        $rawNama = $request->input('nama'); // Mengambil input nama dari request
        $namaForSlug = is_string($rawNama) ? $rawNama : ''; // Pastikan nama adalah string untuk proses slug

        $slugSource = preg_replace( // Membuat slug dasar dari nama
            '/[^A-Za-z0-9-]+/', // Pola karakter yang tidak diizinkan
            '-', // Ganti karakter tidak valid dengan tanda minus
            $namaForSlug // Sumber string
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Ubah ke huruf kecil dan trim spasi hasil slug

        // upload image // Komentar penanda proses upload gambar
        if ($request->file('foto') == null) { // Jika tidak ada file foto diupload
            $img = 'image.png'; // Gunakan gambar default
        } else { // Jika ada file foto diupload
            $image = $request->file('foto'); // Ambil objek file dari request
            $image->storeAs('public/barang/', $image->hashName()); // Simpan file ke storage dengan nama hash
            $img = $image->hashName(); // Simpan nama file hash ke variabel untuk database
        } // Menutup if-else upload

        // create // Komentar penanda proses create data barang
        BarangModel::create([ // Menambahkan data barang baru ke tabel barang
            'barang_gambar'  => $img, // Menyimpan nama gambar (default atau upload)
            'jenisbarang_id' => $request->jenisbarang, // Menyimpan id jenis barang dari request
            'satuan_id'      => $request->satuan, // Menyimpan id satuan dari request
            'merk_id'        => $request->merk, // Menyimpan id merk dari request
            'barang_kode'    => $request->kode, // Menyimpan kode barang
            'barang_nama'    => $request->nama, // Menyimpan nama barang
            'barang_slug'    => $slug, // Menyimpan slug nama barang
            'barang_harga'   => $request->harga, // Menyimpan harga barang
            'barang_stok'    => 0, // Set stok awal 0 saat barang baru dibuat
        ]); // Menutup create array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_ubah(Request $request, BarangModel $barang): JsonResponse // Method untuk proses update barang, menerima model Barang via route model binding
    {
        /** @var string|null $rawNama */ // Anotasi tipe input nama
        $rawNama = $request->input('nama'); // Mengambil input nama dari request
        $namaForSlug = is_string($rawNama) ? $rawNama : ''; // Pastikan nama string agar aman diproses

        $slugSource = preg_replace( // Membuat slug dasar dari nama
            '/[^A-Za-z0-9-]+/', // Pola karakter yang tidak valid
            '-', // Pengganti karakter tidak valid
            $namaForSlug // Sumber string
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Ubah slug ke lowercase dan trim

        // check if image is uploaded // Komentar penanda pengecekan upload gambar
        if ($request->hasFile('foto')) { // Jika request membawa file foto baru

            // upload new image // Komentar penanda upload gambar baru
            $image = $request->file('foto'); // Mengambil file foto
            $image->storeAs('public/barang', $image->hashName()); // Menyimpan file foto ke storage

            // delete old image // Komentar penanda hapus gambar lama
            Storage::delete('public/barang/' . $barang->barang_gambar); // Menghapus file gambar lama dari storage

            // update data with new image // Komentar penanda update data dengan gambar baru
            $barang->update([ // Update data barang termasuk gambar
                'barang_gambar'  => $image->hashName(), // Simpan nama gambar baru
                'jenisbarang_id' => $request->jenisbarang, // Update jenis barang
                'satuan_id'      => $request->satuan, // Update satuan
                'merk_id'        => $request->merk, // Update merk
                'barang_kode'    => $request->kode, // Update kode barang
                'barang_nama'    => $request->nama, // Update nama barang
                'barang_slug'    => $slug, // Update slug barang
                'barang_harga'   => $request->harga, // Update harga barang
                'barang_stok'    => $request->stok, // Update stok barang
            ]); // Menutup update array
        } else { // Jika tidak upload gambar baru
            // update data without image // Komentar penanda update data tanpa mengubah gambar
            $barang->update([ // Update data barang kecuali gambar
                'jenisbarang_id' => $request->jenisbarang, // Update jenis barang
                'satuan_id'      => $request->satuan, // Update satuan
                'merk_id'        => $request->merk, // Update merk
                'barang_kode'    => $request->kode, // Update kode barang
                'barang_nama'    => $request->nama, // Update nama barang
                'barang_slug'    => $slug, // Update slug barang
                'barang_harga'   => $request->harga, // Update harga barang
                'barang_stok'    => $request->stok, // Update stok barang
            ]); // Menutup update array
        } // Menutup if-else upload gambar

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_hapus(Request $request, BarangModel $barang): JsonResponse // Method untuk menghapus data barang dan file gambarnya, return JSON
    {
        // delete image // Komentar penanda hapus gambar
        Storage::delete('public/barang/' . $barang->barang_gambar); // Menghapus file gambar barang dari storage

        // delete // Komentar penanda hapus data
        $barang->delete(); // Menghapus data barang dari database

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }
} // Penutup class BarangController
