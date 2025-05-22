@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        {{-- <div class="page-header">
            <h4 class="page-title">Tamu</h4>
        </div> --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Detail Kunjungan</h4>
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
                                    <td>Jam Pulang</td>
                                    <td>{{ $bukuTamu->jam_pulang }}</td>
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
                                        {{-- <div class="card-action">
                                            @can('edit buku tamu')
                                            <a href="{{ route('bukutamu.edit', $bukuTamu) }}" class="btn btn-warning"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                            @endcan
                                            @can('delete buku tamu')
                                            <button data-id="{{$bukuTamu->id}}" class="btn btn-danger btn-delete"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
                                            @endcan
                                        </div> --}}
                                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#QrCodeModal">QR Code</button>
                                            @can('edit buku tamu')
                                            <a href="{{ route('bukutamu.edit', $bukuTamu) }}" class="btn btn-warning"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                            @endcan
                                            @can('delete buku tamu')
                                            <button data-id="{{$bukuTamu->id}}" class="btn btn-danger btn-delete"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
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
@include('modal.qrcode')
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const url = "{{ $url }}";

    const qrcode = new QRCode("qrcode", {
        text: url,
        width: 300,
        height: 300,
    });

    $('.btn-delete').on('click', function(){
        swal({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak bisa mengembalikan data ini!",
            type: 'warning',
            buttons:{
                confirm: {
                    text : 'Ya, Hapus!',
                    className : 'btn btn-success'
                },
                cancel: {
                    visible: true,
                    text : 'Tidak, batal!',
                    className: 'btn btn-danger'
                },
            },
            dangerMode: true
        }).then((willDelete) => {
            if (willDelete) {
                let id = $(this).data("id");
                let url = '{{ route("bukutamu.destroy", ["bukutamu" => "__ID__"]) }}';
                url = url.replace("__ID__", id);

                $.ajax({
                    url: url,
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    beforeSend: function () {
                        swal({
                            title: "Mohon Tunggu",
                            text: "Sedang memproses...",
                            buttons: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                            icon: "info"
                        });
                    },
                    success: function (res) {
                        swal({
                            title: "Berhasil!",
                            text: res.message,
                            icon: "success",
                            button: {
                                text: "OK",
                                className: "btn btn-success"
                            }
                        }).then(() => {
                            window.location.href = '{{route("aktivitas.index")}}'
                        });
                    },
                    error: function (xhr) {
                        let err = JSON.parse(xhr.responseText);
                        swal({
                            title: "Gagal!",
                            text: err.message,
                            icon: "error",
                            button: {
                                text: "OK",
                                className: "btn btn-danger"
                            }
                        });
                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                });
            } else {
                swal("Data batal dihapus!", {
                    buttons : {
                        confirm : {
                            className: 'btn btn-success'
                        }
                    }
                });
            }
        });
    })
</script>

@endsection
