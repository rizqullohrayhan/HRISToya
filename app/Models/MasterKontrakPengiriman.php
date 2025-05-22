<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKontrakPengiriman extends Model
{
    protected $table = 'master_kontrak_pengiriman';
    protected $guarded = ['id'];

    public function rekapKebunPengiriman()
    {
        return $this->hasMany(RekapKebunPengiriman::class, 'master_kontrak_pengiriman_id');
    }

    public function mengetahui()
    {
        return $this->hasMany(MengetahuiKontrakPengiriman::class, 'master_kontrak_pengiriman_id');
    }
}
