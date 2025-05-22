<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Ijin Masuk Pabrik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 16px;
            padding: 8px 4px;
            width: 100%; /* Lebar A4 */
            height: 100%; /* Tinggi A4 */
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
            text-align: center;
            padding: 4px;
        }
        .header img {
            width: 100px; /* Logo */
        }
        h2 {
            margin: 0;
        }
        .justify-content-between {
            display: flex;
            justify-content: space-between;
        }
        .content {
            margin-top: 0px;
            padding-left: 8px;
            /* width: 0%; */
        }
        .content table {
            table-layout: fixed;
            width: 100%;
            padding: 0px 0px !important;
        }
        .content table tr td.title {
            width: 20%;
        }
        .content table tr td {
            vertical-align: top;
        }
        .content td {
            padding: 0px 8px;
        }
        .signature {
            margin: 40px 16px 8px 2px;
            padding: 0px 8px;
            width: 40%;
            border: 1px solid #000;
            position: relative;
        }
        .ttd-pjs {
            position: absolute;
            bottom: 0;
        }
        .footer {
            padding-top: 8px;
            padding-left: 8px;
        }
        .table-otoritas {
            width: 100%;
        }
        .tighter-text span {
            display: block;
            margin-bottom: 2px; /* atau 1px sesuai selera */
        }
        .rekomendasi {
            border-top: 1px solid #000;
            padding-left: 8px;
        }
        #id {
            width: 60px;
            height: 60px;
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="{{ asset('logo/logo.png') }}" height="60px" alt="Logo"> <!-- Ganti dengan path logo Anda -->
        <div>
            <h2>SURAT IJIN MASUK PABRIK</h2>
            <span>No. {{ $surat->no_surat }}</span>
        </div>
        <div style="max-height: 60px">
            <span id="qrcode"></span>
        </div>
    </div>
    <div class="content">
        <p>Sidoarjo,</p>
        <p>Kepada Yth.<br>
        Direktur<br>
        PT. TOYA INDO MANUNGGAL<br>
        Di Tempat</p>

        Dengan hormat,<br>
        Saya yang bertanda tangan dibawah ini :

        <table>
            <tr>
                <td class="title">Nama</td>
                <td>: {{ $surat->nama }}</td>
            </tr>
        </table>

        Dengan ini mengajukan ijin untuk masuk ke pabrik PT. Toya Indo Manunggal pada :
        @php
            $masuk = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->masuk)->format('Y-m-d');
            $keluar = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->keluar)->format('Y-m-d');
        @endphp
        <table>
            <tr>
                <td class="title">Hari, Tanggal Masuk</td>
                @php
                    $tgl_masuk = $surat->masuk ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->masuk)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '';
                    $tgl_keluar = $surat->keluar ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->keluar)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '';
                    $jam_masuk = $surat->masuk ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->masuk)->locale('id')->isoFormat('H:mm') : '';
                    $jam_keluar = $surat->keluar ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->keluar)->locale('id')->isoFormat('H:mm') : '';
                @endphp
                <td style="width: 27ch">
                    : {{ $tgl_masuk }}<br>&nbsp;&nbsp;{{ $jam_masuk }}
                </td>
                <td style="width: 10%">Keperluan</td>
                <td>: {{ $surat->keperluan }}</td>
            </tr>
            <tr>
                <td class="title">Hari, Tanggal Keluar</td>
                <td>
                    : {{ $tgl_keluar }}<br>&nbsp;&nbsp;{{ $jam_keluar }}
                </td>
                <td style="width: 10%">No Polisi</td>
                <td>: {{ $surat->nopol }}</td>
            </tr>
        </table>

    </div>
    <div class="footer">
        Demikian surat permohonan ini saya buat dengan sebenar-benarnya, apabila dikemudian hari terdapat keterangan yang saya buat ternyata tidak benar dan atau menimbulkan kerugian bagi perusahaan, saya bersedia bertanggung jawab sepenuhnya.
        <table class="table-otoritas">
            <tr>
                <td>Hormat saya,</td>
            </tr>
            <tr>
                <td>Pemohon</td>
            </tr>
            <tr>
                <td>
                    <br>
                    <br>
                    <div class="tighter-text">
                        <span>{{ $surat->nama }}</span>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
<script>
    let url = "{{ route('ijinpabrik.show', $surat->id) }}";
    // url = url.replace('__id__', "{{$surat->id}}");
    var qrcode = new QRCode("qrcode", {
        text: url,
        width: 60,
        height: 60,
    });
    window.print();
</script>
</body>
</html>
