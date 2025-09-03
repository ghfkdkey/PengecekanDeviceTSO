<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceCheckResult extends Model
{
    use HasFactory;

    protected $primaryKey = 'result_id';
    protected $fillable = [
        'device_id',
        'checklist_id', 
        'user_id',
        'status',
        'notes',
        'checked_at',
        'updated_at_custom',
        'original_checked_at' 
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'updated_at_custom' => 'datetime',
        'original_checked_at' => 'datetime'
    ];

    // Boot method untuk set original_checked_at
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->original_checked_at) {
                $model->original_checked_at = $model->checked_at;
            }
        });
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id', 'device_id');
    }

    public function checklistItem()
    {
        return $this->belongsTo(ChecklistItem::class, 'checklist_id', 'checklist_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}