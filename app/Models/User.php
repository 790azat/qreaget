<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLES = ['client', 'manager', 'admin'];

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'is_blocked', 'last_login_at'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_blocked' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'manager'], true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
