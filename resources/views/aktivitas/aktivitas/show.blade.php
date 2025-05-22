@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Aktivitas</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">View Aktivitas</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped mt-3">
                            <tbody>
                                <tr>
                                    <td>User</td>
                                    <td>{{ $aktivitas->user->username }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td>{{ $aktivitas->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>{{ \Carbon\Carbon::parse($aktivitas->tanggal)->format('d/M/Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Awal</td>
                                    <td>{{ $aktivitas->jam_awal }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Akhir</td>
                                    <td>{{ $aktivitas->jam_akhir }}</td>
                                </tr>
                                <tr>
                                    <td>Rencana</td>
                                    <td>{{ $aktivitas->rencana }}</td>
                                </tr>
                                <tr>
                                    <td>Aktivitas</td>
                                    <td>{{ $aktivitas->aktivitas }}</td>
                                </tr>
                                <tr>
                                    <td>Hasil</td>
                                    <td>{{ $aktivitas->hasil }}</td>
                                </tr>
                                <tr>
                                    <td>Rekanan</td>
                                    <td>
                                        @if ($aktivitas->rekanan)
                                        {{ $aktivitas->rekanan->name }}--{{ $aktivitas->rekanan->code }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tipe</td>
                                    <td>
                                        @if ($aktivitas->tipeAktivitas)
                                        {{ $aktivitas->tipeAktivitas->name }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cara</td>
                                    <td>
                                        @if ($aktivitas->caraAktivitas)
                                        {{ $aktivitas->caraAktivitas->name }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>File</td>
                                    <td>
                                        <a href="{{ route('aktivitas.file', $aktivitas->id) }}" target="_blank">{{ $aktivitas->file }}</a>
                                    </td>
                                </tr>
                                @if ($aktivitas->user_id == Auth::user()->id)
                                <tr>
                                    <td colspan="2">
                                        <div class="card-action">
                                            @can('edit aktivitas')
                                            <a href="{{ route('aktivitas.edit', $aktivitas->id) }}" class="btn btn-warning"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                            @endcan
                                            @can('delete aktivitas')
                                            <button data-id="{{$aktivitas->id}}" class="btn btn-danger btn-delete"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>

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
                let url = '{{ route("aktivitas.destroy", ["aktivita" => "__ID__"]) }}';
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
