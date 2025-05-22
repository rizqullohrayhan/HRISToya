<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSO extends Model
{
    protected $table = 'data_so';
    protected $guarded = ['id'];

    public function rekapKebunPengiriman()
    {
        return $this->belongsTo(RekapKebunPengiriman::class, 'rekap_kebun_pengiriman_id');
    }
}
