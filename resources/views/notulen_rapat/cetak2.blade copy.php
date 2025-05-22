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
        .nowrap {
            white-space: nowrap;
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
            /* padding: 0 !important; */
            margin-block-start: 0;
            /* margin-block-end: 4px !important; */
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
                <th colspan="4" class="full-border">
                    NOTULEN RAPAT
                </th>
            </tr>
            <tr>
                <td colspan="2" class="full-border" style="white-space:nowrap">No Risalah : TIM.NR.220.2501.00003</td>
                <td class="full-border" style="white-space:nowrap">Hari, Tangal</td>
                <td class="full-border" style="white-space:nowrap">Kamis, 16 Januari 2025</td>
            </tr>
            <tr>
                <td colspan="4" rowspan="3" class="full-border">
                    Agenda Rapat :
                    <ul>
                        <li>Pengadaan Sulfur</li>
                        <li>Perijinan</li>
                        <li>Company Medan</li>
                    </ul>
                </td>
                <td class="full-border" style="white-space:nowrap">Waktu</td>
                <td class="full-border" style="white-space:nowrap; overflow: visible;">11.30</td>
            </tr>
            <tr>
                <td class="full-border" style="white-space:nowrap">Unit Kerja</td>
                <td class="full-border" style="white-space:nowrap; overflow: visible;">LOG + MKT</td>
            </tr>
            <tr>
                <td class="full-border" style="white-space:nowrap">Pimpinan Rapat</td>
                <td class="full-border" style="white-space:nowrap; overflow: visible;">Ir. Yan Suhirmanto</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="full-border">NO</th>
                <th colspan="2" class="border-bottom">URAIAN</th>
                <th class="border-bottom">ACTION</th>
                <th class="border-bottom">DUE DATE</th>
                <th class="border-bottom">PIC</th>
            </tr>
            <tr>
                <td class="border-right text-right">1.</td>
                <td colspan="2">
                    <p>
                        PT. Buana Mega Perkasa menerbitkan PO ke
                        PT. Herbivor Satu Nusa, tidak diketahui jumlah
                        PO dan kuantumnya
                    </p>
                    <p>
                        PT. Herbivor Satu Nusa menerbitkan PO ke PT.
                        Toya Indo Manunggal :
                    </p>
                    <ol>
                        <li>
                            Thn 2023 terdapat PO dengan kuantum 100
                            ton sebanyak 2x PO dan kuantum 50 ton
                            sebanyak 2x PO
                        </li>
                        <li>
                            Thn 2024 terdapat PO dengan kuantum 50 ton
                            sebanyak 1x PO dan kuantum 100 ton sebanyak
                            1x PO
                        </li>
                    </ol>
                </td>
                <td>
                    <p>
                    </p>
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td class="border-right text-right">2.</td>
                <td colspan="2">
                    <p>
                        PT. Herbivor Satu Nusa (HSN) menerbitkan
                        tagihan kepada PT. Buana Mega Perkasa (BMP)
                        dan BMP menerbitkan Bilyet Giro sebagia alat
                        pembayarannya kepada HSN
                    </p>
                    <p>
                        Terdapat 4 Bilyet Giro total seluruhnya bernilai
                        Rp652.000.000,- yang telah dikliringkan oleh
                        HSN namun ditolak Bank dengan alasan saldo
                        tidak mencukupi
                    </p>
                    <p>
                        Bilyet Giro yang ditolak tsb terdiri dari Tagihan
                        dg kuantum 90 ton (PO no. 37) dan kuantum 10
                        ton (PO no. 27)
                    </p>
                </td>
                <td>
                    <p>
                    </p>
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td class="border-right text-right">3.</td>
                <td colspan="2">
                    <p>
                    Dari sisi Pajak
                    </p>
                    <p>
                        Apakah Faktur Pajak bisa dibatalkan dan apakah
                        bisa didaftarkan/dilaporkan sbg Piutang Tak
                        Tertagih?
                    </p>
                </td>
                <td>
                    <p>
                        Bag Pajak mengkonsultasikan dg AR Pajak
                    </p>
                </td>
                <td>
                    15/01/2025
                </td>
                <td>
                    Bp Zainal
                </td>
            </tr>
            </tr>
            <tr>
                <td class="border-right text-right">4.</td>
                <td colspan="2">
                    <p>
                        Dari sisi Hukum
                    </p>
                    <p>
                        Perdata ; BMP sdg dalam pengajuan PKPU di
                        PN Medan dan jika HSN ikut mendaftarkan
                        piutang sbg Okuren
                    </p>
                    <p>
                        Pidana ; atas Bilyet Giro yang gagal dibayar
                        BMP, dapat dilaporkan sebagai Penipuan Jual
                        Beli
                    </p>
                </td>
                <td>
                    <p>
                        Jika renc melakukan :
                    </p>
                    <ol>
                        <li>
                            PKPU : Akan dikonsultasikan ke
                            Hakim Pengawas serta Kurator
                        </li>
                        <li>
                            Melaporkan pidana : terlebih
                            dahulu mencari info status
                            perusahaan apakah msh berusaha
                            dan keberadaan aset
                        </li>
                    </ol>
                </td>
                <td>
                    13/01/2025
                </td>
                <td>
                    Bp Firman
                </td>
            </tr>
            <tr>
                <td class="border-right text-right">1.</td>
                <td colspan="2">
                    <p>
                        PT. Buana Mega Perkasa menerbitkan PO ke
                        PT. Herbivor Satu Nusa, tidak diketahui jumlah
                        PO dan kuantumnya
                    </p>
                    <p>
                        PT. Herbivor Satu Nusa menerbitkan PO ke PT.
                        Toya Indo Manunggal :
                    </p>
                    <ol>
                        <li>
                            Thn 2023 terdapat PO dengan kuantum 100
                            ton sebanyak 2x PO dan kuantum 50 ton
                            sebanyak 2x PO
                        </li>
                        <li>
                            Thn 2024 terdapat PO dengan kuantum 50 ton
                            sebanyak 1x PO dan kuantum 100 ton sebanyak
                            1x PO
                        </li>
                    </ol>
                </td>
                <td>
                    <p>
                    </p>
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td class="border-right text-right">2.</td>
                <td colspan="2">
                    <p>
                        PT. Herbivor Satu Nusa (HSN) menerbitkan
                        tagihan kepada PT. Buana Mega Perkasa (BMP)
                        dan BMP menerbitkan Bilyet Giro sebagia alat
                        pembayarannya kepada HSN
                    </p>
                    <p>
                        Terdapat 4 Bilyet Giro total seluruhnya bernilai
                        Rp652.000.000,- yang telah dikliringkan oleh
                        HSN namun ditolak Bank dengan alasan saldo
                        tidak mencukupi
                    </p>
                    <p>
                        Bilyet Giro yang ditolak tsb terdiri dari Tagihan
                        dg kuantum 90 ton (PO no. 37) dan kuantum 10
                        ton (PO no. 27)
                    </p>
                </td>
                <td>
                    <p>
                    </p>
                </td>
                <td>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td class="border-right text-right">3.</td>
                <td colspan="2">
                    <p>
                    Dari sisi Pajak
                    </p>
                    <p>
                        Apakah Faktur Pajak bisa dibatalkan dan apakah
                        bisa didaftarkan/dilaporkan sbg Piutang Tak
                        Tertagih?
                    </p>
                </td>
                <td>
                    <p>
                        Bag Pajak mengkonsultasikan dg AR Pajak
                    </p>
                </td>
                <td>
                    15/01/2025
                </td>
                <td>
                    Bp Zainal
                </td>
            </tr>
            </tr>
            <tr>
                <td class="border-right text-right">4.</td>
                <td colspan="2">
                    <p>
                        Dari sisi Hukum
                    </p>
                    <p>
                        Perdata ; BMP sdg dalam pengajuan PKPU di
                        PN Medan dan jika HSN ikut mendaftarkan
                        piutang sbg Okuren
                    </p>
                    <p>
                        Pidana ; atas Bilyet Giro yang gagal dibayar
                        BMP, dapat dilaporkan sebagai Penipuan Jual
                        Beli
                    </p>
                </td>
                <td>
                    <p>
                        Jika renc melakukan :
                    </p>
                    <ol>
                        <li>
                            PKPU : Akan dikonsultasikan ke
                            Hakim Pengawas serta Kurator
                        </li>
                        <li>
                            Melaporkan pidana : terlebih
                            dahulu mencari info status
                            perusahaan apakah msh berusaha
                            dan keberadaan aset
                        </li>
                    </ol>
                </td>
                <td>
                    13/01/2025
                </td>
                <td>
                    Bp Firman
                </td>
            </tr>
            <tr>
                <th class="full-border">NO</th>
                <th colspan="5" class="full-border">Daftar Hadir</th>
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
                <td colspan="2">Pak Alim</td>
                <td>3.</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="border-right text-right">4.</td>
                <td colspan="2">Pak Benny</td>
                <td>&nbsp;</td>
                <td>4.</td>
            </tr>
            <tr>
                <td class="border-right text-right">5.</td>
                <td colspan="2">Pak Rouf</td>
                <td>5.</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="border-right text-right">6.</td>
                <td colspan="2">Bu Tari</td>
                <td>&nbsp;</td>
                <td>6.</td>
            </tr>
            <tr>
                <td class="border-right text-right">7.</td>
                <td colspan="2">Bu Lilis</td>
                <td>7.</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td class="border-right text-right">8.</td>
                <td colspan="2">Bu Devi</td>
                <td>&nbsp;</td>
                <td>8.</td>
            </tr>
            <tr>
                <td class="border-top-right">&nbsp;</td>
                <td colspan="2" class="border-top">Dibuat Oleh</td>
                <td colspan="3" class="border-top">Diperiksa Oleh</td>
            </tr>
            <tr>
                <td class="border-right">&nbsp;</td>
                <td style="width: 5%">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
