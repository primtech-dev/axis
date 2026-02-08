<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    const TYPE_PURCHASE = 'PURCHASE';
    const TYPE_EXPENSE  = 'EXPENSE';

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function payables()
    {
        return $this->hasMany(SupplierPayable::class);
    }

    /* Optional: helper label */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_PURCHASE => 'Pembelian',
            self::TYPE_EXPENSE  => 'Biaya',
            default             => '-',
        };
    }
}
