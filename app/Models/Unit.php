<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'units';

    protected $fillable = [
        'code',
        'name'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ---- RELATIONS ----

    // Produk yang menggunakan unit ini (base unit)
    public function products()
    {
        return $this->hasMany(Product::class, 'base_unit_id');
    }

    // Varian produk yang menggunakan unit ini
    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'unit_id');
    }

    // Order items yang menyimpan snapshot unit ini
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'unit_id');
    }
}
