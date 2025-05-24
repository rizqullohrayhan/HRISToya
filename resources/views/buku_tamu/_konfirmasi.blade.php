                        <table class="table table-striped mt-3">
                            <tbody>
                                <tr>
                                    <td>Nama Tamu</td>
                                    <td>{{ $bukuTamu->name }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>{{ $bukuTamu->alamat }}</td>
                                </tr>
                                <tr>
                                    <td>Asal Instansi</td>
                                    <td>{{ $bukuTamu->instansi }}</td>
                                </tr>
                                <tr>
                                    <td>No Telp</td>
                                    <td>{{ $bukuTamu->telp }}</td>
                                </tr>
                                <tr>
                                    <td>Menemui</td>
                                    <td>{{ $bukuTamu->menemui }}</td>
                                </tr>
                                <tr>
                                    <td>Keperluan</td>
                                    <td>{{ $bukuTamu->keperluan }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Kunjungan</td>
                                    <td>{{ \Carbon\Carbon::parse($bukuTamu->tgl)->translatedFormat('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Kunjungan</td>
                                    <td>{{ $bukuTamu->jam_awal }} - {{ $bukuTamu->jam_akhir }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Datang</td>
                                    <td>{{ $bukuTamu->datang ? \Carbon\Carbon::parse($bukuTamu->datang)->translatedFormat('d F Y, H:i:s') : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Konfirmasi Datang</td>
                                    <td>{{ $bukuTamu->confirmDatang ? $bukuTamu->confirmDatang->name : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Pulang</td>
                                    <td>{{ $bukuTamu->pulang ? \Carbon\Carbon::parse($bukuTamu->pulang)->translatedFormat('d F Y, H:i:s') : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Konfirmasi Pulang</td>
                                    <td>{{ $bukuTamu->confirmPulang ? $bukuTamu->confirmPulang->name : '' }}</td>
                                </tr>
                                <tr>
                                    <td>Foto</td>
                                    <td>
                                        @php
                                            $listFoto = [
                                                'id_card' => 'ID Card',
                                                'surat_pengantar' => 'Surat Pengantar',
                                                'foto_diri' => 'Foto Diri',
                                                'kendaraan_tampak_depan' => 'Kendaraan Depan',
                                                'kendaraan_tampak_belakang' => 'Kendaraan Belakang',
                                                'kendaraan_tampak_samping_kanan' => 'Kendaraan Samping Kanan',
                                                'kendaraan_tampak_samping_kiri' => 'Kendaraan Samping Kiri',
                                                'foto_peralatan' => 'Peralatan'
                                            ];
                                        @endphp
                                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                                            @foreach ($listFoto as $foto => $name)
                                                @if ($bukuTamu->$foto)
                                                    <a href="{{ route('bukutamu.foto', $bukuTamu->id) }}?foto={{$foto}}" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer">{{ $name }}</a>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="d-flex flex-wrap gap-2 justify-content-start">
                                            @can('confirm kedatangan tamu')
                                                @if (is_null($bukuTamu->datang))
                                                    <button type="button" data-id="{{$bukuTamu->id}}" id="btn-datang" class="btn btn-success">
                                                        <i class="fa fa-check"></i>&nbsp;Kedatangan
                                                    </button>
                                                @elseif (is_null($bukuTamu->pulang) && ($bukuTamu->datang_by == auth()->user()->id || auth()->user()->hasRole('ADM')))
                                                    <button type="button" data-id="{{$bukuTamu->id}}" id="btn-batal-datang" class="btn btn-danger">
                                                        <i class="fas fa-times"></i>&nbsp;Batalkan Kedatangan
                                                    </button>
                                                @endif
                                            @endcan
                                            @can('confirm pulang tamu')
                                                @if ($bukuTamu->datang && is_null($bukuTamu->pulang))
                                                    <button type="button" data-id="{{$bukuTamu->id}}" id="btn-pulang" class="btn btn-warning">
                                                        <i class="fas fa-sign-out-alt"></i>&nbsp;Pulang
                                                    </button>
                                                @elseif ($bukuTamu->pulang && ($bukuTamu->pulang_by == auth()->user()->id || auth()->user()->hasRole('ADM')))
                                                    <button type="button" data-id="{{$bukuTamu->id}}" id="btn-batal-pulang" class="btn btn-danger">
                                                        <i class="fas fa-times"></i>&nbsp;Batalkan Pulang
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
