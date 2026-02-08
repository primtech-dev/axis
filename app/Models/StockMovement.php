<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id','qty','type','source_type','source_id','note','created_by'
    ];
}

