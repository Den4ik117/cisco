<?php

namespace App\Http\Controllers;

use App\Enums\TestType;
use App\Models\Choice;
use App\Models\Exercise;
use App\Models\Option;
use App\Models\Task;
use App\Models\Test;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function store(Request $request)
    {
        $test = Test::query()->create([
            'token_uuid' => $request->cookie('guest'),
            'type' => TestType::Exam,
        ]);

        $tasks = Task::query()
//            ->whereNull('task_id')
            ->get(['id'])
            ->shuffle()
            ->take(25);

        foreach ($tasks as $task) {
            $exercise = Exercise::query()->create([
                'test_id' => $test->id,
                'task_id' => $task->id,
            ]);

            $options = Option::query()
                ->where('task_id', $task->id)
                ->get()
                ->shuffle();

            foreach ($options as $option) {
                Choice::query()->create([
                    'exercise_id' => $exercise->id,
                    'option_id' => $option->id,
                ]);
            }
        }

        return to_route('exams.show', $test->uuid);
    }

    public function show(Test $test)
    {
        return view('exams.show', compact(['test']));
    }
}
