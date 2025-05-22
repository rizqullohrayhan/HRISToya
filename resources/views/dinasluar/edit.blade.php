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
            <h4 class="page-title">Edit Dinas Luar</h4>
        </div>
        <form id="add-form" action="{{ route('dinasluar.update', $surat->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                                        @if (auth()->user()->hasRole('ADM') || auth()->user()->hasRole('SPV'))
                                        <select name="user_id" id="user_id" class="form-select form-control select2">
                                            <option value="">Pilih User</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @selected($user->id == old('user_id', $surat->user_id))>
                                                {{ $user->name }} - {{ $user->jabatan }}
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
                                            $berangkat = old('berangkat', $surat->berangkat);
                                            $kembali = old('kembali', $surat->kembali);
                                        @endphp
                                        <div class="input-group">
                                            <input type="text" class="form-control flatpicker @error('berangkat') is-invalid @enderror" id="berangkat" name="berangkat" value="{{ \Carbon\Carbon::parse($berangkat)->format('d/m/Y H:i') }}" readonly>
                                            <span class="input-group-text">Sampai</span>
                                            <input type="text" class="form-control flatpicker @error('kembali') is-invalid @enderror" id="kembali" name="kembali" value="{{ \Carbon\Carbon::parse($kembali)->format('d/m/Y H:i') }}" readonly>
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
                                        <input type="text" class="form-control" name="tipe_kendaraan" id="tipe_kendaraan" value="{{ old('tipe_kendaraan', $surat->tipe_kendaraan) }}">
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
                                        <input type="text" class="form-control" name="no_polisi" id="no_polisi" value="{{ old('no_polisi', $surat->no_polisi) }}">
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
                                        <input type="text" class="form-control" name="instansi" id="instansi" value="{{ old('instansi', $surat->instansi) }}">
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
                                        <input type="text" class="form-control" name="nama_pejabat" id="nama_pejabat" value="{{ old('nama_pejabat', $surat->nama_pejabat) }}">
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
                                        <input type="text" class="form-control" name="alamat" id="alamat" value="{{ old('alamat', $surat->alamat) }}">
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
                                        <input type="text" class="form-control" name="no_telp" id="no_telp" value="{{ old('no_telp', $surat->no_telp) }}">
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
                                        <input type="text" class="form-control" name="tujuan" id="tujuan" value="{{ old('tujuan', $surat->tujuan) }}">
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
                                                    @php $details = old('details', $surat->detail); @endphp
                                                    @foreach ($details as $index => $detail)
                                                    <tr class="row-detail">
                                                        <td>
                                                            <button type="button" title="Hapus" class="btn-link btn-danger btn-hapus">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <input type="hidden" name="details[{{ $index }}][id]" value="{{ old('details.'.$index.'.id', $detail['id'] ?? '') }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="details[{{ $index }}][deskripsi]" id="details[{{ $index }}][deskripsi]" value="{{ old('details.'.$index.'.deskripsi', $detail['deskripsi'] ?? '') }}">
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
        team: "{{ Auth::user()->jabatan }}",
    }
</script>
<script src="{{ asset('custom/dinasluar/create.js') }}"></script>
@endsection
