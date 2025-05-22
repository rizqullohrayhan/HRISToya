<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailVoucher extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the voucher that owns the DetailVoucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
    }

    /**
     * Get the bankCode that owns the DetailVoucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankCode()
    {
        return $this->belongsTo(KodePerkiraan::class, 'bank_code_id', 'id');
    }

    /**
     * Get the perkiraan that owns the DetailVoucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function perkiraan()
    {
        return $this->belongsTo(KodePerkiraan::class, 'perkiraan_id', 'id');
    }

    /**
     * Get the mataUang that owns the DetailVoucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mataUang()
    {
        return $this->belongsTo(MataUang::class, 'currency_id', 'id');
    }

    /**
     * Get the rekanan that owns the DetailVoucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rekanan()
    {
        return $this->belongsTo(Rekanan::class, 'rekan_id', 'id');
    }
}
