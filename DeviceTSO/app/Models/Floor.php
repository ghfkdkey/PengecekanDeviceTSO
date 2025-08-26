<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $table = 'floors';
    protected $primaryKey = 'floor_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'floor_name',
        'building_id',
        'user_id',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id', 'building_id');
    }

    /**
     * Get the user who created this floor.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the rooms for this floor.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'floor_id', 'floor_id');
    }

    /**
     * Get the devices through rooms.
     */
    public function devices()
    {
        return $this->hasManyThrough(
            Device::class,
            Room::class,
            'floor_id', // Foreign key on rooms table
            'room_id',  // Foreign key on devices table
            'floor_id', // Local key on floors table
            'room_id'   // Local key on rooms table
        );
    }

    /**
     * Get rooms count for this floor.
     */
    public function getRoomsCountAttribute()
    {
        return $this->rooms()->count();
    }

    /**
     * Get devices count for this floor.
     */
    public function getDevicesCountAttribute()
    {
        return $this->devices()->count();
    }

    /**
     * Scope untuk pencarian berdasarkan nama lantai
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('floor_name', 'like', '%' . $search . '%');
    }
}