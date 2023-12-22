<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['options' => fn($query) => $query->where('is_answer', true)])
            ->select(['id', 'name'])
//            ->whereNull('task_id')
            ->get();

        return view('base.index', compact(['tasks']));
    }
}
