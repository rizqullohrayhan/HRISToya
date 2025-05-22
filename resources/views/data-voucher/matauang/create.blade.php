@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Mata Uang</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Create Mata Uang
                        </div>
                        <div class="card-category">Tambah Mata Uang</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('matauang.store') }}" method="post">
                            @csrf
                            <div class="form-group @error('code') has-error @enderror">
                                <label for="code">Singkatan Mata Uang</label>
                                <input type="text" class="form-control" name="code" id="code" value="{{ old('code') }}"/>
                                @error('code')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Mata Uang</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('matauang.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
