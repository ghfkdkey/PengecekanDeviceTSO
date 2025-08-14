<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'room_id';
    protected $fillable = ['floor_id', 'room_name'];

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id', 'floor_id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'room_id', 'room_id');
    }
}
