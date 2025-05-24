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
            <h4 class="page-title">Pengajuan Voucher</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Pengajuan Voucher</h4>
                            @can('add voucher')
                            <a href="{{ route('voucher.create') }}" class="btn btn-primary ms-auto">
                                <i class="fa fa-plus"></i>
                                Ajukan Voucher
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="smallSelect">Pilih Tahun</label>
                                    <select class="form-select form-control-sm" id="date-dropdown">
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
                                        <th>No Voucher</th>
                                        <th>Tgl</th>
                                        <th>Status</th>
                                        <th>Kode Kas/Bank</th>
                                        <th>Rekanan</th>
                                        <th>Uraian</th>
                                        <th>Tipe</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Action</th>
                                        <th>No Voucher</th>
                                        <th>Tgl</th>
                                        <th>Status</th>
                                        <th>Kode Kas/Bank</th>
                                        <th>Rekanan</th>
                                        <th>Dibayar Untuk</th>
                                        <th>Tipe</th>
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
        dataUrl: "{{ route('voucher.data') }}",
        deleteUrl: '{{ route("voucher.destroy", ["voucher" => "__ID__"]) }}'
    };
</script>
<script src="{{ asset('custom/voucher/index.js') }}"></script>

@endsection
