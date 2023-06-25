<?php

namespace App\Models;

use App\Enums\TaskType;
use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasUuids;

    protected $fillable = [
        'uuid',
        'name',
        'image_content',
        'type',
        'marathon_id',
        'task_id',
        'is_success',
    ];

    protected $casts = [
//        'content' => 'array',
//        'answer' => 'array',
        'type' => TaskType::class,
        'is_success' => 'boolean',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
