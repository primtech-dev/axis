<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id','customer_id','order_id','order_item_id',
        'product_id','variant_id','rating','title','body',
        'is_verified_purchase','status'
    ];

    protected static function booted()
    {
        static::creating(function ($review) {
            $review->public_id ??= Str::uuid();
        });
    }

    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(
            ProductVariant::class,
            'variant_id' // FK di table reviews
        );
    }

    public function ratingBadgeClass(): string
    {
        return match ($this->rating) {
            5 => 'bg-success',
            4 => 'bg-info',
            3 => 'bg-warning',
            2 => 'bg-orange',
            default => 'bg-danger',
        };
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
