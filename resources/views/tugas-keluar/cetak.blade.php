<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas Keluar</title>
    <style>
        body {
            font-size: 17px;
            font-family: Arial, sans-serif;
            margin: 0;
            height: auto;
            box-sizing: border-box;
        }
        .container {
            margin: 8px;
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
        .table {
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
            <h3>SURAT TUGAS KELUAR</h3>
            <span>No. {{ $surat->no_surat }}</span>
        </div>
        <div>FM-GA-02-06-00</div>
    </div>

    <div class="content">
        <div class="info">
            Dengan ini menugaskan : <br>
            <span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Nama
            </span>
            <span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                : {{ $surat->penerima->name }}
            </span>
            <br>
            <span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                Bagian
            </span>
            <span>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                : {{ $surat->penerima->jabatan }}
            </span>
            <br>
            Untuk melaksanakan tugas dinas luar kantor pada : <br>
            @php
                $tgl_awal = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_awal)->format('Y-m-d');
                $tgl_akhir = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_akhir)->format('Y-m-d');
            @endphp
            <table style="">
                <tr>
                    <td style="padding-left: 40px; vertical-align: top;">Hari, tanggal</td>
                    <td style=" vertical-align: top;">
                        @if ($tgl_awal == $tgl_akhir)
                        : {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_awal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        @else
                        : {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_awal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        sampai <br> &nbsp; {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_akhir)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                        @endif
                    </td>
                    <td style="padding-left: 75px; vertical-align: top;">Kendaraan</td>
                    <td style=" vertical-align: top;">: {{ $surat->kendaraan }} </td>
                </tr>
                <tr>
                    <td style=" padding-left: 40px; vertical-align: top;">Jam</td>
                    <td style=" vertical-align: top;">
                        : {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_awal)->format('H:i') }}
                        sampai dengan {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_akhir)->format('H:i') }}
                    </td>
                    <td style=" padding-left: 75px; vertical-align: top;">No Polisi</td>
                    <td style=" vertical-align: top;">: {{ $surat->no_polisi }} </td>
                </tr>
            </table>
            Dengan tujuan sebagai berikut :
        </div>

        <table id="table-detail" class="table">
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
    </div>

    <div class="footer">
        Sidoarjo,
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
        const header = document.querySelector('.header');
        const info = document.querySelector('.info');
        const table = document.getElementById('table-detail');
        const tbody = document.getElementById('detail-body');
        // const tbody = document.querySelectorAll('.fill-row');
        const lastRow = document.getElementById('last-row');
        const footer = document.querySelector('.footer');

        const bodyTargetHeight = 559; // setengah A4 dalam mm

        // Hitung tinggi header, bagian "dibayar kepada", dan footer
        const usedHeight = header.offsetHeight + info.offsetHeight + footer.offsetHeight;

        const currentTableHeight = table.offsetHeight;
        const tbodyHeight = tbody.offsetHeight;
        const remainingHeight = bodyTargetHeight - usedHeight - currentTableHeight;

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
