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
        <form id="add-form" action="{{ route('dinasluarkota.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                {{-- Dinas Luar Kota --}}
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                Detail Dinas Luar Kota
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
                                <div class="col-md-12">
                                    <div class="form-group @error('berangkat') has-error @enderror">
                                        <label for="berangkat">Tanggal<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control flatpicker @error('berangkat') is-invalid @enderror" id="berangkat" name="berangkat" value="{{ old('berangkat') }}" readonly>
                                        @error('berangkat')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('kendaraan') has-error @enderror">
                                        <label for="kendaraan">Tipe Kendaraan <span class="text-danger">*</span></label>
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
                                <div class="col-md-6">
                                    <div class="form-group @error('kota') has-error @enderror">
                                        <label for="kota">Kota Tujuan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kota" id="kota" value="{{ old('kota') }}">
                                        @error('kota')
                                        <small class="form-text text-muted text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group @error('jangka_waktu') has-error @enderror">
                                        <label for="jangka_waktu">Lama Waktu Dinas <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="jangka_waktu" id="jangka_waktu" value="{{ old('jangka_waktu') }}">
                                            <select name="satuan_waktu" id="satuan_waktu">
                                                <option value="Hari" @selected("Hari" == old('satuan_waktu'))>Hari</option>
                                                <option value="Minggu" @selected("Minggu" == old('satuan_waktu'))>Minggu</option>
                                                <option value="Bulan" @selected("Bulan" == old('satuan_waktu'))>Bulan</option>
                                                <option value="Tahun" @selected("Tahun" == old('satuan_waktu'))>Tahun</option>
                                            </select>
                                        </div>
                                        @error('jangka_waktu')
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
                                Rincian Dinas Luar Kota
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
                                        <div id="tabel-detail">
                                            @php $details = old('detail', [[]]); @endphp
                                            @foreach ($details as $index => $detail)
                                                <div class="row row-detail mb-3">
                                                    <div class="col-12 d-flex justify-content-start mb-3">
                                                        <button type="button" title="Hapus" class="btn btn-sm btn-danger btn-hapus">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>

                                                    <div class="col-xxl-4 col-md-6 mb-4">
                                                        <label for="details_{{ $index }}_instansi" class="form-label">Instansi</label>
                                                        <input type="name" class="form-control" name="details[{{ $index }}][instansi]" id="details_{{ $index }}_instansi" value="{{ old('details.'.$index.'.instansi') }}">
                                                    </div>

                                                    <div class="col-xxl-4 col-md-6 mb-4">
                                                        <label for="details_{{ $index }}_menemui" class="form-label">Menemui</label>
                                                        <input type="name" class="form-control" name="details[{{ $index }}][menemui]" id="details_{{ $index }}_menemui" value="{{ old('details.'.$index.'.menemui') }}">
                                                    </div>

                                                    <div class="col-xxl-4 col-md-6 mb-4">
                                                        <label for="details_{{ $index }}_tujuan" class="form-label">Tujuan</label>
                                                        <input type="text" class="form-control" name="details[{{ $index }}][tujuan]" id="details_{{ $index }}_tujuan" value="{{ old('details.'.$index.'.tujuan') }}">
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
                            <a href="{{ route('dinasluarkota.index') }}" class="btn btn-danger">Cancel</a>
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
<script src="{{ asset('custom/dinasluarkota/create.js') }}"></script>
@endsection
