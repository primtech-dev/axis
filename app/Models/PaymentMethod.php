<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $table = 'payment_methods';

    protected $fillable = [
        'code',
        'name',
        'provider',
        'type',
        'config',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Scope: hanya payment method yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }

    /**
     * Relasi ke Orders (optional tetapi sering dipakai).
     * Many orders can use one payment method.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'payment_method_id');
    }

    /**
     * Helper: apakah method ini menggunakan provider gateway (mis. midtrans, xendit).
     */
    public function isGateway(): bool
    {
        return in_array($this->provider, ['midtrans', 'xendit', 'ipaymu', 'duitku']);
    }

    /**
     * Helper: apakah termasuk bank transfer.
     */
    public function isBankTransfer(): bool
    {
        return $this->type === 'bank_transfer';
    }

    /**
     * Helper: apakah metode COD.
     */
    public function isCOD(): bool
    {
        return $this->type === 'cash_on_delivery';
    }

    /**
     * Helper: Ambil config key dengan fallback.
     */
    public function getConfigValue(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
}
