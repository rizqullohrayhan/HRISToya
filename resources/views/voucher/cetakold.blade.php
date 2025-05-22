<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        {{ $voucher->tipeVoucher->name }}
    </title>
    {{-- <link rel="icon" href="{{ asset('logo/logo.png') }}" type="image/x-icon" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ public_path('custom/voucher/bootstrap.min.css') }}"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <style>
        table {
            width: 100%;
        }
        .text-header{
            font-size: 16px;
        }
        .text-title{
            font-size: 24px;
            font-weight: bold;
            position: relative;
        }
        .text-title p {
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
        table > thead > tr > th,
        table > tbody > tr > td {
            padding: 2px 10px !important;
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

<body class="p-4">
    <table class="table table-sm table-borderless">
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <img alt="Company Logo" class="me-4" src="{{ asset('logo/logo.png') }}" height="70px"/>
                    <span id="qrcode"></span>
                </div>
            </td>
            <td style="display: flex; flex-direction: row; align-items: center; justify-content: center;">
                <div>
                    <h1>
                        {{ $voucher->tipeVoucher->name }}
                    </h1>
                </div>
            </td>
            <td class="text-end">
                <span class="text-header">No Voucher</span> <br>
                <span class="text-header">Tgl Pengajuan</span><br>
                <span class="text-header">No Bank</span><br>
                <span class="text-header">Tgl Pembukuan</span><br>
            </td>
            <td>
                <span class="text-header">: {{ $voucher->no_voucher }}</span><br>
                <span class="text-header">: {{ \Carbon\Carbon::parse($voucher->tanggal)->translatedFormat('d F Y'); }}</span><br>
                <span class="text-header">: .....................................</span><br>
                <span class="text-header">: .....................................</span><br>
            </td>
        </tr>
    </table>
    <div>
        Dibayar Kepada / Untuk : {{ $voucher->rekanan->name }}
    </div>
    <table class="table-bordered align-middle text-sm">
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
        <tbody>
            @foreach ($detailVouchers->take(6) as $detail)
                <tr>
                    <td class="text-end">{{ $loop->iteration }}</td>
                    <td class="text-end">{{ $detail->bankCode ? $detail->bankCode->code : ''}}</td>
                    <td class="text-end">{{ $detail->perkiraan ? $detail->perkiraan->code : '' }}</td>
                    <td>{{ $detail->uraian }}</td>
                    <td>{{ $detail->no_bukti }}</td>
                    <td>{{ $detail->mataUang ? $detail->mataUang->code : '' }}</td>
                    <td class="text-end">{{ number_format($detail->amount,2,",",".") }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-between px-2 mt-1 mb-2">
        <p>
            Blangko Ini Terdiri Dari : {{ $voucher->detailVoucher->count() }} Item Record
        </p>
        <p>
            Total :
            <span class="font-weight-bold">
                {{ number_format($totalJumlah,2,",",".") }}
            </span>
        </p>
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
                    <span class="fw-bold text-decoration-underline" style="font-size: 12px;">{{ $voucher->user ? $voucher->user->name : '' }}</span><br>
                    <span class="text-uppercase" style="font-size: 10px;">{{ $voucher->user ? $voucher->user->team->name : '' }}</span><br>
                    <span class="text-uppercase" style="font-size: 10px;">{{ $voucher->set_at ? \Carbon\Carbon::parse($voucher->set_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                </td>
                <td>
                    <br>
                    <span class="fw-bold text-decoration-underline" style="font-size: 12px;">{{ $voucher->reviewer ? $voucher->reviewer->name : '' }}</span><br>
                    <span class="text-uppercase" style="font-size: 10px;">{{ $voucher->reviewer ? $voucher->reviewer->team->name : '' }}</span><br>
                    <span class="text-uppercase" style="font-size: 10px;">{{ $voucher->reviewed_at ? \Carbon\Carbon::parse($voucher->reviewed_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                </td>
                <td>
                    <br>
                    <span class="fw-bold text-decoration-underline" style="font-size: 12px;">{{ $voucher->bookkeeper ? $voucher->bookkeeper->name : '' }}</span><br>
                    <span class="text-uppercase" style="font-size: 10px;">{{ $voucher->bookkeeper ? $voucher->bookkeeper->team->name : '' }}</span><br>
                    <span class="text-uppercase" style="font-size: 10px;">{{ $voucher->bookkeeped_at ? \Carbon\Carbon::parse($voucher->bookkeeped_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                </td>
                <td>
                    <br>
                    <span class="fw-bold text-decoration-underline" style="font-size: 12px;">{{ $voucher->approver ? $voucher->approver->name : '' }}</span><br>
                    <span class="text-uppercase" style="font-size: 10px;">{{ $voucher->approver ? $voucher->approver->team->name : '' }}</span><br>
                    <span class="text-uppercase" style="font-size: 10px;">{{ $voucher->approved_at ? \Carbon\Carbon::parse($voucher->approved_at)->translatedFormat('d F Y, H.i') : '' }}</span>
                </td>
                <td style="vertical-align: super; font-size: 12px;">
                    Nama Terang & Tanggal
                </td>
            </tr>
        </tbody>
    </table>
    <div class="text-sm mt-2">
        <p class="text-sm fst-italic">
            Di Cetak Oleh : {{ auth()->user()->name }} ({{auth()->user()->team->name}}), {{ \Carbon\Carbon::now()->translatedFormat('d/m/Y H.i.s'); }}
        </p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        let url = "{{ route('voucher.show', '__id__') }}";
        url = url.replace('__id__', "{{$voucher->id}}");
        var qrcode = new QRCode("qrcode", {
            text: url,
            width: 70,
            height: 70,
        });
        window.print();
    </script>
</body>

</html>
