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
            <h4 class="page-title">Edit Ajuan Ijin</h4>
        </div>
        <form id="add-form" action="{{ route('ijin.update', $surat->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Surat Ijin --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ajuan Ijin
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="form-group @error('user_id') has-error @enderror">
                                    <label for="user_id">Nama <span class="text-danger">*</span></label>
                                    @if (auth()->user()->hasRole('ADM'))
                                    <select name="user_id" id="user_id" class="form-select form-control select2">
                                        <option value="">Pilih User</option>
                                        @foreach ($pjs as $user)
                                        <option value="{{ $user->id }}" @selected($user->id == old('user_id', $surat->user_id))>
                                            {{ $user->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                    <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
                                    @endif
                                    @error('user_id')
                                    <small class="form-text text-muted text-danger">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group @error('tanggal') has-error @enderror">
                                    <label for="tanggal">Tgl <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control input-date" id="tanggal" name="tanggal" value="{{ old('tanggal', \Carbon\Carbon::createFromFormat('Y-m-d', $surat->tanggal)->format('d/m/Y')) }}" readonly>
                                    @error('tanggal')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="form-group @error('tgl_awal') has-error @enderror">
                                    <label for="tanggal">Tanggal Ijin<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control flatpicker" id="tgl_awal" name="tgl_awal" value="{{ old('tgl_awal', \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_awal)->format('d/m/Y H:i')) }}" readonly>
                                        {{-- <select name="jam_awal" id="jam_awal" class="form-select  form-control">
                                            <option value="">Pilih Jam Awal</option>
                                            @for ($hour = 5; $hour < 24; $hour++)
                                                @for ($minute = 0; $minute < 60; $minute += 30)
                                                    <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}" @selected(sprintf('%02d:%02d', $hour, $minute) == old('jam_awal'))>
                                                        {{ sprintf('%02d:%02d', $hour, $minute) }}
                                                    </option>
                                                @endfor
                                            @endfor
                                        </select> --}}
                                        <span class="input-group-text">Sampai</span>
                                        <input type="text" class="form-control flatpicker" id="tgl_akhir" name="tgl_akhir" value="{{ old('tgl_akhir', \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $surat->tgl_akhir)->format('d/m/Y H:i')) }}" readonly>
                                        {{-- <select name="jam_akhir" id="jam_akhir" class="form-select  form-control">
                                            <option value="">Pilih Jam Akhir</option>
                                            @for ($hour = 5; $hour < 24; $hour++)
                                                @for ($minute = 0; $minute < 60; $minute += 30)
                                                    <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}" @selected(sprintf('%02d:%02d', $hour, $minute) == old('jam_akhir'))>
                                                        {{ sprintf('%02d:%02d', $hour, $minute) }}
                                                    </option>
                                                @endfor
                                            @endfor
                                        </select> --}}
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            @error('tgl_awal')
                                            <small class="form-text text-muted text-danger">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                        <div class="col-6">
                                            @error('tgl_akhir')
                                            <small class="form-text text-muted text-danger">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('keperluan') has-error @enderror">
                                    <label for="keperluan">Keperluan</label>
                                    <input type="text" class="form-control" name="keperluan" id="keperluan" value="{{ old('keperluan', $surat->keperluan) }}">
                                    @error('keperluan')
                                    <small class="form-text text-muted text-danger">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- PJS --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                PJS
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group @error('penganti_id') has-error @enderror">
                                            <label for="penganti_id">Nama PJS <span class="text-danger">*</span></label>
                                            <select name="penganti_id" id="penganti_id" class="form-select form-control select2">
                                                <option value="">Pilih PJS</option>
                                                @foreach ($pjs as $pjs)
                                                <option value="{{ $pjs->id }}" @selected($pjs->id == old('penganti_id', $surat->pjs[0]->penganti_id))>
                                                    {{ $pjs->name }} - {{ $pjs->jabatan }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('penganti_id')
                                            <small class="form-text text-muted">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2"></th>
                                                        <th rowspan="2">Pelimpahan tugas</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-group-divider" id="tabel-detail">
                                                    @php $details = old('tugas', $surat->pjs); @endphp
                                                    @foreach ($details as $index => $detail)
                                                    <tr class="row-detail">
                                                        <td>
                                                            <button type="button" title="Hapus Tugas" class="btn-link btn-danger btn-hapus">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <input type="hidden" name="tugas[{{ $index }}][id]" value="{{ old('tugas.'.$index.'.id', $detail['id'] ?? '') }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="tugas[{{ $index }}][name]" id="tugas[{{ $index }}][name]" value="{{ old('tugas.'.$index.'.name', $detail['tugas'] ?? '') }}">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-secondary btn-add-detail">Tambah Tugas</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        team: "Staff {{ Auth::user()->jabatan }}",
    }
</script>
<script src="{{ asset('custom/suratijin/create.js') }}"></script>
@endsection
