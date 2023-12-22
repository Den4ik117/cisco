<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskType;
use App\Enums\TestType;
use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Exercise;
use App\Models\Marathon;
use App\Models\Option;
use App\Models\Task;
use App\Models\Test;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function index(Marathon $marathon): JsonResponse
    {
        $marathon->load(['tasks', 'tasks.options', 'tasks.task']);

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

    public function update(Request $request, Marathon $marathon, Task $task): JsonResponse
    {
        $token = Token::query()->firstWhere('uuid', $request->cookie('guest'));

        if (in_array($task->task->type, [TaskType::OneAnswer, TaskType::MultipleAnswers])) {
            $validated = $request->validate([
                'answers' => 'required|array|min:1',
                'answers.*' => 'required|integer|min:1',
            ]);

            $options = Option::query()
                ->where('task_id', $task->id)
                ->whereIn('id', $validated['answers'])
                ->get();

            $options->toQuery()->update([
                'is_chosen' => true,
            ]);

            $task->load(['options']);

            $isSuccess = $task->options->every(fn (Option $option) => (!!$option->is_answer) === (!!$option->is_chosen));
        } else {
            $validated = $request->validate([
                'answers' => 'required|array|size:1',
                'answers.*' => 'required|string|max:255',
            ]);

            $options = Option::query()
                ->where('task_id', $task->id)
                ->where('name', $validated['answers'][0])
                ->get();

            if ($options->isNotEmpty()) {
                $options->toQuery()->update([
                    'is_chosen' => true,
                ]);
            }

            $task->load(['options']);

            $isSuccess = $options->isNotEmpty();
        }

        $task->update([
            'is_success' => $isSuccess,
        ]);

        if (!$isSuccess) {
            $mistakeTest = Test::query()->firstOrCreate([
                'token_uuid' => $token->uuid,
                'course_id' => $token->course_id,
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

        if ($task->marathon_id === $marathon->id) {
            $marathon->update([
                'last_task_id' => $task->id,
            ]);
        }

        return response()->json(['data' => $task]);
    }
}
