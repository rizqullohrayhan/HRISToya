<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusVoucher extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the voucher for the StatusVoucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucher()
    {
        return $this->hasMany(Voucher::class, 'status_id', 'id');
    }
}
