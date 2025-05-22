@extends('template.main')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Kantor</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Kantor</h4>
                            @can('add kantor')
                            <a href="{{ route('kantor.create') }}" class="btn btn-primary btn-round ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Kantor
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-datatable" class="display table table-striped table-hover" >
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Jarak Maks</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Jarak Maks</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    @foreach ($kantors as $kantor)
                                    <tr>
                                        <td>{{ $kantor->name }}</td>
                                        <td>{{ $kantor->latitude }}</td>
                                        <td>{{ $kantor->longitude }}</td>
                                        <td>{{ $kantor->jarak_maks }} KM</td>
                                        <td>
                                            <div class="form-button-action">
                                                <div class="btn-group dropdown">
                                                    <button class="btn btn-icon btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fa fa-align-left"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li>
                                                            @can('edit kantor')
                                                            <a href="{{ route('kantor.edit', $kantor->id) }}" title="Edit" class="btn btn-link btn-primary" data-original-title="Edit">
                                                                <i class="fa fa-edit"></i>&nbsp;Edit
                                                            </a>
                                                            @endcan
                                                            @can('delete kantor')
                                                            <button type="button" data-id="{{ $kantor->id }}" title="Hapus kantor" class="btn btn-link btn-danger btn-destroy" data-original-title="Remove">
                                                                <i class="fa fa-times"></i>&nbsp;Hapus
                                                            </button>
                                                            @endcan
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $('#table-datatable').DataTable({
        "pageLength": 5,
    });
</script>

@can('delete kantor')
<script>
    $('#table-datatable').on('click', '.btn-destroy', function(){
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
                let url = '{{ route("kantor.destroy", ["kantor" => "__ID__"]) }}';
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
                            location.reload();
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
@endcan
@endsection
