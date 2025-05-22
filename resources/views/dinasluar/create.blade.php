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
            <h4 class="page-title">Tambah Dinas Luar</h4>
        </div>
        <form id="add-form" action="{{ route('dinasluar.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Dinas Luar --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Detail Dinas Luar
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group @error('user_id') has-error @enderror">
                                        <label for="user_id">Nama <span class="text-danger">*</span></label>
                                        @if (auth()->user()->hasRole('ADM'))
                                        <select name="user_id" id="user_id" class="form-select form-control select2">
                                            <option value="">Pilih User</option>
                                            @foreach ($users as $user)
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
                                <div class="col-md-12">
                                    <div class="form-group @error('berangkat') has-error @enderror">
                                        <label for="tanggal">Tanggal<span class="text-danger">*</span></label>
                                        @php
                                            $berangkat = old('berangkat') ? \Carbon\Carbon::parse(old('berangkat'))->format('d/m/Y H:i') : '';
                                            $kembali = old('kembali') ? \Carbon\Carbon::parse(old('kembali'))->format('d/m/Y H:i') : '';
                                        @endphp
                                        {{-- <div class="input-group flex-wrap">
                                            <input type="text" class="form-control flatpicker @error('berangkat') is-invalid @enderror" id="berangkat" name="berangkat" value="{{ $berangkat }}" readonly>
                                            <span class="input-group-text">Sampai</span>
                                            <input type="text" class="form-control flatpicker @error('kembali') is-invalid @enderror" id="kembali" name="kembali" value="{{ $kembali }}" readonly>
                                        </div> --}}
                                        <div class="input-group">
                                            <input type="text" class="form-control flatpicker @error('berangkat') is-invalid @enderror" id="berangkat" name="berangkat" value="{{ $berangkat }}" readonly>
                                            <span class="input-group-text">Sampai</span>
                                            <input type="text" class="form-control flatpicker @error('kembali') is-invalid @enderror" id="kembali" name="kembali" value="{{ $kembali }}" readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                @error('berangkat')
                                                <small class="form-text text-muted text-danger">
                                                    {{ $message }}
                                                </small>
                                                @enderror
                                            </div>
                                            <div class="col-6">
                                                @error('kembali')
                                                <small class="form-text text-muted text-danger">
                                                    {{ $message }}
                                                </small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('tipe_kendaraan') has-error @enderror">
                                        <label for="tipe_kendaraan">Tipe Kendaraan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tipe_kendaraan" id="tipe_kendaraan" value="{{ old('tipe_kendaraan') }}">
                                        @error('tipe_kendaraan')
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
                                <div class="col-md-6">
                                    <div class="form-group @error('instansi') has-error @enderror">
                                        <label for="instansi">Instansi Tujuan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="instansi" id="instansi" value="{{ old('instansi') }}">
                                        @error('instansi')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('nama_pejabat') has-error @enderror">
                                        <label for="nama_pejabat">Nama Pejabat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_pejabat" id="nama_pejabat" value="{{ old('nama_pejabat') }}">
                                        @error('nama_pejabat')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @error('alamat') has-error @enderror">
                                        <label for="alamat">Alamat <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="alamat" id="alamat" value="{{ old('alamat') }}">
                                        @error('alamat')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('no_telp') has-error @enderror">
                                        <label for="no_telp">No Telepon <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_telp" id="no_telp" value="{{ old('no_telp') }}">
                                        @error('no_telp')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('tujuan') has-error @enderror">
                                        <label for="tujuan">Tujuan Dinas <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="tujuan" id="tujuan" value="{{ old('tujuan') }}">
                                        @error('tujuan')
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
                {{-- Rincian --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Rincian Hasil Dinas Luar
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->has('details'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $errors->first('details') }}
                                            </div>
                                        @endif
                                        @foreach ($errors->get('details.*.deskripsi') as $key => $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $error[0] }} {{-- Karena $error di sini array, ambil elemen pertama --}}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endforeach
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-striped">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Rincian Hasil</th>
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
                                                            <input type="text" class="form-control" name="details[{{ $index }}][deskripsi]" id="details[{{ $index }}][deskripsi]" value="{{ old('details.'.$index.'.deskripsi') }}">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
                            <a href="{{ route('dinasluar.index') }}" class="btn btn-danger">Cancel</a>
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
<script src="{{ asset('custom/dinasluar/create.js') }}"></script>
@endsection
