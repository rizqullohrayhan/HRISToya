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
            <h4 class="page-title">Detail Surat Ijin Masuk Pabrik</h4>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    {{-- Surat Ijin --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Surat Ijin Masuk Pabrik {{ $surat->no_surat }}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">Nama</span>
                                    </div>
                                    <div class="col-md-10 col-8">
                                        <span>: {{ $surat->nama }}</span>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">No Polisi</span>
                                    </div>
                                    <div class="col-md-10 col-8">
                                        <span>: {{ $surat->nopol }}</span>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">Masuk</span>
                                    </div>
                                    <div class="col-md-10 col-8">
                                        <span>
                                            : {{ $surat->masuk ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->masuk)->locale('id')->isoFormat('dddd, D MMMM YYYY H:mm') : '' }}
                                        </span>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">Keluar</span>
                                    </div>
                                    <div class="col-md-10 col-8">
                                        <span>
                                            : {{ $surat->keluar ? \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->keluar)->locale('id')->isoFormat('dddd, D MMMM YYYY H:mm') : '' }}
                                        </span>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <span class="fw-bold">Keperluan</span>
                                    </div>
                                    <div class="col-md-10 col-8">
                                        <span>: {{ $surat->keperluan }}</span>
                                    </div>
                                    <div class="col-md-2 col-12">
                                        <span class="fw-bold">Surat Pengantar</span>
                                    </div>
                                    <div class="col-md-10 col-12 mb-3">
                                        <span>:
                                            @if ($surat->ktp)
                                                <a href="{{ route('ijinpabrik.ktp', $surat->id) }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">Lihat Surat Pengantar</a>
                                            @endif
                                            {{-- <a href="{{ route('ijinpabrik.ktp', $surat->id) }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">Lihat Surat Pengantar</a> --}}
                                            {{-- <img class="img-preview img-fluid" src="{{ route('ijinpabrik.ktp', $surat->id) }}" alt="Foto KTP"> --}}
                                        </span>
                                    </div>
                                    <div class="col-md-2 col-12 mb-3">
                                        <span class="fw-bold">Kendaraan</span>
                                    </div>
                                    <div class="col-md-10 col-12">
                                        <span>:
                                            @if ($surat->foto_kendaraan)
                                                <a href="{{ route('ijinpabrik.kendaraan', $surat->id) }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">Lihat Kendaraan</a>
                                            @endif
                                            {{-- <a href="{{ route('ijinpabrik.kendaraan', $surat->id) }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">Lihat Kendaraan</a> --}}
                                        </span>
                                    </div>
                                    <div class="col-md-2 col-12 mb-3">
                                        <span class="fw-bold">SIM</span>
                                    </div>
                                    <div class="col-md-10 col-12">
                                        <span>:
                                            @if ($surat->foto_sim)
                                                <a href="{{ route('ijinpabrik.sim', $surat->id) }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">Lihat SIM</a>
                                            @endif
                                            {{-- <a href="{{ route('ijinpabrik.sim', $surat->id) }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">Lihat SIM</a> --}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Detail --}}
                    {{-- <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Detail</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2 col-6">
                                            <span class="fw-bold">Nama PJS</span>
                                        </div>
                                        <div class="col-md-10 col-6">
                                            <span>: {{ $surat->pjs[0]->penganti ? $surat->pjs[0]->penganti->name : '' }}</span>
                                        </div>
                                    </div>
                                    <div class="table-responsive detail-voucher">
                                        <table id="detailVoucher" class="table table-bordered table-striped">
                                            <thead style="text-align:center">
                                                <tr>
                                                    <th colspan="2">Pelimpahan Tugas Sementara</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($surat->pjs as $index => $pjs)
                                                <tr>
                                                    <td class="f-12">{{ $pjs->tugas }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
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
                                                @if ($surat->dibuat_at)
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
                                                @if ($surat->dibuat_at && ($surat->dibuat_id == Auth::user()->id) && ($surat->disetujui_at == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="dibuat">
                                                    Hapus
                                                </button>
                                                @elseif ($surat->dibuat_at == null && $surat->disetujui_at == null)
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
                                <table id="disetujui" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Disetujui</th>
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
                                                @if ($surat->disetujui && ($surat->disetujui_id == Auth::user()->id) && ($surat->diterima == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="disetujui">
                                                    Hapus
                                                </button>
                                                @elseif ($surat->disetujui == null && $surat->dibuat != null)
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
                                <table id="diterima" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Diterima</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="diterima">
                                                @if ($surat->diterima)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($surat->diterima_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $surat->diterima->name }}</strong></span>
                                                    <span>{{ $surat->diterima->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($surat->diterima && ($surat->diterima_id == Auth::user()->id) && ($surat->mengetahui == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="diterima">
                                                    Hapus
                                                </button>
                                                @elseif ($surat->diterima == null && $surat->disetujui != null)
                                                    <button type="button" id="btn-diterima" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="diterima">
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
                                                @elseif ($surat->mengetahui == null && $surat->diterima != null)
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
                            @if ($ruleUser && ($surat->disetujui == null || auth()->user()->hasRole('ADM')))
                                <a href="{{ route('ijinpabrik.edit', $surat->id) }}" class="btn btn-warning">Edit</a>
                            @endif
                            <button class="btn btn-primary" onclick="copyLink()">Copy Link</button>

                            @if ($surat->disetujui && (auth()->user()->id == $surat->dibuat_id || auth()->user()->hasRole('ADM')))
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#QrCodeModal">QR Code</button>
                                <a href="{{ route('ijinpabrik.cetak', $surat->id) }}" target="_blank" class="btn btn-info">Cetak</a>
                            @endif

                            <a href="{{ route('ijinpabrik.index') }}" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('ijinmasukpabrik.modal')
@include('modal.qrcode')
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    window.Laravel = {
        otorisasiUrl: "{{ route('ijinpabrik.update.otoritas', $surat->id) }}",
    };
    const url = "{{ route('ijinpabrik.show', $surat->id) }}";

    const qrcode = new QRCode("qrcode", {
        text: url,
        width: 300,
        height: 300,
    });

    function copyLink() {
        navigator.clipboard.writeText(url);
        $.notify({
        // options
            message: 'Link telah dicopy'
        },{
        // settings
            type: 'success'
        });
    }

    function downloadQR() {
        window.location.href = "{{ route('qrcode') }}?url="+url;
    }
</script>
<script src="{{ asset('custom/otorisasi2.js') }}"></script>
@endsection
