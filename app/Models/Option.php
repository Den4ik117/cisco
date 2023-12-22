<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = [
        'name',
        'is_answer',
        'task_id',
    ];

    protected $casts = [
        'is_answer' => 'boolean',
    ];
}
