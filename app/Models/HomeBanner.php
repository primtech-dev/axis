<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeBanner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'image_path',
        'image_mobile_path',
        'link_url',
        'sort_order',
        'is_active',
        'start_at',
        'end_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image_path
            ? asset('storage/' . $this->image_path)
            : null;
    }

    public function getImageMobileUrlAttribute()
    {
        return $this->image_mobile_path
            ? asset('storage/' . $this->image_mobile_path)
            : null;
    }
}
