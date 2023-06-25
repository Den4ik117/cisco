<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choice extends Model
{
    protected $fillable = [
        'is_chosen',
        'exercise_id',
        'option_id',
    ];

    protected $casts = [
        'is_chosen' => 'boolean',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
