<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionHeader extends Model
{
    protected $guarded = [];

    /**
     * Get all of the details for the TransactionHeader
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'id', 'transaction_id');
    }
}