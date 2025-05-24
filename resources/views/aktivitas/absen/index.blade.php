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
        <div class="page-header">
            <h4 class="page-title">Absensi</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Absensi</h4>
                            @can('add absen')
                            <a href="{{ route('absen.create') }}" class="btn btn-primary ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Absensi
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        @can('view other absen')
                        <div class="form-group">
                            <select class="form-select form-control" id="user">
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->username }}" @selected($user->username == auth()->user()->username)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endcan
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="startdate">Tanggal Awal</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="start-date"
                                        value="{{ $start }}"
                                        readonly
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="enddate">Tanggal Akhir</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="end-date"
                                        value="{{ $end }}"
                                        readonly
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="d-grid justify-content-center">
                            <button class="btn btn-primary" type="button" id="filter">Proses Filter</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table-datatable" class="display table table-striped table-hover" >
                                <thead>
                                    <tr>
                                        <th>created_at</th>
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Jenis</th>
                                        <th>Tgl Jam</th>
                                        <th>Lokasi</th>
                                        <th>Foto</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>created_at</th>
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Jenis</th>
                                        <th>Tgl Jam</th>
                                        <th>Lokasi</th>
                                        <th>Foto</th>
                                        <th>Action</th>
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

    let user = $('#user').val()??'';
    let start = $( "#start-date" ).val();
    let end = $( "#end-date" ).val();
    const initializeDataTable = (user, start, end) => {
        let url = '{{route("absen.data")}}'
        return $('#table-datatable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: `${url}?user=${user}&startdate=${start}&enddate=${end}`,
            drawCallback: function() {
                // Inisialisasi ulang tooltip setiap kali tabel digambar ulang
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            columns: [
                {data: "created_at", visible: false}, // Kolom ini tidak ditampilkan
                {data: "DT_RowIndex"},
                {data: "user.username"},
                {data: "jenis.name"},
                {data: "tanggal"},
                {
                    data: "short_lokasi",
                    render: function (data, type, row) {
                        // return `<span data-bs-toggle="tooltip" data-bs-placement="top" title="${row.location}">${data}</span>`;
                        return `<span class="desc-preview" data-full="${row.location}" data-short="${data}" data-now="short">${data}</span>`;
                    }
                },
                {data: "picture"},
                {data: "action"},
            ],
            order: [[0, "desc"]],
        });
    }

    let table = initializeDataTable(user, start, end);

    $('#table-datatable').on('click', '.desc-preview', function () {
        const $el = $(this);
        const fullText = $el.data('full') || '';
        const shortText = $el.data('short') || '';
        const nowText = ($el.data('now') || '').trim().toLowerCase();

        const isShort = nowText === 'short';
        const newText = isShort ? fullText : shortText;
        const newNowText = isShort ? 'full' : 'short';

        $el.data('now', newNowText); // pakai jQuery .data() bukan attr()
        $el.html(newText);
    });

    $('#filter').on('click', function () {
        user = $('#user').val()??'';
        start = encodeURIComponent($('#start-date').val() ?? '');
        end = encodeURIComponent($('#end-date').val() ?? '');
        table = initializeDataTable(user, start, end);
    });

    // $('#user').change(function(){
    //     table = initializeDataTable($(this).val());
    // });
</script>

@can('delete absen')
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
