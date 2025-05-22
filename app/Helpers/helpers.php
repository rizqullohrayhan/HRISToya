<?php

use App\Models\CutiBersama;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

/*
*   Fungsi untuk mendapatkan cuti yang telah diambil oleh User
*   dalam satu tahun
*/
if (!function_exists('getCutiDiambil')) {
    function getCutiDiambil($user_id, $tahun)
    {
        return DB::table('cutis')
        ->where('user_id', $user_id)
        ->where('periode', $tahun) // misalnya '2025'
        ->whereNotNull('disetujui_at') // asumsi disetujui jika kolom ini terisi
        ->selectRaw('SUM(DATEDIFF(tgl_akhir, tgl_awal) + 1) as total')
        ->value('total');
    }
}

if (!function_exists('hitungHariKerjaCuti')) {
    function hitungHariKerjaCuti($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Ambil semua cuti bersama dari DB
        // $tanggalMerah = CutiBersama::whereBetween('tanggal', [$start, $end])
        //     ->pluck('tanggal')
        //     ->map(fn($date) => Carbon::parse($date)->toDateString())
        //     ->toArray();

        $periode = CarbonPeriod::create($start, $end);
        $jumlahHari = 0;

        foreach ($periode as $tanggal) {
            $isMinggu = $tanggal->isSunday();
            // $isTanggalMerah = in_array($tanggal->toDateString(), $tanggalMerah);

            if (!$isMinggu) {
                $jumlahHari++;
            }
        }

        return $jumlahHari;
    }
}

/**
 * Fungsi untuk menampilkan Format File dalam B, KB, MB, GB
 */
if (!function_exists('formatFileSize')) {
    function formatFileSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

function encodeId($id)
{
    return Crypt::encryptString($id);
}

function decodeId($id)
{
    return Crypt::decryptString($id);
}
