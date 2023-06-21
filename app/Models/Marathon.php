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
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
