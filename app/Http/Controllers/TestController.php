<?php

namespace App\Http\Controllers;

use App\Enums\TestType;
use App\Models\Choice;
use App\Models\Exercise;
use App\Models\Marathon;
use App\Models\Option;
use App\Models\Task;
use App\Models\Test;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $token = Token::query()->firstWhere('uuid', $request->cookie('guest'));
//        $marathons = Marathon::query()
//            ->withCount(['tasks', 'success_tasks', 'error_tasks'])
//            ->where('token_uuid', $request->cookie('guest'))
//            ->get();

        $tests = Test::query()
            ->withCount(['exercises', 'success_exercises', 'error_exercises'])
            ->where('token_uuid', $request->cookie('guest'))
            ->get();

        return view('tests.index', compact(['tests']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::enum(TestType::class)],
        ]);

        DB::beginTransaction();

        $type = TestType::from($validated['type']);
        $shuffle = $type === TestType::Exam || $request->boolean('shuffle');

        $token = Token::query()
            ->with(['course'])
            ->firstWhere('uuid', $request->cookie('guest'));

        $test = Test::query()->create([
            'token_uuid' => $token->uuid,
            'course_id' => $token->course_id,
            'type' => $validated['type'],
        ]);

        $tasks = Task::query()
            ->with(['options'])
            ->where('course_id', $token->course_id)
            ->get();

        if ($shuffle) {
            $tasks = $tasks->shuffle();

            if ($type === TestType::Exam) {
                $tasks = $tasks->take($token->course?->exam_questions_number ?? 25);
            }
        }

        foreach ($tasks as $task) {
            $exercise = Exercise::query()->create([
                'test_id' => $test->id,
                'task_id' => $task->id,
            ]);

            $options = $shuffle ? $task->options->shuffle() : $task->options;

            foreach ($options as $option) {
                Choice::query()->create([
                    'exercise_id' => $exercise->id,
                    'option_id' => $option->id,
                ]);
            }
        }

        DB::commit();

        if ($type === TestType::Exam) {
            return to_route('exams.show', $test->uuid);
        }

        return to_route('marathons.show', $test->uuid);
    }
}
