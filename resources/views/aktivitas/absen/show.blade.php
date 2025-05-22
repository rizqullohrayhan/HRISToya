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
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">View Absensi</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped mt-3">
                            <tbody>
                                <tr>
                                    <td>User</td>
                                    <td>{{ $absen->user->username }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td>{{ $absen->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis</td>
                                    <td>{{ $absen->jenis->name }}</td>
                                </tr>
                                <tr>
                                    <td>Tgl Jam</td>
                                    <td>{{ \Carbon\Carbon::parse($absen->created_at)->format('d-M-Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td>Lokasi</td>
                                    <td>{{ $absen->location }}</td>
                                </tr>
                                <tr>
                                    <td>Foto</td>
                                    <td>
                                        <img src="{{ asset('upload/absen/'.$absen->picture) }}" class="img-preview" width="30%">
                                    </td>
                                </tr>
                                @if ($absen->user_id == Auth::user()->id)
                                <tr>
                                    <td colspan="2">
                                        <div class="card-action">
                                            @can('edit absen')
                                            <a href="{{ route('absen.edit', $absen->id) }}" class="btn btn-warning"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                            @endcan
                                            @can('delete absen')
                                            <button data-id="{{$absen->id}}" class="btn btn-danger btn-delete"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
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
                let url = '{{ route("absen.destroy", ["absen" => "__ID__"]) }}';
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
                            window.location.href = '{{route("absen.index")}}'
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
