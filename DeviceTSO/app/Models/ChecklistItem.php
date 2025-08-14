<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'checklist_id';
    protected $fillable = ['device_type', 'question'];

    public function checkResults()
    {
        return $this->hasMany(DeviceCheckResult::class, 'checklist_id', 'checklist_id');
    }
}
