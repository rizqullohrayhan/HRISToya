@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Kantor</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Create Kantor
                        </div>
                        <div class="card-category">Tambah Kantor</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kantor.store') }}" method="post">
                            @csrf
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Kantor</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('latitude') has-error @enderror">
                                <label for="latitude">Latitude Kantor</label>
                                <input type="text" class="form-control" name="latitude" value="{{ old('latitude') }}"/>
                                @error('latitude')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('longitude') has-error @enderror">
                                <label for="longitude">Longitude Kantor</label>
                                <input type="text" class="form-control" name="longitude" value="{{ old('longitude') }}"/>
                                @error('longitude')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('jarak_maks') has-error @enderror">
                                <label for="jarak_maks">Jarak Maksimum dalam Kilometer</label>
                                <input type="text" class="form-control" name="jarak_maks" value="{{ old('jarak_maks') }}"/>
                                @error('jarak_maks')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('kantor.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
