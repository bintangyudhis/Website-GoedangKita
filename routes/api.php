<?php
// Tag pembuka PHP

use Illuminate\Http\Request;
// Import class Request untuk type-hint parameter pada closure route

use Illuminate\Support\Facades\Route;
// Import facade Route untuk mendefinisikan route API

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Komentar blok bawaan Laravel: menjelaskan file ini untuk definisi route API
// Semua route di sini otomatis masuk grup middleware "api" via RouteServiceProvider

// Route ini hanya bisa diakses kalau user sudah terautentikasi menggunakan Laravel Sanctum (token / SPA authentication).
// Route ini digunakan untuk mengambil data user yang sedang login.
// Komentar user: menjelaskan fungsi endpoint /user

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // Menetapkan middleware auth:sanctum (wajib login/token valid), lalu definisikan GET /api/user
    // Parameter $request ditype-hint sebagai Illuminate\Http\Request

    return $request->user();
    // Mengembalikan user yang terautentikasi dari request saat ini (guard Sanctum)
    // Biasanya response akan berupa JSON object user (Resource default dari model User)
});

// $request->user() akan mengembalikan data user yang sedang login (berdasarkan token / session Sanctum).
// Output-nya biasanya JSON berisi data user (misalnya id, name, email, dll).
// Komentar user: penjelasan tambahan tentang return $request->user()
