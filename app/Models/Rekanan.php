<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekanan extends Model
{
    protected $fillable = ['name', 'code'];

    /**
     * Get all of the aktivitas for the Rekanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'rekan_id', 'id');
    }

    /**
     * Get all of the voucher for the Rekanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voucher()
    {
        return $this->hasMany(Voucher::class, 'rekan_id', 'id');
    }

    /**
     * Get all of the detailVoucher for the Rekanan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailVoucher()
    {
        return $this->hasMany(DetailVoucher::class, 'rekan_id', 'id');
    }
}
