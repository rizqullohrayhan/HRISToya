<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTugasKeluar extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the pemberi that owns the SuratTugasKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pemberi()
    {
        return $this->belongsTo(User::class, 'pemberi_id', 'id');
    }

    /**
     * Get the penerima that owns the SuratTugasKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penerima()
    {
        return $this->belongsTo(User::class, 'penerima_id', 'id');
    }

    /**
     * Get the dibuat that owns the SuratTugasKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dibuat()
    {
        return $this->belongsTo(User::class, 'dibuat_id', 'id');
    }

    /**
     * Get the mengetahui that owns the SuratTugasKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mengetahui()
    {
        return $this->belongsTo(User::class, 'mengetahui_id', 'id');
    }

    /**
     * Get the diperiksa that owns the SuratTugasKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function diperiksa()
    {
        return $this->belongsTo(User::class, 'diperiksa_id', 'id');
    }

    /**
     * Get the disetujui that owns the SuratTugasKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disetujui()
    {
        return $this->belongsTo(User::class, 'disetujui_id', 'id');
    }

    /**
     * Get all of the detail for the SuratTugasKeluar
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detail()
    {
        return $this->hasMany(DetailSuratTugasKeluar::class, 'surat_id', 'id');
    }
}
