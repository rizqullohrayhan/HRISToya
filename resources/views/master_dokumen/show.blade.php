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
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">View Dokumen</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped mt-3">
                            <tbody>
                                <tr>
                                    <td>Pemilik</td>
                                    <td>{{ $dokumen->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>Nama File</td>
                                    <td>{{ $dokumen->nama }}</td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>{{ $dokumen->tipe }}</td>
                                </tr>
                                <tr>
                                    <td>Tipe File</td>
                                    <td>{{ $dokumen->tipe }}</td>
                                </tr>
                                <tr>
                                    <td>Ukuran File</td>
                                    <td>{{ formatFileSize($dokumen->ukuran) }}</td>
                                </tr>
                                <tr>
                                    <td>File</td>
                                    <td>
                                        <a href="{{ route('master_dokumen.download', $dokumen->id) }}" target="_blank" class="btn btn-info btn-sm">Download</a>
                                    </td>
                                </tr>
                                @if ($dokumen->user_id == Auth::user()->id)
                                <tr>
                                    <td colspan="2">
                                        <div class="card-action">
                                            @can('edit master dokumen')
                                            <a href="{{ route('masterdokumen.edit', $dokumen->id) }}" class="btn btn-warning"><i class="fa fa-pen"></i>&nbsp;Edit</a>
                                            @endcan
                                            @can('delete master dokumen')
                                            <button data-id="{{$dokumen->id}}" class="btn btn-danger btn-delete"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
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
                let url = '{{ route("masterdokumen.destroy", ["masterdokumen" => "__ID__"]) }}';
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
                            window.location.href = '{{route("masterdokumen.index")}}'
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
