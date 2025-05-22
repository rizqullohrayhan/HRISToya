<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['user_id', 'jenis_absen_id', 'picture', 'location'];

    /**
     * Get the jenisAbsen that owns the Absensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenis()
    {
        return $this->belongsTo(JenisAbsen::class, 'jenis_absen_id', 'id');
    }

    /**
     * Get the user that owns the Absensi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
