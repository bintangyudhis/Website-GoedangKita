<!DOCTYPE html>
<html lang="en">
<!-- Deklarasi dokumen HTML dan bahasa -->

<?php
use Carbon\Carbon;
// Import library Carbon untuk manipulasi & format tanggal
?>

<head>
    <meta charset="UTF-8">
    <!-- Set encoding karakter ke UTF-8 -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Kompatibilitas dengan browser lama (IE) -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Agar tampilan responsif di berbagai device -->

    <meta name="description" content="{{$web->web_deskripsi}}">
    <!-- Meta deskripsi website (diambil dari database) -->

    <meta name="author" content="{{$web->web_nama}}">
    <!-- Nama author / website -->

    <meta name="keywords" content="">
    <!-- Keyword SEO (kosong) -->

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Token CSRF Laravel untuk keamanan -->

    <title>{{$title}}</title>
    <!-- Judul halaman laporan -->

    <style>
        /* Style khusus laporan cetak / PDF */

        * {
            font-family: Arial, Helvetica, sans-serif;
            /* Font standar agar rapi saat print/PDF */
        }

        #table1 {
            border-collapse: collapse;
            /* Menggabungkan border tabel */
            width: 100%;
            margin-top: 32px;
        }

        #table1 td,
        #table1 th {
            border: 1px solid #ddd;
            /* Border tabel */
            padding: 8px;
        }

        #table1 th {
            padding-top: 12px;
            padding-bottom: 12px;
            color: black;
            font-size: 12px;
            /* Styling header tabel */
        }

        #table1 td {
            font-size: 11px;
            /* Ukuran font isi tabel */
        }

        .font-medium {
            font-weight: 500;
            /* Font medium */
        }

        .font-bold {
            font-weight: 600;
            /* Font bold */
        }

        .d-2 {
            display: flex;
            align-items: flex-start;
            margin-top: 32px;
            /* Utility layout */
        }
    </style>

</head>

<body>

    <center>
        <!-- Judul laporan -->
        <h1 class="font-medium">Laporan Barang Keluar</h1>

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
        <!-- Menampilkan rentang tanggal dengan format Indonesia -->
        @endif
    </center>


    <table border="1" id="table1">
        <!-- Tabel laporan barang keluar -->

        <thead>
            <tr>
                <th align="center" width="1%">NO</th>
                <!-- Nomor urut -->

                <th>TGL KELUAR</th>
                <!-- Tanggal barang keluar -->

                <th>KODE BRG KELUAR</th>
                <!-- Kode transaksi barang keluar -->

                <th>KODE BARANG</th>
                <!-- Kode barang -->

                <th>BARANG</th>
                <!-- Nama barang -->

                <th>JML KELUAR</th>
                <!-- Jumlah barang keluar -->

                <th>TUJUAN</th>
                <!-- Tujuan pengeluaran barang -->
            </tr>
        </thead>

        <tbody>
            @php $no=1; @endphp
            <!-- Variabel nomor urut manual -->

            @foreach($data as $d)
            <!-- Loop data barang keluar -->

            <tr>
                <td align="center">{{$no++}}</td>
                <!-- Nomor urut -->

                <td>{{Carbon::parse($d->bk_tanggal)->translatedFormat('d F Y')}}</td>
                <!-- Tanggal keluar diformat menggunakan Carbon -->

                <td>{{$d->bk_kode}}</td>
                <!-- Kode barang keluar -->

                <td>{{$d->barang_kode}}</td>
                <!-- Kode barang -->

                <td>{{$d->barang_nama}}</td>
                <!-- Nama barang -->

                <td align="center">{{$d->bk_jumlah}}</td>
                <!-- Jumlah barang keluar -->

                <td>{{$d->bk_tujuan}}</td>
                <!-- Tujuan barang keluar -->
            </tr>

            @endforeach
            <!-- Akhir loop data -->
        </tbody>
    </table>

</body>

</html>
