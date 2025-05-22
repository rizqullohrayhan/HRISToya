@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Role</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Edit Permission Role {{ $role->name }}
                        </div>
                        <div class="card-category">Edit permission untuk role {{ $role->name }}</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('role.update.permission', $role->id) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="form-group @error('permission') has-error @enderror">
                                <label for="">Permission</label>
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" name="permission[]" type="checkbox" value="{{$permission->name}}" id="permission{{$permission->id}}" @checked(in_array($permission->id, $rolePermissions))>
                                            <label class="form-check-label" for="permission{{$permission->id}}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('role.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
