<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route ini hanya bisa diakses kalau user sudah terautentikasi menggunakan Laravel Sanctum (token / SPA authentication).
// Route ini digunakan untuk mengambil data user yang sedang login.

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// $request->user() akan mengembalikan data user yang sedang login (berdasarkan token / session Sanctum).

// Output-nya biasanya JSON berisi data user (misalnya id, name, email, dll).

