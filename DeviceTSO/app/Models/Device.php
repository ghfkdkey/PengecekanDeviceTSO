<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory;

    protected $primaryKey = 'device_id';
    
    // Tambahkan 'image_path' ke dalam fillable array
    protected $fillable = [
        'room_id',
        'device_name',
        'device_type',
        'serial_number',
        'image_path',
        'user_id',
        'category',
        'notes',
        'merk',
        'tahun_po',
        'no_po',
        'no_bast',
        'tahun_bast',
        'condition'
    ];    

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function checkResults()
    {
        return $this->hasMany(DeviceCheckResult::class, 'device_id', 'device_id');
    }

    // Optional: Accessor untuk mendapatkan URL gambar lengkap
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        return null;
    }
}