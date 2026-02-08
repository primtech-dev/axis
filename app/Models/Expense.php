<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'purchase_id',
        'expense_number',
        'supplier_id',
        'date',
        'payment_type',
        'due_date',
        'subtotal',
        'grand_total',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(ExpenseItem::class);
    }

    /**
     * Hutang supplier dari EXPENSE
     */
    public function payable()
    {
        return $this->hasOne(SupplierPayable::class, 'reference_id')
            ->where('reference_type', 'EXPENSE');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'reference_id')
            ->where('reference_type', 'EXPENSE');
    }
}
