<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PJSCuti extends Model
{
    protected $table = "pjs_cutis";
    protected $guarded = ['id'];

    /**
     * Get the surat that owns the PJSCuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function surat()
    {
        return $this->belongsTo(Cuti::class, 'surat_id', 'id');
    }

    /**
     * Get the penganti that owns the PJSCuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penganti()
    {
        return $this->belongsTo(User::class, 'penganti_id', 'id');
    }
}
