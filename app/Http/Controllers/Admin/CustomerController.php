<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Admin; // Namespace controller untuk modul Admin

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\AksesModel; // Mengimpor model Akses untuk cek hak akses user
use App\Models\Admin\CustomerModel; // Mengimpor model Customer untuk CRUD data customer
use App\Models\Admin\UserModel; // Mengimpor model User untuk tipe data user pada session
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk type hint response JSON
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dan info request
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk mengambil data user login
use Yajra\DataTables\Facades\DataTables; // Mengimpor DataTables facade untuk membuat response tabel AJAX

class CustomerController extends Controller // Mendefinisikan controller Customer yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman utama modul Customer
    {
        $data['title'] = 'Customer'; // Menetapkan judul halaman untuk dikirim ke view

        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id user (nullsafe jika user null)

        $data['hakTambah'] = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id') // Join akses dengan menu untuk cek hak tambah
            ->where([ // Menambahkan kondisi filter hak akses
                'tbl_akses.role_id'   => $roleId, // Filter role user
                'tbl_menu.menu_judul' => 'Customer', // Filter menu berjudul Customer
                'tbl_akses.akses_type' => 'create', // Filter tipe akses create (tambah)
            ]) // Menutup where array
            ->count(); // Menghitung jumlah hasil (jika > 0 berarti punya hak tambah)

        return view('Admin.Customer.index', $data); // Mengembalikan view halaman Customer dengan data yang sudah disiapkan
    }

    public function show(Request $request): ?JsonResponse // Method untuk mengambil data customer via AJAX (DataTables), return JsonResponse atau null
    {
        if (! $request->ajax()) { // Mengecek apakah request ini bukan AJAX
            return null; // Jika bukan AJAX, kembalikan null agar tidak memproses DataTables
        } // Menutup kondisi bukan AJAX

        $data = CustomerModel::orderBy('customer_id', 'DESC')->get(); // Mengambil semua data customer dan mengurutkan dari yang terbaru

        /** @var UserModel|null $user */ // Anotasi tipe user dari session
        $user   = Session::get('user'); // Mengambil data user login dari session
        $roleId = $user?->role_id; // Mengambil role_id untuk cek hak akses edit/hapus

        return DataTables::of($data) // Membuat DataTables dari collection data customer
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('notelp', function ($row) { // Menambahkan kolom notelp untuk menampilkan nomor telepon
                $notelp = $row->customer_notelp == '' ? '-' : $row->customer_notelp; // Jika no telp kosong tampil '-', jika ada tampil nomornya

                return $notelp; // Mengembalikan nilai untuk kolom notelp
            }) // Menutup addColumn notelp
            ->addColumn('alamat', function ($row) { // Menambahkan kolom alamat untuk menampilkan alamat customer
                $alamat = $row->customer_alamat == '' ? '-' : $row->customer_alamat; // Jika alamat kosong tampil '-', jika ada tampil alamat

                return $alamat; // Mengembalikan nilai untuk kolom alamat
            }) // Menutup addColumn alamat
            ->addColumn('action', function ($row) use ($roleId) { // Menambahkan kolom action untuk tombol edit/hapus berdasarkan hak akses
                // Pastikan subject preg_replace selalu string // Komentar penjelasan agar preg_replace aman dari tipe non-string
                $namaSource = preg_replace( // Mengubah nama customer menjadi slug aman untuk dikirim ke JS/HTML
                    '/[^A-Za-z0-9-]+/', // Pola karakter yang tidak diizinkan
                    '_', // Pengganti karakter yang tidak diizinkan
                    (string) $row->customer_nama // Sumber string nama customer (dipaksa string)
                ); // Menutup preg_replace nama
                $customerNamaSlug = trim($namaSource ?? ''); // Menghapus spasi awal/akhir hasil slug nama

                $alamatSource = preg_replace( // Mengubah alamat customer menjadi slug aman
                    '/[^A-Za-z0-9-]+/', // Pola karakter yang tidak diizinkan
                    '_', // Pengganti karakter tidak valid
                    (string) $row->customer_alamat // Sumber string alamat customer (dipaksa string)
                ); // Menutup preg_replace alamat
                $customerAlamatSlug = trim($alamatSource ?? ''); // Menghapus spasi awal/akhir hasil slug alamat

                $array = [ // Menyiapkan data customer yang akan dikirim ke fungsi JS update/hapus
                    'customer_id'     => $row->customer_id, // ID customer
                    'customer_nama'   => $customerNamaSlug, // Nama customer dalam bentuk slug
                    'customer_alamat' => $customerAlamatSlug, // Alamat customer dalam bentuk slug
                    'customer_notelp' => $row->customer_notelp, // Nomor telepon customer
                ]; // Menutup array data customer

                $button = ''; // Inisialisasi variabel string HTML tombol

                $hakEdit = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id') // Query cek hak edit/update
                    ->where([ // Kondisi hak edit berdasarkan role dan menu
                        'tbl_akses.role_id'   => $roleId, // Role user
                        'tbl_menu.menu_judul' => 'Customer', // Menu Customer
                        'tbl_akses.akses_type' => 'update', // Tipe akses update (edit)
                    ]) // Menutup where
                    ->count(); // Hitung hasil (jika > 0 berarti boleh edit)

                $hakDelete = AksesModel::leftJoin('tbl_menu', 'tbl_menu.menu_id', '=', 'tbl_akses.menu_id') // Query cek hak delete/hapus
                    ->where([ // Kondisi hak delete berdasarkan role dan menu
                        'tbl_akses.role_id'   => $roleId, // Role user
                        'tbl_menu.menu_judul' => 'Customer', // Menu Customer
                        'tbl_akses.akses_type' => 'delete', // Tipe akses delete (hapus)
                    ]) // Menutup where
                    ->count(); // Hitung hasil (jika > 0 berarti boleh hapus)

                if ($hakEdit > 0 && $hakDelete > 0) { // Jika user punya hak edit dan hapus
                    $button .= ' // Menambahkan tombol edit dan hapus
                        <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip"
                           data-bs-original-title="Edit"
                           onclick=update(' . json_encode($array) . ')>
                           <span class="fe fe-edit text-success fs-14"></span>
                        </a>
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                        </div>
                    '; // Menutup string HTML tombol
                } elseif ($hakEdit > 0 && $hakDelete == 0) { // Jika hanya punya hak edit
                    $button .= ' // Menambahkan tombol edit saja
                        <div class="g-2">
                            <a class="btn modal-effect text-primary btn-sm" data-bs-effect="effect-super-scaled"
                               data-bs-toggle="modal" href="#Umodaldemo8" data-bs-toggle="tooltip"
                               data-bs-original-title="Edit"
                               onclick=update(' . json_encode($array) . ')>
                               <span class="fe fe-edit text-success fs-14"></span>
                            </a>
                        </div>
                    '; // Menutup string HTML tombol edit
                } elseif ($hakEdit == 0 && $hakDelete > 0) { // Jika hanya punya hak delete
                    $button .= ' // Menambahkan tombol hapus saja
                        <div class="g-2">
                        <a class="btn modal-effect text-danger btn-sm" data-bs-effect="effect-super-scaled"
                           data-bs-toggle="modal" href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                        </div>
                    '; // Menutup string HTML tombol hapus
                } else { // Jika tidak punya hak edit maupun hapus
                    $button .= '-'; // Tampilkan '-' sebagai pengganti tombol
                } // Menutup kondisi hak akses

                return $button; // Mengembalikan HTML tombol untuk kolom action
            }) // Menutup addColumn action
            ->rawColumns(['action', 'notelp', 'alamat']) // Menandai kolom yang berisi HTML agar tidak di-escape oleh DataTables
            ->make(true); // Menghasilkan response JSON DataTables
    }

    public function proses_tambah(Request $request): JsonResponse // Method untuk menyimpan data customer baru
    {
        /** @var string|null $rawCustomer */ // Anotasi tipe input customer
        $rawCustomer = $request->input('customer'); // Mengambil input nama customer dari request
        $customerForSlug = is_string($rawCustomer) ? $rawCustomer : ''; // Memastikan nilai yang diproses slug adalah string

        $slugSource = preg_replace( // Membuat slug dasar dari nama customer
            '/[^A-Za-z0-9-]+/', // Pola karakter yang tidak diizinkan
            '-', // Mengganti karakter tidak valid dengan '-'
            $customerForSlug // Sumber string nama customer
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Menjadikan lowercase dan trim spasi hasil slug

        // insert data // Komentar penanda proses insert data
        CustomerModel::create([ // Membuat record baru pada tabel customer
            'customer_nama'   => $request->customer, // Menyimpan nama customer
            'customer_slug'   => $slug, // Menyimpan slug nama customer
            'customer_notelp' => $request->notelp, // Menyimpan nomor telepon customer
            'customer_alamat' => $request->alamat, // Menyimpan alamat customer
        ]); // Menutup create array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_ubah(Request $request, CustomerModel $customer): JsonResponse // Method untuk update data customer melalui route model binding
    {
        /** @var string|null $rawCustomer */ // Anotasi tipe input customer
        $rawCustomer = $request->input('customer'); // Mengambil input nama customer dari request
        $customerForSlug = is_string($rawCustomer) ? $rawCustomer : ''; // Memastikan input string untuk diproses slug

        $slugSource = preg_replace( // Membuat slug dasar dari nama customer
            '/[^A-Za-z0-9-]+/', // Pola karakter tidak valid
            '-', // Mengganti karakter tidak valid dengan '-'
            $customerForSlug // Sumber nama customer
        ); // Menutup preg_replace
        $slug = strtolower(trim($slugSource ?? '')); // Jadikan lowercase dan trim spasi

        // update data // Komentar penanda proses update data
        $customer->update([ // Melakukan update record customer
            'customer_nama'   => $request->customer, // Update nama customer
            'customer_slug'   => $slug, // Update slug customer
            'customer_notelp' => $request->notelp, // Update nomor telepon
            'customer_alamat' => $request->alamat, // Update alamat customer
        ]); // Menutup update array

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }

    public function proses_hapus(Request $request, CustomerModel $customer): JsonResponse // Method untuk menghapus data customer
    {
        // delete // Komentar penanda proses delete
        $customer->delete(); // Menghapus record customer dari database

        return response()->json(['success' => 'Berhasil']); // Mengembalikan response JSON sukses
    }
} // Penutup class CustomerController
