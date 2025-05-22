@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Absensi</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Create Absensi
                        </div>
                        <div class="card-category">Tambah absensi</div>
                    </div>
                    <div class="card-body">
                        <form id="add-form" action="{{ route('absen.update', $absen->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Username</label>
                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->username }}" disabled/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('jenis') has-error @enderror">
                                <label for="jenis">Jenis <span class="text-danger">*</span></label>
                                <select class="form-select" name="jenis" id="jenis">
                                    @foreach ($jenisAbsen as $jenis)
                                        <option value="{{$jenis->id}}" @selected($jenis->id == old('jenis', $absen->jenis_absen_id))>{{ $jenis->name }}</option>
                                    @endforeach
                                </select>
                                @error('jenis')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('picture') has-error @enderror">
                                <label for="picture">Foto</label>
                                <input id="picture" type="file" class="form-control-file" name="picture" accept="image/*" capture="environment" onchange="priviewImage()">
                                @error('picture')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <img src="{{ asset('upload/absen/'.$absen->picture) }}" class="img-preview" width="30%">
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('absen.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
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
