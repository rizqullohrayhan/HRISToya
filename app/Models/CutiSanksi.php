<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CutiSanksi extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the user that owns the CutiSanksi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
