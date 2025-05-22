<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Dinas Luar Kota</title>
    <style>
        body {
            font-size: 17px;
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100%;
            box-sizing: border-box;
        }
        .container {
            margin: 10px;
            border-collapse: collapse;
            border: 1px solid #000;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px;
        }
        .logo {
            width: 70px; /* Sesuaikan dengan ukuran logo Anda */
            height: auto;
        }
        .text-center {
            text-align: center !important;
        }
        .text-end {
            text-align: right !important;
        }
        .text-start {
            text-align: left !important;
        }
        h3 {
            text-align: center;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        .content {
            margin-top: 10px;
        }
        .info {
            margin-bottom: 4px;
            padding: 0px 14px;
        }
        .detail-info {
            display: flex;
        }
        .table-detail {
            width: 50%;
        }
        .table-detail td.title {
            padding-left: 32px;
        }
        .table {
            table-layout: fixed;
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }
        .th {
            border: 1px solid #000;
            padding: 0px 8px;
        }
        .td {
            padding: 0px 8px;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            vertical-align: top;
        }
        .footer {
            padding: 8px 0px 0px 40px;
            text-align: left;
        }
        .table-otoritas {
            width: 100%;
        }
        .tighter-text span {
            display: block;
            margin-bottom: 2px; /* atau 1px sesuai selera */
        }
        .text-muted {
            font-size: 11px;
        }
        @media print {
            @page {
                margin: 0; /* Mengurangi margin default agar header/footer dari browser tidak muncul */
            }
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img class="logo" src="{{ asset('logo/logo.png') }}" alt="Logo Perusahaan">
        <div class="text-center">
            <h3>SURAT DINAS LUAR KOTA</h3>
            <span>No. {{ $surat->no_surat }}</span>
        </div>
        <div style="visibility: hidden">FM-GA-02-06-00</div>
    </div>

    <div class="content">
        <div class="info">
            Yang bertanda tangan dibawah ini : <br>
            <div class="detail-info">
                <table class="table-detail">
                    <tr>
                        <td class="title">Nama</td>
                        <td>: {{ $surat->penerima->name }}</td>
                    </tr>
                    <tr>
                        <td class="title">Bagian</td>
                        <td>: {{ $surat->penerima->team->name }}</td>
                    </tr>
                    <tr>
                        <td class="title">Level</td>
                        <td>: {{ $surat->penerima->jabatan }}</td>
                    </tr>
                </table>
                <table class="table-detail">
                    <tr>
                        <td class="title">Kendaraan</td>
                        <td>: {{ $surat->kendaraan }}</td>
                    </tr>
                    <tr>
                        <td class="title">No Polisi</td>
                        <td>: {{ $surat->no_polisi }}</td>
                    </tr>
                </table>
            </div>
            Untuk melakukan perjalanan dinas luar kota ke {{ $surat->kota }} <br>
            selama {{ $surat->jangka_waktu }} {{ $surat->satuan_waktu }} yang dilaksanakan
            mulai tanggal {{ \Carbon\Carbon::parse($surat->berangkat)->format('d/m/Y') }} <br>
            dengan penjelasan sebagai berikut :
        </div>

        <table id="table-tujuan" class="table">
            <thead>
                <tr>
                    <th class="th text-center" style="width: 5%">No.</th>
                    <th class="th text-center">Instansi</th>
                    <th class="th text-center">Pejabat Ditemui</th>
                    <th class="th text-center">Tujuan</th>
                </tr>
            </thead>
            <tbody id="detail-body">
                @foreach ($surat->detail as $detail)
                <tr class="fill-row">
                    <td class="td text-center">{{ $loop->iteration }}</td>
                    <td class="td text-start">{{ $detail->instansi }}</td>
                    <td class="td text-start">{{ $detail->menemui }}</td>
                    <td class="td text-start">{{ $detail->tujuan }}</td>
                </tr>
                @endforeach
                <tr id="last-row">
                    <td class="td text-center">&nbsp;</td>
                    <td class="td">&nbsp;</td>
                    <td class="td">&nbsp;</td>
                    <td class="td">&nbsp;</td>
                </tr>
                <!-- Tambah baris sesuai kebutuhan -->
            </tbody>
        </table>
        <div class="info">
            Selambat-lambatnya 3 (tiga) hari setelah melaksanakan tugas, diwajibkan membuat
            laporan tertulis.
        </div>
    </div>

    <div class="footer">
        @php
            $tgl = \Carbon\Carbon::now()->translatedFormat('d F Y');
        @endphp
        Sidoarjo, {{ $tgl }}
        <table style="width: 100%">
            <tr>
                <td>Pemberi Tugas</td>
                <td>Penerima Tugas</td>
            </tr>
            <tr>
                <td>
                    <br>
                    <br>
                    <div class="tighter-text">
                        <span>{{ $surat->pemberi ? $surat->pemberi->name : '' }}</span>
                        <span>{{ $surat->pemberi ? $surat->pemberi->jabatan : '' }}</span>
                    </div>
                </td>
                <td>
                    <br>
                    <br>
                    {{-- <img src="{{ asset('signature.png') }}" height="38px" alt="TTD {{ $surat->user ? $surat->user->name : '' }}"> --}}
                    <div class="tighter-text">
                        <span>{{ $surat->penerima ? $surat->penerima->name : '' }}</span>
                        <span>{{ $surat->penerima ? $surat->penerima->jabatan : '' }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
    window.onload = function() {
        const container = document.querySelector('.container');
        const lastRow = document.getElementById('last-row');
        const lastRowHeight = lastRow.offsetHeight;
        const bodyTargetHeight = 559 ; // setengah A4 dalam mm

        // Hitung tinggi header, bagian "dibayar kepada", dan footer
        const usedHeight = container.offsetHeight - lastRowHeight;

        const remainingHeight = bodyTargetHeight - usedHeight;

        if (remainingHeight > 1) {
            lastRow.style.lineHeight = `${remainingHeight}px`;
        } else {
            lastRow.style.display = `none`;
        }

        window.print();
    };
</script>
</body>
</html>
