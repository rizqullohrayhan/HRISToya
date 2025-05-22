<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RencanaPengiriman extends Model
{
    // protected $table = 'rencana_pengiriman';
    protected $guarded = ['id'];

    public function rekapKebunPengiriman()
    {
        return $this->belongsTo(RekapKebunPengiriman::class, 'rekap_kebun_pengiriman_id');
    }
}
