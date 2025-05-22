<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataUang extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all of the detailVoucher for the MataUang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailVoucher()
    {
        return $this->hasMany(DetailVoucher::class, 'currency_id', 'id');
    }
}
