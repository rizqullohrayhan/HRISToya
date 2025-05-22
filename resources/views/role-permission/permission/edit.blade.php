@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Permission</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Edit Permission
                        </div>
                        <div class="card-category">Edit permission</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permission.update', $permission->id) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Permission</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $permission->name) }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('permission.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
