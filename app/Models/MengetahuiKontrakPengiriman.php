<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MengetahuiKontrakPengiriman extends Model
{
    protected $table = "mengetahui_kontrak_pengiriman";
    protected $guarded = ['id'];

    /**
     * Get the kontrak that owns the MengetahuiKontrakPengiriman
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kontrak()
    {
        return $this->belongsTo(MasterKontrakPengiriman::class, 'master_kontrak_pengiriman_id', 'id');
    }

}
