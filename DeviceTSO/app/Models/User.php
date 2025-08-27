<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id'; // Changed from 'user_id' to 'id' to match migration

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
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

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGA()
    {
        return $this->role === 'PIC General Affair (GA)';
    }

    public function isOperational()
    {
        return $this->role === 'PIC Operasional';
    }
    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
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
     * Override getAuthIdentifierName to use id
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Relationship dengan device_check_results
     */
    public function deviceCheckResults()
    {
        return $this->hasMany(DeviceCheckResult::class, 'user_id', 'id');
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