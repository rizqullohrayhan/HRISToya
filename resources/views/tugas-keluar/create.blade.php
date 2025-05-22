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

        .row-detail {
            border: 1px solid #e0e0e0;
            padding: 1rem;
            border-radius: 8px;
            background: #f9f9f9;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tambah Tugas Keluar</h4>
        </div>
        <form id="add-form" action="{{ route('tugas-keluar.store') }}" method="post" enctype="multipart/form-data">
            @csrf
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
                                            <option value="{{ $user->id }}" @selected($user->id == old('penerima_id'))>
                                                {{ $user->name }}
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
                                            <option value="{{ $user->id }}" @selected($user->id == old('pemberi_id'))>
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
                                    $tgl_awal = old('tgl_awal') ? \Carbon\Carbon::parse(old('tgl_awal'))->format('d/m/Y H:i') : '';
                                    $tgl_akhir = old('tgl_akhir') ? \Carbon\Carbon::parse(old('tgl_akhir'))->format('d/m/Y H:i') : '';
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group @error('tgl_awal') has-error @enderror">
                                        <label for="tgl_awal">Tanggal Berangkat<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker @error('tgl_awal') is-invalid @enderror" id="tgl_awal" name="tgl_awal" value="{{ $tgl_awal }}" readonly>
                                        @error('tgl_awal')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('tgl_akhir') has-error @enderror">
                                        <label for="tgl_akhir">Tanggal Kembali<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker @error('tgl_akhir') is-invalid @enderror" id="tgl_akhir" name="tgl_akhir" value="{{ $tgl_akhir }}" readonly>
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
                                        <input type="text" class="form-control" name="kendaraan" id="kendaraan" value="{{ old('kendaraan') }}">
                                        @error('kendaraan')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('no_polisi') has-error @enderror">
                                        <label for="no_polisi">No Polisi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_polisi" id="no_polisi" value="{{ old('no_polisi') }}">
                                        @error('no_polisi')
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
                                                    @php $details = old('details', [[]]); @endphp
                                                    @foreach ($details as $index => $detail)
                                                    <tr class="row-detail">
                                                        <td>
                                                            <button type="button" title="Hapus" class="btn-link btn-danger btn-hapus">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control @error('details.'.$index.'.instansi') is-invalid @enderror" name="details[{{ $index }}][instansi]" id="details[{{ $index }}][instansi]" value="{{ old('details.'.$index.'.instansi') }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control @error('details.'.$index.'.menemui') is-invalid @enderror" name="details[{{ $index }}][menemui]" id="details[{{ $index }}][menemui]" value="{{ old('details.'.$index.'.menemui') }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control @error('details.'.$index.'.tujuan') is-invalid @enderror" name="details[{{ $index }}][tujuan]" id="details[{{ $index }}][tujuan]" value="{{ old('details.'.$index.'.tujuan') }}">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div> --}}
                                        <div id="tabel-detail">
                                            @php $details = old('details', [[]]); @endphp
                                            @foreach ($details as $index => $detail)
                                                <div class="row row-detail mb-3">
                                                    <div class="col-12 d-flex justify-content-start mb-3">
                                                        <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="details_{{ $index }}_instansi" class="form-label">Instansi</label>
                                                        <input type="text" class="form-control @error('details.'.$index.'.instansi') is-invalid @enderror" name="details[{{ $index }}][instansi]" id="details_{{ $index }}_instansi" value="{{ old('details.'.$index.'.instansi') }}">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="details_{{ $index }}_menemui" class="form-label">Pejabat Ditemui</label>
                                                        <input type="text" class="form-control @error('details.'.$index.'.menemui') is-invalid @enderror" name="details[{{ $index }}][menemui]" id="details_{{ $index }}_menemui" value="{{ old('details.'.$index.'.menemui') }}">
                                                    </div>

                                                    <div class="col-md-4 mb-3">
                                                        <label for="details_{{ $index }}_tujuan" class="form-label">Tujuan</label>
                                                        <input type="text" class="form-control @error('details.'.$index.'.tujuan') is-invalid @enderror" name="details[{{ $index }}][tujuan]" id="details_{{ $index }}_tujuan" value="{{ old('details.'.$index.'.tujuan') }}">
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
                                @foreach ($authorization as $key => $info)
                                    <div class="col-md-3">
                                        <table id="otorisasi-table" class="table table-bordered {{ $key }}">
                                            <thead>
                                                <tr>
                                                    <td>{{ $info['label'] }}</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="{{ $key }}">
                                                        <input type="hidden" name="{{ $key }}_at" id="{{ $key }}_at" value="">
                                                        <input type="hidden" name="{{ $key }}_by" id="{{ $key }}_by" value="">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{-- @can($info['permission']) --}}
                                                            <button type="button"
                                                                    class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-{{ $key }}"
                                                                    data-target="{{ $key }}"
                                                                    @if ($info['disabled']) disabled @endif>
                                                                Otorisasi
                                                            </button>
                                                        {{-- @endcan --}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                            {{-- <div class="row">
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
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" id="btn-submit" class="btn btn-success">Submit</button>
                            <a href="{{ route('tugas-keluar.index') }}" class="btn btn-danger">Cancel</a>
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
