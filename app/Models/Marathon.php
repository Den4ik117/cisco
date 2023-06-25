<?php

namespace App\Models;

use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marathon extends Model
{
    use HasUuids;

    protected $fillable = [
        'token_uuid',
        'last_task_id',
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function success_tasks(): HasMany
    {
        return $this->hasMany(Task::class)->where('is_success', true);
    }

    public function error_tasks(): HasMany
    {
        return $this->hasMany(Task::class)->where('is_success', false);
    }
}
