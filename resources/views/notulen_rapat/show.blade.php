@extends('template.main')

@section('css')
    <style>
        .table > thead > tr > th,
        .table > tbody > tr > td {
            padding: 2px 10px !important;
        }
        .detail-voucher {
            max-height: 200px;
            overflow-y: auto;
        }
        #uraian-notulen > thead {
            position: sticky;
            top: 0;
            border-color: gray
        }
        .f-12 {
            font-size: 12px !important;
        }
        td.f-12, td.f-12 * {
            font-size: 12px !important;
        }
        table#uraian-notulen > tbody > tr > td {
            vertical-align: top !important;
        }


        /* test */
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .detail-row span {
            flex: 1;
        }

        .detail-row span.label {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Detail Notulen Rapat</h4>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    {{-- Detail Dinas Luar --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    Notulen Rapat {{ $notulenRapat->no_surat }}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-12">
                                                <span class="fw-bold">Agenda :</span>
                                            </div>
                                            <div class="col-12">
                                                <span>{!! $notulenRapat->agenda !!}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Tanggal</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $notulenRapat->tanggal ? \Carbon\Carbon::parse($notulenRapat->tanggal)->format('d/m/Y') : '' }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Jam</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $notulenRapat->tanggal ? \Carbon\Carbon::parse($notulenRapat->tanggal)->format('H:i') : '' }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Unit Kerja</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $notulenRapat->unit_kerja }}</span>
                                            </div>
                                            <div class="col-md-4 col-5">
                                                <span class="fw-bold">Pimpinan</span>
                                            </div>
                                            <div class="col-md-8 col-7">
                                                <span>: {{ $notulenRapat->pimpinan }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Uraian Notulen --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Uraian Notulen Rapat</div>
                                <div class="card-body">
                                    <div class="table-responsive detail-voucher">
                                        <table id="uraian-notulen" class="table table-bordered table-striped">
                                            <thead style="text-align:center">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Uraian</th>
                                                    <th>Action</th>
                                                    <th>Due Date</th>
                                                    <th>PIC</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($notulenRapat->uraian as $index => $detail)
                                                <tr>
                                                    <td class="f-12">{{ $loop->iteration }}</td>
                                                    <td class="f-12">{!! $detail->uraian !!}</td>
                                                    <td class="f-12">{!! $detail->action !!}</td>
                                                    <td class="f-12">{{ $detail->due_date ? \Carbon\Carbon::parse($notulenRapat->tanggal)->format('d/m/Y') : '' }}</td>
                                                    <td class="f-12">{{ $detail->pic }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Daftar Hadir Notulen --}}
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Daftar Hadir</div>
                                <div class="card-body">
                                    <div class="row">
                                        @if ($notulenRapat->picture)
                                        <div class="col-md-2 col-12 mb-3">
                                            <span class="fw-bold">Foto Daftar Hadir</span>
                                        </div>
                                        <div class="col-md-10 col-12">
                                            <span>:
                                                    <a href="{{ route('ijinpabrik.kendaraan', $surat->id) }}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">Lihat Kendaraan</a>
                                            </span>
                                        </div>
                                        @endif
                                        <div class="col-md-12">
                                            <div class="table-responsive detail-voucher">
                                                <table id="detailVoucher" class="table table-bordered table-striped">
                                                    <thead style="text-align:center">
                                                        <tr>
                                                            <th style="width: 5%">No</th>
                                                            <th>Nama</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($notulenRapat->daftarHadir as $index => $hadir)
                                                        <tr>
                                                            <td class="f-12">{{ $loop->iteration }}</td>
                                                            <td class="f-12">
                                                                @if ($hadir->user)
                                                                    {{ $hadir->user->name }}
                                                                @else
                                                                    {{ $hadir->nama }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Otorisasi --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Otorisasi
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="dibuat" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Dibuat Oleh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="dibuat">
                                                @if ($notulenRapat->dibuat_at)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($notulenRapat->dibuat_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $notulenRapat->dibuat->name }}</strong></span>
                                                    <span>{{ $notulenRapat->dibuat->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($notulenRapat->dibuat_at && ($notulenRapat->dibuat_id == Auth::user()->id) && ($notulenRapat->diperiksa == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="dibuat">
                                                    Hapus
                                                </button>
                                                @elseif ($notulenRapat->dibuat_at == null && $notulenRapat->diperiksa == null)
                                                <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="dibuat">
                                                    Otorisasi
                                                </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="diperiksa" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Diperiksa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="diperiksa">
                                                @if ($notulenRapat->diperiksa)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($notulenRapat->diperiksa_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $notulenRapat->diperiksa->name }}</strong></span>
                                                    <span>{{ $notulenRapat->diperiksa->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($notulenRapat->diperiksa && ($notulenRapat->diperiksa_id == Auth::user()->id) && ($notulenRapat->disetujui == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="diperiksa">
                                                    Hapus
                                                </button>
                                                @elseif ($notulenRapat->diperiksa == null && $notulenRapat->dibuat != null)
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="diperiksa">
                                                        Otorisasi
                                                    </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="disetujui" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Menyetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="disetujui">
                                                @if ($notulenRapat->disetujui)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($notulenRapat->disetujui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $notulenRapat->disetujui->name }}</strong></span>
                                                    <span>{{ $notulenRapat->disetujui->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($notulenRapat->disetujui && ($notulenRapat->disetujui_id == Auth::user()->id) && ($notulenRapat->mengetahui == null))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="disetujui">
                                                    Hapus
                                                </button>
                                                @elseif ($notulenRapat->disetujui == null && $notulenRapat->diperiksa != null)
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="disetujui">
                                                        Otorisasi
                                                    </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <table id="mengetahui" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Mengetahui</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="mengetahui">
                                                @if ($notulenRapat->mengetahui)
                                                <div class="d-flex flex-column text-center">
                                                    <span>{{ \Carbon\Carbon::parse($notulenRapat->mengetahui_at)->translatedFormat('d F Y, H.i.s'); }}</span>
                                                    <span><strong>{{ $notulenRapat->mengetahui->name }}</strong></span>
                                                    <span>{{ $notulenRapat->mengetahui->team->name }}</span>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if ($notulenRapat->mengetahui && ($notulenRapat->mengetahui_id == Auth::user()->id))
                                                <button type="button" class="btn btn-secondary otorisasi-btn hapus-mode" data-target="mengetahui">
                                                    Hapus
                                                </button>
                                                @elseif ($notulenRapat->mengetahui == null && $notulenRapat->disetujui != null)
                                                    <button type="button" class="btn btn-secondary otorisasi-btn otorisasi-mode" data-target="mengetahui">
                                                        Otorisasi
                                                    </button>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Button --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @php
                            $ruleUser = ( (auth()->user()->id == $notulenRapat->created_by) ||
                                            (auth()->user()->id == $notulenRapat->dibuat_id) ||
                                            (auth()->user()->hasRole('ADM'))
                                        );
                        @endphp

                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                            @if ($ruleUser && ($notulenRapat->diperiksa == null || auth()->user()->hasRole('ADM')))
                                <a href="{{ route('notulen_rapat.edit', $notulenRapat->id) }}" class="btn btn-warning">Edit</a>
                            @endif
                            <button class="btn btn-primary" onclick="copyLink()">Copy Link</button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#QrCodeModal">QR Code</button>

                            @if ($notulenRapat->disetujui && auth()->user()->id == $notulenRapat->dibuat_id)
                                {{-- <a href="{{ route('notulen_rapat.cetak', $notulenRapat->id) }}" target="_blank" class="btn btn-info">Cetak</a> --}}
                                <button onclick="print()" class="btn btn-info">Cetak</button>
                            @endif

                            <a href="{{ route('notulen_rapat.index') }}" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modal.qrcode')
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    window.Laravel = {
        otorisasiUrl: "{{ route('notulen_rapat.update.otoritas', $notulenRapat->id) }}",
        closeUrl: "{{ route('notulen_rapat.close', $notulenRapat->id) }}"
    };
    const url = "{{ route('notulen_rapat.show', $notulenRapat->id) }}";

    const qrcode = new QRCode("qrcode", {
        text: url,
        width: 300,
        height: 300,
    });

    function copyLink() {
        navigator.clipboard.writeText(url);
        $.notify({
        // options
            message: 'Link telah dicopy'
        },{
        // settings
            type: 'success'
        });
    }

    function downloadQR() {
        window.location.href = "{{ route('qrcode') }}?url="+url;
    }

    function print() {
        const printUrl = "{{ route('notulen_rapat.cetak', $notulenRapat->id) }}"
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = printUrl;
        iframe.onload = function () {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            setTimeout(() => iframe.remove(), 1000); // Hapus setelah 1 detik
        };
        document.body.appendChild(iframe);
    }
    </script>
<script src="{{ asset('custom/otorisasi.js') }}"></script>
@endsection
