{{-- @dd(old('hadir')) --}}
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
        <form id="add-form" action="{{ route('notulen_rapat.store') }}" method="post">
            @csrf
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
                                        <input type="text" class="form-control flatpicker" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" readonly>
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
                                        <textarea class="form-control editor" name="agenda" id="agenda">{{ old('agenda') }}</textarea>
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
                                        <input type="text" class="form-control" name="unit_kerja" id="unit_kerja" value="{{ old('unit_kerja') }}">
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
                                        <input type="text" class="form-control" name="pimpinan" id="pimpinan" value="{{ old('pimpinan') }}">
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
                                            @php $details = old('detail', [[]]); @endphp
                                            @foreach ($details as $index => $detail)
                                                <div class="row row-detail mb-3">
                                                    <div class="col-12 d-flex justify-content-start mb-3">
                                                        <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus-detail">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3 form-uraian">
                                                        <label for="detail_{{ $index }}_uraian" class="form-label">Uraian</label>
                                                        <textarea class="form-control editor" name="detail[{{ $index }}][uraian]" id="detail_{{ $index }}_uraian">{{ old('detail.'.$index.'.uraian') }}</textarea>
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3 form-action">
                                                        <label for="detail_{{ $index }}_action" class="form-label">Action</label>
                                                        <textarea class="form-control editor" name="detail[{{ $index }}][action]" id="detail_{{ $index }}_action">{{ old('detail.'.$index.'.action') }}</textarea>
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3">
                                                        <label for="detail_{{ $index }}_due_date" class="form-label">Due Date</label>
                                                        <input type="text" class="form-control flatpicker-detail" name="detail[{{ $index }}][due_date]" id="detail_{{ $index }}_due_date" value="{{ old('detail.'.$index.'.due_date') }}">
                                                    </div>

                                                    <div class="col-xxl-3 col-md-6 mb-3">
                                                        <label for="detail_{{ $index }}_pic" class="form-label">PIC</label>
                                                        <input type="text" class="form-control" name="detail[{{ $index }}][pic]" id="detail_{{ $index }}_pic" value="{{ old('detail.'.$index.'.pic') }}">
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
                                                        <th colspan="2">Nama</th>
                                                        {{-- <th>Nama Tamu</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody id="tabel-hadir">
                                                    @php $hadirs = old('hadir', [[]]); @endphp
                                                    @foreach ($hadirs as $index => $detail)
                                                    <tr class="row-hadir">
                                                        <td>
                                                            <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus-hadir">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </td>
                                                        <td class="td-hadir">
                                                            <select name="hadir[{{ $index }}][user_id]" class="select_hadir" id="hadir_{{ $index }}_user_id">
                                                                <option value="" data-code="" data-name="">Pilih User</option>
                                                                @foreach ($users as $user)
                                                                <option value="{{ $user->id }}" @selected($user->id == old("hadir.$index.user_id"))>
                                                                    {{ $user->name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="hadir[{{ $index }}][nama]" id="hadir_{{ $index }}_nama" value="{{ old('hadir.'.$index.'.nama') }}">
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
