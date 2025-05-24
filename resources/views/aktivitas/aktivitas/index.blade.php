@extends('template.main')

@section('css')
    <style>
        .td-wrap {
            white-space: normal !important;
            word-wrap: break-word;
        }
        /* table {
            table-layout: fixed;
        } */
        .dataTable > thead > tr > th[class*="sort"]:before,
        .dataTable > thead > tr > th[class*="sort"]:after {
            content: "" !important;
        }
        table.table > thead > tr > th,
        table.table > tbody > tr > td {
            padding: 0px 10px !important;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        {{-- <div class="page-header">
            <h4 class="page-title">Aktivitas</h4>
        </div> --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Aktivitas</h4>
                            @can('add aktivitas')
                            <a href="{{ route('aktivitas.create') }}" class="btn btn-primary ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Aktivitas
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        @can('view other aktivitas')
                        <div class="form-group">
                            <select class="form-select form-control" id="user">
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->username }}" @selected($user->username == auth()->user()->username)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endcan
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start-date">Tanggal Awal Aktivitas</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="start-date"
                                        value="{{ $start->format('d/m/Y') }}"
                                        readonly
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end-date">Tanggal Akhir Aktivitas</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="end-date"
                                        value="{{ $end->format('d/m/Y') }}"
                                        readonly
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="d-grid justify-content-center">
                            <button class="btn btn-primary" type="button" id="filter">Proses Filter</button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-secondary my-2" onclick="cetak()"><i class="icon-printer"></i>&nbsp;Cetak</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Aktivitas</h4>
                            @can('add aktivitas')
                            <a href="{{ route('aktivitas.create') }}" class="btn btn-primary ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Aktivitas
                            </a>
                            @endcan
                        </div>
                    </div> --}}
                    <div class="card-body">
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-secondary mb-4" onclick="cetak()"><i class="icon-printer"></i>&nbsp;Cetak</button>
                            </div>
                        </div> --}}
                        <div class="table-responsive">
                            <table id="table-datatable" class="display table table-striped table-hover" >
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl</th>
                                        <th>Jam Awal</th>
                                        <th>Jam Akhir</th>
                                        <th>Rencana</th>
                                        <th>Aktivitas</th>
                                        <th>Hasil</th>
                                        <th>File</th>
                                        {{-- <th style="width: 10%">Action</th> --}}
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl</th>
                                        <th>Jam Awal</th>
                                        <th>Jam Akhir</th>
                                        <th>Rencana</th>
                                        <th>Aktivitas</th>
                                        <th>Hasil</th>
                                        <th>File</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </tfoot>
                                <tbody>
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
    $( "#start-date" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
    });
    $( "#end-date" ).datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
    });
    let user = '';
    let start = $( "#start-date" ).val();
    let end = $( "#end-date" ).val();
    const urlData = '{{route("aktivitas.data")}}';
    const initializeDataTable = (user, start, end) => {
        return $('#table-datatable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: `${urlData}?user=${user}&startdate=${start}&enddate=${end}`,
            columns: [
                {
                    data: "DT_RowIndex",
                    render: function (data, type, row) {
                        return data + '&nbsp;' + row.action; // Gabungkan nomor dengan tombol action
                    },
                    orderable: false, // Agar tidak bisa diurutkan
                    searchable: false, // Agar tidak bisa dicari
                },
                {
                    data: "tanggal",
                    render: function (data, type, row) {
                        return row.tanggal_show;
                    },
                },
                {data: "jam_awal"},
                {data: "jam_akhir"},
                {
                    data: "short_rencana",
                    render: function (data, type, row) {
                        return `<span class="desc-preview" data-full="${row.rencana}" data-short="${data}" data-now="short">${data}</span>`;
                    },
                },
                {
                    data: "short_aktivitas",
                    render: function (data, type, row) {
                        return `<span class="desc-preview" data-full="${row.aktivitas}" data-short="${data}" data-now="short">${data}</span>`;
                    },
                },
                {
                    data: "short_hasil",
                    render: function (data, type, row) {
                        return `<span class="desc-preview" data-full="${row.hasil}" data-short="${data}" data-now="short">${data}</span>`;
                    },
                },
                {data: "file"},
                // {data: "action", visible: false},
            ],
            order: [[1, "desc"], [2, "asc"]],
        });
    }

    let table = initializeDataTable(user, start, end);

    $('#table-datatable').on('click', '.desc-preview', function () {
        let full = $(this).data('full');
        let short = $(this).data('short');
        let now = $(this).data('now');

        if (now == 'short') {
            $(this).text(full);
            $(this).data('now', 'full');
        } else {
            $(this).text(short);
            $(this).data('now', 'short');
        }
    });

    $('#filter').on('click', function () {
        user = $('#user').val()??'';
        start = encodeURIComponent($('#start-date').val() ?? '');
        end = encodeURIComponent($('#end-date').val() ?? '');
        table = initializeDataTable(user, start, end);
    })

    function cetak() {
        let url = '{{ route("aktivitas.cetak") }}';
        user = $('#user').val()??'';
        start = encodeURIComponent($('#start-date').val() ?? '');
        end = encodeURIComponent($('#end-date').val() ?? '');
        window.open(`${url}?user=${user}&startdate=${start}&enddate=${end}`, '_blank');
    }
</script>

@can('delete aktivitas')
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
                            table.ajax.reload();
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
