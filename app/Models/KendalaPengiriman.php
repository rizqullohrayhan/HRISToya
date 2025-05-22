<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendalaPengiriman extends Model
{
    protected $table = 'kendala_pengirimen';
    protected $guarded = ['id'];

    public function masterKontrakPengiriman()
    {
        return $this->belongsTo(MasterKontrakPengiriman::class, 'master_kontrak_pengiriman_id');
    }
}
