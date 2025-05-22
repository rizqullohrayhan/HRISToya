@extends('template.main')

@section('css')
    <style>
        .td-wrap {
            white-space: normal !important;
            word-wrap: break-word;
        }
        .dataTable > thead > tr > th[class*="sort"]:before,
        .dataTable > thead > tr > th[class*="sort"]:after {
            content: "" !important;
        }
        table.table > thead > tr > th,
        table.table > tbody > tr > td {
            padding: 0px 10px !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List Kunjungan</h4>
                            @can('add buku tamu')
                            <a href="{{ route('bukutamu.create') }}" class="btn btn-primary ms-auto">
                                <i class="fa fa-plus"></i>
                                Tambah Kunjungan
                            </a>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start-date">Tanggal Awal</label>
                                    <input
                                        type="text"
                                        class="form-control flatpicker"
                                        id="start-date"
                                        placeholder="Pilih tanggal"
                                        value="{{ date('01/m/Y') }}"
                                        readonly
                                    />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end-date">Tanggal Akhir</label>
                                    <input
                                        type="text"
                                        class="form-control flatpicker"
                                        id="end-date"
                                        placeholder="Pilih tanggal"
                                        value="{{ date('t/m/Y') }}"
                                        readonly
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="cetak()"><i class="icon-printer"></i>&nbsp;Cetak</button>
                            <button type="button" id="filter" class="btn btn-primary">Proses Filter</button>
                            <button type="button" id="scanqr" class="btn btn-success"><i class="fas fa-qrcode"></i>&nbsp;Scan QR</button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                            </div>
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
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Tgl</th>
                                        <th>Jam</th>
                                        <th>Menemui</th>
                                        <th>Keperluan</th>
                                        <th>Telp</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Tgl</th>
                                        <th>Jam</th>
                                        <th>Menemui</th>
                                        <th>Keperluan</th>
                                        <th>Telp</th>
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
<!-- Modal Komponen -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qr-reader" style="width: 100%; max-width: 400px; margin: auto;"></div>
                <div id="qr-reader-results" class="mt-2 text-success fw-bold"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    window.Laravel = {
        dataUrl: "{{ route('bukutamu.data') }}",
        deleteUrl: "{{ route('bukutamu.destroy', '__ID__') }}",
    }
</script>
<script src="{{ asset('custom/bukutamu/index.js') }}"></script>
@endsection
