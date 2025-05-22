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
        #my-textarea .trumbowyg-box, #my-textarea .trumbowyg-editor {
            min-height: 50px !important;
        }
        #my-textarea .trumbowyg-editor, #my-textarea .trumbowyg-textarea {
            min-heigh: 50px !important;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Notulen Rapat</h4>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Notulen Rapat</h4>
                            @can('add notulen rapat')
                            <a href="{{ route('notulen_rapat.create') }}" class="btn btn-primary ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Notulen
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="smallSelect">Pilih Tahun</label>
                                    <select class="form-select form-control-sm" id="date-dropdown">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table-datatable" class="display table table-striped table-hover" >
                                <thead>
                                    <tr>
                                        <th style="width: 0px !important;">Action</th>
                                        <th>No Surat</th>
                                        <th>Tgl</th>
                                        <th>Unit Kerja</th>
                                        <th>Pimpinan</th>
                                        <th>Agenda</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Action</th>
                                        <th>No Surat</th>
                                        <th>Tgl</th>
                                        <th>Unit Kerja</th>
                                        <th>Pimpinan</th>
                                        <th>Agenda</th>
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
        dataUrl: "{{ route('notulen_rapat.data') }}",
        deleteUrl: '{{ route("notulen_rapat.destroy", ["notulen_rapat" => "__ID__"]) }}'
    };
</script>
<script src="{{ asset('custom/notulenrapat/index.js') }}"></script>

@endsection
