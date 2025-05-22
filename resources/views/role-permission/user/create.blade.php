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
                            Create User
                        </div>
                        <div class="card-category">Tambah user</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('username') has-error @enderror">
                                <label for="username">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" id="username" value="{{ old('username') }}"/>
                                @error('username')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('email') has-error @enderror">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}"/>
                                @error('email')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('jabatan') has-error @enderror">
                                <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="jabatan" id="jabatan" value="{{ old('jabatan') }}"/>
                                @error('jabatan')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('role') has-error @enderror">
                                <label for="role">Role <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" id="role">
                                    @foreach ($roles as $role)
                                        <option value="{{$role->name}}" @selected($role->name == old('role'))>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('team') has-error @enderror">
                                <label for="team">Team <span class="text-danger">*</span></label>
                                <select class="form-select" name="team" id="team">
                                    @foreach ($teams as $team)
                                        <option value="{{$team->id}}" @selected($team->id == old('team'))>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                                @error('team')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('kantor') has-error @enderror">
                                <label for="kantor">Kantor <span class="text-danger">*</span></label>
                                <select class="form-select" name="kantor" id="kantor">
                                    @foreach ($kantors as $kantor)
                                        <option value="{{$kantor->id}}" @selected($kantor->id == old('kantor'))>{{ $kantor->name }}</option>
                                    @endforeach
                                </select>
                                @error('kantor')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('picture') has-error @enderror">
                                <label for="picture">Foto <span class="text-danger">*</span></label>
                                <input id="picture" type="file" class="form-control-file" name="picture" accept="image/*" required>
                                @error('picture')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('password') has-error @enderror">
                                <label for="password">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" id="password"/>
                                <div id="passwordHelpBlock" class="form-text">
                                    Password harus memiliki minimal 5 karakter, mengandung huruf besar, huruf kecil, dan angka.
                                </div>
                                @error('password')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"/>
                            </div>
                            <div class="card-action">
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
