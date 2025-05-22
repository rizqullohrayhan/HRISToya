<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapKebunPengiriman extends Model
{
    protected $table = 'rekap_kebun_pengiriman';
    protected $guarded = ['id'];

    public function masterKontrakPengiriman()
    {
        return $this->belongsTo(MasterKontrakPengiriman::class, 'master_kontrak_pengiriman_id');
    }

    public function dataSo()
    {
        return $this->hasMany(DataSo::class, 'rekap_kebun_pengiriman_id', 'id');
    }

    /**
     * Get all of the detailRealisasiPengiriman for the RekapKebunPengiriman
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailRealisasiPengiriman()
    {
        return $this->hasMany(DetailRealisasiPengiriman::class, 'rekap_kebun_pengiriman_id', 'id');
    }

    /**
     * Get all of the rencanaPengiriman for the RekapKebunPengiriman
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rencanaPengiriman()
    {
        return $this->hasMany(RencanaPengiriman::class, 'rekap_kebun_pengiriman_id', 'id');
    }
}
