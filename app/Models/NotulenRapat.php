<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotulenRapat extends Model
{
    protected $guarded = ['id'];

    public function uraian()
    {
        return $this->hasMany(UraianNotulenRapat::class);
    }

    public function daftarHadir()
    {
        return $this->hasMany(DaftarHadirNotulenRapat::class);
    }

    /**
     * Get the dibuat that owns the DinasLuar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dibuat()
    {
        return $this->belongsTo(User::class, 'dibuat_id', 'id');
    }

    /**
     * Get the mengetahui that owns the DinasLuar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mengetahui()
    {
        return $this->belongsTo(User::class, 'mengetahui_id', 'id');
    }

    /**
     * Get the diperiksa that owns the DinasLuar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diperiksa()
    {
        return $this->belongsTo(User::class, 'diperiksa_id', 'id');
    }

    /**
     * Get the disetujui that owns the DinasLuar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disetujui()
    {
        return $this->belongsTo(User::class, 'disetujui_id', 'id');
    }
}
