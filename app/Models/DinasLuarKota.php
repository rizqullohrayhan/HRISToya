<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinasLuarKota extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the penerima that owns the DinasLuarKota
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id', 'id');
    }

    /**
     * Get the pemberi that owns the DinasLuarKota
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pemberi()
    {
        return $this->belongsTo(User::class, 'pemberi_id', 'id');
    }

    /**
     * Get all of the detail for the DetailDinasLuarKota
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail()
    {
        return $this->hasMany(DetailDinasLuarKota::class, 'surat_id', 'id');
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
