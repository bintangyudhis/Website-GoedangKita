<?php // Tag pembuka PHP untuk file controller ini

namespace App\Http\Controllers\Master; // Namespace controller untuk modul Master (pengaturan Web)

use App\Http\Controllers\Controller; // Mengimpor base Controller Laravel sebagai parent class
use App\Models\Admin\WebModel; // Mengimpor model Web untuk membaca dan mengubah data pengaturan web (nama, deskripsi, logo)
use Illuminate\Contracts\View\View; // Mengimpor kontrak View untuk type hint return view
use Illuminate\Http\RedirectResponse; // Mengimpor RedirectResponse untuk type hint hasil redirect
use Illuminate\Http\Request; // Mengimpor Request untuk mengambil input form update web
use Illuminate\Support\Facades\Session; // Mengimpor Session untuk flash message (notifikasi sukses)
use Illuminate\Support\Facades\Storage; // Mengimpor Storage untuk menghapus file logo lama dan menyimpan logo baru

class WebController extends Controller // Mendefinisikan controller Web yang mewarisi Controller Laravel
{
    public function index(): View // Method untuk menampilkan halaman pengaturan web
    {
        $data['title'] = 'Web'; // Menetapkan judul halaman
        $data['data']  = WebModel::all(); // Mengambil semua data dari tabel web (biasanya hanya 1 baris, tapi tetap ambil semua)

        return view('Master.Web.index', $data); // Mengembalikan view Master.Web.index dengan data web dan title
    }

    public function update(Request $request, WebModel $web): RedirectResponse // Method untuk update data web (route model binding)
    {
        // check if image is uploaded // Komentar: cek apakah ada file logo baru yang diupload
        if ($request->hasFile('photo')) { // Jika input 'photo' berisi file

            // upload new image // Komentar: proses upload logo baru
            $image = $request->file('photo'); // Mengambil file logo dari request
            $image->storeAs('public/web', $image->hashName()); // Simpan file ke storage/app/public/web dengan nama hash

            // delete old image // Komentar: hapus logo lama dari storage agar tidak menumpuk
            Storage::delete('public/web/' . $web->web_logo); // Menghapus file logo lama berdasarkan nama file di database

            // update post with new image // Komentar: update data web termasuk logo baru
            $web->update([ // Update record web yang dipilih
                'web_logo'      => $image->hashName(), // Simpan nama file logo baru (hash)
                'web_nama'      => $request->nmweb, // Update nama web dari input 'nmweb'
                'web_deskripsi' => $request->desk, // Update deskripsi web dari input 'desk'
            ]); // Menutup update dengan logo
        } else { // Jika tidak ada file logo baru
            $web->update([ // Update record web tanpa mengubah logo
                'web_nama'      => $request->nmweb, // Update nama web
                'web_deskripsi' => $request->desk, // Update deskripsi web
            ]); // Menutup update tanpa logo
        } // Menutup kondisi upload logo

        $data['title'] = 'Web'; // Menyiapkan title untuk dibawa saat redirect (opsional)
        Session::flash('status', 'success'); // Menyimpan status flash sukses untuk notifikasi di halaman
        Session::flash('msg', 'Berhasil diubah!'); // Menyimpan pesan flash sukses untuk notifikasi

        // redirect to index // Komentar: kembali ke halaman index pengaturan web
        return redirect()->route('web.index')->with($data); // Redirect ke route web.index sambil membawa data tambahan
    }
} // Penutup class WebController
