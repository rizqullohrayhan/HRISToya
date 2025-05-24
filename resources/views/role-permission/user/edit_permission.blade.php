@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">User</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Edit Permission User {{ $user->name }}
                        </div>
                        <div class="card-category">Edit permission langsung untuk user ini (tidak termasuk dari role)</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.update.permission', $user->id) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="form-group @error('permissions') has-error @enderror">
                                <label for="">Permissions</label>
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                name="permissions[]"
                                                type="checkbox"
                                                value="{{ $permission->name }}"
                                                id="permission{{ $permission->id }}"
                                                @checked($user->hasDirectPermission($permission->name))
                                            >
                                            <label class="form-check-label" for="permission{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                            @if ($user->hasPermissionTo($permission->name) && !$user->hasDirectPermission($permission->name))
                                                <span class="text-muted text-danger" style="font-size: 12px;">(via role)</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-action mt-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('user.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
