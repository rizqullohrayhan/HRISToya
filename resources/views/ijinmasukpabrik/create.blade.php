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
            <h4 class="page-title">Tambah Ajuan Surat Ijin Masuk Pabrik</h4>
        </div>
        <form id="add-form" action="{{ route('ijinpabrik.store') }}" method="post" autocomplete="off" enctype="multipart/form-data">
            @csrf
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
                                        <input type="text" class="form-control" name="nama" id="nama" value="{{ old('nama') }}">
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
                                            <input type="text" class="form-control flatpicker" id="masuk" name="masuk" value="{{ old('masuk') }}" readonly>
                                            <span class="input-group-text">Sampai</span>
                                            <input type="text" class="form-control flatpicker" id="keluar" name="keluar" value="{{ old('keluar') }}" readonly>
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
                                        <input type="text" class="form-control" name="keperluan" id="keperluan" value="{{ old('keperluan') }}">
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
                                        <input type="text" class="form-control" name="nopol" id="nopol" value="{{ old('nopol') }}">
                                        @error('nopol')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @error('picture') has-error @enderror">
                                        <label for="picture">Foto (Surat Pengantar) <span class="text-danger">*</span></label>
                                        <input id="picture" type="file" class="form-control-file" name="picture" accept="image/jpeg, image/png" capture="environment" required onchange="priviewImage()">
                                        @error('picture')
                                        <small class="form-text text-muted">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <img class="img-preview" width="30%">
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
                            @error('diterima_by')
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
                                    <table id="otorisasi-table" class="table table-bordered disetujui">
                                        <thead>
                                            <tr>
                                                <td>Disetujui</td>
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
                                    <table id="otorisasi-table" class="table table-bordered diterima">
                                        <thead>
                                            <tr>
                                                <td>Diterima</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="diterima">
                                                    <input type="hidden" name="diterima_at" id="diterima_at" value="">
                                                    <input type="hidden" name="diterima_by" id="diterima_by" value="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-diterima" data-target="diterima" disabled>
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
                            <a href="{{ route('ijinpabrik.index') }}" class="btn btn-danger">Cancel</a>
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
