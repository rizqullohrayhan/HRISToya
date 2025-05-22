<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permohonan Cuti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            width: 100%;
            /* height: 100%; */
            box-sizing: border-box;
        }
        .container {
            /* margin: 8px; */
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
            width: 65px; /* Sesuaikan dengan ukuran logo Anda */
            height: auto;
        }
        .img-qrcode {
            display: flex;
            justify-content: space-between;
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
            /* margin-top: 10px; */
        }
        .info {
            margin-bottom: 4px;
            padding: 0px 4px;
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
            padding: 0px 14px;
            text-align: left;
        }
        .perhitungan {
            margin: 0;
            padding: 4px;
            border-collapse: collapse;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .tighter-text span {
            display: block;
            margin-bottom: 2px; /* atau 1px sesuai selera */
        }
        .text-muted {
            font-size: 11px;
        }
        .tighter-text span {
            display: block;
            margin-bottom: 2px; /* atau 1px sesuai selera */
        }
        .rekomendasi {
            /* border-top: 1px solid #000; */
            padding-left: 4px;
        }
        .perhitungan table tr td {
            /* border-collapse: collapse;
            border: 1px solid #000; */
            padding: 0;
        }
        .table-test {
            width: 100%;
            padding-left: 12px;
            /* border-collapse: collapse;
            border: 1px solid #000; */
        }
        hr {
            border: 1px solid black;
        }
        @media print {
            @page {
                size: A4;
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
        <div class="img-qrcode">
            <img src="{{ asset('logo/logo.png') }}" class="logo" alt="Logo Perusahaan">
            &nbsp;
            <div id="qrcode"></div>
        </div>
        <div class="text-center">
            <h3>Permohonan Cuti</h3>
            <span>No. {{ $surat->no_surat }}</span>
        </div>
        <div>FM-GA-02-01-00</div>
    </div>

    <div class="content">
        <div class="info">
            Yang bertanda tangan dibawah ini : <br>
            <table class="table-test">
                <tr>
                    <td style="width: 25%; vertical-align: top;">Nama</td>
                    <td>: {{ $surat->user ? $surat->user->name : '' }}</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Bagian / Departemen</td>
                    <td>: {{ $surat->user ? $surat->user->team->name : '' }}</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Jabatan</td>
                    <td>: {{ $surat->user ? $surat->user->jabatan : '' }}</td>
                </tr>
            </table>
            Mengajukan permohonan cuti sebagai berikut :
            @php
                $tgl_awal = \Carbon\Carbon::createFromFormat('Y-m-d', $surat->tgl_awal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                $tgl_akhir = \Carbon\Carbon::createFromFormat('Y-m-d', $surat->tgl_akhir)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                if ($tgl_awal == $tgl_akhir) {
                    $tgl = $tgl_awal;
                } else {
                    $tgl = $tgl_awal.' s/d '.$tgl_akhir;
                }

            @endphp
            <table class="table-test">
                <tr>
                    <td style="width: 25%; vertical-align: top;">Macam Cuti</td>
                    <td>: {{ $surat->macamCuti ? $surat->macamCuti->name : '' }}</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Periode Tahun</td>
                    <td>: {{ $surat->periode }}</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Dilaksanakan Tanggal</td>
                    <td>: {{ $tgl }} ({{$surat->keperluan}})</td>
                </tr>
            </table>
            Demikian permohonan kami dan atas perkenaannya kami sampaikan terima kasih.
        </div>
    </div>

    <div class="footer">
        Sidoarjo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

        <table style="width: 100%">
            <tr>
                <td>Pemohon</td>
                <td>Menyetujui</td>
            </tr>
            <tr>
                <td>
                    <br>
                    <div class="tighter-text">
                        <span>{{ $surat->dibuat ? $surat->dibuat->name : '' }}</span>
                    </div>
                </td>
                <td>
                    <br>
                    <div class="tighter-text">
                        <span>{{ $surat->disetujui ? $surat->disetujui->name : '' }}</span>
                        <span class="text-muted">{{ $surat->disetujui_at ? \Carbon\Carbon::parse($surat->disetujui_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="perhitungan">
        @php
        @endphp
        <table style="width: 100%">
            <tr>
                <td colspan="9">Rekomendasi & Data Administrasi Personalia :</td>
                <td rowspan="13" style="vertical-align: top; border: 1px solid #000; position: relative; padding: 8px;">
                    <span>Nama PJS : {{ $surat->pjs[0]->penganti ? $surat->pjs[0]->penganti->name : '' }}</span>
                    <br>
                    <span>Pelimpahan Tugas Sementara :</span>
                    <ul>
                        @foreach ($surat->pjs as $pjs)
                            <li>{{ $pjs->tugas }}</li>
                        @endforeach
                    </ul>
                    <span class="ttd-pjs" style="position: absolute; bottom: 8px;">Tanda Tangan PJS : ____________</span>
                </td>
            </tr>
            <tr>
                <td colspan="9">Perhitungan Cuti Tahunan :</td>
            </tr>
            <tr>
                <td colspan="5">Cuti Tahunan untuk Tahun ......</td>
                <td class="text-center">=</td>
                <td class="text-center">{{ $jatah_cuti }}</td>
                <td class="text-center">Hari</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Cuti Bersama (Hari Raya) Tahun ......</td>
                <td class="text-center">=</td>
                <td class="text-center">{{ $cuti_bersama }}</td>
                <td class="text-center">Hari</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Cuti yang telah diambil</td>
                <td class="text-center">=</td>
                <td class="text-center">{{ $cuti_diambil }}</td>
                <td class="text-center">Hari</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Pelaksanaan Sanksi</td>
                <td class="text-center">=</td>
                <td class="text-center">{{ $cuti_sanksi }}</td>
                <td class="text-center">Hari</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td colspan="2">----------</td>
                <td>(+)</td>
                <td class="text-center">=</td>
                <td class="text-center">{{ $pengurangan_jatah }}</td>
                <td class="text-center">Hari</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="line-height: 1px;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td colspan="2">----------</td>
                {{-- <td>&nbsp;</td> --}}
                <td>(-)</td>
            </tr>
            <tr>
                <td>Hak Cuti yang tersedia</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-center">=</td>
                <td class="text-center">{{ $cuti_tersedia }}</td>
                <td class="text-center">Hari</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Berdasarkan permohonan</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-center">=</td>
                <td class="text-center">{{ $permohonan_cuti }}</td>
                <td class="text-center">Hari</td>
                <td>&nbsp;</td>
            </tr>
            <tr style="line-height: 1px;">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td colspan="2">----------</td>
                {{-- <td>&nbsp;</td> --}}
                <td>(-)</td>
            </tr>
            <tr>
                <td>Sisa Cuti Periode ..... s/d .....</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-center">=</td>
                <td class="text-center">{{ $sisa_cuti }}</td>
                <td class="text-center">Hari</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="9">Rekomendasi (Disetujui / ditolak, dengan penjelasan):</td>
            </tr>
        </table>
    </div>
    <div class="rekomendasi">
        <table>
            <tr>
                <td>Rekomendasi Kepala Departemen : (Disetujui/Ditolak, dengan penjelasan)</td>
            </tr>
        </table>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    let url = "{{ route('cuti.show', $surat->id) }}";
    new QRCode("qrcode", { text: url, width: 65, height: 65 });
    window.print();
</script>
</body>
</html>
