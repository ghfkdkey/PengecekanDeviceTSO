<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model {
    use HasFactory;

    protected $primaryKey = 'building_id';
    protected $fillable = ['building_code', 'building_name', 'regional_id', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function regional() {
        return $this->belongsTo(Regional::class, 'regional_id');
    }

    public function floors() {
        return $this->hasMany(Floor::class, 'building_id');
    }

    public function getFloorsCountAttribute()
    {
        return $this->floors()->count() ?? 0;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}