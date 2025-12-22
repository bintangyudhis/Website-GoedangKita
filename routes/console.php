<?php
// Tag pembuka PHP

use Illuminate\Foundation\Inspiring;
// Import class Inspiring untuk mengambil quote bawaan Laravel

use Illuminate\Support\Facades\Artisan;
// Import facade Artisan untuk mendaftarkan/membuat console command

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| File ini digunakan untuk mendefinisikan command console berbasis Closure
| (tanpa membuat class command terpisah).
|
| Setiap Closure akan di-bind ke instance command, sehingga kamu bisa pakai
| method IO seperti $this->info(), $this->comment(), $this->error(), dll.
|
*/

Artisan::command('inspire', function () {
    // Mendaftarkan command bernama "inspire" (jalan via: php artisan inspire)

    $this->comment(Inspiring::quote());
    // Menampilkan quote inspiratif ke console dengan style "comment"
    // Inspiring::quote() mengambil 1 quote acak bawaan Laravel
})
->purpose('Display an inspiring quote');
// Memberikan deskripsi/purpose command, biasanya tampil di daftar `php artisan list`
