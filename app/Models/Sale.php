<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'date',
        'customer_id',
        'payment_type',
        'due_date',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'status',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function receivable()
    {
        return $this->hasOne(CustomerReceivable::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'reference_id')
            ->where('reference_type', 'SALE');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
