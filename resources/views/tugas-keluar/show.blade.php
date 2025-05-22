@extends('template.main')

@section('css')
    <style>
        .table > thead > tr > th,
        .table > tbody > tr > td {
            padding: 2px 10px !important;
        }
        .detail-voucher {
            max-height: 200px;
            overflow-y: auto;
        }
        #detailVoucher > thead {
            position: sticky;
            top: 0;
            border-color: gray
        }
        .f-12 {
            font-size: 12px !important;
        }

        /* test */
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .detail-row span {
            flex: 1;
        }

        .detail-row span.label {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail Tugas Keluar</h4>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    {{-- Tugas Keluar --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Tugas Keluar {{ $surat->no_surat }}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">Nama Pemberi</span>
                                    </div>
                                    <div class="col-md-4 col-8">
                                        <span>: {{ $surat->pemberi ? $surat->pemberi->name : '' }}</span>
                                    </div>
                                    <div class="col-md-3 col-4">
                                        <span class="fw-bold">Jabatan Pemberi</span>
                                    </div>
                                    <div class="col-md-3 col-8">
                                        <span>: {{ $surat->pemberi ? $surat->pemberi->jabatan : '' }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">Nama Penerima</span>
                                    </div>
                                    <div class="col-md-4 col-8">
                                        <span>: {{ $surat->penerima ? $surat->penerima->name : '' }}</span>
                                    </div>
                                    <div class="col-md-3 col-4">
                                        <span class="fw-bold">Jabatan Penerima</span>
                                    </div>
                                    <div class="col-md-3 col-8">
                                        <span>: {{ $surat->penerima ? $surat->penerima->jabatan : '' }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">Tgl</span>
                                    </div>
                                    @php
                                        $tgl_awal = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_awal)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                                        $tgl_akhir = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_akhir)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                                    @endphp
                                    <div class="col-md-10 col-8">
                                        <span>
                                            @if ($tgl_awal == $tgl_akhir)
                                            : {{ $tgl_awal }} {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_awal)->format('H:i') }}
                                            Sampai {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_akhir)->format('H:i') }}
                                            @else
                                            : {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_awal)->locale('id')->isoFormat('dddd, D MMMM YYYY H:mm') }}
                                            Sampai {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_akhir)->locale('id')->isoFormat('dddd, D MMMM YYYY H:mm') }}
                                            @endif
                                        </span>
                                    </div>
                                    {{-- <div class="col-md-2 col-6">
                                        <span class="fw-bold">Jam</span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <span>: {{ $surat->jam_awal }} WIB sampai {{ $surat->jam_akhir }} WIB</span>
                                    </div> --}}
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">Kendaraan</span>
                                    </div>
                                    <div class="col-md-4 col-8">
                                        <span>: {{ $surat->kendaraan }}</span>
                                    </div>
                                    <div class="col-md-3 col-4">
                                        <span class="fw-bold">No Polisi</span>
                                    </div>
                                    <div class="col-md-3 col-8">
                                        <span>: {{ $surat->no_polisi }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Tujuan --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Tujuan</div>
                                <div class="card-body">
                                    <div class="table-responsive detail-voucher">
                                        <table id="detailVoucher" class="table table-bordered table-striped">
                                            <thead style="text-align:center">
                                                <tr>
                                                    <th>Instansi</th>
                                                    <th>Pejabat Ditemui</th>
                                                    <th>Tujuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($surat->detail as $index => $detail)
                                                <tr>
                                                    <td class="f-12">{{ $detail->instansi }}</td>
                                                    <td class="f-12">{{ $detail->menemui }}</td>
                                                    <td class="f-12">{{ $detail->tujuan }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Otorisasi --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Otorisasi
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="dibuat" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Dibuat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="dibuat">
                                                @if ($surat->dibuat)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($surat->dibuat_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $surat->dibuat->name }}</strong></span>
                                                    <span>{{ $surat->dibuat->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($surat->dibuat && ($surat->dibuat_id == Auth::user()->id) && ($surat->diperiksa_at == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="dibuat">
                                                    Hapus
                                                </button>
                                                @elseif ($surat->dibuat == null && $surat->diperiksa_at == null)
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="dibuat">
                                                    Otorisasi
                                                </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="diperiksa" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Diperiksa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="diperiksa">
                                                @if ($surat->diperiksa)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($surat->diperiksa_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $surat->diperiksa->name }}</strong></span>
                                                    <span>{{ $surat->diperiksa->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($surat->diperiksa && ($surat->diperiksa_id == Auth::user()->id) && ($surat->disetujui == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="diperiksa">
                                                    Hapus
                                                </button>
                                                @elseif ($surat->diperiksa == null && $surat->dibuat != null)
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="diperiksa">
                                                        Otorisasi
                                                    </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="disetujui" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Menyetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="disetujui">
                                                @if ($surat->disetujui)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($surat->disetujui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $surat->disetujui->name }}</strong></span>
                                                    <span>{{ $surat->disetujui->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($surat->disetujui && ($surat->disetujui_id == Auth::user()->id) && ($surat->mengetahui == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="disetujui">
                                                    Hapus
                                                </button>
                                                @elseif ($surat->disetujui == null && $surat->diperiksa != null)
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="disetujui">
                                                        Otorisasi
                                                    </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="mengetahui" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Mengetahui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="mengetahui">
                                                @if ($surat->mengetahui)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($surat->mengetahui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $surat->mengetahui->name }}</strong></span>
                                                    <span>{{ $surat->mengetahui->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($surat->mengetahui && ($surat->mengetahui_id == Auth::user()->id))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="mengetahui">
                                                    Hapus
                                                </button>
                                                @elseif ($surat->mengetahui == null && $surat->disetujui != null)
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="mengetahui">
                                                        Otorisasi
                                                    </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Button --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @php
                            $ruleUser = ( (auth()->user()->id == $surat->created_by) ||
                                            (auth()->user()->id == $surat->dibuat_id) ||
                                            (auth()->user()->hasRole('ADM'))
                                        );
                        @endphp

                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                            @if ($ruleUser && ($surat->diperiksa == null || auth()->user()->hasRole('ADM')))
                                <a href="{{ route('tugas-keluar.edit', $surat->id) }}" class="btn btn-warning">Edit</a>
                            @endif
                            <button class="btn btn-primary" onclick="copyLink()">Copy Link</button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#QrCodeModal">QR Code</button>

                            @if ($surat->disetujui && (auth()->user()->id == $surat->dibuat_id || (auth()->user()->hasRole('ADM'))))
                                <a href="{{ route('tugas-keluar.cetak', $surat->id) }}" target="_blank" class="btn btn-info">Cetak</a>
                            @endif

                            <a href="{{ route('tugas-keluar.index') }}" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modal.qrcode')
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    window.Laravel = {
        otorisasiUrl: "{{ route('tugas-keluar.update.otoritas', $surat->id) }}",
    };
    const url = "{{ route('tugas-keluar.show', $surat->id) }}";

    const qrcode = new QRCode("qrcode", {
        text: url,
        width: 300,
        height: 300,
    });

    function copyLink() {
        navigator.clipboard.writeText(url);
        $.notify({ message: 'Link telah dicopy' },{ type: 'success' });
    }

    function downloadQR() {
        window.location.href = "{{ route('qrcode') }}?url="+url;
    }
</script>
<script src="{{ asset('custom/otorisasi.js') }}"></script>
@endsection
