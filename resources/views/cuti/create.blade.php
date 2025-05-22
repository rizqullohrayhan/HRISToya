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
            <h4 class="page-title">Tambah Ajuan Cuti</h4>
        </div>
        <form id="add-form" action="{{ route('cuti.store') }}" method="post" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ajuan Cuti
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @error('user_id') has-error @enderror">
                                        <label for="user_id">Nama <span class="text-danger">*</span></label>
                                        @if (auth()->user()->hasRole('ADM'))
                                        <select name="user_id" id="user_id" class="form-select form-control select2">
                                            <option value="">Pilih User</option>
                                            @foreach ($pjs as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == old('user_id'))>
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
                                <div class="col-md-6">
                                    <div class="form-group @error('periode') has-error @enderror">
                                        <label for="periode">Periode Tahun <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="periode" id="periode" value="{{ old('periode', date('Y')) }}">
                                        @error('periode')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @error('tgl_awal') has-error @enderror">
                                        <label for="tanggal">Tanggal Awal Cuti<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker" id="tgl_awal" name="tgl_awal" value="{{ old('tgl_awal') }}" readonly>
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
                                    <div class="form-group @error('tgl_awal') has-error @enderror">
                                        <label for="tanggal">Tanggal Akhir Cuti<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control flatpicker" id="tgl_akhir" name="tgl_akhir" value="{{ old('tgl_akhir') }}" readonly>
                                        </div>
                                        @error('tgl_akhir')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('keperluan') has-error @enderror">
                                        <label for="keperluan">Keperluan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="keperluan" id="keperluan" value="{{ old('keperluan') }}">
                                        @error('keperluan')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('macam_id') has-error @enderror">
                                        <label for="macam_id">Macam Cuti <span class="text-danger">*</span></label>
                                        <select name="macam_id" id="macam_id" class="form-select form-control select2">
                                            <option value="">Pilih Macam Cuti</option>
                                            @foreach ($macams as $macam)
                                            <option value="{{ $macam->id }}" @selected($macam->id == old('macam_id'))>
                                                {{ $macam->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('macam_id')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('jatah_cuti') has-error @enderror">
                                        <label for="jatah_cuti">Jatah Cuti Tahunan <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" min="0" name="jatah_cuti" id="jatah_cuti" value="{{ old('jatah_cuti', 12) }}">
                                        @error('jatah_cuti')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('cuti_bersama') has-error @enderror">
                                        <label for="cuti_bersama">Cuti Bersama (Hari Raya) Tahunan <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" min="0" name="cuti_bersama" id="cuti_bersama" value="{{ old('cuti_bersama', 0) }}">
                                        @error('cuti_bersama')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('cuti_diambil') has-error @enderror">
                                        <label for="cuti_diambil">Cuti yang telah diambil <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" min="0" name="cuti_diambil" id="cuti_diambil" value="{{ old('cuti_diambil', 0) }}">
                                        @error('cuti_diambil')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('cuti_sanksi') has-error @enderror">
                                        <label for="cuti_sanksi">Pelaksanaan Sanksi <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" min="0" name="cuti_sanksi" id="cuti_sanksi" value="{{ old('cuti_sanksi', 0) }}">
                                        @error('cuti_sanksi')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
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
                                            <label for="penganti_id">Nama PJS</label>
                                            <select name="penganti_id" id="penganti_id" class="form-select form-control select2">
                                                <option value="">Pilih PJS</option>
                                                @foreach ($pjs as $pjs)
                                                <option value="{{ $pjs->id }}" @selected($pjs->id == old('penganti_id'))>
                                                    {{ $pjs->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('penganti_id')
                                            <small class="form-text text-muted text-danger">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @if ($errors->has('tugas'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $errors->first('tugas') }}
                                            </div>
                                        @endif
                                        @foreach ($errors->get('tugas.*.name') as $key => $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $error[0] }} {{-- Karena $error di sini array, ambil elemen pertama --}}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endforeach
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-striped">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2"></th>
                                                        <th rowspan="2">Pelimpahan tugas</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tabel-detail">
                                                    @php $details = old('tugas', [[]]); @endphp
                                                    @foreach ($details as $index => $detail)
                                                    <tr class="row-detail">
                                                        <td>
                                                            <button type="button" title="Hapus Tugas" class="btn-link btn-danger btn-hapus">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="tugas[{{ $index }}][name]" id="tugas[{{ $index }}][name]" value="{{ old('tugas.'.$index.'.name') }}">
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
                            @error('mengetahui_by')
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
                                                    <input type="hidden" name="dibuat_at" id="dibuat_at" value="">
                                                    <input type="hidden" name="dibuat_by" id="dibuat_by" value="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-dibuat" data-target="dibuat">
                                                        Otorisasi
                                                    </button>
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
                                                    <input type="hidden" name="diperiksa_at" id="diperiksa_at" value="">
                                                    <input type="hidden" name="diperiksa_by" id="diperiksa_by" value="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-diperiksa" data-target="diperiksa" disabled>
                                                        Otorisasi
                                                    </button>
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
                                                    <input type="hidden" name="disetujui_at" id="disetujui_at" value="">
                                                    <input type="hidden" name="disetujui_by" id="disetujui_by" value="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-disetujui" data-target="disetujui" disabled>
                                                        Otorisasi
                                                    </button>
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
                                                    <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="">
                                                    <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-mengetahui" data-target="mengetahui" disabled>
                                                        Otorisasi
                                                    </button>
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
                            <a href="{{ route('cuti.index') }}" class="btn btn-danger">Cancel</a>
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
<script src="{{ asset('custom/cuti/create.js') }}"></script>
@endsection
