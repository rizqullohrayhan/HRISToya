@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Cuti Tahunan</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Create Cuti Tahunan
                        </div>
                        <div class="card-category">Tambah Cuti Tahunan</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('cutitahunan.store') }}" method="post">
                            @csrf
                            <div class="form-group @error('user_id') has-error @enderror">
                                <label for="user_id">Nama User</label>
                                <select name="user_id" id="user_id" class="form-select form-control select2">
                                    <option value="">Pilih User</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected($user->id == old('user_id'))>
                                        {{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('tahun') has-error @enderror">
                                <label for="tahun">Tahun</label>
                                <input type="number" class="form-control" name="tahun" id="tahun" value="{{ old('tahun', date('Y')) }}">
                                @error('tahun')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('total_jatah') has-error @enderror">
                                <label for="total_jatah">Jatah Cuti Tahunan</label>
                                <input type="number" class="form-control" name="total_jatah" id="total_jatah" value="{{ old('total_jatah', 12) }}">
                                @error('total_jatah')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('tambahan') has-error @enderror">
                                <label for="tambahan">Tambahan Cuti Tahunan</label>
                                <input type="number" class="form-control" name="tambahan" id="tambahan" value="{{ old('tambahan', 0) }}">
                                @error('tambahan')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            {{-- <div class="form-group @error('sanksi') has-error @enderror">
                                <label for="sanksi">Sanksi Cuti Tahunan</label>
                                <input type="number" class="form-control" name="sanksi" id="sanksi" value="0">
                                @error('sanksi')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div> --}}
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('cutitahunan.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
