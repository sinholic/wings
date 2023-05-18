<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransactionDetail extends Model
{
    protected $guarded = [];

    /**
     * Get the product associated with the TransactionDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(User::class, 'product_code', 'product_code');
    }
}
