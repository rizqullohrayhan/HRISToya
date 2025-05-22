@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Macam Cuti</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Edit Macam Cuti
                        </div>
                        <div class="card-category">Edit Macam Cuti</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('macamcuti.update', $macam->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group @error('name') has-error @enderror">
                                <label for="name">Nama Macam Cuti</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $macam->name) }}"/>
                                @error('name')
                                <small class="form-text text-muted">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('macamcuti.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
