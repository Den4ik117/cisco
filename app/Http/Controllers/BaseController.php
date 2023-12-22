<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Token;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function index(Request $request)
    {
        $token = Token::query()->firstWhere('uuid', $request->cookie('guest'));

        $tasks = Task::with(['options' => function ($query) {
            $query->select('id', 'name', 'task_id')->where('is_answer', true);
        }])
            ->select(['id', 'name'])
            ->where('course_id', $token->course_id)
            ->get();

        return view('base.index', compact(['tasks']));
    }
}
