<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Ijin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 15px;
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }
        .container {
            margin: 16px;
            border-collapse: collapse;
            border: 1px solid #000;
            max-width: 100%;
            word-break: break-word;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            padding: 4px;
        }
        /* .header img {
            width: 100px;
        } */
        .logo {
            width: 65px; /* Sesuaikan dengan ukuran logo Anda */
            height: auto;
        }
        .img-qrcode {
            display: flex;
            justify-content: space-between;
        }
        h2 {
            margin: 0;
        }
        .justify-content-between {
            display: flex;
            justify-content: space-between;
            min-width: 0;
            max-width: 100%;
        }
        .content {
            margin-top: 0px;
            padding-left: 8px;
            width: 60%;
            min-width: 0;
            max-width: 100%;
            word-break: break-word;
        }
        .content table {
            table-layout: fixed;
            width: 100%;
            padding: 0px 8px;
        }
        .content table td.title {
            width: 25%;
            vertical-align: top;
        }
        .content table td {
            padding: 0px 8px;
        }
        .content table td.keperluan {
            white-space: nowrap;
            /* overflow: hidden; */
            /* text-overflow: ellipsis; */
        }
        .signature {
            margin: 14px 16px 24px 2px;
            padding: 0px 8px;
            width: 40%;
            border: 1px solid #000;
            position: relative;
        }
        .signature p {
            margin: 4px 0 0 0;
        }
        .signature ul {
            margin-block-start: 4px;
            margin-block-end: 4px;
            padding-inline-start: 20px;
        }
        .ttd-pjs {
            position: absolute;
            bottom: 0;
            margin-bottom: 4px;
        }
        .footer {
            padding-left: 8px;
        }
        .table-otoritas {
            width: 100%;
        }
        .text-muted {
            font-size: 11px;
        }
        .tighter-text span {
            display: block;
            margin-bottom: 2px;
        }
        .rekomendasi {
            border-top: 1px solid #000;
            padding-left: 8px;
        }
        .table-rekomendasi > tr > th,
        .table-rekomendasi > tr > td {
            padding: 2px 10px !important;
        }
        @media print {
            @page {
                size: A4;
                margin: 0;
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
            <div class="img-qrcode">
                <img src="{{ asset('logo/logo.png') }}" class="logo" alt="Logo Perusahaan">
                &nbsp;
                <div id="qrcode"></div>
            </div>
            <div>
                <h2>SURAT IJIN</h2>
                <span>No. {{ $surat->no_surat }}</span>
            </div>
            <div>FM-GA-02-02-00</div>
        </div>
        <div class="justify-content-between">
            <div class="content">
                <p style="margin-block-start: 8px; margin-block-end: 8px;">
                    Sidoarjo, {{ $surat->dibuat_at ? \Carbon\Carbon::parse($surat->dibuat_at)->translatedFormat('d F Y') : '' }}
                </p>
                <p style="margin-block-start: 8px; margin-block-end: 8px;">
                    Kepada Yth.<br>
                    Direktur<br>
                    PT. TOYA INDO MANUNGGAL<br>
                    Di Tempat
                </p>

                <p style="margin-block-start: 8px; margin-block-end: 0;">
                    Dengan hormat, <br>
                    Saya yang bertanda tangan dibawah ini :
                </p>

                <table>
                    <tr>
                        <td class="title">Nama</td>
                        <td>: {{ $surat->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="title">Jabatan</td>
                        <td>: {{ $surat->user->jabatan }}</td>
                    </tr>
                </table>
                <p style="margin-block-start: 2px; margin-block-end: 0;">
                    Saya yang bertanda tangan dibawah ini :
                </p>
                @php
                    $tgl_awal = \Carbon\Carbon::parse($surat->tgl_awal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                    $tgl_akhir = \Carbon\Carbon::parse($surat->tgl_akhir)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                    $jam_awal = \Carbon\Carbon::parse($surat->tgl_awal)->format('H:i');
                    $jam_akhir = \Carbon\Carbon::parse($surat->tgl_akhir)->format('H:i');
                    if ($tgl_awal == $tgl_akhir) {
                        $tgl = $tgl_awal;
                    } else {
                        $tgl = $tgl_awal.' s/d '.$tgl_akhir;
                    }
                @endphp
                <table>
                    <tr>
                        <td class="title">Hari, tanggal</td>
                        <td>: {{ $tgl }}</td>
                    </tr>
                    <tr>
                        <td class="title">Jam</td>
                        <td>: {{ $jam_awal }} sampai dengan {{ $jam_akhir }}</td>
                    </tr>
                    <tr>
                        <td class="title">Keperluan</td>
                        <td class="keperluan">: {{ $surat->keperluan }}</td>
                    </tr>
                </table>
            </div>
            <div class="signature">
                <p>Nama PJS : {{ $surat->pjs[0]->penganti ? $surat->pjs[0]->penganti->name : '' }}</p>
                <p>Pelimpahan Tugas Sementara :</p>
                <ul>
                    @foreach ($surat->pjs as $pjs)
                        <li>{{ $pjs->tugas }}</li>
                    @endforeach
                </ul>
                <div class="ttd-pjs">Tanda Tangan PJS : _______________</div>
            </div>
        </div>
        <div class="footer">
            <p style="margin-block-start: 3px; margin-block-end: 4px;">
                Demikian surat permohonan ini saya buat dengan sebenar-benarnya, apabila dikemudian hari terdapat keterangan yang saya buat ternyata tidak benar dan atau menimbulkan kerugian bagi perusahaan, saya bersedia bertanggung jawab sepenuhnya.
            </p>
            <table class="table-otoritas">
                <tr>
                    <td>Hormat saya,</td>
                    <td>Mengetahui</td>
                </tr>
                <tr>
                    <td>Pemohon</td>
                    <td>Bag. GA</td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <div class="tighter-text">
                            <span>{{ $surat->dibuat ? $surat->dibuat->name : '' }}</span>
                            <span class="text-muted">{{ $surat->dibuat_at ? \Carbon\Carbon::parse($surat->dibuat_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                        </div>
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="rekomendasi">
            <table class="table-rekomendasi">
                <tr>
                    <td>Rekomendasi Kepala Departemen :</td>
                    <td>{{ $surat->disetujui ? $surat->disetujui->name : '' }}</td>
                </tr>
                <tr>
                    <td>(Disetujui/Ditolak, dengan penjelasan)</td>
                    <td>{{ $surat->disetujui_at ? \Carbon\Carbon::parse($surat->disetujui_at)->translatedFormat('d F Y, H.i') : '' }}</td>
                </tr>
            </table>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        let url = "{{ route('ijin.show', $surat->id) }}";
        new QRCode("qrcode", { text: url, width: 65, height: 65 });
        window.print();
    </script>
</body>
</html>
