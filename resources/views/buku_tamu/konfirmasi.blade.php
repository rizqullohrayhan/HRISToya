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
                    <div class="card-body">
                        <table class="table table-striped mt-3">
                            <tbody>
                                <tr>
                                    <td>Nama Tamu</td>
                                    <td>{{ $bukuTamu->name }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>{{ $bukuTamu->alamat }}</td>
                                </tr>
                                <tr>
                                    <td>Asal Instansi</td>
                                    <td>{{ $bukuTamu->instansi }}</td>
                                </tr>
                                <tr>
                                    <td>No Telp</td>
                                    <td>{{ $bukuTamu->telp }}</td>
                                </tr>
                                <tr>
                                    <td>Menemui</td>
                                    <td>{{ $bukuTamu->menemui }}</td>
                                </tr>
                                <tr>
                                    <td>Keperluan</td>
                                    <td>{{ $bukuTamu->keperluan }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Kunjungan</td>
                                    <td>{{ \Carbon\Carbon::parse($bukuTamu->tgl)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Kunjungan</td>
                                    <td>{{ $bukuTamu->jam_awal }} - {{ $bukuTamu->jam_akhir }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Datang</td>
                                    <td>{{ $bukuTamu->jam_datang }}</td>
                                </tr>
                                <tr>
                                    <td>Konfirmasi Datang</td>
                                    <td>{{ $bukuTamu->confirmDatang->name }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Pulang</td>
                                    <td>{{ $bukuTamu->jam_pulang }}</td>
                                </tr>
                                <tr>
                                    <td>Konfirmasi Pulang</td>
                                    <td>{{ $bukuTamu->confirmPulang->name }}</td>
                                </tr>
                                <tr>
                                    <td>Foto</td>
                                    <td>
                                        @php
                                            $listFoto = [
                                                'id_card' => 'ID Card',
                                                'surat_pengantar' => 'Surat Pengantar',
                                                'foto_diri' => 'Foto Diri',
                                                'kendaraan_tampak_depan' => 'Kendaraan Depan',
                                                'kendaraan_tampak_belakang' => 'Kendaraan Belakang',
                                                'kendaraan_tampak_samping_kanan' => 'Kendaraan Samping Kanan',
                                                'kendaraan_tampak_samping_kiri' => 'Kendaraan Samping Kiri',
                                                'foto_peralatan' => 'Peralatan'
                                            ];
                                        @endphp
                                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                                            @foreach ($listFoto as $foto => $name)
                                                @if ($bukuTamu->$foto)
                                                    <a href="{{ route('bukutamu.foto', $bukuTamu->id) }}?foto={{$foto}}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">{{ $name }}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                                            @can('confirm kedatangan tamu')
                                                @if (is_null($bukuTamu->jam_datang))
                                                    <button type="button" data-id="{{$bukuTamu->id}}" id="btn-datang" class="btn btn-success">
                                                        <i class="fa fa-check"></i>&nbsp;Kedatangan
                                                    </button>
                                                @elseif (is_null($bukuTamu->jam_pulang) && ($bukuTamu->datang_by == auth()->user()->id || auth()->user()->hasRole('ADM')))
                                                    <button type="button" data-id="{{$bukuTamu->id}}" id="btn-batal-datang" class="btn btn-success">
                                                        <i class="fa fa-check"></i>&nbsp;Batalkan Kedatangan
                                                    </button>
                                                @endif
                                            @endcan
                                            @can('confirm pulang tamu')
                                                @if ($bukuTamu->jam_datang)
                                                    <button type="button" href="{{ route('bukutamu.edit', $bukuTamu) }}" id="btn-pulang" class="btn btn-warning">
                                                        <i class="fa fa-pen"></i>&nbsp;Pulang
                                                    </button>
                                                @elseif ($bukuTamu->pulang_by == auth()->user()->id || auth()->user()->hasRole('ADM'))
                                                    <button type="button" data-id="{{$bukuTamu->id}}" class="btn btn-success">
                                                        <i class="fa fa-check"></i>&nbsp;Batalkan Pulang
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
