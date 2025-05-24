<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BukuTamu extends Model
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::uuid(); // Buat UUID otomatis
            }
        });
    }

    /**
     * Get the confirmDatang that owns the BukuTamu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function confirmDatang()
    {
        return $this->belongsTo(User::class, 'datang_by', 'id');
    }

    /**
     * Get the confirmPulang that owns the BukuTamu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function confirmPulang()
    {
        return $this->belongsTo(User::class, 'pulang_by', 'id');
    }

    /**
     * Get the creator that owns the BukuTamu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
