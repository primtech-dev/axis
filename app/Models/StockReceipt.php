<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockReceipt extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'stock_receipts';

    protected $fillable = [
        'public_id', 'branch_id', 'supplier_name', 'reference_no',
        'status', 'total_items', 'created_by', 'received_at', 'notes', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'received_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->public_id)) $model->public_id = (string) Str::uuid();
        });
    }

    public function items()
    {
        return $this->hasMany(StockReceiptItem::class, 'receipt_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
