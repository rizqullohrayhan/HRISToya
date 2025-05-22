<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CutiTahunan extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the user that owns the CutiTahunan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Hitung total jatah efektif tahun ini
    public function getJatahEfektifAttribute()
    {
        return ($this->total_jatah + $this->tambahan) - $this->sanksi;
    }

    // Hitung jumlah cuti yang sudah dipakai (hari kerja)
    public function getCutiTerpakaiAttribute()
    {
        $cutis = Cuti::where('user_id', $this->user_id)
            ->where('periode', $this->tahun)
            ->whereNotNull('disetujui_at')
            ->get();

        $total = 0;
        foreach ($cutis as $cuti) {
            $total += hitungHariKerjaCuti($cuti->tgl_awal, $cuti->tgl_akhir);
        }

        return $total;
    }

    // Sisa cuti otomatis
    public function getSisaCutiAttribute()
    {
        return $this->jatah_efektif - $this->cuti_terpakai;
    }
}
