<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'category_id',
        'unit_id',
        'price_buy_default',
        'price_sell_default',
        'is_active',
    ];

    protected $casts = [
        'price_buy_default'  => 'decimal:2',
        'price_sell_default' => 'decimal:2',
        'is_active'          => 'boolean',
    ];

    /* =========================
     * RELATIONS
     * ========================= */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
