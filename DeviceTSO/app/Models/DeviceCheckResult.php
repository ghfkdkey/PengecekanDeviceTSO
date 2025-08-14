<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'checked_at'
    ];

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
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}