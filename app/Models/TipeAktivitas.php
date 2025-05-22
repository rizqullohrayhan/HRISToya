<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeAktivitas extends Model
{
    protected $fillable = ['name'];

    /**
     * Get all of the aktivitas for the TipeAktivitas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'tipe_id', 'id');
    }
}
