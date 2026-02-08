<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerReceivable extends Model
{
    protected $fillable = [
        'sale_id',
        'customer_id',
        'total',
        'paid',
        'balance',
        'status',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

