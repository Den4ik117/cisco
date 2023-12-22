<?php

namespace App\Http\Controllers;

use App\Enums\TaskType;
use App\Enums\TestType;
use App\Models\Choice;
use App\Models\Exercise;
use App\Models\Option;
use App\Models\Task;
use App\Models\Test;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExerciseController extends Controller
{
    public function index(Test $test): JsonResponse
    {
        $test->load(['exercises', 'exercises.task', 'exercises.choices', 'exercises.choices.option']);

        return response()->json(['data' => $test->exercises]);
    }

    public function store(Test $test, Exercise $exercise): Response
    {
        if ($exercise->test_id === $test->id) {
            $test->update([
                'last_exercise_id' => $exercise->id,
            ]);
        }

        return response()->noContent();
    }

    public function update(Request $request, Test $test, Exercise $exercise): JsonResponse
    {
        $exercise->load(['task']);

        $token = Token::query()->firstWhere('uuid', $request->cookie('guest'));

        if (in_array($exercise->task->type, [TaskType::OneAnswer, TaskType::MultipleAnswers])) {
            $validated = $request->validate([
                'answers' => 'required|array|min:1',
                'answers.*' => 'required|integer|min:1',
            ]);

            $choices = Choice::query()
                ->where('exercise_id', $exercise->id)
                ->whereIn('id', $validated['answers'])
                ->get();

            $choices->toQuery()->update([
                'is_chosen' => true,
            ]);

            $isSuccess = $exercise->isSuccessful();
        } else {
            $validated = $request->validate([
                'answers' => 'required|array|size:1',
                'answers.*' => 'required|string|max:255',
            ]);

            $choices = Choice::query()
                ->where('exercise_id', $exercise->id)
                ->whereHas('option', function ($query) use ($validated) {
                    $query->where('name', $validated['answers'][0]);
                })
                ->get();

            if ($choices->isNotEmpty()) {
                $choices->toQuery()->update([
                    'is_chosen' => true,
                ]);
            }

            $isSuccess = $choices->isNotEmpty();
        }

        $exercise->load(['choices', 'choices.option']);

        $exercise->update([
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
                'task_id' => $exercise->task_id,
            ]);

            $options = Option::query()
                ->where('task_id', $exercise->task_id)
                ->get()
                ->shuffle();

            foreach ($options as $option) {
                Choice::query()->create([
                    'exercise_id' => $mistakeExercise->id,
                    'option_id' => $option->id,
                ]);
            }
        }

        if ($exercise->test_id === $test->id) {
            $test->update([
                'last_exercise_id' => $exercise->id,
            ]);
        }

        return response()->json(['data' => $exercise]);
    }
}
