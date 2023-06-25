<?php

namespace App\Http\Controllers\Api;

use App\Enums\TestType;
use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Exercise;
use App\Models\Marathon;
use App\Models\Option;
use App\Models\Task;
use App\Models\Test;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(Marathon $marathon): JsonResponse
    {
        $marathon->load(['tasks', 'tasks.options']);

        return response()->json(['data' => $marathon->tasks]);
    }

    public function store(Marathon $marathon, Task $task): Response
    {
        if ($task->marathon_id === $marathon->id) {
            $marathon->update([
                'last_task_id' => $task->id,
            ]);
        }

        return response()->noContent();
    }

    public function update(Request $request, $marathon, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*' => 'required|integer|min:1',
        ]);

        foreach ($validated['answers'] as $answerID) {
            $option = Option::query()
                ->where('task_id', $task->id)
                ->where('id', $answerID)
                ->firstOrFail();

            $option->update([
                'is_chosen' => true,
            ]);
        }

        $task->load(['options']);

        $isSuccess = $task->options->every(fn (Option $option) => (!!$option->is_answer) === (!!$option->is_chosen));

        $task->update([
            'is_success' => $isSuccess,
        ]);

        if (!$isSuccess) {
            $mistakeTest = Test::query()->firstOrCreate([
                'token_uuid' => $request->cookie('guest'),
                'type' => TestType::Mistake->value,
            ]);

            $mistakeExercise = Exercise::query()->create([
                'test_id' => $mistakeTest->id,
                'task_id' => $task->task_id,
            ]);

            $options = $task->options->shuffle();

            foreach ($options as $option) {
                Choice::query()->create([
                    'exercise_id' => $mistakeExercise->id,
                    'option_id' => $option->id,
                ]);
            }
        }

        return response()->json(['data' => $task]);
    }
}
