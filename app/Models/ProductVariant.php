<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id','sku','variant_name', 'length','width','height','is_active','is_sellable', 'price','unit_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_sellable' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }

    public function inventories()
    {
        return $this->hasMany(\App\Models\Inventory::class, 'variant_id');
    }

    public function images()
    {
        return $this->hasMany(\App\Models\ProductImage::class, 'variant_id');
        // jika model gambar kamu bernama ProductImage dan kolomnya variant_id, ganti class di atas.
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
