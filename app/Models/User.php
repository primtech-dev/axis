<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasRoles, HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','phone','address','branch_id','is_superadmin','is_active','meta'
    ];

    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }

    public function isSuperAdmin(): bool
    {
        return (bool)$this->is_superadmin || $this->hasRole('superadmin');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'is_superadmin' => 'boolean',
        'is_active' => 'boolean',
        'meta' => 'array',
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    // helper check: apakah user boleh akses resource cabang $branchId
    public function canAccessBranch(?int $branchId): bool
    {
        if ($this->isSuperadmin()) return true;
        if (is_null($branchId)) return false;
        return $this->branch_id === $branchId;
    }
}
