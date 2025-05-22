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
            font-size: 16px;
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
            margin-block-end: 6px;
            /* padding: 0 !important;
            margin: 0 !important; */
        }
        ul, ol {
            margin-block-start: 4px;
            margin-block-end: 4px;
            padding-inline-start: 20px;
        }
        @media print {
            @page {
                 /* Mengurangi margin default agar header/footer dari browser tidak muncul */
                padding: 0;
                margin: 30px;
                @bottom-center {
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
                <td colspan="2" class="full-border" style="white-space:nowrap">No Risalah : {{ $surat->no_surat }}</td>
                <td class="full-border" style="white-space:nowrap">Hari, Tangal</td>
                <td class="full-border" style="white-space:nowrap">{{ $surat->tanggal ? \Carbon\Carbon::parse($surat->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '' }}</td>
            </tr>
            <tr>
                <td colspan="4" rowspan="3" class="full-border">
                    Agenda Rapat :
                    {!! $surat->agenda !!}
                </td>
                <td class="full-border" style="white-space:nowrap">Waktu</td>
                <td class="full-border" style="white-space:nowrap;">{{ $surat->tanggal ? \Carbon\Carbon::parse($surat->tanggal)->format('H.i') : '' }}</td>
            </tr>
            <tr>
                <td class="full-border" style="white-space:nowrap">Unit Kerja</td>
                <td class="full-border" style="white-space:wrap;">{{ $surat->unit_kerja }}</td>
            </tr>
            <tr>
                <td class="full-border" style="white-space:nowrap">Pimpinan Rapat</td>
                <td class="full-border" style="white-space:nowrap;">{{ $surat->pimpinan }}</td>
            </tr>
            <tr>
                <th class="full-border">NO</th>
                <th colspan="2" class="border-bottom">URAIAN</th>
                <th class="border-bottom">ACTION</th>
                <th class="border-bottom">DUE DATE</th>
                <th class="border-bottom">PIC</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($surat->uraian as $uraian)
                <tr>
                    <td class="border-right text-right">{{ $loop->iteration }}.</td>
                    <td colspan="2">
                        {!! $uraian->uraian !!}
                    </td>
                    <td>
                        {!! $uraian->action !!}
                    </td>
                    <td>
                        {{ $uraian->due_date ? \Carbon\Carbon::parse($uraian->due_date)->format('d/m/Y') : '' }}
                    </td>
                    <td>
                        {{ $uraian->pic }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th class="full-border">NO</th>
                <th colspan="5" class="full-border">Daftar Hadir</th>
            </tr>
            @php
                $daftarHadir = $surat->daftarHadir;
                $total = count($daftarHadir);
                if ($total % 2 != 0) {
                    $daftarHadir[] = null; // tambahkan slot kosong jika ganjil
                }
            @endphp
            @if ($daftarHadir->isEmpty())
                <tr>
                    <td class="border-right">&nbsp;</td>
                    <td colspan="6" class="text-center">Tidak ada daftar hadir</td>
                </tr>
            @else
                @for ($i = 0; $i < count($daftarHadir); $i += 2)
                    <tr>
                        <td class="border-right text-right">{{ $i + 1 }}.</td>
                        <td colspan="2">
                            @if ($daftarHadir[$i])
                                @if ($daftarHadir[$i]->user)
                                    {{ $daftarHadir[$i]->user->name }}
                                @else
                                    {{ $daftarHadir[$i]->nama }}
                                @endif
                            @endif
                        </td>
                        <td>{{ $i + 1 }}.</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="border-right text-right">{{ $i + 2 }}.</td>
                        <td colspan="2">
                            @if ($daftarHadir[$i + 1])
                                @if ($daftarHadir[$i + 1]->user)
                                    {{ $daftarHadir[$i + 1]->user->name }}
                                @else
                                    {{ $daftarHadir[$i + 1]->nama }}
                                @endif
                            @endif
                        </td>
                        <td>&nbsp;</td>
                        <td>{{ $i + 2 }}.</td>
                    </tr>
                @endfor
            @endif
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
            <tr>
                <td class="border-right">&nbsp;</td>
                <td colspan="2">
                    {{ $surat->dibuat ? $surat->dibuat->name : '' }}
                </td>
                <td colspan="3">
                    {{ $surat->diperiksa ? $surat->diperiksa->name : '' }}
                </td>
            </tr>
        </tbody>
    </table>
    <script>
        window.print();
        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>
</html>
