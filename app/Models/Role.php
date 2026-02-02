<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends SpatieRole
{
    use HasFactory;

    // Jika kamu menambahkan kolom custom (mis: display_name, description),
    // tambahkan fillable / casts di sini.
    protected $fillable = [
        'name',
        'guard_name',
        // 'display_name', 'description' // uncomment jika ada
    ];
}
