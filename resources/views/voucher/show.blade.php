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
            <h4 class="page-title">Detail Pengajuan Voucher</h4>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    {{-- Voucher --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Voucher {{ $voucher->no_voucher }}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-6">
                                        <span class="fw-bold">Tgl</span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <span>: {{ \Carbon\Carbon::createFromFormat('Y-m-d', $voucher->tanggal)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <span class="fw-bold">Status</span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <span>: {{ $voucher->statusVoucher ? $voucher->statusVoucher->name : '' }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-6">
                                        <span class="fw-bold">Kode Kas/Bank</span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <span>: {{ $voucher->bankCode ? $voucher->bankCode->code : '' }}</span>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <span class="fw-bold">Rekanan</span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <span>: {{ $voucher->rekanan ? $voucher->rekanan->code.' '.$voucher->rekanan->name : '' }}</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2 col-6">
                                        <span class="fw-bold">Dibayar Untuk</span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <span>: {{ $voucher->pay_for }}</span>
                                    </div>
                                    <div class="col-md-2 col-6">
                                        <span class="fw-bold">Tipe</span>
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <span>: {{ $voucher->tipeVoucher ? $voucher->tipeVoucher->name : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Detail Voucher --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Detail Voucher</div>
                                <div class="card-body">
                                    <div class="table-responsive detail-voucher">
                                        <table id="detailVoucher" class="table table-bordered table-striped">
                                            <thead style="text-align:center">
                                                <tr>
                                                    <th colspan="2">Kode</th>
                                                    <th rowspan="2">Nama Perkiraan</th>
                                                    <th rowspan="2">Mu</th>
                                                    <th rowspan="2">Jumlah</th>
                                                    <th rowspan="2" class="text-nowrap">Uraian</th>
                                                    <th colspan="2">Bukti</th>
                                                    <th colspan="2">Rekanan</th>
                                                </tr>
                                                <tr>
                                                    <th>Bank</th>
                                                    <th>Akun</th>
                                                    <th>Nomer</th>
                                                    <th>Tgl</th>
                                                    <th>Kode</th>
                                                    <th>Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($detailVoucher as $index => $detail)
                                                <tr>
                                                    <td class="f-12">{{ $detail->bankCode ? $detail->bankCode->code : '' }}</td>
                                                    <td class="f-12">{{ $detail->perkiraan ? $detail->perkiraan->code : '' }}</td>
                                                    <td class="f-12" style="white-space:nowrap;">
                                                        {!! $detail->perkiraan->name ? implode('<br>', array_map(fn($chunk) => implode(' ', $chunk), array_chunk(explode(' ', $detail->perkiraan->name), 3))) : '' !!}
                                                    </td>
                                                    <td class="f-12">{{ $detail->mataUang ? $detail->mataUang->code : '' }}</td>
                                                    <td class="f-12">{{ $detail->amount }}</td>
                                                    <td class="f-12" style="white-space:nowrap;">
                                                        {!! implode('<br>', array_map(fn($chunk) => implode(' ', $chunk), array_chunk(explode(' ', $detail->uraian), 10))) !!}
                                                    </td>
                                                    <td class="f-12">{{ $detail->no_bukti }}</td>
                                                    <td class="f-12">{{ $detail->tgl_bukti ? \Carbon\Carbon::createFromFormat('Y-m-d', $detail->tgl_bukti)->format('d/m/Y') : '' }}</td>
                                                    <td class="f-12">{{ $detail->rekanan ? $detail->rekanan->code : '' }}</td>
                                                    <td class="f-12" style="white-space:nowrap;">
                                                        {!! $detail->rekanan->name ? implode('<br>', array_map(fn($chunk) => implode(' ', $chunk), array_chunk(explode(' ', $detail->rekanan->name), 5))) : '' !!}
                                                    </td>
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
                                <table id="user" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Dibuat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="user">
                                                @if ($voucher->user)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($voucher->set_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $voucher->user->name }}</strong></span>
                                                    <span>{{ $voucher->user->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($voucher->status_id == 1)
                                        <tr>
                                            <td>
                                                @if ($voucher->user && ($voucher->user_id == Auth::user()->id) && ($voucher->reviewer == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="user">
                                                    Hapus
                                                </button>
                                                @elseif ($voucher->user == null && $voucher->reviewer == null)
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="user">
                                                    Otorisasi
                                                </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="reviewed" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Mengetahui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="reviewed">
                                                @if ($voucher->reviewer)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($voucher->reviewed_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $voucher->reviewer->name }}</strong></span>
                                                    <span>{{ $voucher->reviewer->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($voucher->status_id == 1)
                                        <tr>
                                            <td>
                                                @if ($voucher->reviewer && ($voucher->reviewed_by == Auth::user()->id) && ($voucher->bookkeeper == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="reviewed">
                                                    Hapus
                                                </button>
                                                @elseif ($voucher->reviewer == null && $voucher->bookkeeper == null && $voucher->user != null)
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="reviewed">
                                                        Otorisasi
                                                    </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="bookkeeped" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Pembukuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="bookkeeped">
                                                @if ($voucher->bookkeeper)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($voucher->bookkeeped_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $voucher->bookkeeper->name }}</strong></span>
                                                    <span>{{ $voucher->bookkeeper->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($voucher->status_id == 1)
                                        <tr>
                                            <td>
                                                @if ($voucher->bookkeeper && ($voucher->bookkeeped_by == Auth::user()->id) && ($voucher->approver == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="bookkeeped">
                                                    Hapus
                                                </button>
                                                @elseif ($voucher->bookkeeper == null && $voucher->approver == null && $voucher->reviewer != null)
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="bookkeeped">
                                                    Otorisasi
                                                </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="approved" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="text-transform: none;">Disetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="approved">
                                                @if ($voucher->approver)
                                                <div class="d-flex flex-column">
                                                    <span>{{ \Carbon\Carbon::parse($voucher->approved_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $voucher->approver->name }}</strong></span>
                                                    <span>{{ $voucher->approver->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        @if ($voucher->status_id == 1)
                                        <tr>
                                            <td>
                                                @if ($voucher->approver && ($voucher->approved_by == Auth::user()->id))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="approved">
                                                    Hapus
                                                </button>
                                                @elseif ($voucher->approver == null && $voucher->bookkeeper != null)
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="approved">
                                                    Otorisasi
                                                </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
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
                        <div class="row">
                            @php
                                $ruleUser = ( (auth()->user()->id == $voucher->created_by) ||
                                                (auth()->user()->id == $voucher->user_id) ||
                                                (auth()->user()->hasRole('ADM'))
                                            );
                            @endphp
                            @if ( $ruleUser && ( $voucher->reviewer == null || (auth()->user()->hasRole('ADM')) ) )
                            <div class="col-md-1 my-2">
                                <a href="{{ route('voucher.edit', $voucher->id) }}" class="btn btn-warning"><i class="fas fa-pen"></i>Edit</a>
                            </div>
                            @endif
                            @if ( $ruleUser && ( $voucher->status_id == '1' ) && $voucher->approver )
                            <div class="col-md-1 my-2">
                                <button type="button" class="btn btn-success close-btn"><i class="far fa-window-close"></i>Close</button>
                            </div>
                            @endif
                            <div class="col-md-1 my-2">
                                <a href="{{ route('voucher.cetak', $voucher->id) }}" target="_blank" class="btn btn-info"><i class="fas fa-print"></i>Cetak</a>
                            </div>
                            <div class="col-md-1 my-2">
                                <a href="{{ route('voucher.index') }}" class="btn btn-danger">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    window.Laravel = {
        otorisasiUrl: "{{ route('voucher.update.otoritas', $voucher->id) }}",
        closeUrl: "{{ route('voucher.close', $voucher->id) }}"
    };
</script>
<script src="{{ asset('custom/otorisasi.js') }}"></script>
<script src="{{ asset('custom/voucher/show.js') }}"></script>
@endsection
