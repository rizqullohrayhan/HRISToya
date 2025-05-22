@extends('template.main')

@section('css')
    <style>
        .table > thead > tr > th,
        .table > tbody > tr > td {
            padding: 2px 10px !important;
        }
        .detail-kebun {
            max-height: 200px;
            overflow: auto;
        }
        #detailKebun > thead {
            position: sticky;
            top: 0;
            border-color: gray
        }
        .f-12 {
            font-size: 12px !important;
        }

        .editable-select,
        .editable-date,
        .editable {
            text-decoration-line: underline;
            text-decoration-style: dotted;
        }

        span.contenteditable {
            display: inline-block;
            min-width: 100px;
            padding: 2px;
            background-color: #f5f7fd;
            border: 1px dashed #ccc;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail Kontrak Pengiriman</h4>
        </div>
        <div class="row">
            {{-- Button --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @php
                            $ruleUser = ( (auth()->user()->id == $kontrak->created_by) ||
                                            (auth()->user()->id == $kontrak->dibuat_id) ||
                                            (auth()->user()->hasRole('ADM'))
                                        );
                        @endphp

                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                            <a href="{{ route('kontrak.index') }}" class="btn btn-danger">Kembali</a>
                            <button class="btn btn-primary" onclick="copyLink()">Copy Link</button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#QrCodeModal">QR Code</button>

                            @if ($kontrak->disetujui && (auth()->user()->id == $kontrak->dibuat_id || (auth()->user()->hasRole('ADM'))))
                                <a href="{{ route('kontrak.cetak', $kontrak->id) }}" target="_blank" class="btn btn-info">Cetak</a>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            {{-- Kontrak Pengiriman --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Kontrak Pengiriman</h4>
                            @if ($ruleUser && ($kontrak->diperiksa == null || auth()->user()->hasRole('ADM')))
                            <a href="{{ route('kontrak.edit', $kontrak->id) }}" class="btn btn-warning ms-auto">
                                <i class="fa fa-pen"></i>
                                Edit
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body" id="rekapKontrakPengiriman">
                        @include('pengiriman._rekap_data')
                    </div>
                </div>
            </div>
            {{-- Rencana Pengiriman --}}
            @include('pengiriman._rencana')
            {{-- Data SO --}}
            @include('pengiriman._data_so')
            {{-- Detail Pengiriman --}}
            @include('pengiriman._detail')
            {{-- Kendala Pengiriman --}}
            @include('pengiriman._kendala')
            {{-- Mengetahui Pengiriman --}}
            @include('pengiriman._mengetahui')
            {{-- Otorisasi --}}
            {{-- <div class="col-md-4">
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
                                            <th>Dibuat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="dibuat">
                                                @if ($kontrak->dibuat)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($kontrak->dibuat_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $kontrak->dibuat->name }}</strong></span>
                                                    <span>{{ $kontrak->dibuat->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($kontrak->dibuat && ($kontrak->dibuat_id == Auth::user()->id) && ($kontrak->diperiksa_at == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="dibuat">
                                                    Hapus
                                                </button>
                                                @elseif ($kontrak->dibuat == null && $kontrak->diperiksa_at == null)
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
                                            <th>Diperiksa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="diperiksa">
                                                @if ($kontrak->diperiksa)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($kontrak->diperiksa_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $kontrak->diperiksa->name }}</strong></span>
                                                    <span>{{ $kontrak->diperiksa->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($kontrak->diperiksa && ($kontrak->diperiksa_id == Auth::user()->id) && ($kontrak->disetujui == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="diperiksa">
                                                    Hapus
                                                </button>
                                                @elseif ($kontrak->diperiksa == null && $kontrak->dibuat != null)
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
                                            <th>Menyetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="disetujui">
                                                @if ($kontrak->disetujui)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($kontrak->disetujui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $kontrak->disetujui->name }}</strong></span>
                                                    <span>{{ $kontrak->disetujui->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($kontrak->disetujui && ($kontrak->disetujui_id == Auth::user()->id) && ($kontrak->mengetahui == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="disetujui">
                                                    Hapus
                                                </button>
                                                @elseif ($kontrak->disetujui == null && $kontrak->diperiksa != null)
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
                                            <th>Mengetahui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="mengetahui">
                                                @if ($kontrak->mengetahui)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($kontrak->mengetahui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $kontrak->mengetahui->name }}</strong></span>
                                                    <span>{{ $kontrak->mengetahui->jabatan }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($kontrak->mengetahui && ($kontrak->mengetahui_id == Auth::user()->id))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="mengetahui">
                                                    Hapus
                                                </button>
                                                @elseif ($kontrak->mengetahui == null && $kontrak->disetujui != null)
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
            </div> --}}
        </div>
    </div>
    <div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
    </div>
</div>
@include('modal.qrcode')
@endsection

@section('js')
{{-- <script src="https://cdn.datatables.net/plug-ins/2.3.0/features/scrollResize/dataTables.scrollResize.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    window.Laravel = {
        kontrakId: "{{ $kontrak->id }}",
        otorisasiUrl: "{{ route('tugas-keluar.update.otoritas', $kontrak->id) }}",

        rencanaUrl: "{{ route('rencana.index') }}",
        rencanaInlineEditUrl: "{{ route('rencana.inlineEdit', '__ID__') }}",
        rencanaDeleteUrl: "{{ route('rencana.destroy', '__ID__') }}",

        detailUrl: "{{ route('detail_realisasi.index') }}",
        detailInlineEditUrl: "{{ route('detail_realisasi.inlineEdit', '__ID__') }}",
        detailDeleteUrl: "{{ route('detail_realisasi.destroy', '__ID__') }}",

        dataSOUrl: "{{ route('dataso.index') }}",
        dataSOInlineEditUrl: "{{ route('dataso.inlineEdit', '__ID__') }}",
        dataSODeleteUrl: "{{ route('dataso.destroy', '__ID__') }}",

        kendalaUrl: "{{ route('kendala.index') }}",
        kendalaInlineEditUrl: "{{ route('kendala.inlineEdit', '__ID__') }}",
        kendalaDeleteUrl: "{{ route('kendala.destroy', '__ID__') }}",

        mengetahuiUrl: "{{ route('mengetahui.index') }}",
        mengetahuiInlineEditUrl: "{{ route('mengetahui.inlineEdit', '__ID__') }}",
        mengetahuiDeleteUrl: "{{ route('mengetahui.destroy', '__ID__') }}",
    };
    window.kebunList = @json($rekapKebun->mapWithKeys(fn($k) => [$k->id => $k->kebun]));
    const url = "{{ route('tugas-keluar.show', $kontrak->id) }}";

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
<script src="{{ asset('custom/kontrakpengiriman/dataso.js') }}"></script>
<script src="{{ asset('custom/kontrakpengiriman/kendala.js') }}"></script>
<script src="{{ asset('custom/kontrakpengiriman/realisasi.js') }}"></script>
<script src="{{ asset('custom/kontrakpengiriman/rencananew.js') }}"></script>
<script src="{{ asset('custom/kontrakpengiriman/mengetahui.js') }}"></script>
<script src="{{ asset('custom/kontrakpengiriman/show.js') }}"></script>
{{-- <script src="{{ asset('custom/otorisasi.js') }}"></script> --}}
@endsection
