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
    <link rel="stylesheet" href="{{ asset ('trumbowyg/dist/ui/trumbowyg.min.css')}}">
    <link href="{{ asset('summernote-0.9.0-dist/summernote-lite.min.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tambah Notulen Rapat</h4>
        </div>
        <form id="add-form" action="{{ route('notulen_rapat.update', $notulenRapat->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Notulen Rapat
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group @error('tanggal') has-error @enderror">
                                        <label for="tanggal">Tanggal<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker" id="tanggal" name="tanggal" value="{{ old('tanggal', $notulenRapat->tanggal ? \Carbon\Carbon::parse($notulenRapat->tanggal)->format('d/m/Y H:i') : '') }}" readonly>
                                        @error('tanggal')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @error('agenda') has-error @enderror">
                                        <label for="agenda">Agenda Rapat</label>
                                        <textarea class="form-control editor" name="agenda" id="agenda">{{ old('agenda', $notulenRapat->agenda) }}</textarea>
                                        @error('agenda')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @error('unit_kerja') has-error @enderror">
                                        <label for="unit_kerja">Unit Kerja</label>
                                        <input type="text" class="form-control" name="unit_kerja" id="unit_kerja" value="{{ old('unit_kerja', $notulenRapat->unit_kerja) }}">
                                        @error('unit_kerja')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group @error('pimpinan') has-error @enderror">
                                        <label for="pimpinan">Pimpinan Rapat</label>
                                        <input type="text" class="form-control" name="pimpinan" id="pimpinan" value="{{ old('pimpinan', $notulenRapat->pimpinan) }}">
                                        @error('pimpinan')
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
                {{-- Uraian --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Uraian
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($errors->has('detail'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $errors->first('detail') }}
                                            </div>
                                        @endif
                                        @foreach ($errors->get('detail.*.uraian') as $key => $error)
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $error[0] }} {{-- Karena $error di sini array, ambil elemen pertama --}}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endforeach
                                        <div id="tabel-detail">
                                            @php $details = old('detail', $notulenRapat->uraian); @endphp
                                            @foreach ($details as $index => $detail)
                                                <div class="row row-detail mb-3">
                                                    <div class="col-12 d-flex justify-content-start mb-3">
                                                        <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus-detail">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <input type="hidden" name="detail[{{ $index }}][id]" value="{{ old('detail.'.$index.'.id', $detail['id'] ?? '') }}">
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3 form-uraian">
                                                        <label for="detail_{{ $index }}_uraian" class="form-label">Uraian</label>
                                                        <textarea class="form-control editor" name="detail[{{ $index }}][uraian]" id="detail_{{ $index }}_uraian">{{ old('detail.'.$index.'.uraian', $detail['uraian'] ?? '') }}</textarea>
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3 form-action">
                                                        <label for="detail_{{ $index }}_action" class="form-label">Action</label>
                                                        <textarea class="form-control editor" name="detail[{{ $index }}][action]" id="detail_{{ $index }}_action">{{ old('detail.'.$index.'.action', $detail['action'] ?? '') }}</textarea>
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3">
                                                        <label for="detail_{{ $index }}_due_date" class="form-label">Due Date</label>
                                                        <input type="text" class="form-control flatpicker-detail" name="detail[{{ $index }}][due_date]" id="detail_{{ $index }}_due_date" value="{{ old('detail.'.$index.'.due_date', $detail['due_date'] ?? '') }}">
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3">
                                                        <label for="detail_{{ $index }}_pic" class="form-label">PIC</label>
                                                        <input type="text" class="form-control" name="detail[{{ $index }}][pic]" id="detail_{{ $index }}_pic" value="{{ old('detail.'.$index.'.pic', $detail['pic'] ?? '') }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-secondary btn-add-uraian">Tambah Uraian</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Daftar Hadir --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Daftar Hadir
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group @error('picture') has-error @enderror">
                                            <label for="picture">Foto Daftar Hadir</label>
                                            <input type="file" class="form-control" name="picture" id="picture" accept="image/*">
                                            @error('picture')
                                            <small class="form-text text-muted text-danger">
                                                {{ $message }}
                                            </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @if ($errors->has('hadir'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ $errors->first('hadir') }}
                                            </div>
                                        @endif
                                        @foreach ($errors->get('hadir.*.nama') as $key => $error)
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
                                                        <th>Nama</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tabel-hadir">
                                                    @php
                                                        $hadirs = old('hadir', $notulenRapat->daftarHadir->isNotEmpty() ? $notulenRapat->daftarHadir->toArray() : [[]]);
                                                    @endphp
                                                    @foreach ($hadirs as $index => $detail)
                                                    <tr class="row-hadir">
                                                        <td>
                                                            <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus-hadir">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                            <input type="hidden" name="hadir[{{ $index }}][id]" value="{{ old('hadir.'.$index.'.id', $detail['id'] ?? '') }}">
                                                        </td>
                                                        <td class="td-hadir">
                                                            <select name="hadir[{{ $index }}][user_id]" class="hadir_user_id select_hadir" id="hadir[{{ $index }}][user_id]">
                                                                <option value="" data-code="" data-name="">Pilih User</option>
                                                                @foreach ($users as $user)
                                                                <option value="{{ $user->id }}" @selected($user->id == old("hadir.$index.user_id", $detail['user_id'] ?? ''))>
                                                                    {{ $user->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="hadir[{{ $index }}][nama]" id="hadir_{{ $index }}_nama" value="{{ old('hadir.'.$index.'.nama', $detail['nama'] ?? '') }}">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-secondary btn-add-hadir">Tambah Daftar</button>
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
                                                    @if ($notulenRapat->dibuat_at)
                                                    <div class="d-flex flex-column text-center">
                                                        <span>{{ \Carbon\Carbon::parse($notulenRapat->dibuat_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $notulenRapat->dibuat->name }}</strong></span>
                                                        <span>{{ $notulenRapat->dibuat->jabatan }}</span>
                                                        <input type="hidden" name="dibuat_by" id="dibuat_by" value="{{ $notulenRapat->dibuat_id }}">
                                                        <input type="hidden" name="dibuat_at" id="dibuat_at" value="{{ $notulenRapat->dibuat_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="dibuat_at" id="dibuat_at" value="">
                                                    <input type="hidden" name="dibuat_by" id="dibuat_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($notulenRapat->dibuat_at && ($notulenRapat->dibuat_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-dibuat" data-target="dibuat" @disabled($notulenRapat->diperiksa)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($notulenRapat->dibuat_at)
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
                                                    @if ($notulenRapat->diperiksa)
                                                    <div class="d-flex flex-column text-center">
                                                        <span>{{ \Carbon\Carbon::parse($notulenRapat->diperiksa_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $notulenRapat->diperiksa->name }}</strong></span>
                                                        <span>{{ $notulenRapat->diperiksa->jabatan }}</span>
                                                        <input type="hidden" name="diperiksa_by" id="diperiksa_by" value="{{ $notulenRapat->diperiksa_id }}">
                                                        <input type="hidden" name="diperiksa_at" id="diperiksa_at" value="{{ $notulenRapat->diperiksa_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="diperiksa_at" id="diperiksa_at" value="">
                                                    <input type="hidden" name="diperiksa_by" id="diperiksa_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($notulenRapat->diperiksa_at && ($notulenRapat->diperiksa_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-diperiksa" data-target="diperiksa" @disabled($notulenRapat->disetujui)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($notulenRapat->diperiksa_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-diperiksa" data-target="diperiksa" @disabled($notulenRapat->dibuat_at == null)>
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
                                                    @if ($notulenRapat->disetujui)
                                                    <div class="d-flex flex-column text-center">
                                                        <span>{{ \Carbon\Carbon::parse($notulenRapat->disetujui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $notulenRapat->disetujui->name }}</strong></span>
                                                        <span>{{ $notulenRapat->disetujui->jabatan }}</span>
                                                        <input type="hidden" name="disetujui_by" id="disetujui_by" value="{{ $notulenRapat->disetujui_id }}">
                                                        <input type="hidden" name="disetujui_at" id="disetujui_at" value="{{ $notulenRapat->disetujui_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="disetujui_at" id="disetujui_at" value="">
                                                    <input type="hidden" name="disetujui_by" id="disetujui_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($notulenRapat->disetujui_at && ($notulenRapat->disetujui_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-disetujui" data-target="disetujui" @disabled($notulenRapat->mengetahui)>
                                                        Hapus
                                                    </button>
                                                    @elseif ($notulenRapat->disetujui_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-disetujui" data-target="disetujui" @disabled($notulenRapat->diperiksa_at == null)>
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
                                                    @if ($notulenRapat->mengetahui)
                                                    <div class="d-flex flex-column text-center">
                                                        <span>{{ \Carbon\Carbon::parse($notulenRapat->mengetahui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                        <span><strong>{{ $notulenRapat->mengetahui->name }}</strong></span>
                                                        <span>{{ $notulenRapat->mengetahui->jabatan }}</span>
                                                        <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="{{ $notulenRapat->mengetahui_id }}">
                                                        <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="{{ $notulenRapat->mengetahui_at }}">
                                                    </div>
                                                    @else
                                                    <input type="hidden" name="mengetahui_at" id="mengetahui_at" value="">
                                                    <input type="hidden" name="mengetahui_by" id="mengetahui_by" value="">
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ($notulenRapat->mengetahui_at && ($notulenRapat->mengetahui_id == Auth::user()->id))
                                                    <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode otorisasi-mengetahui" data-target="mengetahui">
                                                        Hapus
                                                    </button>
                                                    @elseif ($notulenRapat->mengetahui_at)
                                                    @else
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode otorisasi-mengetahui" data-target="mengetahui" @disabled($notulenRapat->disetujui_at == null)>
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
                            <a href="{{ route('notulen_rapat.index') }}" class="btn btn-danger">Cancel</a>
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
<script src="{{ asset('custom/notulenrapat/create.js') }}"></script>
@endsection
