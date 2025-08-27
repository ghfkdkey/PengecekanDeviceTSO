<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regional extends Model {
    use HasFactory;

    protected $primaryKey = 'regional_id';
    protected $fillable = ['regional_name', 'area_id', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function area() {
        return $this->belongsTo(Area::class, 'area_id', 'area_id');
    }

    public function buildings() {
        return $this->hasMany(Building::class, 'regional_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}