<?php

namespace App\Http\Controllers;

use App\Enums\TestType;
use App\Models\Marathon;
use App\Models\Test;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'marathon_resolved_tasks_count' => Marathon::query()->where('token_uuid', $request->cookie('guest'))->latest()->first()?->tasks()->whereNotNull('is_success')->count() ?? 0,
            'marathon_tasks_count' => Marathon::query()->where('token_uuid', $request->cookie('guest'))->latest()->first()?->tasks()->count() ?? 0,
            'exam_resolved_exercises_count' => Test::query()
                    ->where('token_uuid', $request->cookie('guest'))
                    ->where('type', TestType::Exam->value)
                    ->latest()
                    ->first()
                    ?->exercises()
                    ->whereNotNull('is_success')
                    ->count() ?? 0,
            'exam_exercises_count' => Test::query()
                    ->where('token_uuid', $request->cookie('guest'))
                    ->where('type', TestType::Exam->value)
                    ->latest()
                    ->first()
                    ?->exercises()
                    ->count() ?? 0,
            'mistake_resolved_exercises_count' => Test::query()
                    ->where('token_uuid', $request->cookie('guest'))
                    ->where('type', TestType::Mistake->value)
                    ->latest()
                    ->first()
                    ?->exercises()
                    ->whereNotNull('is_success')
                    ->count() ?? 0,
            'mistake_exercises_count' => Test::query()
                    ->where('token_uuid', $request->cookie('guest'))
                    ->where('type', TestType::Mistake->value)
                    ->latest()
                    ->first()
                    ?->exercises()
                    ->count() ?? 0,
        ];

        return view('index', compact(['stats']));
    }
}
