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
                            Edit Dokumen
                        </div>
                        <div class="card-category">Edit dokumen</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('masterdokumen.update', $dokumen->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Dokumen <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $dokumen->name) }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('keterangan') has-error @enderror">
                                <label for="keterangan">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan', $dokumen->keterangan) }}</textarea>
                                @error('keterangan')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group @error('file') has-error @enderror">
                                <label for="file">File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="file" id="file"/>
                                @error('file')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                                <a href="{{ route('master_dokumen.download', $dokumen->id) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-file-alt"></i> File Sebelumnya
                                </a>
                                <small class="form-text text-muted">
                                    <strong>Note:</strong> Jika tidak ingin mengubah file, biarkan kosong.
                                </small>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
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
