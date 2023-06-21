<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TaskResource;
use App\Models\Marathon;
use App\Models\Option;
use App\Models\Task;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function index(Request $request, Marathon $marathon): AnonymousResourceCollection
    {
        $marathon->load(['tasks', 'tasks.options']);

        return TaskResource::collection($marathon->tasks);
    }

    public function update(Request $request, $marathon, Task $task)
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

        $task->is_success = $task->options->every(fn (Option $option) => (!!$option->is_answer) === (!!$option->is_chosen));
        $task->save();

        return new TaskResource($task);
    }
}
