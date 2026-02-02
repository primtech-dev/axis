<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCategory extends Pivot
{
    // use timestamps handled by belongsToMany()->withTimestamps()
    protected $table = 'product_categories';
}
