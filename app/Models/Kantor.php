<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the users for the Kantor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
