<div class="row">
    <div class="col-md-2 col-4">
        <span class="fw-bold">Perusahaan</span>
    </div>
    <div class="col-md-10 col-8">
        :&nbsp;<span> {{ $kontrak->perusahaan }}</span>
    </div>
    <div class="col-md-2 col-4">
        <span class="fw-bold">Customer</span>
    </div>
    <div class="col-md-10 col-8">
        :&nbsp;<span> {{ $kontrak->customer }}</span>
    </div>
    <div class="col-md-2 col-4">
        <span class="fw-bold">No Kontrak</span>
    </div>
    <div class="col-md-10 col-8">
        :&nbsp;<span> {{ $kontrak->no_kontrak }}</span>
    </div>
    <div class="col-md-2 col-4">
        <span class="fw-bold">Barang</span>
    </div>
    <div class="col-md-10 col-8">
        :&nbsp;<span> {{ $kontrak->barang }}</span>
    </div>
    <div class="col-md-2 col-4">
        <span class="fw-bold">Kuantitas</span>
    </div>
    <div class="col-md-10 col-8">
        :&nbsp;<span> {{ number_format($kontrak->kuantitas) }} Kg</span>
    </div>
    <div class="col-md-2 col-4">
        <span class="fw-bold">Semester</span>
    </div>
    <div class="col-md-10 col-8">
        :&nbsp;<span> {{ $kontrak->semester }}</span>
    </div>
    <div class="col-md-2 col-4">
        <span class="fw-bold">Tgl Mulai Kirim</span>
    </div>
    <div class="col-md-10 col-8">
        :&nbsp;<span> {{ $kontrak->tgl_mulai_kirim ? \Carbon\Carbon::parse($kontrak->tgl_mulai_kirim)->locale('id')->isoFormat('dddd, D MMMM YYYY') : '' }}</span>
    </div>
    <div class="col-md-2 col-4">
        <span class="fw-bold">Jangka Waktu Kirim</span>
    </div>
    <div class="col-md-10 col-8">
        :&nbsp;<span> {{ $kontrak->jangka_waktu_kirim }}</span> Hari
    </div>
    <div class="col-12">
        <div class="table-responsive">
            <table id="RekapKebun" class="table table-bordered table-striped">
                <thead style="text-align:center">
                    <tr>
                        <th>Target</th>
                        <th>Batas Kirim</th>
                        <th>Sisa Hari</th>
                        <th>Target Perhari</th>
                        <th>Kontrak</th>
                        <th colspan="2">Realisasi</th>
                        <th colspan="2">Sisa</th>
                        <th>Rencana</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">
                            {{ $kontrak->target }}%
                        </td>
                        <td class="text-center">
                            {{ $kontrak->batas_kirim ? \Carbon\Carbon::parse($kontrak->batas_kirim)->translatedFormat('d-M-Y') : '' }}
                        </td>
                        <td class="text-center">
                            {{-- Hitung sisa hari --}}
                            {{ $sisaHari }}
                        </td>
                        <td class="text-end">
                            {{-- Target Perhari --}}
                            {{ number_format($targetPerHari) }} Kg
                        </td>
                        <td class="text-end">
                            {{-- Kontrak --}}
                            {{ number_format($kontrak->kuantitas) }} Kg
                        </td>
                        <td class="text-end">
                            {{-- Realisasi --}}
                            {{ number_format($realisasi->realisasi ?? 0) }} Kg
                        </td>
                        <td>
                            {{-- Persentase Realisasi --}}
                            {{ floor(100 * ($realisasi->realisasi ?? 0) / $kontrak->kuantitas) }}%
                        </td>
                        <td class="text-end">
                            {{-- Sisa --}}
                            {{ number_format($sisa) }} Kg
                        </td>
                        <td>
                            {{-- Persentase Sisa --}}
                            {{ floor(100 * $sisa / $kontrak->kuantitas) }}%
                        </td>
                        <td>
                            {{-- Rencana --}}
                            {{ $maxTglRencana ? \Carbon\Carbon::parse($maxTglRencana)->translatedFormat('d-M-Y') : '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12">
        <div class="table-responsive detail-kebun">
            <table id="detailKebun" class="table table-bordered table-striped">
                <thead style="text-align:center">
                    <tr>
                        <th>No</th>
                        <th>Vendor</th>
                        <th>Kebun</th>
                        <th>Kontrak</th>
                        <th>Realisasi</th>
                        <th>Sisa</th>
                        <th>Progress</th>
                        <th>Rencana</th>
                        <th>SJ Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekapKebun as $kebun)
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $kebun->vendor }}
                            </td>
                            <td>
                                {{ $kebun->kebun }}
                            </td>
                            <td class="text-end">
                                {{ number_format($kebun->kontrak) }}
                            </td>
                            <td class="text-end">{{ number_format($realisasiPerKebun[$kebun->id] ?? 0) }}</td>
                            <td class="text-end">{{ number_format($kebun->kontrak - ($realisasiPerKebun[$kebun->id] ?? 0)) }}</td>
                            <td class="text-center">
                                @php
                                    $persen = floor(100 * ($realisasiPerKebun[$kebun->id] ?? 0) / $kebun->kontrak);
                                    if ($persen <= 50) {
                                        $warna = 'bg-danger';
                                    } elseif ($persen <= 80) {
                                        $warna = 'bg-warning';
                                    } else {
                                        $warna = 'bg-success';
                                    }
                                @endphp
                                <div class="progress" title="{{ $persen }}%">
                                    <div class="progress-bar {{ $warna }}" role="progressbar" style="width: {{ $persen }}%;" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $persen }}%
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $rencanaPerKebun[$kebun->id] }}</td>
                            <td class="text-center">{{ $SJPerKebun[$kebun->id] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
