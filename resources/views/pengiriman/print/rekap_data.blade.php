<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        table {
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-bordered tr th,
        .table-bordered tr td {
            border: 1px solid #000;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: end;
        }

        .bg-danger {
            background-color: #f25961 !important;
        }

        .bg-warning {
            background-color: #ffad46 !important;
        }

        .bg-success {
            background-color: #31ce36 !important;
        }

        .progress .progress-bar {
            border-radius: 100px;
            color: #000;
        }

        .progress-bar {
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            color: #ffffff;
            text-align: center;
            white-space: nowrap;
            background-color: #0d6efd;
            transition: width 0.6s ease;
        }

        .mengetahui {
            margin-left: auto;
            margin-right: 0;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="2">{{ $kontrak->perusahaan }}</td>
        </tr>
        <tr>
            <td>Customer</td>
            <td>: {{ $kontrak->customer }}</td>
        </tr>
        <tr>
            <td>No Kontrak</td>
            <td>: {{ $kontrak->no_kontrak }}</td>
        </tr>
        <tr>
            <td>Barang</td>
            <td>: {{ $kontrak->barang }}</td>
        </tr>
        <tr>
            <td>Kuantitas</td>
            <td>: {{ number_format($kontrak->kuantitas) }} Kg</td>
        </tr>
        <tr>
            <td>Semester</td>
            <td>: {{ $kontrak->semester }}</td>
        </tr>
        <tr>
            <td>Tgl Mulai Kirim</td>
            <td>: {{ $kontrak->tgl_mulai_kirim ? \Carbon\Carbon::parse($kontrak->tgl_mulai_kirim)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '' }}</td>
        </tr>
        <tr>
            <td>Jangka Waktu Kirim</td>
            <td>: {{ $kontrak->jangka_waktu_kirim }} Hari</td>
        </tr>
        <tr>
            <td>Update Per Tgl</td>
            <td>: {{ \Carbon\Carbon::now()->translatedFormat('d-F-Y') }}</td>
        </tr>
    </table>

    <table id="RekapKebun" class="table table-bordered">
        <thead style="text-align:center">
            <tr>
                <th>Target</th>
                <th>Batas Kirim</th>
                <th>Sisa Hari</th>
                <th>Target Perhari</th>
                <th>Kontrak</th>
                <th colspan="2">Realisasi</th>
                <th colspan="2">Sisa</th>
                <th>Rencana</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">
                    {{ $kontrak->target }}%
                </td>
                <td class="text-center">
                    {{ $kontrak->batas_kirim ? \Carbon\Carbon::parse($kontrak->batas_kirim)->translatedFormat('d-M-Y') : '' }}
                </td>
                <td class="text-center">
                    {{-- Hitung sisa hari --}}
                    {{ $sisaHari }}
                </td>
                <td class="text-end">
                    {{-- Target Perhari --}}
                    {{ number_format($targetPerHari) }} Kg
                </td>
                <td class="text-end">
                    {{-- Kontrak --}}
                    {{ number_format($kontrak->kuantitas) }} Kg
                </td>
                <td class="text-end">
                    {{-- Realisasi --}}
                    {{ number_format($realisasi->realisasi ?? 0) }} Kg
                </td>
                <td class="text-center">
                    {{-- Persentase Realisasi --}}
                    {{ floor(100 * ($realisasi->realisasi ?? 0) / $kontrak->kuantitas) }}%
                </td>
                <td class="text-end">
                    {{-- Sisa --}}
                    {{ number_format($sisa) }} Kg
                </td>
                <td class="text-center">
                    {{-- Persentase Sisa --}}
                    {{ floor(100 * $sisa / $kontrak->kuantitas) }}%
                </td>
                <td class="text-center">
                    {{-- Rencana --}}
                    {{ $maxTglRencana ? \Carbon\Carbon::parse($maxTglRencana)->translatedFormat('d-M-Y') : '' }}
                </td>
            </tr>
        </tbody>
    </table>


    <table id="detailKebun" class="table table-bordered">
        <thead style="text-align:center">
            <tr>
                <th>No</th>
                <th>Vendor</th>
                <th>Kebun</th>
                <th>Kontrak</th>
                <th>Realisasi</th>
                <th>Sisa</th>
                <th>Progress</th>
                <th>Rencana</th>
                <th>SJ Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekapKebun as $kebun)
                <tr>
                    <td class="text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $kebun->vendor }}
                    </td>
                    <td>
                        {{ $kebun->kebun }}
                    </td>
                    <td class="text-end">
                        {{ number_format($kebun->kontrak) }}
                    </td>
                    <td class="text-end">{{ number_format($realisasiPerKebun[$kebun->id] ?? 0) }}</td>
                    <td class="text-end">{{ number_format($kebun->kontrak - ($realisasiPerKebun[$kebun->id] ?? 0)) }}</td>
                    <td class="text-center">
                        @php
                            $persen = floor(100 * ($realisasiPerKebun[$kebun->id] ?? 0) / $kebun->kontrak);
                        @endphp
                        {{ $persen }}%
                    </td>
                    <td class="text-center">{{ $rencanaPerKebun[$kebun->id] }}</td>
                    <td class="text-center">{{ $SJPerKebun[$kebun->id] }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td class="text-end">Total Kiriman</td>
                <td class="text-end">{{ number_format($kontrak->kuantitas) }}</td>
                <td class="text-end">{{ number_format($realisasi->realisasi ?? 0) }}</td>
                <td class="text-end">{{ number_format($sisa) }}</td>
                <td class="text-center">
                    {{ floor(100 * ($realisasi->realisasi ?? 0) / $kontrak->kuantitas) }}%
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="text-end">Progres Kiriman</td>
                <td class="text-end">100%</td>
                <td class="text-end">{{ floor(100 * ($realisasi->realisasi ?? 0) / $kontrak->kuantitas) }}%</td>
                <td class="text-end">{{ floor(100 * $sisa / $kontrak->kuantitas) }}%</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <table class="mengetahui">
        <tr>
            @for ($i = 0; $i < 5; $i++)
                <td></td>
            @endfor
            <td>Mengetahui</td>
        </tr>
        <tr style="line-height: 100px;">
            @for ($i = 0; $i < 5; $i++)
                <td>{{ $kontrak->mengetahui[$i]->name ?? '' }}</td>
            @endfor
            <td>Ir. Yan Suhirmanto, MH</td>
        </tr>
    </table>
</body>
</html>
