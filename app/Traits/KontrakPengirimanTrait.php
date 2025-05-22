<?php

namespace App\Traits;

use App\Models\MasterKontrakPengiriman;
use App\Models\RekapKebunPengiriman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait KontrakPengirimanTrait
{
    protected function generateRekapData($kontrakId)
    {
        $kontrak = MasterKontrakPengiriman::findOrFail($kontrakId);

        $rekapKebun = RekapKebunPengiriman::where('master_kontrak_pengiriman_id', $kontrakId)->get();

        $idRekapKebun = $rekapKebun->pluck('id');

        $rencanaPerKebun = DB::table('rencana_pengirimen')
            ->select(DB::raw('rekap_kebun_pengiriman_id, MAX(tgl) as tgl_terbaru'))
            ->whereIn('rekap_kebun_pengiriman_id', $idRekapKebun)
            ->groupBy('rekap_kebun_pengiriman_id')
            ->pluck('tgl_terbaru', 'rekap_kebun_pengiriman_id');

        $SJPerKebun = DB::table('detail_realisasi_pengiriman')
            ->select(DB::raw('rekap_kebun_pengiriman_id, MAX(tgl) as tgl_terbaru'))
            ->whereIn('rekap_kebun_pengiriman_id', $idRekapKebun)
            ->groupBy('rekap_kebun_pengiriman_id')
            ->pluck('tgl_terbaru', 'rekap_kebun_pengiriman_id');

        $realisasiPerKebun = DB::table('detail_realisasi_pengiriman')
            ->select(DB::raw('SUM(kirim) AS realisasi, rekap_kebun_pengiriman_id'))
            ->whereIn('rekap_kebun_pengiriman_id', $idRekapKebun)
            ->groupBy('rekap_kebun_pengiriman_id')
            ->pluck('realisasi', 'rekap_kebun_pengiriman_id');

        $realisasi = DB::table('detail_realisasi_pengiriman')
            ->select(DB::raw('SUM(kirim) AS realisasi'))
            ->whereIn('rekap_kebun_pengiriman_id', $idRekapKebun)
            ->first();

        $maxTglRencana = collect($rencanaPerKebun)->max();

        $rencanaFormatted = [];
        $SJFormatted = [];

        foreach ($rekapKebun as $rekap) {
            $tglRencana = $rencanaPerKebun[$rekap->id] ?? null;
            $tglSJ = $SJPerKebun[$rekap->id] ?? null;

            $rencanaFormatted[$rekap->id] = $tglRencana ? Carbon::parse($tglRencana)->translatedFormat('d-M-Y') : '';
            $SJFormatted[$rekap->id] = $tglSJ ? Carbon::parse($tglSJ)->translatedFormat('d-M-Y') : '';
        }

        $batasKirim = Carbon::parse($kontrak->batas_kirim)->startOfDay();
        $now = Carbon::now()->startOfDay();
        $sisaHari = $now->diffInDays($batasKirim, false);
        $sisa = $kontrak->kuantitas - ($realisasi->realisasi ?? 0);
        $targetPerHari = $sisaHari <= 0 ? $sisa : $sisa / $sisaHari;

        return [
            'kontrak' => $kontrak,
            'rekapKebun' => $rekapKebun,
            'rencanaPerKebun' => $rencanaFormatted,
            'SJPerKebun' => $SJFormatted,
            'realisasiPerKebun' => $realisasiPerKebun,
            'realisasi' => $realisasi,
            'maxTglRencana' => $maxTglRencana,
            'sisaHari' => $sisaHari,
            'sisa' => $sisa,
            'targetPerHari' => $targetPerHari,
        ];
    }
}
