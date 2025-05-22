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
        #detailVoucher {
            table-layout: fixed;
            width: 100%;
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
            <h4 class="page-title">Detail Dinas Luar Kota</h4>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    {{-- Detail Dinas Luar Kota --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Surat Dinas Luar Kota {{ $surat->no_surat }}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Nama Penerima</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $surat->penerima ? $surat->penerima->name : '' }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Departemen</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $surat->penerima ? $surat->penerima->team->name : '' }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Level</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $surat->penerima ? $surat->penerima->jabatan : '' }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Tipe Kendaraan</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $surat->kendaraan }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">No Polisi</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $surat->no_polisi }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Kota</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $surat->kota }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Jangka Waktu</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $surat->jangka_waktu ." ". $surat->satuan_waktu }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Berangkat</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $surat->berangkat ? \Carbon\Carbon::parse($surat->berangkat)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Tujuan Dinas Luar --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Tujuan Dinas Luar Kota</div>
                                <div class="card-body">
                                    <div class="table-responsive detail-voucher">
                                        <table id="detailVoucher" class="table table-bordered table-striped">
                                            <thead style="text-align:center">
                                                <tr>
                                                    <th>Instansi</th>
                                                    <th>Menemui</th>
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
                                                @if ($surat->dibuat_at)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($surat->dibuat_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $surat->dibuat->name }}</strong></span>
                                                    <span>{{ $surat->dibuat->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($surat->dibuat_at && ($surat->dibuat_id == Auth::user()->id) && ($surat->diperiksa == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="dibuat">
                                                    Hapus
                                                </button>
                                                @elseif ($surat->dibuat_at == null && $surat->diperiksa == null)
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
                                                    <span>{{ $surat->diperiksa->team->name }}</span>
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
                                                    <span>{{ $surat->disetujui->team->name }}</span>
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
                                                    <span>{{ $surat->mengetahui->team->name }}</span>
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
                                            (auth()->user()->id == $surat->penerima_id) ||
                                            (auth()->user()->id == $surat->pemberi_id) ||
                                            (auth()->user()->hasRole('ADM'))
                                        );
                        @endphp

                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                            @if ($ruleUser && ($surat->diperiksa == null || auth()->user()->hasRole('ADM')))
                                <a href="{{ route('dinasluarkota.edit', $surat->id) }}" class="btn btn-warning">Edit</a>
                            @endif
                            <button class="btn btn-primary" onclick="copyLink()">Copy Link</button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#QrCodeModal">QR Code</button>

                            @if ($surat->disetujui && $ruleUser)
                                <a href="{{ route('dinasluarkota.cetak', $surat->id) }}" target="_blank" class="btn btn-info">Cetak</a>
                            @endif

                            <a href="{{ route('dinasluarkota.index') }}" class="btn btn-danger">Kembali</a>
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
        otorisasiUrl: "{{ route('dinasluarkota.update.otoritas', $surat->id) }}",
        closeUrl: "{{ route('dinasluarkota.close', $surat->id) }}"
    };
    const url = "{{ route('dinasluarkota.show', $surat->id) }}";

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
