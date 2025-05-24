@extends('template.main')

@section('css')
    <style>
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
            <h4 class="page-title">Permohonan Cuti</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Permohonan Cuti</h4>
                            @can('add cuti')
                            <a href="{{ route('cuti.create') }}" class="btn btn-primary ms-auto">
                                <i class="fa fa-plus"></i>
                                Ajukan Cuti
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date-dropdown">Pilih Tahun</label>
                                    <select class="form-select form-control-sm select2" id="date-dropdown">
                                    </select>
                                </div>
                            </div>
                        </div> --}}
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
                                        <th style="width: 0px !important;">Action</th>
                                        <th>No Surat</th>
                                        <th>Macam Cuti</th>
                                        <th>Tanggal</th>
                                        <th>Pemohon</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Action</th>
                                        <th>No Surat</th>
                                        <th>Macam Cuti</th>
                                        <th>Tanggal</th>
                                        <th>Pemohon</th>
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
    window.Laravel = {
        dataUrl: "{{ route('cuti.data') }}",
        deleteUrl: '{{ route("cuti.destroy", ["cuti" => "__ID__"]) }}'
    };
</script>
<script src="{{ asset('custom/cuti/index.js') }}"></script>

@endsection
