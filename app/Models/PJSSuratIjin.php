<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PJSSuratIjin extends Model
{
    protected $table = "pjs_surat_ijins";
    protected $guarded = ['id'];

    /**
     * Get the suratIjin that owns the PJSSuratIjin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function suratIjin()
    {
        return $this->belongsTo(SuratIjin::class, 'surat_id', 'id');
    }

    /**
     * Get the penganti that owns the SuratIjin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penganti()
    {
        return $this->belongsTo(User::class, 'penganti_id', 'id');
    }
}
