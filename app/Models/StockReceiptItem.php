<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockReceiptItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'stock_receipt_items';

    protected $fillable = [
        'receipt_id','variant_id','quantity_received','unit_cost_cents'
    ];

    public function receipt()
    {
        return $this->belongsTo(StockReceipt::class, 'receipt_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
