<x-modal-action :action="$action" :data="$data">
    @if ($data->id)
        @method('PUT')
    @endif
    <div class="row">
        <div class="col-12">
            <div class="form-group @error('tanggal') has-error @enderror">
                <label for="tanggal">Tanggal</label>
                <input type="text" class="form-control" name="tanggal" id="tanggal" value="{{ $data->tanggal ?? request()->tanggal }}"/>
                @error('tanggal')
                <small class="form-text text-muted">
                    {{ $message }}
                </small>
                @enderror
            </div>
            <div class="form-group @error('keterangan') has-error @enderror">
                <label for="keterangan">Keterangan</label>
                <textarea name="keterangan" class="form-control" id="keterangan" rows="2">{{ $data->keterangan }}</textarea>
                @error('keterangan')
                <small class="form-text text-muted">
                    {{ $message }}
                </small>
                @enderror
            </div>
        </div>
    </div>
</x-modal-action>
