<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSuratTugasKeluar extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the surat that owns the DetailSuratTugasKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function surat()
    {
        return $this->belongsTo(SuratTugasKeluar::class, 'surat_id', 'id');
    }
}
