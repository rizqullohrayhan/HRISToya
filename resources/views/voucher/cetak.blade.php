<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $voucher->tipeVoucher->name }}</title>

    <style>
        html, body {
            font-family: Arial, Helvetica, sans-serif;
            /* width: 200mm; A4 width */
        }
        table {
            width: 100%;
        }
        .table-bordered {
            /* border: 1px solid black; */
            border-collapse: collapse;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
            padding: 4px;
        }
        .table-header {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 16px;
        }
        .logo {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 8px;
        }
        .text-header {
            font-size: 16px;
        }
        .text-title {
            display: flex;
            align-items: center;      /* vertikal center */
            justify-content: center;  /* horizontal center */
            font-size: 24px;
            font-weight: bold;
            height: 100%;             /* pastikan td punya tinggi */
        }
        .text-end {
            text-align: end;
        }
        table > thead > tr > th,
        table > tbody > tr > td {
            padding: 2px 10px !important;
        }
        .detail-voucher-wrapper {
            min-height: 200px; /* default minimal tinggi kontainer */
            display: flex;
            flex-direction: column;
            justify-content: stretch;
        }
        .detail-voucher-wrapper .table-fill {
            flex-grow: 1;
        }
        .detail-voucher-container table {
            border-bottom: 1px solid black; /* tambahan jaga-jaga */
        }
        .footer-detail-voucher {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
        }
        .tighter-text{
            text-align: center;
        }
        .tighter-text span {
            display: block;
        }
        .text-muted {
            font-size: 11px;
        }
        .info-cetak {
            font-size: 12px;
            margin-top: 8px;
            font-style: italic;
        }
        @media print {
            @page {
                margin: 0;
            }
            body {
                margin: 20px;
                height: 561px; /* setengah A4 potrait */
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body class="p-4">
    <table class="table-header">
        <tr>
            <td style="width: 150px;">
                <div class="logo">
                    <img alt="Company Logo" class="me-4" src="{{ asset('logo/logo.png') }}" height="60px"/>
                    <span id="qrcode"></span>
                </div>
            </td>
            <td>
                <div class="text-title">
                    {{ $voucher->tipeVoucher->name }}
                </div>
            </td>
            <td class="text-end" style="width: 13ch; padding-right: 0 !important;">
                <span class="text-header">No Voucher</span> <br>
                <span class="text-header">Tgl Pengajuan</span><br>
                <span class="text-header">No Bank</span><br>
                <span class="text-header">Tgl Pembukuan</span><br>
            </td>
            <td style="width: 17ch; padding-left: 0 !important;">
                <span class="text-header">: {{ $voucher->no_voucher }}</span><br>
                <span class="text-header">: {{ \Carbon\Carbon::parse($voucher->tanggal)->translatedFormat('d F Y'); }}</span><br>
                <span class="text-header">: .............................</span><br>
                <span class="text-header">: .............................</span><br>
            </td>
        </tr>
    </table>

    <div>
        Dibayar Kepada / Untuk : {{ $voucher->rekanan->name }}
    </div>

    <div class="detail-voucher-container">
        <div class="detail-voucher-wrapper">
            <table id="table-detail" class="table-bordered align-middle text-sm">
                <thead style="text-align:center">
                    <tr>
                        <th rowspan="2">NO</th>
                        <th colspan="2">KODE</th>
                        <th rowspan="2">URAIAN</th>
                        <th rowspan="2">NO BUKTI</th>
                        <th rowspan="2">MU</th>
                        <th rowspan="2">JUMLAH</th>
                    </tr>
                    <tr>
                        <th>Bank</th>
                        <th>Akun</th>
                    </tr>
                </thead>
                <tbody id="detail-body">
                    @foreach ($detailVouchers as $detail)
                    <tr class="detail-row">
                        <td class="text-end">{{ $loop->iteration }}</td>
                        <td class="text-end">{{ $detail->bankCode ? $detail->bankCode->code : '' }}</td>
                        <td class="text-end">{{ $detail->perkiraan ? $detail->perkiraan->code : '' }}</td>
                        <td>{{ $detail->uraian }}</td>
                        <td>{{ $detail->no_bukti }}</td>
                        <td>{{ $detail->mataUang ? $detail->mataUang->code : '' }}</td>
                        <td class="text-end">{{ number_format($detail->amount,2,",",".") }}</td>
                    </tr>
                    @endforeach
                    <tr id="last-row">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer-section">
        <div class="footer-detail-voucher">
            <span>
                Blangko Ini Terdiri Dari : {{ $voucher->detailVoucher->count() }} Item Record
            </span>
            <span>
                Total :
                <span class="font-weight-bold">
                    {{ number_format($totalJumlah,2,",",".") }}
                </span>
            </span>
        </div>

        <table class="table-bordered text-sm text-center">
            <thead>
                <tr>
                    <th>Dibuat Oleh</th>
                    <th>Mengetahui</th>
                    <th>Pembukuan</th>
                    <th>Disetujui</th>
                    <th>Tanda Terima</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <br>
                        <div class="tighter-text">
                            <span>{{ $voucher->user ? $voucher->user->name : '' }}</span>
                            <span class="text-muted">{{ $voucher->user ? $voucher->user->team->name : '' }}</span>
                            <span class="text-muted">{{ $voucher->set_at ? \Carbon\Carbon::parse($voucher->set_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                        </div>
                    </td>
                    <td>
                        <br>
                        <div class="tighter-text">
                            <span>{{ $voucher->reviewer ? $voucher->reviewer->name : '' }}</span>
                            <span class="text-muted">{{ $voucher->reviewer ? $voucher->reviewer->team->name : '' }}</span>
                            <span class="text-muted">{{ $voucher->reviewed_at ? \Carbon\Carbon::parse($voucher->reviewed_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                        </div>
                    </td>
                    <td>
                        <br>
                        <div class="tighter-text">
                            <span>{{ $voucher->bookkeeper ? $voucher->bookkeeper->name : '' }}</span>
                            <span class="text-muted">{{ $voucher->bookkeeper ? $voucher->bookkeeper->team->name : '' }}</span>
                            <span class="text-muted">{{ $voucher->bookkeeped_at ? \Carbon\Carbon::parse($voucher->bookkeeped_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                        </div>
                    </td>
                    <td>
                        <br>
                        <div class="tighter-text">
                            <span>{{ $voucher->approver ? $voucher->approver->name : '' }}</span>
                            <span class="text-muted">{{ $voucher->approver ? $voucher->approver->team->name : '' }}</span>
                            <span class="text-muted">{{ $voucher->approved_at ? \Carbon\Carbon::parse($voucher->approved_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                        </div>
                    </td>
                    <td style="vertical-align: super; font-size: 12px;">
                        Nama Terang & Tanggal
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="info-cetak">
            Di Cetak Oleh : {{ auth()->user()->name }} ({{auth()->user()->team->name}}), {{ \Carbon\Carbon::now()->translatedFormat('d/m/Y H.i.s'); }}
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script>
        let url = "{{ route('voucher.show', '__id__') }}";
        url = url.replace('__id__', "{{$voucher->id}}");
        new QRCode("qrcode", { text: url, width: 65, height: 65 });

        window.onload = function() {
            const headerTable = document.querySelector('table.table-header');
            const bayarKepada = document.querySelector('body > div'); // bagian "Dibayar Kepada"
            const table = document.getElementById('table-detail');
            const tableWrapper = document.querySelector('.detail-voucher-wrapper');
            const lastRow = document.getElementById('last-row');
            const footer = document.querySelector('.footer-section');

            const bodyTargetHeight = 559; // setengah A4 dalam mm

            // Hitung tinggi header, bagian "dibayar kepada", dan footer
            const usedHeight = headerTable.offsetHeight + bayarKepada.offsetHeight + footer.offsetHeight;

            const currentTableHeight = table.offsetHeight;
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
