<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'invoice_number',
        'date',
        'supplier_id',
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

    /* =========================
     * RELATIONSHIPS
     * ========================= */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function payable()
    {
        return $this->hasOne(SupplierPayable::class);
    }

    public function payments()
    {
        return $this->hasMany(
            Payment::class,
            'reference_id'
        )->where('reference_type', 'PURCHASE');
    }
}
