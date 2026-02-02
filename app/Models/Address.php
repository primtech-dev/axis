<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addresses';

    protected $fillable = [
        'customer_id',
        'label',
        'street',
        'city',
        'province',
        'latitude',
        'longitude',
        'postal_code',
        'country',
        'phone',
        'is_default',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'latitude'    => 'double',
        'longitude'   => 'double',
        'is_default'  => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    /**
     * RELATIONS
     */

    /**
     * Address belongs to a Customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id', 'id');
    }

    /**
     * BOOTED: ensure only one default address per customer.
     *
     * Behaviour:
     * - When creating/updating an address where is_default == true,
     *   all other addresses for the same customer will be set is_default = false.
     *
     * Note: We use DB transaction to avoid race-conditions in normal usage,
     * but for very high concurrency you might want a DB-level constraint or
     * serialized queue handling.
     */
    protected static function booted()
    {
        static::saving(function (Address $address) {
            // only act when is_default explicitly true
            if ($address->is_default) {
                // Use a transaction to flip other defaults off atomically
                DB::transaction(function () use ($address) {
                    // set other addresses for the same customer to false
                    static::where('customer_id', $address->customer_id)
                        ->where('id', '!=', $address->id ?? 0)
                        ->where('is_default', true)
                        ->update(['is_default' => false]);
                });
            }
        });

        // If an address is deleted and it was default, optionally make another default
        static::deleted(function (Address $address) {
            if ($address->is_default) {
                // promote the most recently updated (non-deleted) address to default, if any
                $next = static::where('customer_id', $address->customer_id)
                    ->whereNull('deleted_at')
                    ->orderByDesc('updated_at')
                    ->first();

                if ($next) {
                    $next->is_default = true;
                    $next->save();
                }
            }
        });
    }

    /* -------------------------
     | HELPERS
     | ------------------------- */

    /**
     * Set this address as default for its customer.
     *
     * Usage:
     *   $address->setAsDefault();
     */
    public function setAsDefault(): self
    {
        if (!$this->exists) {
            $this->save();
        }

        DB::transaction(function () {
            static::where('customer_id', $this->customer_id)
                ->where('id', '!=', $this->id)
                ->update(['is_default' => false]);

            $this->is_default = true;
            $this->save();
        });

        return $this->refresh();
    }

    /**
     * Static helper: make given address id default for a customer.
     *
     * Usage:
     *   Address::makeDefaultForCustomer($customerId, $addressId);
     */
    public static function makeDefaultForCustomer(int $customerId, int $addressId): ?self
    {
        $address = static::where('customer_id', $customerId)->find($addressId);
        if (!$address) {
            return null;
        }

        return $address->setAsDefault();
    }
}
