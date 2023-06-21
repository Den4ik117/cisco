<?php

namespace App\Models;

use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Token extends Model
{
    use HasUuids;

    protected $fillable = [
        'ip',
    ];

    public function marathons(): HasMany
    {
        return $this->hasMany(Marathon::class, 'token_uuid', 'uuid');
    }
}
