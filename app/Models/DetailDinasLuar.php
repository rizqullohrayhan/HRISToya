<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDinasLuar extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the dinasLuar that owns the DetailDinasLuar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dinasLuar()
    {
        return $this->belongsTo(DinasLuar::class, 'surat_id', 'id');
    }
}
