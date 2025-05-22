@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">My Profile</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <div class="card-title">
                            Edit Team
                        </div>
                        <div class="card-category">Edit team</div>
                    </div> --}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 border-right">
                                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                                    <img class="rounded-circle mt-5" style="object-fit:cover;" width="150px" height="150px" src="{{asset('upload/profile_picture/'.$user->picture)}}">
                                    <span class="font-weight-bold">{{ $user->name }}</span>
                                    <span class="text-black-50">{{ $user->email }}</span>
                                    <span class="text-black-50"> Role:
                                        @foreach ($user->roles as $role)
                                        {{ $role->name }},&nbsp;
                                        @endforeach
                                    </span>
                                    <span class="text-black-50">Team: {{ $user->team->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-9 border-right">
                                <div class="p-3 py-5">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="text-right">Profile Settings</h4>
                                    </div>
                                    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <div class="form-group @error('name') has-error @enderror">
                                                        <label class="labels">Nama Lengkap</label>
                                                        <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="{{ old('name', $user->name) }}">
                                                        @error('name')
                                                        <small class="form-text text-muted">
                                                            {{ $message }}
                                                        </small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group @error('username') has-error @enderror">
                                                        <label class="labels">Username</label>
                                                        <input type="text" class="form-control" name="username" placeholder="surname" value="{{ old('username', $user->username) }}">
                                                        @error('username')
                                                        <small class="form-text text-muted">
                                                            {{ $message }}
                                                        </small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group @error('email') has-error @enderror">
                                                        <label class="labels">Email</label>
                                                        <input type="email" class="form-control" name="email" placeholder="enter email" value="{{ old('email', $user->email) }}">
                                                        @error('email')
                                                        <small class="form-text text-muted">
                                                            {{ $message }}
                                                        </small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group @error('jabatan') has-error @enderror">
                                                        <label class="labels">Jabatan</label>
                                                        <input type="text" class="form-control" name="jabatan" placeholder="enter jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                                                        @error('jabatan')
                                                        <small class="form-text text-muted">
                                                            {{ $message }}
                                                        </small>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-md-12">
                                                    <div class="form-group @error('picture') has-error @enderror">
                                                        <label class="labels">New Profile Picture</label>
                                                        <input id="picture" type="file" class="form-control-file @error('picture') is-invalid @enderror" name="picture" accept="image/*" onchange="priviewImage()">
                                                        @error('picture')
                                                        <small class="form-text text-muted">
                                                            {{ $message }}
                                                        </small>
                                                        @enderror
                                                    </div>
                                                    <img class="img-preview mx-auto" width="150px">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-5 text-center">
                                            <button class="btn btn-primary profile-button" type="submit">Save Profile</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        function priviewImage(params) {
            const image = $('#picture')[0];
            const imgPreview = $('.img-preview');

            imgPreview.css('display', 'block');
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.attr('src', oFREvent.target.result);
            }
        }
    </script>
@endsection
