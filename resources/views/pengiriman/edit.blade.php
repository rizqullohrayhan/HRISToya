{{-- @dd(old('hadir')) --}}
@extends('template.main')

@section('css')
    <style>
        table.table > thead > tr > th,
        table.table > tbody > tr > td {
            padding: 2px 10px !important;
        }

        .row-kebun {
            border: 1px solid #e0e0e0;
            padding: 1rem;
            border-radius: 8px;
            background: #f9f9f9;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset ('trumbowyg/dist/ui/trumbowyg.min.css')}}">
    <link href="{{ asset('summernote-0.9.0-dist/summernote-lite.min.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4"></script>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tambah Kontrak Pengiriman</h4>
        </div>
        <form id="add-form" action="{{ route('kontrak.update', $kontrak->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Kontrak --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Kontrak Pengiriman
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @error('perusahaan') has-error @enderror">
                                        <label for="perusahaan">Perusahaan<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="perusahaan" id="perusahaan" value="{{ old('perusahaan', $kontrak->perusahaan) }}">
                                        @error('perusahaan')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('customer') has-error @enderror">
                                        <label for="customer">Customer<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer" id="customer" value="{{ old('customer', $kontrak->customer) }}">
                                        @error('customer')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('no_kontrak') has-error @enderror">
                                        <label for="no_kontrak">No Kontrak<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="no_kontrak" id="no_kontrak" value="{{ old('no_kontrak', $kontrak->no_kontrak) }}">
                                        @error('no_kontrak')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('barang') has-error @enderror">
                                        <label for="barang">Barang<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="barang" id="barang" value="{{ old('barang', $kontrak->barang) }}">
                                        @error('barang')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('kuantitas') has-error @enderror">
                                        <label for="kuantitas">Kuantitas</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control input-number" name="kuantitas" id="kuantitas" value="{{ old('kuantitas', $kontrak->kuantitas) }}" readonly>
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                        <small class="form-text text-muted">
                                            Terisi otomatis dari rekap kebun
                                        </small>
                                        @error('kuantitas')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('semester') has-error @enderror">
                                        <label for="semester">Semester</label>
                                        <input type="text" class="form-control" name="semester" id="semester" value="{{ old('semester', $kontrak->semester) }}">
                                        @error('semester')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('tgl_mulai_kirim') has-error @enderror">
                                        <label for="tgl_mulai_kirim">Tanggal Mulai Kirim<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker" id="tgl_mulai_kirim" name="tgl_mulai_kirim" value="{{ old('tgl_mulai_kirim', $kontrak->tgl_mulai_kirim ? \Carbon\Carbon::parse($kontrak->tgl_mulai_kirim)->format('dm/Y') : '') }}" readonly>
                                        @error('tgl_mulai_kirim')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('jangka_waktu_kirim') has-error @enderror">
                                        <label for="jangka_waktu_kirim">Jangka Waktu Kirim<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="jangka_waktu_kirim" id="jangka_waktu_kirim" value="{{ old('jangka_waktu_kirim', $kontrak->jangka_waktu_kirim) }}">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                        @error('jangka_waktu_kirim')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('target') has-error @enderror">
                                        <label for="target">Target<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="target" id="target" value="{{ old('target', $kontrak->target) }}">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('target')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('batas_kirim') has-error @enderror">
                                        <label for="batas_kirim">Batas Kirim<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker" name="batas_kirim" id="batas_kirim" value="{{ old('batas_kirim', $kontrak->batas_kirim ? \Carbon\Carbon::parse($kontrak->batas_kirim)->format('dm/Y') : '') }}">
                                        @error('batas_kirim')
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
                {{-- Kebun --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Data Kebun
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->has('kebun'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $errors->first('kebun') }}
                                            </div>
                                        @endif
                                        @foreach ($errors->get('kebun.*.uraian') as $key => $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $error[0] }} {{-- Karena $error di sini array, ambil elemen pertama --}}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endforeach
                                        <div id="tabel-kebun">
                                            @php $kebuns = old('kebun', $kontrak->rekapKebunPengiriman); @endphp
                                            @foreach ($kebuns as $index => $kebun)
                                                <div class="row row-kebun mb-3">
                                                    <div class="col-1 align-self-center mb-3">
                                                        <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus-kebun">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <input type="hidden" name="kebun[{{ $index }}][id]" id="kebun_{{ $index }}_id" value="{{$kebun['id'] ?? ''}}">
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3">
                                                        <label for="kebun_{{ $index }}_vendor" class="form-label">Vendor</label>
                                                        <input type="text" class="form-control" name="kebun[{{ $index }}][vendor]" id="kebun_{{ $index }}_vendor" value="{{ old('kebun.'.$index.'.vendor', $kebun['vendor'] ?? '') }}">
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3">
                                                        <label for="kebun_{{ $index }}_kebun" class="form-label">Kebun<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="kebun[{{ $index }}][kebun]" id="kebun_{{ $index }}_kebun" value="{{ old('kebun.'.$index.'.kebun', $kebun['kebun'] ?? '') }}">
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3">
                                                        <label for="kebun_{{ $index }}_kontrak" class="form-label">Kontrak<span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control kontrak-kebun input-number" name="kebun[{{ $index }}][kontrak]" id="kebun_{{ $index }}_kontrak" value="{{ old('kebun.'.$index.'.kontrak', $kebun['kontrak'] ?? '') }}">
                                                            <span class="input-group-text">Kg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-secondary btn-add-kebun">Tambah Kebun</button>
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
                            @error('dibuat_id')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('diperiksa_id')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('disetujui_id')
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('mengetahui_id')
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
                                                    @if ($kontrak->dibuat_at)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($kontrak->dibuat_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $kontrak->dibuat->name }}</strong></span>
                                                        <span>{{ $kontrak->dibuat->jabatan }}</span>
                                                        <input type="hidden" name="dibuat_id" id="dibuat_id" value="{{ $kontrak->dibuat_id }}">
                                                        <input type="hidden" name="dibuat_at" id="dibuat_at" value="{{ $kontrak->dibuat_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="dibuat_at" id="dibuat_at" value="">
                                                    <input type="hidden" name="dibuat_id" id="dibuat_id" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($kontrak->dibuat_at && ($kontrak->dibuat_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-dibuat" data-target="dibuat" @disabled($kontrak->diperiksa)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($kontrak->dibuat_at)
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
                                                    @if ($kontrak->diperiksa)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($kontrak->diperiksa_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $kontrak->diperiksa->name }}</strong></span>
                                                        <span>{{ $kontrak->diperiksa->jabatan }}</span>
                                                        <input type="hidden" name="diperiksa_id" id="diperiksa_id" value="{{ $kontrak->diperiksa_id }}">
                                                        <input type="hidden" name="diperiksa_at" id="diperiksa_at" value="{{ $kontrak->diperiksa_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="diperiksa_at" id="diperiksa_at" value="">
                                                    <input type="hidden" name="diperiksa_id" id="diperiksa_id" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($kontrak->diperiksa_at && ($kontrak->diperiksa_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-diperiksa" data-target="diperiksa" @disabled($kontrak->disetujui)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($kontrak->diperiksa_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-diperiksa" data-target="diperiksa" @disabled($kontrak->dibuat_at == null)>
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
                                                    @if ($kontrak->disetujui)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($kontrak->disetujui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $kontrak->disetujui->name }}</strong></span>
                                                        <span>{{ $kontrak->disetujui->jabatan }}</span>
                                                        <input type="hidden" name="disetujui_id" id="disetujui_id" value="{{ $kontrak->disetujui_id }}">
                                                        <input type="hidden" name="disetujui_at" id="disetujui_at" value="{{ $kontrak->disetujui_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="disetujui_at" id="disetujui_at" value="">
                                                    <input type="hidden" name="disetujui_id" id="disetujui_id" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($kontrak->disetujui_at && ($kontrak->disetujui_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-disetujui" data-target="disetujui" @disabled($kontrak->mengetahui)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($kontrak->disetujui_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-disetujui" data-target="disetujui" @disabled($kontrak->diperiksa_at == null)>
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
                                                    @if ($kontrak->mengetahui)
                                                    <div class="d-flex flex-column">
                                                        <span>{{ \Carbon\Carbon::parse($kontrak->mengetahui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $kontrak->mengetahui->name }}</strong></span>
                                                        <span>{{ $kontrak->mengetahui->jabatan }}</span>
                                                        <input type="hidden" name="mengetahui_id" id="mengetahui_id" value="{{ $kontrak->mengetahui_id }}">
                                                        <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="{{ $kontrak->mengetahui_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="">
                                                    <input type="hidden" name="mengetahui_id" id="mengetahui_id" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($kontrak->mengetahui_at && ($kontrak->mengetahui_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-mengetahui" data-target="mengetahui">
                                                        Hapus
                                                    </button>
                                                    @elseif ($kontrak->mengetahui_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-mengetahui" data-target="mengetahui" @disabled($kontrak->disetujui_at == null)>
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
<!-- Import Trumbowyg -->
<script src="{{ asset ('trumbowyg/dist/trumbowyg.min.js')}}"></script>
<script src="{{ asset ('trumbowyg/dist/plugins/cleanpaste/trumbowyg.cleanpaste.min.js')}}"></script>
<script src="{{ asset ('trumbowyg/dist/plugins/fontsize/trumbowyg.fontsize.min.js')}}"></script>
<script src="{{ asset ('summernote-0.9.0-dist/summernote-lite.min.js') }}"></script>
<script>
    window.Laravel = {
        userId: "{{ Auth::user()->id }}",
        name: "{{ Auth::user()->name }}",
        team: "{{ Auth::user()->jabatan }}",
    }
</script>
<script src="{{ asset('custom/kontrakpengiriman/edit.js') }}"></script>
@endsection
