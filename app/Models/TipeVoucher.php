<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeVoucher extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the voucher for the TipeVoucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucher()
    {
        return $this->hasMany(Voucher::class, 'tipe_id', 'id');
    }
}
