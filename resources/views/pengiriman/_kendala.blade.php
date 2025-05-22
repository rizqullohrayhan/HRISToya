<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Laporan Kendala Pengiriman</h4>
                @if ($ruleUser && ($kontrak->diperiksa == null || auth()->user()->hasRole('ADM')))
                <button id="btn-add-kendala" data-mode="add" class="btn btn-primary ms-auto">
                    <i class="fa fa-plus"></i>
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form id="add-kendala-form" action="{{ route('kendala.store') }}" method="post" style="display: none;">
                @csrf
                <input type="hidden" name="kontrakId" value="{{ $kontrak->id }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="add-tgl-kendala">Tanggal<span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpicker" id="add-tgl-kendala" name="tgl" readonly>
                        </div>
                    </div>
                    <div class="col-md-8 mb-3">
                        <div class="form-group">
                            <label for="add-nopol-kendala">Uraian<span class="text-danger">*</span></label>
                            <textarea name="uraian" id="add-nopol-kendala" class="form-control"></textarea>
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
                <table id="table-kendala" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Tanggal</th>
                            <th>Uraian</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Uraian</th>
                            <th>#</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
