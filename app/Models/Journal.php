<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $fillable = [
        'date',
        'reference_type',
        'reference_id',
        'description',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
