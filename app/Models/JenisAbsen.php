<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisAbsen extends Model
{
    /**
     * Get all of the absensi for the JenisAbsen
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'jenis_absen_id', 'id');
    }
}
