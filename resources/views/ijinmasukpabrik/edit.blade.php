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
            <h4 class="page-title">Edit Ajuan Surat Ijin Masuk Pabrik</h4>
        </div>
        <form id="add-form" action="{{ route('ijinpabrik.update', $surat->id) }}" method="post" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Ajuan Surat Ijin Masuk Pabrik
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group @error('nama') has-error @enderror">
                                        <label for="nama">Nama Tamu<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama" id="nama" value="{{ old('nama', $surat->nama) }}">
                                        @error('nama')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @error('masuk') has-error @enderror">
                                        <label for="masuk">Waktu Masuk dan Keluar<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control flatpicker" id="masuk" name="masuk" value="{{ old('masuk', $surat->masuk ? \Carbon\Carbon::parse($surat->masuk)->format('d/m/Y H:i') : '') }}" readonly>
                                            <span class="input-group-text">Sampai</span>
                                            <input type="text" class="form-control flatpicker" id="keluar" name="keluar" value="{{ old('keluar', $surat->keluar ? \Carbon\Carbon::parse($surat->keluar)->format('d/m/Y H:i') : '') }}" readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                @error('masuk')
                                                <small class="form-text text-muted text-danger">
                                                    {{ $message }}
                                                </small>
                                                @enderror
                                            </div>
                                            <div class="col-6">
                                                @error('keluar')
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
                                <div class="col-md-12">
                                    <div class="form-group @error('nopol') has-error @enderror">
                                        <label for="nopol">Nomor Polisi Kendaraan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nopol" id="nopol" value="{{ old('nopol', $surat->nopol) }}">
                                        @error('nopol')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @error('picture') has-error @enderror">
                                        <label for="picture">Foto (Surat Pengantar)</label>
                                        <input id="picture" type="file" class="form-control-file" name="picture" accept="image/jpeg, image/png" capture="environment" onchange="priviewImage()">
                                        @error('picture')
                                        <small class="form-text text-muted">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start">
                                    <a href="{{ route('ijinpabrik.ktp', $surat->id) }}" target="_blank" class="btn btn-primary" rel="noopener noreferrer">Lihat Surat Pengantar</a>
                                    {{-- <img class="img-preview img-fluid" src="{{ route('ijinpabrik.ktp', $surat->id) }}" alt="Foto KTP"> --}}
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
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-dibuat" data-target="dibuat" @disabled($surat->disetujui)>
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
                                    <table id="otorisasi-table" class="table table-bordered disetujui">
                                        <thead>
                                            <tr>
                                                <td>Disetujui</td>
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
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-disetujui" data-target="disetujui" @disabled($surat->diterima)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($surat->disetujui_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-disetujui" data-target="disetujui" @disabled($surat->dibuat_at == null)>
                                                        Otorisasi
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table id="otorisasi-table" class="table table-bordered diterima">
                                        <thead>
                                            <tr>
                                                <td>Diterima</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="diterima">
                                                    @if ($surat->diterima)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($surat->diterima_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $surat->diterima->name }}</strong></span>
                                                        <span>{{ $surat->diterima->jabatan }}</span>
                                                        <input type="hidden" name="diterima_by" id="diterima_by" value="{{ $surat->diterima_id }}">
                                                        <input type="hidden" name="diterima_at" id="diterima_at" value="{{ $surat->diterima_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="diterima_at" id="diterima_at" value="">
                                                    <input type="hidden" name="diterima_by" id="diterima_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($surat->diterima_at && ($surat->diterima_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-diterima" data-target="diterima" @disabled($surat->mengetahui)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($surat->diterima_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-diterima" data-target="diterima" @disabled($surat->disetujui_at == null)>
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
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-mengetahui" data-target="mengetahui" @disabled($surat->diterima_at == null)>
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

    function priviewImage(params) {
        const image = $('#picture')[0];
        const imgPreview = $('.img-preview');

        imgPreview.css('display', 'block');
        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent) {
            imgPreview.attr('src', oFREvent.target.result);
        }
    }
</script>
<script src="{{ asset('custom/ijinpabrik/create.js') }}"></script>
@endsection
