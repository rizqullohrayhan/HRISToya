<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusSuratIjin extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the suratIjin for the StatusSuratIjin
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function suratIjin()
    {
        return $this->hasMany(SuratIjin::class, 'status_id', 'id');
    }
}
