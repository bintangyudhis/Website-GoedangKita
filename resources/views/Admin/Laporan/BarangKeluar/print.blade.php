<!DOCTYPE html>
<html lang="en">
<!-- Deklarasi dokumen HTML dan bahasa -->

<?php
use Carbon\Carbon;
// Import library Carbon untuk format tanggal
?>

<head>
    <meta charset="UTF-8">
    <!-- Encoding karakter UTF-8 -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Kompatibilitas browser -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Responsive layout -->

    <meta name="description" content="{{$web->web_deskripsi}}">
    <!-- Deskripsi website dari database -->

    <meta name="author" content="{{$web->web_nama}}">
    <!-- Nama website / author -->

    <meta name="keywords" content="">
    <!-- Keyword SEO (kosong) -->

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Token CSRF Laravel -->

    <!-- FAVICON -->
    @if($web->web_logo == '' || $web->web_logo == 'default.png')
        <!-- Jika logo belum di-set atau default -->
        <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/default/web/default.png')}}" />
    @else
        <!-- Jika logo custom tersedia -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/web/' . $web->web_logo)}}" />
    @endif

    <title>{{$title}}</title>
    <!-- Judul halaman -->

    <style>
        /* Style khusus halaman print */

        * {
            font-family: Arial, Helvetica, sans-serif;
            /* Font standar agar rapi saat dicetak */
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
            /* Border dan padding sel tabel */
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
            /* Font tebal */
        }

        .d-2 {
            display: flex;
            align-items: flex-start;
            margin-top: 32px;
            /* Utility layout */
        }
    </style>

</head>

<body onload="window.print()">
<!-- Saat halaman dibuka, langsung memicu dialog print -->

    <center>
        <!-- Logo laporan -->
        @if($web->web_logo == '' || $web->web_logo == 'default.png')
            <!-- Logo default -->
            <img src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
        @else
            <!-- Logo custom dari database -->
            <img src="{{asset('storage/web/' . $web->web_logo)}}" width="80px" alt="">
        @endif
    </center>

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
        <!-- Tabel laporan -->

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
            <!-- Variabel nomor urut -->

            @foreach($data as $d)
            <!-- Loop data laporan barang keluar -->

            <tr>
                <td align="center">{{$no++}}</td>
                <!-- Nomor urut -->

                <td>{{Carbon::parse($d->bk_tanggal)->translatedFormat('d F Y')}}</td>
                <!-- Tanggal keluar diformat dengan Carbon -->

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
            <!-- Akhir loop -->
        </tbody>
    </table>

</body>

</html>
