<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Mengetahui</h4>
                @if ($ruleUser && ($kontrak->diperiksa == null || auth()->user()->hasRole('ADM')))
                <button id="btn-add-mengetahui" data-mode="add" class="btn btn-primary ms-auto">
                    <i class="fa fa-plus"></i>
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form id="add-mengetahui-form" action="{{ route('mengetahui.store') }}" method="post" style="display: none;">
                @csrf
                <input type="hidden" name="kontrakId" value="{{ $kontrak->id }}">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="add-name-mengetahui">Nama<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add-name-mengetahui" name="name">
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
                <table id="table-mengetahui" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>#</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
