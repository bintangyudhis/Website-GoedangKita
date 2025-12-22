<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Master; // Namespace controller untuk modul Master (manajemen user)

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\RoleModel; // Mengimpor model Role untuk mengambil data role (dropdown/relasi)
use App\Models\Admin\UserModel; // Mengimpor model User untuk CRUD data user di database
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\JsonResponse; // Mengimpor JsonResponse untuk response JSON (DataTables/AJAX)
use Illuminate\Http\RedirectResponse; // Mengimpor RedirectResponse untuk type hint redirect
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input dari form
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk flash message dan akses session user
use Illuminate\Support\Facades\Storage; // Mengimpor Storage untuk upload/hapus file foto user di storage
use Yajra\DataTables\Facades\DataTables; // Mengimpor DataTables facade untuk membuat response DataTables

class UserController extends Controller // Mendefinisikan controller User (manajemen user) yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman daftar user
    {
        $data['title'] = 'User'; // Menetapkan judul halaman
        $data['role']  = RoleModel::where('role_id', '!=', 1)->latest()->get(); // Mengambil daftar role kecuali Super Admin (role_id = 1)

        return view('Master.User.index', $data); // Mengembalikan view daftar user dengan data role dan title
    }

    public function profile(UserModel $user): View // Method untuk menampilkan halaman profile berdasarkan user (route model binding)
    {
        $data['title'] = 'Profile'; // Menetapkan judul halaman profile
        $data['data']  = UserModel::leftJoin('tbl_role', 'tbl_role.role_id', '=', 'tbl_user.role_id') // Join user dengan tabel role agar dapat role_title
            ->select() // Mengambil semua kolom hasil join (sesuai default select *)
            ->where('tbl_user.user_id', '=', $user->user_id) // Filter berdasarkan user_id yang sedang dibuka
            ->first(); // Mengambil 1 baris pertama (detail user)

        return view('Master.User.profile', $data); // Mengembalikan view profile dengan data user
    }

    public function show(Request $request): ?JsonResponse // Method untuk mengirim data user ke DataTables (AJAX)
    {
        if (! $request->ajax()) { // Jika bukan request AJAX, kembalikan null
            return null; // Menghindari akses langsung method show dari browser biasa
        }

        $data = UserModel::leftJoin('tbl_role', 'tbl_role.role_id', '=', 'tbl_user.role_id') // Join tabel user dengan role
            ->select() // Mengambil semua kolom dari hasil join
            ->orderBy('user_id', 'DESC') // Mengurutkan user terbaru berdasarkan user_id
            ->get(); // Mengambil semua data

        return DataTables::of($data) // Membuat response DataTables dari data user
            ->addIndexColumn() // Menambahkan kolom index/nomor urut
            ->addColumn('img', function ($row) { // Menambahkan kolom img untuk menampilkan foto user
                if ($row->user_foto == 'undraw_profile.svg') { // Jika user memakai foto default
                    $img = '<span class="avatar avatar-lg brround cover-image" data-bs-image-src="' . url('assets/images/users/14.jpg') . '" style="background: url(&quot;' . url('/assets/default/users') . '/' . $row->user_foto . '&quot;) center center;"></span>'; // HTML avatar dari asset default
                } else { // Jika user memakai foto upload
                    $img = '<span class="avatar avatar-lg brround cover-image" data-bs-image-src="' . url('assets/images/users/14.jpg') . '" style="background: url(&quot;' . asset('storage/users/' . $row->user_foto) . '&quot;) center center;"></span>'; // HTML avatar dari storage
                }

                return $img; // Mengembalikan HTML img
            })
            ->addColumn('role', function ($row) { // Menambahkan kolom role dalam bentuk badge
                $badge = '<span class="badge bg-primary badge-sm  me-1 mb-1 mt-1">' . $row->role_title . '</span>'; // HTML badge role_title

                return $badge; // Mengembalikan badge
            })
            ->addColumn('action', function ($row) { // Menambahkan kolom action (tombol edit/hapus)
                // jaga supaya preg_replace subject selalu string // Komentar: mencegah error jika null/non-string
                $userNamaSource = is_string($row->user_nama) ? $row->user_nama : ''; // Pastikan user_nama adalah string
                $userNamaClean  = preg_replace('/[^A-Za-z0-9-]+/', '_', $userNamaSource); // Bersihkan karakter aneh agar aman ke JS/HTML
                $userNamaSlug   = trim($userNamaClean ?? ''); // Trim hasil slug nama user

                $userNmLengkapSource = is_string($row->user_nmlengkap) ? $row->user_nmlengkap : ''; // Pastikan user_nmlengkap adalah string
                $userNmLengkapClean  = preg_replace('/[^A-Za-z0-9-]+/', '_', $userNmLengkapSource); // Bersihkan karakter aneh
                $userNmLengkapSlug   = trim($userNmLengkapClean ?? ''); // Trim hasil slug nama lengkap

                $array = [ // Menyiapkan array data untuk dikirim ke JS saat klik edit/hapus
                    'user_id'        => $row->user_id, // ID user
                    'user_nama'      => $userNamaSlug, // Username yang sudah “aman”
                    'user_nmlengkap' => $userNmLengkapSlug, // Nama lengkap yang sudah “aman”
                    'user_foto'      => $row->user_foto, // Nama file foto user
                    'role_id'        => $row->role_id, // Role id user
                    'user_email'     => $row->user_email, // Email user
                ]; // Menutup array data

                $button  = ''; // Inisialisasi string tombol
                $button .= '
                    <div class="g-2">
                        <a class="btn modal-effect text-primary btn-sm"
                           data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                           href="#Umodaldemo8" data-bs-toggle="tooltip"
                           data-bs-original-title="Edit"
                           onclick=update(' . json_encode($array) . ')>
                           <span class="fe fe-edit text-success fs-14"></span>
                        </a>
                        <a class="btn modal-effect text-danger btn-sm"
                           data-bs-effect="effect-super-scaled" data-bs-toggle="modal"
                           href="#Hmodaldemo8"
                           onclick=hapus(' . json_encode($array) . ')>
                           <span class="fe fe-trash-2 fs-14"></span>
                        </a>
                    </div>
                '; // Menutup HTML tombol

                return $button; // Mengembalikan HTML action
            })
            ->rawColumns(['action', 'img', 'role']) // Mengizinkan kolom action/img/role berisi HTML (tidak di-escape)
            ->make(true); // Mengembalikan response JSON DataTables
    }

    public function store(Request $request): RedirectResponse // Method untuk menambahkan user baru
    {
        // Validasi agar memastikan itu bukan menambahkan user admin // Komentar: mencegah create super admin baru
        if ($request->role == 1) { // Jika role yang dipilih adalah Super Admin
            Session::flash('status', 'error'); // Set flash status error
            Session::flash('msg', 'Tidak dapat menambahkan Super Admin baru!'); // Set flash pesan error

            return redirect()->route('user.index'); // Redirect kembali ke halaman user
        }

        $img = ''; // Inisialisasi nama file foto user

        // upload image // Komentar: proses upload foto user
        if ($request->file('photo') === null) { // Jika foto tidak diupload
            $img = 'undraw_profile.svg'; // Gunakan foto default
        } else { // Jika ada file foto yang diupload
            $image = $request->file('photo'); // Ambil file dari request
            $image->storeAs('public/users/', $image->hashName()); // Simpan ke storage public/users dengan nama hash
            $img = $image->hashName(); // Simpan nama file hash sebagai foto user
        }

        // jaga supaya password selalu string // Komentar: mencegah md5 error jika input bukan string
        $pwdRaw    = $request->input('pwd'); // Ambil input password
        $pwdString = is_string($pwdRaw) ? $pwdRaw : ''; // Pastikan password berbentuk string

        // create post // Komentar: simpan user baru ke database
        UserModel::create([ // Menambahkan record user baru
            'user_foto'     => $img, // Menyimpan foto user (default atau upload)
            'user_nmlengkap' => $request->nmlengkap, // Menyimpan nama lengkap dari form
            'user_nama'     => $request->username, // Menyimpan username dari form
            'user_email'    => $request->email, // Menyimpan email dari form
            'role_id'       => $request->role, // Menyimpan role user (bukan super admin)
            'user_password' => md5($pwdString), // Menyimpan password dalam bentuk md5 (mengikuti logika project lama)
        ]);

        $data['title'] = 'User'; // Menyiapkan title untuk dikirim bersama redirect (opsional)
        Session::flash('status', 'success'); // Set flash status sukses
        Session::flash('msg', 'Berhasil ditambah!'); // Set flash pesan sukses

        // redirect to index // Komentar: kembali ke halaman index user
        return redirect()->route('user.index')->with($data); // Redirect ke user.index dengan data tambahan
    }

    public function update(Request $request, UserModel $user): RedirectResponse // Method untuk update data user (admin update)
    {
        // 1. Mencegah user lain dipromosikan menjadi Super Admin // Komentar: proteksi tidak boleh promote jadi super admin
        if ($user->role_id != 1 && $request->roleU == 1) { // Jika user bukan super admin tapi ingin diubah jadi super admin
            Session::flash('status', 'error'); // Set flash status error
            Session::flash('msg', 'Tidak dapat mengubah user lain menjadi Super Admin!'); // Set flash pesan error

            return redirect()->route('user.index'); // Redirect kembali tanpa update
        }

        // 2. Mencegah Super Admin diturunkan rolenya // Komentar: proteksi super admin tidak boleh diturunkan
        if ($user->role_id == 1 && $request->roleU != 1) { // Jika user adalah super admin tapi ingin diganti role lain
            Session::flash('status', 'error'); // Set flash status error
            Session::flash('msg', 'Role Super Admin tidak boleh diubah!'); // Set flash pesan error

            return redirect()->route('user.index'); // Redirect kembali tanpa update
        }

        // siapkan password baru (kalau diisi) // Komentar: ambil password baru dari form update
        $pwdURaw    = $request->input('pwdU'); // Ambil password baru (field pwdU)
        $pwdUString = is_string($pwdURaw) ? $pwdURaw : ''; // Pastikan password baru string

        // check if image is uploaded // Komentar: cek apakah ada upload foto baru
        if ($request->hasFile('photoU')) { // Jika ada foto update

            // upload new image // Komentar: upload foto baru
            $image = $request->file('photoU'); // Ambil file foto baru
            $image->storeAs('public/users', $image->hashName()); // Simpan foto baru ke storage

            // delete old image // Komentar: hapus foto lama dari storage
            Storage::delete('public/users/' . $user->user_foto); // Menghapus file foto lama

            if ($request->pwd == '') { // Jika field pwd kosong (tidak mengubah password) - mengikuti logika project
                // update post with new image tanpa ubah password // Komentar: update data tanpa ganti password
                $user->update([ // Update data user
                    'user_foto'     => $image->hashName(), // Update foto user
                    'user_nmlengkap' => $request->nmlengkapU, // Update nama lengkap
                    'user_nama'     => $request->usernameU, // Update username
                    'user_email'    => $request->emailU, // Update email
                    'role_id'       => $request->roleU, // Update role user
                ]);
            } else { // Jika password diisi (ubah password)
                // update post dengan new image + password baru // Komentar: update data dan update password
                $user->update([ // Update data user
                    'user_foto'     => $image->hashName(), // Update foto user
                    'user_nmlengkap' => $request->nmlengkapU, // Update nama lengkap
                    'user_nama'     => $request->usernameU, // Update username
                    'user_email'    => $request->emailU, // Update email
                    'role_id'       => $request->roleU, // Update role user
                    'user_password' => md5($pwdUString), // Update password dengan md5
                ]);
            }
        } else { // Jika tidak ada upload foto baru
            if ($request->pwd == '') { // Jika tidak mengubah password
                // update post without image & tanpa ubah password // Komentar: update data biasa
                $user->update([ // Update data user tanpa foto
                    'user_nmlengkap' => $request->nmlengkapU, // Update nama lengkap
                    'user_nama'     => $request->usernameU, // Update username
                    'user_email'    => $request->emailU, // Update email
                    'role_id'       => $request->roleU, // Update role user
                ]);
            } else { // Jika password diisi (ubah password)
                // update post tanpa image tapi dengan password baru // Komentar: update data user + password tanpa ubah foto
                $user->update([ // Update data user
                    'user_nmlengkap' => $request->nmlengkapU, // Update nama lengkap
                    'user_nama'     => $request->usernameU, // Update username
                    'user_email'    => $request->emailU, // Update email
                    'role_id'       => $request->roleU, // Update role user
                    'user_password' => md5($pwdUString), // Update password dengan md5
                ]);
            }
        }

        $data['title'] = 'User'; // Menyiapkan title untuk dikirim bersama redirect (opsional)
        Session::flash('status', 'success'); // Set flash status sukses
        Session::flash('msg', 'Berhasil diubah!'); // Set flash pesan sukses update

        // redirect to index // Komentar: kembali ke halaman index user
        return redirect()->route('user.index')->with($data); // Redirect ke user.index dengan data tambahan
    }

    public function updatePassword(Request $request, UserModel $user): RedirectResponse // Method untuk update password dari halaman profile
    {
        $currentRaw    = $request->input('currentpassword'); // Ambil password saat ini dari input
        $currentString = is_string($currentRaw) ? $currentRaw : ''; // Pastikan password saat ini string

        $newRaw    = $request->input('newpassword'); // Ambil password baru dari input
        $newString = is_string($newRaw) ? $newRaw : ''; // Pastikan password baru string

        $checkPassword = UserModel::where([ // Mengecek apakah password lama cocok
            'user_id'       => $user->user_id, // Cocokkan berdasarkan user_id
            'user_password' => md5($currentString), // Cocokkan hash md5 password lama
        ])->count(); // Hitung apakah ada record yang cocok

        if ($checkPassword > 0) { // Jika password lama benar
            $user->update([ // Update password user
                'user_password' => md5($newString), // Set password baru (md5)
            ]);
            Session::flash('status', 'success'); // Set flash status sukses
            Session::flash('msg', 'Password berhasil di ubah!'); // Set flash pesan sukses
        } else { // Jika password lama tidak cocok
            Session::flash('status', 'error'); // Set flash status error
            Session::flash('msg', 'Password saat ini tidak sama dengan password lama!'); // Set flash pesan error
            Session::flash('currentpassword', $request->currentpassword); // Menyimpan input agar form bisa diisi ulang (sesuai logika project)
            Session::flash('newpassword', $request->newpassword); // Menyimpan input password baru
            Session::flash('confirmpassword', $request->confirmpassword); // Menyimpan input konfirmasi password
        }

        $data['title'] = 'Profile'; // Menyiapkan title untuk halaman profile

        // redirect to index // Komentar: kembali ke halaman profile user
        return redirect(url('admin/profile/' . $user->user_id))->with($data); // Redirect ke URL profile user
    }

    public function updateProfile(Request $request, UserModel $user): RedirectResponse // Method untuk update data profile (nama, username, email, foto)
    {
        // check if image is uploaded // Komentar: cek apakah ada foto baru
        if ($request->hasFile('photoU')) { // Jika ada foto baru diupload

            // upload new image // Komentar: upload foto baru
            $image = $request->file('photoU'); // Ambil file foto baru
            $image->storeAs('public/users', $image->hashName()); // Simpan foto baru ke storage

            // delete old image // Komentar: hapus foto lama
            Storage::delete('public/users/' . $user->user_foto); // Menghapus file foto lama

            // update post with new image // Komentar: update data profile + foto
            $user->update([ // Update data user
                'user_foto'     => $image->hashName(), // Update foto user
                'user_nmlengkap' => $request->nmlengkap, // Update nama lengkap
                'user_nama'     => $request->username, // Update username
                'user_email'    => $request->email, // Update email
            ]);
        } else { // Jika tidak ada foto baru
            // update post without image // Komentar: update data profile tanpa foto
            $user->update([ // Update data user
                'user_nmlengkap' => $request->nmlengkap, // Update nama lengkap
                'user_nama'     => $request->username, // Update username
                'user_email'    => $request->email, // Update email
            ]);
        }

        $data['title'] = 'Profile'; // Menyiapkan title halaman profile
        Session::flash('status', 'success'); // Set flash status sukses
        Session::flash('msg', 'Profile Berhasil diubah!'); // Set flash pesan sukses

        // redirect to index // Komentar: kembali ke halaman profile
        return redirect(url('admin/profile/' . $user->user_id))->with($data); // Redirect ke URL profile user
    }

    public function hapus(Request $request): RedirectResponse // Method untuk menghapus user
    {
        // pastikan ambil satu baris UserModel, bukan Collection // Komentar: ambil id yang akan dihapus
        $id = $request->input('iduser'); // Mengambil user_id yang akan dihapus

        $detail = UserModel::where('user_id', $id)->firstOrFail(); // Mengambil detail user, jika tidak ada maka 404

        // biar tidak bisa hapus super admin // Komentar: proteksi super admin
        if ($detail->role_id == 1) { // Jika user tersebut role super admin
            Session::flash('status', 'error'); // Set flash status error
            Session::flash('msg', 'Super Admin tidak dapat dihapus'); // Set flash pesan error

            return redirect()->route('user.index'); // Redirect kembali tanpa menghapus
        }

        // delete image // Komentar: hapus foto user dari storage
        Storage::delete('public/users/' . $detail->user_foto); // Menghapus file foto user

        // delete post // Komentar: hapus record user
        $detail->delete(); // Menghapus data user dari database

        $data['title'] = 'User'; // Menyiapkan title untuk redirect
        Session::flash('status', 'success'); // Set flash status sukses
        Session::flash('msg', 'Berhasil dihapus!'); // Set flash pesan sukses

        // redirect to index // Komentar: kembali ke halaman index user
        return redirect()->route('user.index')->with($data); // Redirect ke user.index dengan data tambahan
    }
} // Penutup class UserController
