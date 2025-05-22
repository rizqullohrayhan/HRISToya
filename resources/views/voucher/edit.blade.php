{{-- @dd(old('details')) --}}
@extends('template.main')

@section('css')
    <style>
        table.table > thead > tr > th,
        table.table > tbody > tr > td {
            padding: 2px 10px !important;
        }

        select.kode_bank {
            width: 6ch;
        }
        select.kode_bank option {
            width: auto;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Pengajuan Voucher</h4>
        </div>
        <form id="add-form" action="{{ route('voucher.update', $voucher->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Voucher {{ $voucher->no_voucher }}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('tanggal') has-error @enderror">
                                            <label for="tanggal">Tgl <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control input-date" id="tanggal" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::createFromFormat('Y-m-d', $voucher->tanggal)->format('d/m/Y')) }}" readonly>
                                            @error('tanggal')
                                            <small class="form-text text-muted">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('status_id') has-error @enderror">
                                            <label for="status_id">Status <span class="text-danger">*</span></label>
                                            <select name="status_id" id="status_id" class="form-select form-control">
                                                @foreach ($status as $statu)
                                                <option value="{{ $statu->id }}" @selected($statu->id == old('status_id', $voucher->status_id))>
                                                    {{ $statu->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('status_id')
                                            <small class="form-text text-muted">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('bank_code_id') has-error @enderror">
                                            <label for="bank_code_id">Kode Kas/Bank <span class="text-danger">*</span></label>
                                            <select name="bank_code_id" id="bank_code_id" class="form-select form-control">
                                                <option value="">Pilih Bank</option>
                                                @foreach ($perkiraans as $perkiraan)
                                                <option value="{{ $perkiraan->id }}" @selected($perkiraan->id == old('bank_code_id', $voucher->bank_code_id))>
                                                    {{ $perkiraan->code }} - {{ $perkiraan->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('bank_code_id')
                                            <small class="form-text text-muted">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('rekan_id') has-error @enderror">
                                            <label for="rekan_id">Rekanan <span class="text-danger">*</span></label>
                                            <select name="rekan_id" id="rekan_id" class="form-select form-control">
                                                <option value="">Pilih Rekan</option>
                                                @foreach ($rekans as $rekan)
                                                <option value="{{ $rekan->id }}" @selected($rekan->id == old('rekan_id', $voucher->rekan_id))>
                                                    {{ $rekan->name }}---{{ $rekan->code }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('rekan_id')
                                            <small class="form-text text-muted">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('pay_for') has-error @enderror">
                                            <label for="pay_for">Dibayar Untuk</label>
                                            <input type="text" class="form-control" name="pay_for" id="pay_for" value="{{ old('pay_for', $voucher->pay_for) }}">
                                            @error('pay_for')
                                            <small class="form-text text-muted">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('tipe_id') has-error @enderror">
                                            <label for="tipe_id">Tipe <span class="text-danger">*</span></label>
                                            <select name="tipe_id" id="tipe_id" class="form-select form-control">
                                                <option value="">Pilih Tipe</option>
                                                @foreach ($tipes as $tipe)
                                                <option value="{{ $tipe->id }}" @selected($tipe->id == old('tipe_id', $voucher->tipe_id))>
                                                    {{ $tipe->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('tipe')
                                            <small class="form-text text-muted">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Detail Voucher
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th rowspan="2"></th>
                                                <th rowspan="2">Kode Bank</th>
                                                <th rowspan="2">Kode</th>
                                                <th rowspan="2">Nama Perkiraan</th>
                                                <th rowspan="2">Mu</th>
                                                <th rowspan="2">Jumlah</th>
                                                <th rowspan="2">Uraian</th>
                                                <th colspan="2">Bukti</th>
                                                <th colspan="2">Rekanan</th>
                                            </tr>
                                            <tr>
                                                <th>Nomer</th>
                                                <th>Tgl</th>
                                                <th>Kode</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider" id="tabel-detail">
                                            @php $details = old('details', $detailVoucher); @endphp
                                            @foreach ($details as $index => $detail)
                                            <tr class="row-detail">
                                                <td>
                                                    <button type="button" title="Hapus Detail Voucher" class="btn-link btn-danger btn-hapus">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                    <input type="hidden" name="details[{{ $index }}][id]" value="{{ old('details.'.$index.'.id', $detail['id']) }}">
                                                </td>
                                                <td class="td_kode_bank">
                                                    <select name="details[{{ $index }}][bank_code_id]" class="kode_bank select_detail" id="details[{{ $index }}][bank_code_id]">
                                                        @foreach ($perkiraans as $perkiraan)
                                                        <option value="{{ $perkiraan->id }}" data-code="{{ $perkiraan->code }}" data-name="{{ $perkiraan->name }}" @selected($perkiraan->id == old('details.'.$index.'.bank_code_id', $detail['bank_code_id']))>
                                                            {{ $perkiraan->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="td_kode_perkiraan">
                                                    <select name="details[{{ $index }}][code]" class="kode_perkiraan select_detail" id="details[{{ $index }}][code]">
                                                        <option value="" data-code="" data-name=""></option>
                                                        @foreach ($perkiraans as $perkiraan)
                                                        <option value="{{ $perkiraan->id }}" data-code="{{ $perkiraan->code }}" data-name="{{ $perkiraan->name }}" @selected($perkiraan->id == old("details.$index.code", $detail['perkiraan_id'] ?? ''))>
                                                            {{ $perkiraan->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $index }}][name]" id="details[{{ $index }}][name]" value="{{ old('details.'.$index.'.name', $detail['perkiraan']['name'] ?? '') }}" readonly>
                                                </td>
                                                <td class="td_currency">
                                                    <select name="details[{{ $index }}][currency_id]" id="details[{{ $index }}][currency_id]" class="currency">
                                                        <option value="">Pilih Mata Uang</option>
                                                        @foreach ($mataUangs as $uang)
                                                        <option value="{{ $uang->id }}" data-code="{{ $uang->code }}" @selected($uang->id == old('details.'.$index.'.currency_id', $detail['currency_id']))>
                                                            {{ $uang->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="input-number" name="details[{{ $index }}][amount]" id="details[{{ $index }}][amount]" value="{{ old('details.'.$index.'.amount', $detail['amount']) }}" placeholder="Jumlah" autocomplete="off"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $index }}][uraian]" id="details[{{ $index }}][uraian]" value="{{ old('details.'.$index.'.uraian', $detail['uraian']) }}" placeholder="Uraian"/>
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $index }}][no_bukti]" id="details[{{ $index }}][no_bukti]" value="{{ old('details.'.$index.'.no_bukti', $detail['no_bukti']) }}" placeholder="No Bukti"/>
                                                </td>
                                                <td>
                                                    <input type="text" class="input-date" id="details[{{ $index }}][tgl_bukti]" name="details[{{ $index }}][tgl_bukti]"
                                                        value="{{ old('details.'.$index.'.tgl_bukti',
                                                        isset($detail['tgl_bukti']) && \Carbon\Carbon::hasFormat($detail['tgl_bukti'], 'Y-m-d')
                                                        ? \Carbon\Carbon::createFromFormat('Y-m-d', $detail['tgl_bukti'])->format('d/m/Y')
                                                        : '') }}"
                                                        readonly>
                                                </td>
                                                <td class="td_rekanan">
                                                    <select name="details[{{ $index }}][rekan_id]" id="details[{{ $index }}][rekan_id]" class="rekanan">
                                                        <option value="" data-code="" data-name="">Pilih Rekanan</option>
                                                        @foreach ($rekans as $rekan)
                                                        <option value="{{ $rekan->id }}" data-code="{{ $rekan->code }}" data-name="{{ $rekan->name }}" @selected($rekan->id == old('details.'.$index.'.rekan_id', $detail['rekan_id']))>
                                                            {{ $rekan->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $index }}][name_rekan]" id="details[{{ $index }}][name_rekan]" value="{{ old('details.'.$index.'.name_rekan', $detail['rekanan']['name'] ?? '') }}" readonly>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-secondary btn-add-detail">Tambah Detail</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Otorisasi
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="otorisasi-table" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Dibuat</td>
                                            <td>Mengetahui</td>
                                            <td>Pembukuan</td>
                                            <td>Disetujui</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="dibuat">
                                                @if ($voucher->user)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($voucher->set_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $voucher->user->name }}</strong></span>
                                                    <span>{{ $voucher->user->team->name }}</span>
                                                    <input type="hidden" name="dibuat_by" id="dibuat_by" value="{{ $voucher->user_id }}">
                                                    <input type="hidden" name="dibuat_at" id="dibuat_at" value="{{ $voucher->set_at }}">
                                                </div>
                                                @else
                                                <input type="hidden" name="dibuat_at" id="dibuat_at" value="">
                                                <input type="hidden" name="dibuat_by" id="dibuat_by" value="">
                                                @endif
                                            </td>
                                            <td class="mengetahui">
                                                @if ($voucher->reviewer)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($voucher->reviewed_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $voucher->reviewer->name }}</strong></span>
                                                    <span>{{ $voucher->reviewer->team->name }}</span>
                                                    <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="{{ $voucher->reviewed_by }}">
                                                    <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="{{ $voucher->reviewed_at }}">
                                                </div>
                                                @else
                                                <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="">
                                                <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="">
                                                @endif
                                            </td>
                                            <td class="pembukuan">
                                                @if ($voucher->bookkeeper)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($voucher->bookkeeped_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $voucher->bookkeeper->name }}</strong></span>
                                                    <span>{{ $voucher->bookkeeper->team->name }}</span>
                                                    <input type="hidden" name="pembukuan_by" id="pembukuan_by" value="{{ $voucher->bookkeeped_by }}">
                                                    <input type="hidden" name="pembukuan_at" id="pembukuan_at" value="{{ $voucher->bookkeeped_at }}">
                                                </div>
                                                @else
                                                <input type="hidden" name="pembukuan_at" id="pembukuan_at" value="">
                                                <input type="hidden" name="pembukuan_by" id="pembukuan_by" value="">
                                                @endif
                                            </td>
                                            <td class="disetujui">
                                                @if ($voucher->approver)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($voucher->approved_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $voucher->approver->name }}</strong></span>
                                                    <span>{{ $voucher->approver->team->name }}</span>
                                                    <input type="hidden" name="pembukuan_by" id="pembukuan_by" value="{{ $voucher->approved_by }}">
                                                    <input type="hidden" name="pembukuan_at" id="pembukuan_at" value="{{ $voucher->approved_at }}">
                                                </div>
                                                @else
                                                <input type="hidden" name="disetujui_at" id="disetujui_at" value="">
                                                <input type="hidden" name="disetujui_by" id="disetujui_by" value="">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($voucher->user && ($voucher->user_id == Auth::user()->id))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-dibuat" data-target="dibuat" @disabled($voucher->reviewer)>
                                                    Hapus
                                                </button>
                                                @elseif ($voucher->user)
                                                @else
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-dibuat" data-target="dibuat">
                                                    Otorisasi
                                                </button>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($voucher->reviewer && ($voucher->reviewed_by == Auth::user()->id))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-mengetahui" data-target="mengetahui" @disabled($voucher->bookkeeper)>
                                                    Hapus
                                                </button>
                                                @elseif ($voucher->reviewer)
                                                @else
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-mengetahui" data-target="mengetahui" @disabled(is_null($voucher->user))>
                                                    Otorisasi
                                                </button>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($voucher->bookkeeper && ($voucher->bookkeeped_by == Auth::user()->id))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-pembukuan" data-target="pembukuan" @disabled($voucher->approver)>
                                                    Hapus
                                                </button>
                                                @elseif ($voucher->bookkeeper)
                                                @else
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-pembukuan" data-target="pembukuan" @disabled(is_null($voucher->reviewer))>
                                                    Otorisasi
                                                </button>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($voucher->approver && ($voucher->approved_by == Auth::user()->id))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-disetujui" data-target="disetujui">
                                                    Hapus
                                                </button>
                                                @elseif ($voucher->approver)
                                                @else
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-disetujui" data-target="disetujui" @disabled(is_null($voucher->bookkeeper))>
                                                    Otorisasi
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- Otorisasi --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Otorisasi
                            </div>
                        </div>
                        <div class="card-body">
                            @error('dibuat_by')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('diperiksa_by')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('disetujui_by')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('mengetahui_by')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="row">
                                <div class="col-md-3">
                                    <table id="otorisasi-table" class="table table-bordered dibuat">
                                        <thead>
                                            <tr>
                                                <td>Dibuat</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="dibuat">
                                                    @if ($voucher->user)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($voucher->set_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $voucher->user->name }}</strong></span>
                                                        <span>{{ $voucher->user->team->name }}</span>
                                                        <input type="hidden" name="dibuat_by" id="dibuat_by" value="{{ $voucher->user_id }}">
                                                        <input type="hidden" name="dibuat_at" id="dibuat_at" value="{{ $voucher->set_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="dibuat_at" id="dibuat_at" value="">
                                                    <input type="hidden" name="dibuat_by" id="dibuat_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($voucher->user && ($voucher->user_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-dibuat" data-target="dibuat" @disabled($voucher->reviewer)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($voucher->user)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-dibuat" data-target="dibuat">
                                                        Otorisasi
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table id="otorisasi-table" class="table table-bordered mengetahui">
                                        <thead>
                                            <tr>
                                                <td>Mengetahui</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="mengetahui">
                                                    @if ($voucher->reviewer)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($voucher->reviewed_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $voucher->reviewer->name }}</strong></span>
                                                        <span>{{ $voucher->reviewer->team->name }}</span>
                                                        <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="{{ $voucher->reviewed_by }}">
                                                        <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="{{ $voucher->reviewed_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="">
                                                    <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($voucher->reviewer && ($voucher->reviewed_by == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-mengetahui" data-target="mengetahui" @disabled($voucher->bookkeeper)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($voucher->reviewer)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-mengetahui" data-target="mengetahui" @disabled(is_null($voucher->user))>
                                                        Otorisasi
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table id="otorisasi-table" class="table table-bordered pembukuan">
                                        <thead>
                                            <tr>
                                                <td>Pembukuan</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="pembukuan">
                                                    @if ($voucher->bookkeeper)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($voucher->bookkeeped_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $voucher->bookkeeper->name }}</strong></span>
                                                        <span>{{ $voucher->bookkeeper->team->name }}</span>
                                                        <input type="hidden" name="pembukuan_by" id="pembukuan_by" value="{{ $voucher->bookkeeped_by }}">
                                                        <input type="hidden" name="pembukuan_at" id="pembukuan_at" value="{{ $voucher->bookkeeped_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="pembukuan_at" id="pembukuan_at" value="">
                                                    <input type="hidden" name="pembukuan_by" id="pembukuan_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($voucher->reviewer && ($voucher->reviewed_by == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-mengetahui" data-target="mengetahui" @disabled($voucher->bookkeeper)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($voucher->reviewer)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-mengetahui" data-target="mengetahui" @disabled(is_null($voucher->user))>
                                                        Otorisasi
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table id="otorisasi-table" class="table table-bordered disetujui">
                                        <thead>
                                            <tr>
                                                <td>Disetujui</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="disetujui">
                                                    @if ($voucher->approver)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($voucher->approved_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $voucher->approver->name }}</strong></span>
                                                        <span>{{ $voucher->approver->team->name }}</span>
                                                        <input type="hidden" name="pembukuan_by" id="pembukuan_by" value="{{ $voucher->approved_by }}">
                                                        <input type="hidden" name="pembukuan_at" id="pembukuan_at" value="{{ $voucher->approved_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="disetujui_at" id="disetujui_at" value="">
                                                    <input type="hidden" name="disetujui_by" id="disetujui_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($voucher->approver && ($voucher->approved_by == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-disetujui" data-target="disetujui">
                                                        Hapus
                                                    </button>
                                                    @elseif ($voucher->approver)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-disetujui" data-target="disetujui" @disabled(is_null($voucher->bookkeeper))>
                                                        Otorisasi
                                                    </button>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" id="btn-submit" class="btn btn-success">Submit</button>
                            <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('custom/voucher/create.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.otorisasi-btn').on('click', function () {
            const userId = "{{ Auth::user()->id }}"; // ID user yang login
            const name = "{{ Auth::user()->name }}"; // Nama user yang login
            const team = "Staff {{ Auth::user()->team->name }}"; // team user

            // Format waktu saat ini (Indonesia)
            const now = new Date().toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });

            // Ambil target kolom dari atribut data-target (misalnya 'dibuat', 'mengetahui', 'pembukuan', 'disetujui')
            const target = $(this).data('target');
            const otorisasiCell = $(`#otorisasi-table tbody tr td.${target}`);

            if ($(this).hasClass('otorisasi-mode')) {
                const waktuLokal = new Date().toLocaleString('sv-SE', { timeZone: 'Asia/Jakarta' }).replace(' ', 'T');
                // Tambahkan tampilan nama user, waktu, dan team di dalam sel tabel (untuk visualisasi)
                otorisasiCell.html(`
                    <div class="d-flex flex-column text-center">
                        <span>${now}</span>
                        <span><strong>${name}</strong></span>
                        <span>${team}</span>
                        <input type="hidden" name="${target}_by" id="${target}_by" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="${target}_at" id="${target}_at" value="${waktuLokal}">
                    </div>
                `);

                // Ubah teks tombol dan kelas
                $(this).removeClass('otorisasi-mode').addClass('hapus-mode').text('Hapus');

                // Aktifkan tombol otorisasi berikutnya
                $(this).closest('td').next('td').find('.otorisasi-btn').prop('disabled', false);
                // Nonaktifkan tombol hapus sebelumnya
                $(this).closest('td').prev('td').find('.otorisasi-btn').prop('disabled', true);
            } else {
                // Kosongkan kolom
                otorisasiCell.html(`
                    <input type="hidden" name="${target}_by" id="${target}_by" value="">
                    <input type="hidden" name="${target}_at" id="${target}_at" value="">
                `);

                // Ubah teks tombol dan kelas
                $(this).removeClass('hapus-mode').addClass('otorisasi-mode').text('Otorisasi');
                // Nonaktifkan tombol otorisasi berikutnya
                $(this).closest('td').next('td').find('.otorisasi-btn').prop('disabled', true);
                // Aktifkan tombol hapus sebelumnya
                $(this).closest('td').prev('td').find('.otorisasi-btn').prop('disabled', false);
            }
        });
    });
</script>
@endsection
