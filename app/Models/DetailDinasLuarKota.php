<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDinasLuarKota extends Model
{
    protected $guarded = ['id'];

    public function surat()
    {
        return $this->belongsTo(DinasLuarKota::class, 'surat_id', 'id');
    }
}
