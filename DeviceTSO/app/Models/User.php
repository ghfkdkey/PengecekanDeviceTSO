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

    // Role constants - PERBAIKAN: gunakan format yang konsisten
    const ROLE_ADMIN = 'admin';
    const ROLE_PIC_GA = 'pic_ga';  // Ubah dari 'PIC General Affair (GA)'
    const ROLE_PIC_OPERATIONAL = 'pic_operational';  // Ubah dari 'PIC Operasional'

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

    // Role checker methods - PERBAIKAN: tambah support untuk format lama
    public function isAdmin()
    {
        return in_array($this->role, [self::ROLE_ADMIN, 'admin']);
    }

    public function isGA()
    {
        return in_array($this->role, [
            self::ROLE_PIC_GA, 
            'pic_ga', 
            'PIC General Affair (GA)',
            'PIC GA'
        ]);
    }

    public function isOperational()
    {
        return in_array($this->role, [
            self::ROLE_PIC_OPERATIONAL, 
            'pic_operational', 
            'PIC Operasional',
            'PIC Operational'
        ]);
    }

    // Permission methods untuk middleware
    public function canManageArea()
    {
        return $this->isAdmin();
    }

    public function canManageRegional()
    {
        return $this->isAdmin();
    }

    public function canManageBuilding()
    {
        return $this->isAdmin() || $this->isGA();
    }

    public function canManageFloor()
    {
        return $this->isAdmin() || $this->isGA();
    }

    public function canManageRoom()
    {
        return $this->isAdmin() || $this->isGA();
    }

    public function canManageDevice()
    {
        return $this->isAdmin() || $this->isGA();
    }

    public function canManageChecklist()
    {
        return $this->isAdmin() || $this->isGA();
    }

    public function canManageUser()
    {
        return $this->isAdmin() || $this->isGA();
    }

    // PERBAIKAN: Pastikan semua role bisa akses device check
    public function canAccessDeviceCheck()
    {
        return true; // Semua role authenticated bisa akses
    }

    public function canPerformCheck()
    {
        return true; // Semua role bisa perform checks
    }

    // TAMBAHAN: Method khusus untuk device check operations
    public function canDeviceCheck()
    {
        return $this->canAccessDeviceCheck();
    }

    public function canAccessDevice()
    {
        return $this->canAccessDeviceCheck();
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
            return true;
        }

        if ($this->isGA()) {
            return $targetUser->isOperational() && 
                   $targetUser->regional_id == $this->regional_id;
        }

        return false;
    }

    // Method untuk mengecek apakah user bisa create role tertentu
    public function canCreateRole($role, $regionalId = null)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isGA()) {
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
            'canAccessDeviceCheck' => $this->canAccessDeviceCheck(),
            'canPerformCheck' => $this->canPerformCheck(),
            'canDeviceCheck' => $this->canDeviceCheck(),
        ];
    }
}