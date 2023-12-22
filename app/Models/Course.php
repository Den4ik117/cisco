<?php

namespace App\Models;

use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'exam_questions_number',
    ];
}
