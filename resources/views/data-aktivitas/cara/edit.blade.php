@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Cara Aktivitas</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Edit Cara Aktivitas
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('caraaktivitas.update', $cara->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Cara Aktivitas</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $cara->name) }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('caraaktivitas.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
