<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPayable extends Model
{
    protected $fillable = [
        'reference_type',
        'reference_id',
        'supplier_id',
        'total',
        'paid',
        'balance',
        'status',
    ];

    public function purchase()
    {
        return $this->reference_type === 'PURCHASE'
            ? $this->belongsTo(Purchase::class, 'reference_id')
            : null;
    }

    public function expense()
    {
        return $this->reference_type === 'EXPENSE'
            ? $this->belongsTo(Expense::class, 'reference_id')
            : null;
    }
}
