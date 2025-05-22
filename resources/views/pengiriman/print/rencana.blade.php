<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        table tr th,
        table tr td {
            border: 1px solid #000;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: end;
        }

        .footer {
            display: flex;
            justify-content: end;
        }

        @media print {
            @page {
                size: landscape;
                margin: 8px;
            }
            /* .page-break {
                page-break-before: always;
            } */
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    <p>
        RENCANA PENGIRIMAN
    </p>
    <p>
        Periode
    </p>
    <p>
        Target Qty Minimal =
    </p>

    {{-- <table>
        <thead>
            <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">Vendor</th>
                <th rowspan="2">Kebun</th>
                <th rowspan="2">Batas Kirim Terakhir</th>
                <th rowspan="2">Jumlah</th>
                @foreach ($tglPengiriman as $tgl)
                    <th colspan="2">{{ \Carbon\Carbon::parse($tgl)->format('d/m/y') }}</th>
                @endforeach
                <th rowspan="2">Sisa</th>
            </tr>
            <tr>
                @foreach ($tglPengiriman as $tgl)
                    <th>Nopol</th>
                    <th>Qty</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($dataPerKebun as $kebun => $tanggalan)
                @php
                    $first = $tanggalan->first()->first(); // Ambil salah satu entry untuk vendor dan jumlah
                    $totalQty = $tanggalan->flatMap(fn($t) => $t)->sum('qty');
                    $jumlah = $first->kontrak;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $first->vendor }}</td>
                    <td>{{ $kebun }}</td>
                    <td></td>
                    <td>{{ number_format($jumlah) }}</td>

                    @foreach ($tglPengiriman as $tgl)
                        @php
                            $data = $tanggalan[$tgl][0] ?? null;
                        @endphp
                        <td>{{ $data?->nopol }}</td>
                        <td>{{ $data ? number_format($data->qty) : '' }}</td>
                    @endforeach

                    <td>{{ number_format($jumlah - $totalQty) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

{{-- @foreach ($tglChunks as $tglGroup)
    <div class="page-break">
        <table>
            <thead>
                <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">Vendor</th>
                    <th rowspan="2">Kebun</th>
                    <th rowspan="2">Jumlah</th>
                    @foreach ($tglGroup as $tgl)
                        @php
                            $totalPerTanggal[$tgl] = 0;
                        @endphp
                        <th colspan="2">{{ \Carbon\Carbon::parse($tgl)->format('d/m/y') }}</th>
                    @endforeach
                    <th rowspan="2">SISA</th>
                </tr>
                <tr>
                    @foreach ($tglGroup as $tgl)
                        <th>Nopol</th>
                        <th>Qty</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($dataPerKebun as $kebun => $tanggalan)
                    @php
                        // Ambil satu data pertama dari setiap kebun untuk mengambil informasi umum seperti kontrak, vendor, dsb.
                        // $tanggalan adalah Collection yang dikelompokkan berdasarkan tanggal, jadi kita ambil grup pertama lalu item pertamanya.
                        $first = $tanggalan->first()->first();

                        // Gabungkan semua item pengiriman dari semua tanggal untuk kebun ini menjadi satu koleksi datar
                        // flatMap digunakan karena tiap tanggal berisi array/collection dari data pengiriman.
                        $totalQty = $tanggalan->flatMap(function ($items) {
                            return $items;
                        })->sum('qty'); // Hitung total kuantitas pengiriman dari semua tanggal

                        @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $first->vendor }}</td>
                        <td>{{ $kebun }}</td>
                        <td class="text-end">{{ number_format($first->kontrak) }}</td>

                        @foreach ($tglGroup as $tgl)
                            @php
                                // Hitung sisa kuantitas yang belum dikirim berdasarkan jumlah kontrak awal dikurangi total pengiriman
                                $data = $tanggalan[$tgl][0] ?? null;
                                $sisa[$kebun] -= $data->qty ?? 0;
                                $totalPerTanggal[$tgl] += $data->qty ?? 0;
                            @endphp
                            <td class="text-center">{{ $data?->nopol }}</td>
                            <td class="text-end">{{ $data ? number_format($data->qty) : '' }}</td>
                        @endforeach

                        <td class="text-end">{{ number_format($sisa[$kebun] ) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-end">{{ number_format($kontrakPengiriman->kuantitas) }}</td>
                    @php
                        $sisaTotal = $kontrakPengiriman->kuantitas;
                    @endphp
                    @foreach ($tglGroup as $tgl)
                        @php
                            $sisaTotal -= $totalPerTanggal[$tgl];
                        @endphp
                        <td class="text-center"></td>
                        <td class="text-end">{{ number_format($totalPerTanggal[$tgl]) }}</td>
                    @endforeach
                    <td class="text-end">{{ number_format(array_sum($sisa)) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endforeach --}}

<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Vendor</th>
            <th>Kebun</th>
            <th>Nopol</th>
            <th>Qty</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rencanaPengiriman->sortBy('tgl') as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tgl)->format('d/m/y') }}</td>
                <td>{{ $item->vendor }}</td>
                <td>{{ $item->kebun }}</td>
                <td>{{ $item->nopol }}</td>
                <td class="text-end">{{ number_format($item->qty) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


    {{-- <div class="footer">
        @php
            $tglAkhir = $tglPengiriman->last();
            $tglPalingLambat = $tglAkhir ? \Carbon\Carbon::parse($tglAkhir)->translatedFormat('d F Y') : '';
        @endphp
        <span>Rencana Selesai Pengiriman Paling lambat tgl. {{ $tglPalingLambat }}</span>
    </div> --}}


</body>
</html>
