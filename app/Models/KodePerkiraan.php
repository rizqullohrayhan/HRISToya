<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodePerkiraan extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the voucher for the KodePerkiraan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucher()
    {
        return $this->hasMany(Voucher::class, 'bank_code_id', 'id');
    }

    /**
     * Get all of the detailVoucher for the KodePerkiraan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailVoucher()
    {
        return $this->hasMany(DetailVoucher::class, 'bank_code_id', 'id');
    }
}
