<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Detail Pengiriman</h4>
                @if ($ruleUser && ($kontrak->diperiksa == null || auth()->user()->hasRole('ADM')))
                <button id="btn-add-detail" data-mode="add" class="btn btn-primary ms-auto">
                    <i class="fa fa-plus"></i>
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <form id="add-detail-form" action="{{ route('detail_realisasi.store') }}" method="post" style="display: none;">
                @csrf
                <input type="hidden" name="kontrakId" value="{{ $kontrak->id }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-kebun-detail">Kebun<span class="text-danger">*</span></label>
                            <select name="rekap_kebun_pengiriman_id" id="add-kebun-detail" class="form-select select2">
                                <option value="">Pilih Kebun</option>
                                @foreach ($rekapKebun as $kebun)
                                    <option value="{{$kebun->id}}">{{ $kebun->kebun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-tgl-detail">Tanggal<span class="text-danger">*</span></label>
                            <input type="text" class="form-control flatpicker" id="add-tgl-detail" name="tgl" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-nopol-detail">NOPOL<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nopol" id="add-nopol-detail">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-no-sj-detail">No SJ<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_sj" id="add-no-sj-detail">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-no-so-pkt-detail">No SO PKT<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_so_pkt" id="add-no-so-pkt-detail">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-vendor-detail">Vendor<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="vendor" id="add-vendor-detail">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-kirim-detail">Kirim</label>
                            <div class="input-group">
                                <input type="text" class="form-control format-number" name="kirim" id="add-kirim-detail">
                                <span class="input-group-text">Kg</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label for="add-terima-detail">Terima</label>
                            <div class="input-group">
                                <input type="text" class="form-control format-number" name="terima" id="add-terima-detail">
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
            <table id="table-detail" class="table table-bordered table-striped w-100">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Tanggal</th>
                        <th>Nopol</th>
                        <th>No. SJ</th>
                        <th>No SO PKT</th>
                        <th>Vendor</th>
                        <th>Kirim</th>
                        <th>Terima</th>
                        <th>Kebun</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>#</th>
                        <th>#</th>
                        <th>#</th>
                        <th>#</th>
                        <th>#</th>
                        <th>#</th>
                        <th>#</th>
                        <th>#</th>
                        <th>#</th>
                    </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
            {{-- <div class="table-responsive">
            </div> --}}
        </div>
    </div>
</div>
