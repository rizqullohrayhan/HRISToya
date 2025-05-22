@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Kode Perkiraan</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Create Kode Perkiraan
                        </div>
                        <div class="card-category">Tambah kode perkiraan</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kodeperkiraan.store') }}" method="post">
                            @csrf
                            <div class="form-group @error('code') has-error @enderror">
                                <label for="code">Kode Perkiraan</label>
                                <input type="text" class="form-control" name="code" value="{{ old('code') }}"/>
                                @error('code')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Perkiraan</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('kodeperkiraan.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
