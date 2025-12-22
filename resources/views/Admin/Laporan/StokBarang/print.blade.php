<!DOCTYPE html>
<html lang="en"> <!-- Dokumen HTML, bahasa Inggris -->

<?php

use App\Models\Admin\BarangkeluarModel;
// Import model Barang Keluar untuk menghitung jumlah keluar per barang

use App\Models\Admin\BarangmasukModel;
// Import model Barang Masuk untuk menghitung jumlah masuk per barang

use Carbon\Carbon;
// Import Carbon untuk format tanggal laporan
?>

<head>
    <meta charset="UTF-8">
    <!-- Encoding UTF-8 -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Kompatibilitas browser -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Agar responsif pada mobile -->

    <meta name="description" content="{{$web->web_deskripsi}}">
    <!-- Meta deskripsi diambil dari data web -->

    <meta name="author" content="{{$web->web_nama}}">
    <!-- Meta author diambil dari data web -->

    <meta name="keywords" content="">
    <!-- Meta keywords (opsional) -->

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Token CSRF Laravel (tidak wajib untuk print, tapi aman) -->

    <!-- FAVICON -->
    @if($web->web_logo == '' || $web->web_logo == 'default.png')
        <!-- Jika logo kosong atau default -->
        <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/default/web/default.png')}}" />
    @else
        <!-- Jika logo custom tersedia -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/web/' . $web->web_logo)}}" />
    @endif

    <title>{{$title}}</title>
    <!-- Judul halaman dinamis -->

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            /* Font default halaman */
        }

        #table1 {
            border-collapse: collapse;
            width: 100%;
            margin-top: 32px;
            /* Style tabel (rapi untuk print) */
        }

        #table1 td,
        #table1 th {
            border: 1px solid #ddd;
            padding: 8px;
            /* Border dan padding tabel */
        }

        #table1 th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: black;
            font-size: 12px;
            /* Style header tabel */
        }

        #table1 td {
            font-size: 11px;
            /* Style isi tabel */
        }

        .font-medium {
            font-weight: 500;
            /* Font sedang */
        }

        .font-bold {
            font-weight: 600;
            /* Font lebih tebal */
        }

        .d-2 {
            display: flex;
            align-items: flex-start;
            margin-top: 32px;
            /* Utility class (tidak dipakai di file ini) */
        }
    </style>

</head>

<body onload="window.print()">
    <!-- Body halaman: otomatis menampilkan dialog print saat halaman dibuka -->

    <center>
        <!-- Menampilkan logo di atas laporan -->
        @if($web->web_logo == '' || $web->web_logo == 'default.png')
            <!-- Jika logo default -->
            <img src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
        @else
            <!-- Jika logo custom (di kode ini masih memakai default juga) -->
            <img src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
            <!-- Catatan: seharusnya bisa pakai asset('storage/web/' . $web->web_logo) -->
        @endif
    </center>

    <center>
        <!-- Judul laporan -->
        <h1 class="font-medium">Laporan Stok Barang</h1>

        @if($tglawal == '')
            <!-- Jika tidak ada filter tanggal -->
            <h4 class="font-medium">Semua Tanggal</h4>
        @else
            <!-- Jika ada filter tanggal -->
            <h4 class="font-medium">
                {{Carbon::parse($tglawal)->translatedFormat('d F Y')}}
                -
                {{Carbon::parse($tglakhir)->translatedFormat('d F Y')}}
            </h4>
            <!-- Menampilkan rentang tanggal dalam format Indonesia -->
        @endif
    </center>

    <table border="1" id="table1">
        <!-- Tabel laporan stok barang -->

        <thead>
            <tr>
                <th align="center" width="1%">NO</th>
                <!-- Nomor urut -->

                <th>KODE BARANG</th>
                <!-- Kode barang -->

                <th>BARANG</th>
                <!-- Nama barang -->

                <th>STOK AWAL</th>
                <!-- Stok awal dari tabel barang -->

                <th>JML MASUK</th>
                <!-- Total barang masuk (hasil hitung) -->

                <th>JML KELUAR</th>
                <!-- Total barang keluar (hasil hitung) -->

                <th>TOTAL</th>
                <!-- Total stok akhir -->
            </tr>
        </thead>

        <tbody>
            @php $no=1; @endphp
            <!-- Inisialisasi nomor urut -->

            @foreach($data as $d)
            <!-- Loop data barang -->

            <?php
            /* ==============================
               HITUNG JUMLAH MASUK (BM)
               ============================== */
            if($tglawal == ''){
                // Jika tidak ada filter tanggal: jumlahkan semua BM untuk barang ini
                $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                    ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                    ->where('tbl_barangmasuk.barang_kode', '=', $d->barang_kode)
                    ->sum('tbl_barangmasuk.bm_jumlah');
            }else{
                // Jika ada filter tanggal: jumlahkan BM pada range tglawal - tglakhir
                $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                    ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                    ->where('tbl_barangmasuk.barang_kode', '=', $d->barang_kode)
                    ->whereBetween('bm_tanggal', [$tglawal, $tglakhir])
                    ->sum('tbl_barangmasuk.bm_jumlah');
            }

            /* ==============================
               HITUNG JUMLAH KELUAR (BK)
               ============================== */
            if ($tglawal != '') {
                // Jika filter tanggal aktif: jumlahkan BK sesuai range tanggal
                $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                    ->whereBetween('bk_tanggal', [$tglawal, $tglakhir])
                    ->where('tbl_barangkeluar.barang_kode', '=', $d->barang_kode)
                    ->sum('tbl_barangkeluar.bk_jumlah');
            } else {
                // Jika tidak ada filter tanggal: jumlahkan semua BK untuk barang ini
                $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                    ->where('tbl_barangkeluar.barang_kode', '=', $d->barang_kode)
                    ->sum('tbl_barangkeluar.bk_jumlah');
            }

            /* ==============================
               HITUNG TOTAL STOK AKHIR
               totalStok = stok_awal + (masuk - keluar)
               ============================== */
            $totalStok = $d->barang_stok + ($jmlmasuk - $jmlkeluar);
            ?>

            <tr>
                <td align="center">{{$no++}}</td>
                <!-- Nomor urut -->

                <td>{{$d->barang_kode}}</td>
                <!-- Kode barang -->

                <td>{{$d->barang_nama}}</td>
                <!-- Nama barang -->

                <td align="center">{{$d->barang_stok}}</td>
                <!-- Stok awal -->

                <td align="center">{{$jmlmasuk}}</td>
                <!-- Jumlah masuk -->

                <td align="center">{{$jmlkeluar}}</td>
                <!-- Jumlah keluar -->

                <td align="center">{{$totalStok}}</td>
                <!-- Total stok akhir -->
            </tr>

            @endforeach
            <!-- Akhir loop -->
        </tbody>
    </table>

</body>
</html>
