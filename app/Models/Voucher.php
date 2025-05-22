<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the statusVoucher that owns the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function statusVoucher()
    {
        return $this->belongsTo(StatusVoucher::class, 'status_id', 'id');
    }

    /**
     * Get the bankCode that owns the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bankCode()
    {
        return $this->belongsTo(KodePerkiraan::class, 'bank_code_id', 'id');
    }

    /**
     * Get the rekanan that owns the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rekanan()
    {
        return $this->belongsTo(Rekanan::class, 'rekan_id', 'id');
    }

    /**
     * Get the tipeVoucher that owns the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipeVoucher()
    {
        return $this->belongsTo(TipeVoucher::class, 'tipe_id', 'id');
    }

    /**
     * Get the user that owns the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    public function bookkeeper()
    {
        return $this->belongsTo(User::class, 'bookkeeped_by', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get all of the detailVoucher for the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detailVoucher()
    {
        return $this->hasMany(DetailVoucher::class, 'voucher_id', 'id');
    }
}
