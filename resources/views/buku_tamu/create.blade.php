@extends('template.main')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Tambah Kunjungan
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="row" id="add-form" action="{{ route('bukutamu.store') }}" method="post">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group @error('tgl') has-error @enderror">
                                    <label for="tgl">Tanggal <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control flatpicker" id="tgl" name="tgl" value="{{ old('tgl') }}" readonly>
                                    @error('tgl')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-3 align-items-center" style="padding: 10px">
                                    <div class="col-auto">
                                        <label for="jam_awal">Jam Awal Kunjungan <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="time" class="form-control @error('jam_awal') is-invalid @enderror" name="jam_awal" id="jam_awal" value="{{ old('jam_awal') }}">
                                    </div>
                                    <div class="col-auto">
                                        <span class="form-text">
                                            WIB
                                        </span>
                                    </div>
                                </div>
                                @error('jam_awal')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="row g-3 align-items-center" style="padding: 10px">
                                    <div class="col-auto">
                                        <label for="jam_akhir">Jam Akhir Kunjungan</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="time" class="form-control @error('jam_akhir') is-invalid @enderror" name="jam_akhir" id="jam_akhir" value="{{ old('jam_akhir') }}">
                                    </div>
                                    <div class="col-auto">
                                        <span class="form-text">
                                            WIB
                                        </span>
                                    </div>
                                </div>
                                @error('jam_akhir')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('name') has-error @enderror">
                                    <label for="name">Nama Tamu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                    @error('name')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('telp') has-error @enderror">
                                    <label for="telp">No Telp</label>
                                    <input type="text" class="form-control" name="telp" value="{{ old('telp') }}">
                                    @error('telp')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('instansi') has-error @enderror">
                                    <label for="instansi">Instansi</label>
                                    <input type="text" class="form-control" name="instansi" value="{{ old('instansi') }}">
                                    @error('instansi')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('alamat') has-error @enderror">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" name="alamat" value="{{ old('alamat') }}">
                                    @error('alamat')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('menemui') has-error @enderror">
                                    <label for="menemui">Menemui <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="menemui" value="{{ old('menemui', auth()->user()->name) }}">
                                    @error('menemui')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @error('keperluan') has-error @enderror">
                                    <label for="keperluan">Keperluan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="keperluan" value="{{ old('keperluan') }}">
                                    @error('keperluan')
                                    <small class="form-text text-muted">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="fst-italic p-3"><span class="text-danger">*</span>) Wajib diisi</div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-primary" id="btn-submit">Submit&nbsp;<i class="fas fa-paper-plane"></i></button>
                                <a href="{{ route('bukutamu.index') }}" class="btn btn-danger">Cancel</a>
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
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        $( ".flatpicker" ).flatpickr({
            dateFormat: 'd/m/Y',
        });
    });

    $('#add-form').submit(function (e) {
        $('#btn-submit').prop('disabled', true);
        $('#btn-submit').html('loading...');
    })
</script>
@endsection
