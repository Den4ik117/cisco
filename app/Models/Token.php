<?php

namespace App\Models;

use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Token extends Model
{
    use HasUuids;

    protected $fillable = [
        'ip',
        'course_id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
