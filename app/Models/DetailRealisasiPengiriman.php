<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRealisasiPengiriman extends Model
{
    protected $table = 'detail_realisasi_pengiriman';
    protected $guarded = ['id'];

    public function rekapKebunPengiriman()
    {
        return $this->belongsTo(RekapKebunPengiriman::class, 'rekap_kebun_pengiriman_id');
    }
}
