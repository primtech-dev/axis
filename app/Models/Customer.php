<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'customers';

    /**
     * Primary key is the usual big increment (id)
     * public_id is UUID for public references and routing.
     */

    protected $fillable = [
        'public_id',
        'email',
        'google_id',
        'phone',
        'full_name',
        'password_hash',
        'token',
        'expired_at',
        'is_active',
        'meta',
    ];

    /**
     * Hide sensitive fields when model is serialized
     */
    protected $hidden = [
        'password_hash',
        'token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'public_id'   => 'string',
        'expired_at'  => 'datetime',
        'is_active'   => 'boolean',
        'meta'        => 'array',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    /**
     * Use public_id (UUID) for route model binding by default.
     */
    public function getRouteKeyName()
    {
        return 'public_id';
    }

    /**
     * Booted: set public_id UUID on creating if not provided
     */
    protected static function booted()
    {
        static::creating(function (Customer $customer) {
            if (empty($customer->public_id)) {
                $customer->public_id = (string) Str::uuid();
            }
        });
    }

    /**
     * Mutator: allow setting plain password via $customer->password = 'secret';
     * it will be hashed and stored in password_hash column.
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password_hash'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    /**
     * Accessor: check password (helper)
     * Usage: $customer->checkPassword('candidate')
     */
    public function checkPassword(string $plain): bool
    {
        if (empty($this->password_hash)) {
            return false;
        }

        return Hash::check($plain, $this->password_hash);
    }

    /* ------------------------------------------------------------------
     | RELATIONS
     | Add or uncomment relations below depending on which models exist
     | ----------------------------------------------------------------- */

    /**
     * Carts created by this customer
     * (table carts has customer_id FK)
     */
    public function carts(): HasMany
    {
        return $this->hasMany(\App\Models\Cart::class, 'customer_id', 'id');
    }

    /**
     * (Optional) One-to-many: orders placed by customer
     * Uncomment if `Order` model & `orders` table exist.
     */
    // public function orders(): HasMany
    // {
    //     return $this->hasMany(\App\Models\Order::class, 'customer_id', 'id');
    // }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'customer_id', 'id');
    }
    /**
     * (Optional) One-to-one: profile record (if you keep extended profile separate)
     */
    // public function profile(): HasOne
    // {
    //     return $this->hasOne(\App\Models\CustomerProfile::class, 'customer_id', 'id');
    // }

    /**
     * (Optional) Product reviews left by customer
     */
    // public function reviews(): HasMany
    // {
    //     return $this->hasMany(\App\Models\ProductReview::class, 'customer_id', 'id');
    // }

    /* ------------------------------------------------------------------
     | HELPERS
     | ----------------------------------------------------------------- */

    /**
     * Simple helper to create or get active cart for a branch.
     * - finds an existing non-deleted cart for this customer and branch, or creates one.
     *
     * Usage:
     *   $cart = $customer->getActiveCart($branchId);
     */
    public function getActiveCart(int $branchId)
    {
        return $this->carts()
            ->where('branch_id', $branchId)
            ->whereNull('deleted_at')
            ->first()
            ?? $this->carts()->create([
                'branch_id' => $branchId,
            ]);
    }
}
