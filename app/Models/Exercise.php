<?php

namespace App\Models;

use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    use HasUuids;

    protected $fillable = [
        'test_id',
        'task_id',
        'is_success',
    ];

    protected $casts = [
        'is_success' => 'boolean',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }

    public function isSuccessful()
    {
        return $this->choices->every(function (Choice $choice) {
            return (!!$choice->option->is_answer) === (!!$choice->is_chosen);
        });
    }
}
