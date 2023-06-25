<?php

namespace App\Models;

use App\Enums\TestType;
use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasUuids;

    protected $fillable = [
        'token_uuid',
        'last_exercise_id',
        'type',
    ];

    protected $casts = [
        'type' => TestType::class,
    ];

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }
}
