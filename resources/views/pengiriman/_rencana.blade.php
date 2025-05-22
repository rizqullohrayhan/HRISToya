<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Rencana Pengiriman</h4>
                @if ($ruleUser && ($kontrak->diperiksa == null || auth()->user()->hasRole('ADM')))
                <button id="btn-add-rencana" data-mode="add" class="btn btn-primary ms-auto">
                    <i class="fa fa-plus"></i>
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form id="add-rencana-form" action="{{ route('rencana.store') }}" method="post" style="display: none;">
                @csrf
                <input type="hidden" name="kontrakId" value="{{ $kontrak->id }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-kebun-rencana" class="form-label">Kebun</label>
                            <select name="rekap_kebun_pengiriman_id" class="form-control select2" id="add-kebun-rencana">
                                <option value="">Pilih Kebun</option>
                                @foreach ($rekapKebun as $kebun)
                                    <option value="{{ $kebun->id }}">{{ $kebun->kebun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-tgl-rencana" class="form-label">Tanggal</label>
                            <input type="text" class="form-control flatpicker" id="add-tgl-rencana" name="tgl" required>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-nopol-rencana" class="form-label">Nopol</label>
                            <input type="text" class="form-control" id="add-nopol-rencana" name="nopol">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-qty-rencana" class="form-label">Qty</label>
                            <div class="input-group">
                                <input type="text" class="form-control format-number" id="add-qty-rencana" name="qty">
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
            <table id="table-rencana" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Vendor</th>
                        <th>Kebun</th>
                        <th>Tanggal</th>
                        <th>Nopol</th>
                        <th>Qty</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Vendor</th>
                        <th>Kebun</th>
                        <th>Tanggal</th>
                        <th>Nopol</th>
                        <th>Qty</th>
                        <th>#</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
