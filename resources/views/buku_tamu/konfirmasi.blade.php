@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Konfirmasi Kunjungan</h4>
                        </div>
                    </div>
                    <div class="card-body" id="cardKonfirmasi">
                        @include('buku_tamu._konfirmasi')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Komponen -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-3">
            <form id="form-kedatangan" action="{{ route('bukutamu.confirm', $bukuTamu->token) }}" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="confirm" value="datang">
                    <div class="mb-3">
                        <label for="foto_diri" class="form-label">Foto Diri <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="foto_diri" name="foto_diri" accept="image/jpeg, image/png" capture="environment" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_card" class="form-label">Foto ID Card <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" id="id_card" name="id_card" accept="image/jpeg, image/png" capture="environment" required>
                    </div>
                    <div class="mb-3">
                        <label for="surat_pengantar" class="form-label">Surat Pengantar</label>
                        <input class="form-control" type="file" id="surat_pengantar" name="surat_pengantar" accept="image/jpeg, image/png" capture="environment">
                    </div>
                    <div class="mb-3">
                        <label for="kendaraan_tampak_depan" class="form-label">Kendaraan Tampak Depan</label>
                        <input class="form-control" type="file" id="kendaraan_tampak_depan" name="kendaraan_tampak_depan" accept="image/jpeg, image/png" capture="environment">
                    </div>
                    <div class="mb-3">
                        <label for="kendaraan_tampak_belakang" class="form-label">Kendaraan Tampak Belakang</label>
                        <input class="form-control" type="file" id="kendaraan_tampak_belakang" name="kendaraan_tampak_belakang" accept="image/jpeg, image/png" capture="environment">
                    </div>
                    <div class="mb-3">
                        <label for="kendaraan_tampak_samping_kanan" class="form-label">Kendaraan Tampak Samping Kanan</label>
                        <input class="form-control" type="file" id="kendaraan_tampak_samping_kanan" name="kendaraan_tampak_samping_kanan" accept="image/jpeg, image/png" capture="environment">
                    </div>
                    <div class="mb-3">
                        <label for="kendaraan_tampak_samping_kiri" class="form-label">Kendaraan Tampak Samping Kiri</label>
                        <input class="form-control" type="file" id="kendaraan_tampak_samping_kiri" name="kendaraan_tampak_samping_kiri" accept="image/jpeg, image/png" capture="environment">
                    </div>
                    <div class="mb-3">
                        <label for="foto_peralatan" class="form-label">Foto Peralatan</label>
                        <input class="form-control" type="file" id="foto_peralatan" name="foto_peralatan" accept="image/jpeg, image/png" capture="environment">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload & Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    window.Laravel = {
        token: "{{ $bukuTamu->token }}",
        url: "{{ route('bukutamu.confirm', $bukuTamu->token) }}"
    }
</script>
<script src="{{ asset('custom/bukutamu/confirm.js') }}"></script>
@endsection
