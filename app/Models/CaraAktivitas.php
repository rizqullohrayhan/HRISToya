<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaraAktivitas extends Model
{
    protected $fillable = ['name'];

    /**
     * Get all of the aktivitas for the CaraAktivitas
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'cara_id', 'id');
    }
}
