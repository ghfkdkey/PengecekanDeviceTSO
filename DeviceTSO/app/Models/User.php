<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_PIC_GA = 'PIC General Affair (GA)';
    const ROLE_PIC_OPERATIONAL = 'PIC Operasional';

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'full_name',
        'role',
        'regional_id' 
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function deviceCheckResults()
    {
        return $this->hasMany(DeviceCheckResult::class, 'user_id', 'id');
    }

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    // Role checker methods
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isGA()
    {
        return $this->role === self::ROLE_PIC_GA;
    }

    public function isOperational()
    {
        return $this->role === self::ROLE_PIC_OPERATIONAL;
    }

    // Permission methods untuk middleware (sesuai dengan nama di routes)
    public function canManageArea()
    {
        return $this->isAdmin(); // Hanya Admin
    }

    public function canManageRegional()
    {
        return $this->isAdmin(); // Hanya Admin
    }

    public function canManageBuilding()
    {
        return $this->isAdmin() || $this->isGA(); // Admin & PIC GA
    }

    public function canManageFloor()
    {
        return $this->isAdmin() || $this->isGA(); // Admin & PIC GA
    }

    public function canManageRoom()
    {
        return $this->isAdmin() || $this->isGA(); // Admin & PIC GA
    }

    public function canManageDevice()
    {
        return $this->isAdmin() || $this->isGA(); // Admin & PIC GA
    }

    public function canManageChecklist()
    {
        return $this->isAdmin() || $this->isGA(); // Admin & PIC GA
    }

    public function canManageUser()
    {
        // Admin bisa manage semua user
        // PIC GA hanya bisa manage PIC Operational di regional yang sama
        return $this->isAdmin() || $this->isGA();
    }

    public function canAccessDeviceCheck()
    {
        return true; // Semua role bisa akses pengecekan device
    }

    public function canPerformCheck()
    {
        return true; // All roles can perform checks
    }

    public function canAssignOperational()
    {
        return $this->isAdmin() || $this->isGA();
    }

    // Scope methods untuk data access
    public function scopeInRegional($query, $regionalId)
    {
        return $query->where('regional_id', $regionalId);
    }

    // Get accessible data based on role
    public function getAccessibleDevices()
    {
        if ($this->isAdmin()) {
            return Device::with(['room.floor.building.regional']);
        }

        return Device::with(['room.floor.building.regional'])
            ->whereHas('room.floor.building.regional', function($query) {
                $query->where('regional_id', $this->regional_id);
            });
    }

    public function getAccessibleBuildings()
    {
        if ($this->isAdmin()) {
            return Building::with(['regional']);
        }

        return Building::with(['regional'])
            ->where('regional_id', $this->regional_id);
    }

    public function getAccessibleRegionals()
    {
        if ($this->isAdmin()) {
            return Regional::all();
        }

        if ($this->isGA() || $this->isOperational()) {
            return Regional::where('regional_id', $this->regional_id)->get();
        }

        return collect();
    }

    // Method untuk mengecek apakah user bisa manage user tertentu
    public function canManageTargetUser($targetUser)
    {
        if ($this->isAdmin()) {
            return true; // Admin bisa manage semua
        }

        if ($this->isGA()) {
            // PIC GA hanya bisa manage PIC Operational di regional yang sama
            return $targetUser->isOperational() && 
                   $targetUser->regional_id == $this->regional_id;
        }

        return false;
    }

    // Method untuk mengecek apakah user bisa create role tertentu
    public function canCreateRole($role, $regionalId = null)
    {
        if ($this->isAdmin()) {
            return true; // Admin bisa create semua role
        }

        if ($this->isGA()) {
            // PIC GA hanya bisa create PIC Operational di regional yang sama
            return $role === self::ROLE_PIC_OPERATIONAL && 
                   $regionalId == $this->regional_id;
        }

        return false;
    }

    // Debug method untuk troubleshooting
    public function debugPermissions()
    {
        return [
            'user_id' => $this->id,
            'role' => $this->role,
            'regional_id' => $this->regional_id,
            'isAdmin' => $this->isAdmin(),
            'isGA' => $this->isGA(),
            'isOperational' => $this->isOperational(),
            'canManageBuilding' => $this->canManageBuilding(),
        ];
    }
}