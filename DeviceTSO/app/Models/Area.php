<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model {
    use HasFactory;

    protected $primaryKey = 'area_id';
    protected $fillable = ['area_name', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function regionals() {
        return $this->hasMany(Regional::class, 'area_id');
    }
}