<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Dinas Luar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            margin: 0;
            /* padding: 20px; */
            width: 100%;
            height: 100%;
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
        .text-center {
            text-align: center !important;
        }
        .text-end {
            text-align: right !important;
        }
        .text-start {
            text-align: left !important;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        .content {
            margin-top: 10px;
        }
        .info {
            display: flex;
            justify-content: space-between;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            /* border: 1px solid #000; */
        }
        .th {
            /* border: 1px solid #000; */
            padding: 0px 8px;
        }
        .td {
            padding: 0px 8px;
        }
        #td-detail {
            vertical-align: top;
        }
        .border-top {
            border-top: 1px solid #000;
        }
        .border-bottom {
            border-bottom: 1px solid #000;
        }
        .border-left {
            border-left: 1px solid #000;
        }
        .border-right {
            border-right: 1px solid #000;
        }
        .text-muted {
            font-size: 11px;
        }
        .tighter-text span {
            display: block;
            margin-bottom: 2px; /* atau 1px sesuai selera */
        }
        .footer {
            padding: 8px 0px 0px 40px;
            text-align: left;
        }
        ul {
            margin-block-start: 4px;
            margin-block-end: 4px;
            padding-inline-start: 16px;
        }
        @media print {
            @page {
                size: A4;
                margin: 0; /* Mengurangi margin default agar header/footer dari browser tidak muncul */
            }
            body {
                /* margin: 2; */
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('logo/logo.png') }}" height="50px" alt="Logo Perusahaan">
        <div class="text-center">
            <h1>FORM DINAS LUAR</h1>
            <span>No. {{ $surat->no_surat }}</span>
        </div>
        <div id="qrcode"></div>
        {{-- <img src="{{ asset('logo/logo.png') }}" style="visibility: hidden;" height="50px" alt="Logo Perusahaan"> --}}
    </div>

    <div class="content">
        <div class="info">
            <table class="table border-top border-right">
                <tr>
                    <td style=" padding-left: 10px;">Nama</td>
                    <td style="">: {{ $surat->user->name }}</td>
                </tr>
                <tr>
                    <td style=" padding-left: 10px;">Departemen</td>
                    <td style="">: {{ $surat->user->team->name }} </td>
                </tr>
                <tr>
                    <td style=" padding-left: 10px;">Tipe Kendaraan</td>
                    <td style="">: {{ $surat->tipe_kendaraan }} </td>
                </tr>
                <tr>
                    <td style=" padding-left: 10px;">No. Polisi</td>
                    <td style="">: {{ $surat->no_polisi }} </td>
                </tr>
                <tr>
                    <td style=" padding-left: 10px;" colspan="2">Waktu</td>
                </tr>
                <tr>
                    <td style=" padding-left: 20px;">Berangkat</td>
                    <td style="">: {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->berangkat)->locale('id')->isoFormat('dddd, D MMMM YYYY H:mm') }} </td>
                </tr>
                <tr>
                    <td style=" padding-left: 20px;">Kembali</td>
                    <td style="">: {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->kembali)->locale('id')->isoFormat('dddd, D MMMM YYYY H:mm') }} </td>
                </tr>
            </table>
            <table class="table border-top">
                <tr>
                    <td style=" padding-left: 10px;">Instansi Tujuan</td>
                    <td style="">: {{ $surat->instansi }}</td>
                </tr>
                <tr>
                    <td style=" padding-left: 10px;">Nama Pejabat</td>
                    <td style="">: {{ $surat->nama_pejabat }} </td>
                </tr>
                <tr>
                    <td style=" padding-left: 10px;">Alamat</td>
                    <td style="">: {{ $surat->alamat }} </td>
                </tr>
                <tr>
                    <td style=" padding-left: 10px;">No. Telepon</td>
                    <td style="">: {{ $surat->no_telp }} </td>
                </tr>
                <tr>
                    <td style=" padding-left: 10px;">Tujuan Dinas</td>
                    <td style="">: {{ $surat->tujuan }} </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
        <table class="table border-top border-bottom">
            <thead>
                <tr>
                    <th class="th text-center border-bottom">RINGKASAN HASIL DINAS LUAR</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="td-detail" class="td text-start">
                        @foreach ($surat->detail as $detail)
                            - {{ $detail->deskripsi }} <br>
                        @endforeach
                    </td>
                </tr>
                <!-- Tambah baris sesuai kebutuhan -->
            </tbody>
        </table>
        <table class="table">
            <tr>
                <th rowspan="2" class="th border-right">Pemohon</th>
                <th colspan="2" class="th border-bottom border-right">Berangkat</th>
                <th colspan="2" class="th border-bottom border-right">Kembali</th>
                <th rowspan="2" class="th border-right">Leader</th>
                <th rowspan="2" class="th">Instansi Tujuan</th>
            </tr>
            <tr>
                <th class="th border-right">HCM</th>
                <th class="th border-right">FO</th>
                <th class="th border-right">HCM</th>
                <th class="th border-right">FO</th>
            </tr>
            <tr>
                <td class="td text-center border-top border-right" style="vertical-align: bottom;">
                    <div class="tighter-text">
                        <span>{{ $surat->dibuat ? $surat->dibuat->name : '' }}</span>
                        <span class="text-muted">{{ $surat->dibuat_at ? \Carbon\Carbon::parse($surat->dibuat_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                    </div>
                </td>
                <td class="td border-top border-right">
                </td>
                <td class="td border-top border-right">
                </td>
                <td class="td border-top border-right">
                </td>
                <td class="td border-top border-right">
                </td>
                <td class="td text-center border-top border-right">
                </td>
                <td class="td text-center border-top">
                    <br>
                    <br>
                    <br>
                    Nama dan Stempel
                </td>
            </tr>
        </table>
    </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    let url = "{{ route('dinasluar.show', $surat->id) }}";
    new QRCode("qrcode", { text: url, width: 65, height: 65 });
    window.onload = function() {
        const header = document.querySelector('.header');
        const container = document.querySelector('.container');
        const content = document.querySelector('.info');
        const tdDetail = document.getElementById('td-detail');

        const bodyTargetHeight = 559; // setengah A4 dalam px

        // Hitung tinggi header, bagian "dibayar kepada", dan footer
        const usedHeight = header.offsetHeight + content.offsetHeight;
        const containerHeight = container.offsetHeight;

        const remainingHeight = bodyTargetHeight - containerHeight;

        if (remainingHeight > 1) {
            tdDetail.style.height = `${remainingHeight}px`;
        }

        window.print();
    };
</script>
</body>
</html>
