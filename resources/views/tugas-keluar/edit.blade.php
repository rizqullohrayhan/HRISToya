@extends('template.main')

@section('css')
    <style>
        table.table > thead > tr > th,
        table.table > tbody > tr > td {
            padding: 2px 10px !important;
        }

        select.select_detail {
            width: 6ch;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Tugas Keluar</h4>
        </div>
        <form id="add-form" action="{{ route('tugas-keluar.update', $surat->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Tugas Keluar --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Tugas Keluar
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @error('penerima_id') has-error @enderror">
                                        <label for="penerima_id">Penerima Tugas<span class="text-danger">*</span></label>
                                        @if (auth()->user()->hasRole('ADM') || auth()->user()->hasRole('SPV'))
                                        <select name="penerima_id" id="penerima_id" class="form-select form-control select2">
                                            <option value="">Pilih User</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == old('penerima_id', $surat->penerima_id))>
                                                {{ $user->name }} - {{ $user->jabatan }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @else
                                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                        <input type="hidden" id="penerima_id" name="penerima_id" value="{{ auth()->user()->id }}">
                                        @endif
                                        @error('penerima_id')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('pemberi_id') has-error @enderror">
                                        <label for="pemberi_id">Pemberi Tugas<span class="text-danger">*</span></label>
                                        <select name="pemberi_id" id="pemberi_id" class="form-select form-control select2">
                                            <option value="">Pilih User</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == old('pemberi_id', $surat->pemberi_id))>
                                                {{ $user->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('pemberi_id')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                @php
                                    $tgl_awal = old('tgl_awal', $surat->tgl_awal);
                                    $tgl_akhir = old('tgl_akhir', $surat->tgl_akhir);
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group @error('tgl_awal') has-error @enderror">
                                        <label for="tgl_awal">Tanggal Berangkat<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker @error('tgl_awal') is-invalid @enderror" id="tgl_awal" name="tgl_awal" value="{{ \Carbon\Carbon::parse($tgl_awal)->format('d/m/Y H:i') }}" readonly>
                                        @error('tgl_awal')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('tgl_akhir') has-error @enderror">
                                        <label for="tgl_akhir">Sampai Tanggal<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker @error('tgl_akhir') is-invalid @enderror" id="tgl_akhir" name="tgl_akhir" value="{{ \Carbon\Carbon::parse($tgl_akhir)->format('d/m/Y H:i') }}" readonly>
                                        <div class="col-6">
                                            @error('tgl_akhir')
                                            <small class="form-text text-muted text-danger">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('kendaraan') has-error @enderror">
                                        <label for="kendaraan">Kendaraan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kendaraan" id="kendaraan" value="{{ old('kendaraan', $surat->kendaraan) }}">
                                        @error('kendaraan')
                                        <small class="form-text text-muted">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('no_polisi') has-error @enderror">
                                        <label for="no_polisi">No Polisi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_polisi" id="no_polisi" value="{{ old('no_polisi', $surat->no_polisi) }}">
                                        @error('no_polisi')
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
                {{-- Tujuan --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Tujuan
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->has('details'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $errors->first('details') }}
                                            </div>
                                        @endif
                                        @foreach ($errors->get('details.*.instansi') as $key => $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $error[0] }} {{-- Karena $error di sini array, ambil elemen pertama --}}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endforeach
                                        @foreach ($errors->get('details.*.menemui') as $key => $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $error[0] }} {{-- Karena $error di sini array, ambil elemen pertama --}}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endforeach
                                        @foreach ($errors->get('details.*.tujuan') as $key => $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $error[0] }} {{-- Karena $error di sini array, ambil elemen pertama --}}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endforeach
                                        {{-- <div class="table-responsive">
                                            <table class="table table-borderless table-striped">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2"></th>
                                                        <th rowspan="2">Instansi</th>
                                                        <th rowspan="2">Pejabat Ditemui</th>
                                                        <th rowspan="2">Tujuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tabel-detail">
                                                    @php $details = old('details', $surat->detail); @endphp
                                                    @foreach ($details as $index => $detail)
                                                    <tr class="row-detail">
                                                        <td>
                                                            <button type="button" title="Hapus" class="btn-link btn-danger btn-hapus">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <input type="hidden" name="details[{{ $index }}][id]" value="{{ old('details.'.$index.'.id', $detail['id']) }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="details[{{ $index }}][instansi]" id="details[{{ $index }}][instansi]" value="{{ old('details.'.$index.'.instansi', $detail['instansi']) }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="details[{{ $index }}][menemui]" id="details[{{ $index }}][menemui]" value="{{ old('details.'.$index.'.menemui', $detail['menemui']) }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="details[{{ $index }}][tujuan]" id="details[{{ $index }}][tujuan]" value="{{ old('details.'.$index.'.tujuan', $detail['tujuan']) }}">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div> --}}
                                        <div id="tabel-detail">
                                            @php $details = old('details', $surat->detail); @endphp
                                            @foreach ($details as $index => $detail)
                                                <div class="row row-detail mb-3">
                                                    <div class="col-12 d-flex justify-content-start mb-3">
                                                        <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <input type="hidden" name="details[{{ $index }}][id]" value="{{ old('details.'.$index.'.id', $detail['id']) }}">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="details_{{ $index }}_instansi" class="form-label">Instansi</label>
                                                        <input type="text" class="form-control @error('details.'.$index.'.instansi') is-invalid @enderror" name="details[{{ $index }}][instansi]" id="details_{{ $index }}_instansi" value="{{ old('details.'.$index.'.instansi', $detail['instansi']) }}">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="details_{{ $index }}_menemui" class="form-label">Pejabat Ditemui</label>
                                                        <input type="text" class="form-control @error('details.'.$index.'.menemui') is-invalid @enderror" name="details[{{ $index }}][menemui]" id="details_{{ $index }}_menemui" value="{{ old('details.'.$index.'.menemui', $detail['menemui']) }}">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="details_{{ $index }}_tujuan" class="form-label">Tujuan</label>
                                                        <input type="text" class="form-control @error('details.'.$index.'.tujuan') is-invalid @enderror" name="details[{{ $index }}][tujuan]" id="details_{{ $index }}_tujuan" value="{{ old('details.'.$index.'.tujuan', $detail['tujuan']) }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-secondary btn-add-detail">Tambah Tujuan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Otorisasi 196 --}}
                {{-- <div class="col-md-12">
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
                                                <td>Dibuat Oleh</td>
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
                                                        <input type="hidden" name="dibuat_by" id="dibuat_by" value="{{ $surat->dibuat_id }}">
                                                        <input type="hidden" name="dibuat_at" id="dibuat_at" value="{{ $surat->dibuat_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="dibuat_at" id="dibuat_at" value="">
                                                    <input type="hidden" name="dibuat_by" id="dibuat_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($surat->dibuat_at && ($surat->dibuat_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-dibuat" data-target="dibuat" @disabled($surat->diperiksa)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($surat->dibuat_at)
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
                                    <table id="otorisasi-table" class="table table-bordered diperiksa">
                                        <thead>
                                            <tr>
                                                <td>Diperiksa</td>
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
                                                        <input type="hidden" name="diperiksa_by" id="diperiksa_by" value="{{ $surat->diperiksa_id }}">
                                                        <input type="hidden" name="diperiksa_at" id="diperiksa_at" value="{{ $surat->diperiksa_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="diperiksa_at" id="diperiksa_at" value="">
                                                    <input type="hidden" name="diperiksa_by" id="diperiksa_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($surat->diperiksa_at && ($surat->diperiksa_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-diperiksa" data-target="diperiksa" @disabled($surat->disetujui)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($surat->diperiksa_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-diperiksa" data-target="diperiksa" @disabled($surat->dibuat_at == null)>
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
                                                <td>Menyetujui</td>
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
                                                        <input type="hidden" name="disetujui_by" id="disetujui_by" value="{{ $surat->disetujui_id }}">
                                                        <input type="hidden" name="disetujui_at" id="disetujui_at" value="{{ $surat->disetujui_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="disetujui_at" id="disetujui_at" value="">
                                                    <input type="hidden" name="disetujui_by" id="disetujui_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($surat->disetujui_at && ($surat->disetujui_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-disetujui" data-target="disetujui" @disabled($surat->mengetahui)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($surat->disetujui_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-disetujui" data-target="disetujui" @disabled($surat->diperiksa_at == null)>
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
                                                    @if ($surat->mengetahui)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($surat->mengetahui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $surat->mengetahui->name }}</strong></span>
                                                        <span>{{ $surat->mengetahui->jabatan }}</span>
                                                        <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="{{ $surat->mengetahui_id }}">
                                                        <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="{{ $surat->mengetahui_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="">
                                                    <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($surat->mengetahui_at && ($surat->mengetahui_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-mengetahui" data-target="mengetahui">
                                                        Hapus
                                                    </button>
                                                    @elseif ($surat->mengetahui_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-mengetahui" data-target="mengetahui" @disabled($surat->disetujui_at == null)>
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
                </div> --}}
                {{-- Otorisasi 103 --}}
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
                                @foreach ($authorization as $key => $info)
                                    @php
                                        $user = $surat->$key;
                                        $user_id = $key . '_id';
                                        $user_at = $key . '_at';
                                    @endphp
                                    <div class="col-md-3">
                                        <table id="otorisasi-table" class="table table-bordered {{ $key }}">
                                            <thead>
                                                <tr><td>{{ $info['label'] }}</td></tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="{{ $key }}">
                                                        @if ($surat->$user_at)
                                                            <div class="d-flex flex-column">
                                                                <span>{{ \Carbon\Carbon::parse($surat->$user_at)->translatedFormat('d F Y, H.i.s') }}</span>
                                                                <span><strong>{{ $user->name }}</strong></span>
                                                                <span>{{ $user->jabatan }}</span>
                                                                <input type="hidden" name="{{ $key }}_by" id="{{ $key }}_by" value="{{ $surat->$user_id }}">
                                                                <input type="hidden" name="{{ $key }}_at" id="{{ $key }}_at" value="{{ $surat->$user_at }}">
                                                            </div>
                                                        @else
                                                            <input type="hidden" name="{{ $key }}_at" id="{{ $key }}_at" value="">
                                                            <input type="hidden" name="{{ $key }}_by" id="{{ $key }}_by" value="">
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @if ($surat->$user_at && $surat->$user_id == Auth::id())
                                                            <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-{{ $key }}" data-target="{{ $key }}"
                                                                @if ($info['next']) @disabled($surat->{$info['next']}) @endif>
                                                                Hapus
                                                            </button>
                                                        @elseif (!$surat->$user_at)
                                                            <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-{{ $key }}" data-target="{{ $key }}"
                                                                @if ($info['disabled_if']) disabled @endif>
                                                                Otorisasi
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    window.Laravel = {
        userId: "{{ Auth::user()->id }}",
        name: "{{ Auth::user()->name }}",
        team: "{{ Auth::user()->jabatan }}",
    }
</script>
<script src="{{ asset('custom/suratijin/create.js') }}"></script>
@endsection
