<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reference_type',
        'reference_id',
        'payment_method_id', // legacy
        'cash_account_id',   // new
        'amount',
        'date',
        'created_by',
    ];

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function cashAccount()
    {
        return $this->belongsTo(Account::class, 'cash_account_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
