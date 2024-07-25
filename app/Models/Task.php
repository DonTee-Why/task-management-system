<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasUuids, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = TaskStatusEnum::GetEnumsNameByValue($value);

        if ($value === TaskStatusEnum::COMPLETED && is_null($this->completed_at)) {
            $this->completed_at = now();
        } elseif ($value !== TaskStatusEnum::COMPLETED) {
            $this->completed_at = null;
        }
    }

    public function getStatusAttribute($value)
    {
        return TaskStatusEnum::GetEnumsNameByValue($value);
    }
}
