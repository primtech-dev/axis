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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'reference_id');
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'reference_id');
    }
}
