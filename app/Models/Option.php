<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'name',
        'is_answer',
        'is_chosen',
        'task_id',
    ];

    protected $casts = [
        'is_answer' => 'boolean',
        'is_chosen' => 'boolean',
    ];
}
