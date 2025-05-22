@extends('template.main')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Aktivitas</h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Create Aktivitas
                        </div>
                        <div class="card-category">Tambah aktivitas</div>
                    </div>
                    <div class="card-body">
                        <form class="row" id="add-form" action="{{ route('aktivitas.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group @error('name') has-error @enderror">
                                    <label for="name">Username</label>
                                    <input type="text" class="form-control" name="name" value="{{ Auth::user()->username }}" disabled>
                                    @error('name')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('tanggal') has-error @enderror">
                                    <label for="tanggal">Tgl <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', date('d/m/Y')) }}">
                                    @error('tanggal')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('jam_awal') has-error @enderror">
                                    <label for="jam_awal">Jam Awal <span class="text-danger">*</span></label>
                                    <select name="jam_awal" id="jam_awal" class="form-select">
                                        @for ($hour = 5; $hour < 24; $hour++)
                                            @for ($minute = 0; $minute < 60; $minute += 30)
                                                <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}" @selected(sprintf('%02d:%02d', $hour, $minute) == old('jam_awal'))>
                                                    {{ sprintf('%02d:%02d', $hour, $minute) }}
                                                </option>
                                            @endfor
                                        @endfor
                                    </select>
                                    @error('jam_awal')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('jam_akhir') has-error @enderror">
                                    <label for="jam_akhir">Jam Akhir <span class="text-danger">*</span></label>
                                    <select name="jam_akhir" id="jam_akhir" class="form-select">
                                        @for ($hour = 5; $hour < 24; $hour++)
                                            @for ($minute = 0; $minute < 60; $minute += 30)
                                                <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}" @selected(sprintf('%02d:%02d', $hour, $minute) == old('jam_akhir'))>
                                                    {{ sprintf('%02d:%02d', $hour, $minute) }}
                                                </option>
                                            @endfor
                                        @endfor
                                    </select>
                                    @error('jam_akhir')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('rencana') has-error @enderror">
                                    <label for="rencana">Rencana</label>
                                    <input type="text" class="form-control" name="rencana" value="{{ old('rencana') }}">
                                    @error('rencana')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('aktivitas') has-error @enderror">
                                    <label for="aktivitas">Aktivitas</label>
                                    <textarea class="form-control" name="aktivitas" id="aktivitas" rows="5">{{ old('aktivitas') }}</textarea>
                                    @error('aktivitas')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('hasil') has-error @enderror">
                                    <label for="hasil">Hasil</label>
                                    <textarea class="form-control" name="hasil" id="hasil" rows="5">{{ old('hasil') }}</textarea>
                                    @error('hasil')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('rekan_id') has-error @enderror">
                                    <label for="rekan_id">Rekanan</label>
                                    <select name="rekan_id" id="rekan_id" class="form-select">
                                        <option value="">Pilih Rekanan</option>
                                        @foreach ($rekanans as $rekan)
                                        <option value="{{ $rekan->id }}" @selected($rekan->id == old('rekan_id'))>
                                            {{ $rekan->name }}---{{ $rekan->code }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('rekan_id')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('tipe_id') has-error @enderror">
                                    <label for="tipe_id">Tipe</label>
                                    <select name="tipe_id" id="tipe_id" class="form-select">
                                        <option value="">Pilih Tipe</option>
                                        @foreach ($tipes as $tipe)
                                        <option value="{{ $tipe->id }}" @selected($tipe->id == old('tipe_id'))>
                                            {{ $tipe->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('tipe_id')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('cara_id') has-error @enderror">
                                    <label for="cara_id">Cara</label>
                                    <select name="cara_id" id="cara_id" class="form-select">
                                        <option value="">Pilih Cara</option>
                                        @foreach ($caras as $cara)
                                        <option value="{{ $cara->id }}" @selected($cara->id == old('cara_id'))>
                                            {{ $cara->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('cara_id')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @error('file') has-error @enderror">
                                    <label for="file">File</label>
                                    <input type="file" class="form-control-file" name="file">
                                    @error('file')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-primary" id="btn-submit">Submit&nbsp;<i class="fas fa-paper-plane"></i></button>
                                <a href="{{ route('aktivitas.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#rekan_id').select2({
            placeholder: 'Pilih Rekanan',
            allowClear: true
        });
        $( "#tanggal" ).datepicker({
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
        });
    });

    $('#add-form').submit(function (e) {
        $('#btn-submit').prop('disabled', true);
        $('#btn-submit').html('loading...');
    })
</script>
@endsection
