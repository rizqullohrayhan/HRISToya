@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Dokumen</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Add Dokumen
                        </div>
                        <div class="card-category">Tambah dokumen</div>
                    </div>
                    <div class="card-body">
                        <form id="add-form" action="{{ route('masterdokumen.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('keterangan') has-error @enderror">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('file') has-error @enderror">
                                <label for="file">File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="file" id="file" required/>
                                @error('file')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" id="btn-submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('masterdokumen.index') }}" class="btn btn-danger">Cancel</a>
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
        $('#add-form').submit(function (e) {
            $('#btn-submit').prop('disabled', true);
            $('#btn-submit').html('loading...');
        });
    </script>
@endsection
