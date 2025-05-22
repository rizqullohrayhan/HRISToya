<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the macamCuti that owns the Cuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function macamCuti()
    {
        return $this->belongsTo(MacamCuti::class, 'macam_id', 'id');
    }

    /**
     * Get the user that owns the Cuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the dibuat that owns the Cuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dibuat()
    {
        return $this->belongsTo(User::class, 'dibuat_id', 'id');
    }

    /**
     * Get the mengetahui that owns the Cuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mengetahui()
    {
        return $this->belongsTo(User::class, 'mengetahui_id', 'id');
    }

    /**
     * Get the diperiksa that owns the Cuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diperiksa()
    {
        return $this->belongsTo(User::class, 'diperiksa_id', 'id');
    }

    /**
     * Get the disetujui that owns the Cuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disetujui()
    {
        return $this->belongsTo(User::class, 'disetujui_id', 'id');
    }

    /**
     * Get all of the pjs for the Cuti
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pjs()
    {
        return $this->hasMany(PJSCuti::class, 'surat_id', 'id');
    }
}
