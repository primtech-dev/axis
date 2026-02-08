<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'expense_id',
        'name',
        'qty',
        'price',
        'subtotal',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}

