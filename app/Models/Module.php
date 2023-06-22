<?php

namespace App\Models;

use App\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
    ];
}
