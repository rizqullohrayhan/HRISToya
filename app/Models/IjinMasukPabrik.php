<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IjinMasukPabrik extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the dibuat that owns the SuratIjin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dibuat()
    {
        return $this->belongsTo(User::class, 'dibuat_id', 'id');
    }

    /**
     * Get the mengetahui that owns the SuratIjin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mengetahui()
    {
        return $this->belongsTo(User::class, 'mengetahui_id', 'id');
    }

    /**
     * Get the diterima that owns the SuratIjin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diterima()
    {
        return $this->belongsTo(User::class, 'diterima_id', 'id');
    }

    /**
     * Get the disetujui that owns the SuratIjin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disetujui()
    {
        return $this->belongsTo(User::class, 'disetujui_id', 'id');
    }

    /**
     * Get the creator that owns the SuratIjin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
