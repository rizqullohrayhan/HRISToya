<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the user that owns the Aktivitas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the rekanan that owns the Aktivitas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rekanan()
    {
        return $this->belongsTo(Rekanan::class, 'rekan_id', 'id');
    }

    /**
     * Get the tipeAktivitas that owns the Aktivitas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipeAktivitas()
    {
        return $this->belongsTo(TipeAktivitas::class, 'tipe_id', 'id');
    }

    /**
     * Get the caraAktivitas that owns the Aktivitas
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function caraAktivitas()
    {
        return $this->belongsTo(CaraAktivitas::class, 'cara_id', 'id');
    }
}
