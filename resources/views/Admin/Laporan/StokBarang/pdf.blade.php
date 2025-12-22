<!DOCTYPE html>
<html lang="en">
<!-- Dokumen HTML -->

<?php

use App\Models\Admin\BarangkeluarModel;
// Import model Barang Keluar untuk hitung jumlah keluar

use App\Models\Admin\BarangmasukModel;
// Import model Barang Masuk untuk hitung jumlah masuk

use Carbon\Carbon;
// Import Carbon untuk format tanggal
?>

<head>
    <meta charset="UTF-8">
    <!-- Encoding UTF-8 -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Kompatibilitas browser -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Responsive layout -->

    <meta name="description" content="{{$web->web_deskripsi}}">
    <!-- Deskripsi web dari database -->

    <meta name="author" content="{{$web->web_nama}}">
    <!-- Author/nama web -->

    <meta name="keywords" content="">
    <!-- Keywords SEO (opsional) -->

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Token CSRF (umumnya tidak terlalu dibutuhkan di halaman print, tapi aman) -->

    <title>{{$title}}</title>
    <!-- Judul halaman -->

    <style>
        /* Style halaman laporan agar rapi saat dicetak/di-PDF-kan */

        * {
            font-family: Arial, Helvetica, sans-serif;
            /* Font standar */
        }

        #table1 {
            border-collapse: collapse;
            width: 100%;
            margin-top: 32px;
            /* Styling tabel laporan */
        }

        #table1 td,
        #table1 th {
            border: 1px solid #ddd;
            padding: 8px;
            /* Border & padding */
        }

        #table1 th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: black;
            font-size: 12px;
            /* Header tabel */
        }

        #table1 td {
            font-size: 11px;
            /* Isi tabel */
        }

        .font-medium {
            font-weight: 500;
            /* Font medium */
        }

        .font-bold {
            font-weight: 600;
            /* Font lebih tebal */
        }

        .d-2 {
            display: flex;
            align-items: flex-start;
            margin-top: 32px;
            /* Utility class (tidak dipakai langsung di file ini) */
        }
    </style>

</head>

<body>
    <!-- Body halaman -->

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
        <!-- Menampilkan rentang tanggal -->
        @endif
    </center>

    <table border="1" id="table1">
        <!-- Tabel laporan stok -->

        <thead>
            <tr>
                <th align="center" width="1%">NO</th>
                <!-- Nomor urut -->

                <th>KODE BARANG</th>
                <!-- Kode barang -->

                <th>BARANG</th>
                <!-- Nama barang -->

                <th>STOK AWAL</th>
                <!-- Stok awal (stok yang tersimpan di tabel barang) -->

                <th>JML MASUK</th>
                <!-- Total barang masuk (hasil hitung dari tabel barang masuk) -->

                <th>JML KELUAR</th>
                <!-- Total barang keluar (hasil hitung dari tabel barang keluar) -->

                <th>TOTAL</th>
                <!-- Total stok akhir = stok awal + masuk - keluar -->
            </tr>
        </thead>

        <tbody>
            @php $no=1; @endphp
            <!-- Inisialisasi nomor urut -->

            @foreach($data as $d)
            <!-- Loop data barang (umumnya dari tabel barang) -->

            <?php
            /* ============================================================
               HITUNG JUMLAH MASUK (BM) BERDASARKAN FILTER TANGGAL / SEMUA
               ============================================================ */

            if ($tglawal == '') {
                // Jika tidak ada filter tanggal: jumlahkan semua data masuk untuk barang ini
                $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                    ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                    ->where('tbl_barangmasuk.barang_kode', '=', $d->barang_kode)
                    ->sum('tbl_barangmasuk.bm_jumlah');
            } else {
                // Jika ada filter tanggal: jumlahkan data masuk pada rentang tglawal - tglakhir
                $jmlmasuk = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangmasuk.barang_kode')
                    ->leftJoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_barangmasuk.customer_id')
                    ->where('tbl_barangmasuk.barang_kode', '=', $d->barang_kode)
                    ->whereBetween('bm_tanggal', [$tglawal, $tglakhir])
                    ->sum('tbl_barangmasuk.bm_jumlah');
            }

            /* ============================================================
               HITUNG JUMLAH KELUAR (BK) BERDASARKAN FILTER TANGGAL / SEMUA
               ============================================================ */

            if ($tglawal) {
                // Jika ada tglawal (filter aktif): ambil jumlah keluar sesuai tanggal dan kode barang
                $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                    ->whereBetween('bk_tanggal', [$tglawal, $tglakhir])
                    ->where('tbl_barangkeluar.barang_kode', '=', $d->barang_kode)
                    ->sum('tbl_barangkeluar.bk_jumlah');
            } else {
                // Jika tidak ada filter: ambil jumlah keluar semua data untuk barang ini
                $jmlkeluar = BarangkeluarModel::leftJoin('tbl_barang', 'tbl_barang.barang_kode', '=', 'tbl_barangkeluar.barang_kode')
                    ->where('tbl_barangkeluar.barang_kode', '=', $d->barang_kode)
                    ->sum('tbl_barangkeluar.bk_jumlah');
            }

            /* ============================================================
               HITUNG TOTAL STOK AKHIR
               totalStok = stok_awal + (jumlah_masuk - jumlah_keluar)
               ============================================================ */
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
                <!-- Stok awal (dari data barang) -->

                <td align="center">{{$jmlmasuk}}</td>
                <!-- Jumlah masuk yang sudah dihitung -->

                <td align="center">{{$jmlkeluar}}</td>
                <!-- Jumlah keluar yang sudah dihitung -->

                <td align="center">{{$totalStok}}</td>
                <!-- Total stok akhir -->
            </tr>

            @endforeach
            <!-- Akhir loop -->
        </tbody>
    </table>

</body>
</html>
