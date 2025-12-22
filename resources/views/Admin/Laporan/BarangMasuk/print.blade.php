<!DOCTYPE html>
<html lang="en"> <!-- Dokumen HTML dengan bahasa Inggris -->

<?php
use Carbon\Carbon;
// Import library Carbon untuk manipulasi & format tanggal
?>

<head>
    <meta charset="UTF-8">
    <!-- Encoding karakter UTF-8 -->

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Kompatibilitas browser -->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Agar tampilan responsif -->

    <meta name="description" content="{{$web->web_deskripsi}}">
    <!-- Deskripsi website dari database -->

    <meta name="author" content="{{$web->web_nama}}">
    <!-- Nama author / aplikasi -->

    <meta name="keywords" content="">
    <!-- Keyword SEO (opsional) -->

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Token CSRF Laravel -->

    <!-- FAVICON -->
    @if($web->web_logo == '' || $web->web_logo == 'default.png')
        <!-- Jika logo kosong atau default -->
        <link rel="shortcut icon" type="image/x-icon" href="{{url('/assets/default/web/default.png')}}" />
    @else
        <!-- Jika logo custom tersedia -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/web/' . $web->web_logo)}}" />
    @endif

    <title>{{$title}}</title>
    <!-- Judul halaman -->

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            /* Font global */
        }

        #table1 {
            border-collapse: collapse;
            width: 100%;
            margin-top: 32px;
            /* Style tabel utama */
        }

        #table1 td,
        #table1 th {
            border: 1px solid #ddd;
            padding: 8px;
            /* Border & padding sel */
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
            /* Ukuran teks isi tabel */
        }

        .font-medium {
            font-weight: 500;
            /* Font sedang */
        }

        .font-bold {
            font-weight: 600;
            /* Font tebal */
        }

        .d-2 {
            display: flex;
            align-items: flex-start;
            margin-top: 32px;
            /* Utility class */
        }
    </style>

</head>

<body onload="window.print()">
    <!-- Body halaman, otomatis memanggil print saat dibuka -->

    <center>
        <!-- Logo aplikasi -->
        @if($web->web_logo == '' || $web->web_logo == 'default.png')
            <!-- Logo default -->
            <img src="{{url('/assets/default/web/default.png')}}" width="80px" alt="">
        @else
            <!-- Logo custom -->
            <img src="{{asset('storage/web/' . $web->web_logo)}}" width="80px" alt="">
        @endif
    </center>

    <center>
        <!-- Judul laporan -->
        <h1 class="font-medium">Laporan Barang Masuk</h1>

        @if($tglawal == '')
            <!-- Jika tidak menggunakan filter tanggal -->
            <h4 class="font-medium">Semua Tanggal</h4>
        @else
            <!-- Jika menggunakan filter tanggal -->
            <h4 class="font-medium">
                {{Carbon::parse($tglawal)->translatedFormat('d F Y')}}
                -
                {{Carbon::parse($tglakhir)->translatedFormat('d F Y')}}
            </h4>
        @endif
    </center>

    <table border="1" id="table1">
        <!-- Tabel laporan barang masuk -->

        <thead>
            <tr>
                <th align="center" width="1%">NO</th>
                <!-- Nomor urut -->

                <th>TGL MASUK</th>
                <!-- Tanggal barang masuk -->

                <th>KODE BRG MASUK</th>
                <!-- Kode transaksi barang masuk -->

                <th>KODE BARANG</th>
                <!-- Kode barang -->

                <th>CUSTOMER</th>
                <!-- Nama customer -->

                <th>BARANG</th>
                <!-- Nama barang -->

                <th>JML MASUK</th>
                <!-- Jumlah barang masuk -->
            </tr>
        </thead>

        <tbody>
            @php $no = 1; @endphp
            <!-- Inisialisasi nomor urut -->

            @foreach($data as $d)
            <!-- Loop data barang masuk -->

            <tr>
                <td align="center">{{$no++}}</td>
                <!-- Nomor baris -->

                <td>
                    {{Carbon::parse($d->bm_tanggal)->translatedFormat('d F Y')}}
                </td>
                <!-- Format tanggal masuk -->

                <td>{{$d->bm_kode}}</td>
                <!-- Kode barang masuk -->

                <td>{{$d->barang_kode}}</td>
                <!-- Kode barang -->

                <td>{{$d->customer_nama}}</td>
                <!-- Nama customer -->

                <td>{{$d->barang_nama}}</td>
                <!-- Nama barang -->

                <td align="center">{{$d->bm_jumlah}}</td>
                <!-- Jumlah barang masuk -->
            </tr>

            @endforeach
            <!-- Akhir looping -->
        </tbody>
    </table>

</body>
</html>

