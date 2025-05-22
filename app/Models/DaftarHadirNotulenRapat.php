<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarHadirNotulenRapat extends Model
{
    protected $guarded = ['id'];

    // Relasi ke NotulenRapat
    public function notulenRapat()
    {
        return $this->belongsTo(NotulenRapat::class);
    }

    // Relasi ke User (nullable)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
