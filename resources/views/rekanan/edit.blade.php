@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Rekanan</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Edit Rekanan
                        </div>
                        <div class="card-category">Edit rekanan</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('rekan.update', $rekanan->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Rekanan</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $rekanan->name) }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('code') has-error @enderror">
                                <label for="code">Kode Rekanan</label>
                                <input type="text" class="form-control" name="code" value="{{ old('code', $rekanan->code) }}"/>
                                @error('code')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('rekan.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
