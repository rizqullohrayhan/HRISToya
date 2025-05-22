<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Data SO</h4>
                @if ($ruleUser && ($kontrak->diperiksa == null || auth()->user()->hasRole('ADM')))
                <button id="btn-add-data-so" data-mode="add" class="btn btn-primary ms-auto">
                    <i class="fa fa-plus"></i>
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form id="add-data-so-form" action="{{ route('dataso.store') }}" method="post" style="display: none;">
                @csrf
                <input type="hidden" name="kontrakId" value="{{ $kontrak->id }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-tgl-so">Tanggal<span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpicker" id="add-tgl-so" name="tgl" value="{{ old('tgl') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-nomor-so">Nomer<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nomor" id="add-nomor-so" value="{{ old('nomor') }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-qty-so">Qty</label>
                            <div class="input-group">
                                <input type="text" class="form-control format-number" name="qty" id="add-qty-so" value="{{ old('qty') }}">
                                <span class="input-group-text">Kg</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-sisa-so">Sisa</label>
                            <div class="input-group">
                                <input type="text" class="form-control format-number" name="sisa" id="add-sisa-so" value="{{ old('sisa') }}">
                                <span class="input-group-text">Kg</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary">
                        Simpan&nbsp;
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
            <div class="table-responsive">
                <table id="table-so" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Tanggal</th>
                            <th>Nomer</th>
                            <th>Qty</th>
                            <th>Sisa</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nomer</th>
                            <th>Qty</th>
                            <th>Sisa</th>
                            <th>#</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
