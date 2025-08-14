<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $primaryKey = 'device_id';
    protected $fillable = ['room_id', 'device_name', 'device_type', 'serial_number'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function checkResults()
    {
        return $this->hasMany(DeviceCheckResult::class, 'device_id', 'device_id');
    }
}
