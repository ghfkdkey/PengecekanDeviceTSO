<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password_hash',
        'full_name',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Override getAuthPassword method to use password_hash field
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Override getAuthIdentifierName to use user_id
     */
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    /**
     * Relationship dengan device_check_results
     */
    public function deviceCheckResults()
    {
        return $this->hasMany(DeviceCheckResult::class, 'user_id', 'user_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is supervisor
     */
    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }

    /**
     * Scope untuk filter berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}