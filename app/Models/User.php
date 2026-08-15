<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_id',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * A user (except super_admin) belongs to one company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
        
    }

    public function shop(): BelongsTo
{
    return $this->belongsTo(Shop::class);
}

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDistributor(): bool
    {
        return $this->role === 'distributor';
    }

    public function isShopkeeper(): bool
    {
        return $this->role === 'shopkeeper';
    }
}
