<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table > thead > tr > th,
        table > thead > tr > td,
        table > tbody > tr > td {
            padding: 4px 10px !important;
            vertical-align: top;
        }
        .logo {
            width: 50px; /* Sesuaikan dengan ukuran logo Anda */
            height: auto;
        }
        .full-border {
            border: 1px solid #000;
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
        .border-top-left {
            border-top: 1px solid #000;
            border-left: 1px solid #000;
        }
        .border-top-right {
            border-top: 1px solid #000;
            border-right: 1px solid #000;
        }
        .border-bottom-left {
            border-bottom: 1px solid #000;
            border-left: 1px solid #000;
        }
        .border-bottom-right {
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }
        .border-left-right {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }
        .border-top-left-right {
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }
        .border-top-bottom-left {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            border-left: 1px solid #000;
        }
        .border-top-bottom-right {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
        }
        .border-bottom-left-right {
            border-bottom: 1px solid #000;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }
        .text-top {
            vertical-align: top;
        }
        .text-bottom {
            vertical-align: bottom;
        }
        .text-right {
            text-align: right;
        }
        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        th {
            text-align: center;
        }
        p {
            padding: 0 !important;
            margin: 0 !important;
        }
        ul, ol {
            margin-block-start: 4px;
            margin-block-end: 4px;
            padding-inline-start: 10px;
        }
        @media print {
            @page {
                padding: 40px;
                margin-top: 0; /* Mengurangi margin default agar header/footer dari browser tidak muncul */
                margin-right: 0; /* Mengurangi margin default agar header/footer dari browser tidak muncul */
                margin-left: 0; /* Mengurangi margin default agar header/footer dari browser tidak muncul */
                @bottom-right {
                    content: "Halaman " counter(page) " dari " counter(pages);
                    font-size: 12px;
                }
            }
            /* body {
                padding: 20px;
            } */
            .nowrap {
                white-space: nowrap !important;
            }
        }
    </style>
</head>
<body>
    <table class="full-border">
        <thead>
            <tr>
                <th rowspan="2" colspan="2" class="full-border"><img class="logo" src="{{ asset('logo/logo.png') }}" alt="Logo Perusahaan"></th>
                <th colspan="3" class="full-border">NOTULEN RAPAT</th>
            </tr>
            <tr>
                <td class="full-border">No Risalah : TIM.NR.220.2501.00003</td>
                <td class="full-border">Hari, Tangal</td>
                <td class="full-border">Kamis, 16 Januari 2025</td>
            </tr>
            <tr>
                <td colspan="3" rowspan="3" class="full-border">
                    Agenda Rapat :
                    <ul>
                        <li>Pengadaan Sulfur</li>
                        <li>Perijinan</li>
                        <li>Company Medan</li>
                    </ul>
                </td>
                <td class="full-border">Waktu</td>
                <td class="full-border">11.30</td>
            </tr>
            <tr>
                <td class="full-border">Unit Kerja</td>
                <td class="full-border">LOG + MKT</td>
            </tr>
            <tr>
                <td class="full-border">Pimpinan Rapat</td>
                <td class="full-border">Ir. Yan Suhirmanto</td>
            </tr>
            <tr>
                <th class="full-border" style="width: 5%">NO</th>
                <th colspan="4" class="full-border">URAIAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td colspan="4" class="border-left-right">
                    <p>
                        COMPANY MEDAN
                    </p>
                    <ul>
                        <li>Menggunakan PT. NPA</li>
                        <li>Distribution Fee</li>
                        <li>Biaya BBM ditanggung</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td colspan="4" class="border-left-right">
                    <p>
                        BALI
                    </p>
                    <ul>
                        <li>Mbk. lilis diminta segera mainten PDAM Bali</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td>3</td>
                <td colspan="4" class="border-left-right">
                    <p>
                        AMAN NTB akan di arrange Mbk Tari
                    </p>
                </td>
            </tr>
            <tr>
                <td>4</td>
                <td colspan="4" class="border-left-right">
                    <p>
                        PERIJINAN
                    </p>
                    <p>
                        PROGRES
                    </p>
                    <ul>
                        <li>
                            Proses B3
                        </li>
                    </ul>
                    <p>
                        PKKPR DARAT sudah masuk pengajuan BPN Pusat Evaluasi Datar
                    </p>
                    <ul>
                        <li>IJIN EDAR khusus = koreksi nomer SK</li>
                        <li>SP36 bisa jalan</li>
                        <li>IJIN DAP</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th class="full-border">NO</th>
                <th colspan="4" class="full-border">Daftar Hadir</th>
            </tr>
            <tr>
                <td class="border-right text-right">1.</td>
                <td colspan="2">Ir. Yan Suhiarto</td>
                <td>1.</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="border-right text-right">2.</td>
                <td colspan="2">Pak Agus S</td>
                <td>&nbsp;</td>
                <td>2.</td>
            </tr>
            <tr>
                <td class="border-right text-right">3.</td>
                <td colspan="2">Ir. Yan Suhiarto</td>
                <td>3.</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="border-right text-right">4.</td>
                <td colspan="2">Pak Agus S</td>
                <td>&nbsp;</td>
                <td>4.</td>
            </tr>
            <tr>
                <td class="border-right text-right">5.</td>
                <td colspan="2">Ir. Yan Suhiarto</td>
                <td>5.</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="border-top-right">&nbsp;</td>
                <td colspan="2" class="border-top">Dibuat Oleh</td>
                <td colspan="2" class="border-top">Diperiksa Oleh</td>
            </tr>
            <tr>
                <td class="border-right">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
