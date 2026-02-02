<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = 'carts';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'customer_id',
        'branch_id',
    ];

    /**
     * Items in the cart
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }

    /**
     * Customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Branch
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    /**
     * Cascade soft-deletes to items when this cart is deleted (soft or force).
     */
    protected static function booted()
    {
        static::deleting(function (Cart $cart) {
            if ($cart->isForceDeleting()) {
                $cart->items()->withTrashed()->forceDelete();
            } else {
                $cart->items()->delete();
            }
        });

        static::restoring(function (Cart $cart) {
            // restore items when cart is restored
            $cart->items()->withTrashed()->restore();
        });
    }
}
