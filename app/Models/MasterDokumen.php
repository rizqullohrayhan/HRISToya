<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDokumen extends Model
{
    protected $table = 'master_dokumens';
    protected $guarded = ['id'];

    /**
     * Get the user that owns the MasterDokumen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
