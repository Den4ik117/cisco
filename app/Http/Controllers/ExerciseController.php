<?php

namespace App\Http\Controllers;

use App\Enums\TaskType;
use App\Models\Choice;
use App\Models\Exercise;
use App\Models\Test;
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
        $validated = $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*' => 'required|integer|min:1',
        ]);

        $exercise->load(['task']);

        if (in_array($exercise->task->type, [TaskType::MultipleAnswers, TaskType::OneAnswer])) {
            $choices = Choice::query()
                ->where('exercise_id', $exercise->id)
                ->whereIn('id', $validated['answers'])
                ->get();

            $choices->toQuery()->update([
                'is_chosen' => true,
            ]);
        }

        $exercise->load(['choices', 'choices.option']);

        $exercise->update([
            'is_success' => $exercise->isSuccessful(),
        ]);

        return response()->json(['data' => $exercise]);
    }
}
