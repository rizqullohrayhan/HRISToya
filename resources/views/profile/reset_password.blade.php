@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Reset Password</h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('profile.reset') }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group @error('old_pass') has-error @enderror">
                                <label for="old_pass">Password Lama</label>
                                <input type="password" class="form-control" name="old_pass"/>
                                @error('old_pass')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('password') has-error @enderror">
                                <label for="password">Password Baru</label>
                                <input type="password" class="form-control" name="password" autocomplete="new-password"/>
                                <div id="passwordHelpBlock" class="form-text">
                                    Password harus memiliki minimal 5 karakter, mengandung huruf besar, huruf kecil, dan angka.
                                </div>
                                @error('password')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('password_confirmation') has-error @enderror">
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password"/>
                                @error('password_confirmation')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('home') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
