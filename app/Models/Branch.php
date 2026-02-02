<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $table = 'branches';

    protected $casts = [
        'latitude' => 'double',
        'longitude' => 'double',
        'is_active' => 'boolean',
    ];

    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'province',
        'phone',
        'latitude',
        'longitude',
        'manager_user_id',
        'is_active',
    ];

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_user_id');
    }
}
