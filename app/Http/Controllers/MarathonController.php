<?php

namespace App\Http\Controllers;

use App\Models\Marathon;
use App\Models\Task;
use Illuminate\Http\Request;

class MarathonController extends Controller
{
    public function store(Request $request)
    {
        $marathon = Marathon::query()->create([
            'token_uuid' => $request->cookie('guest'),
        ]);

        $tasks = Task::with(['options'])
            ->whereNull('task_id')
            ->get();

        foreach ($tasks as $task) {
            $newTask = $task->replicate()->fill([
                'marathon_id' => $marathon->id,
                'task_id' => $task->id,
            ]);

            $newTask->save();

            foreach ($task->options as $option) {
                $option->replicate()->fill([
                    'is_chosen' => null,
                    'task_id' => $newTask->id,
                ])->save();
            }
        }

        return to_route('marathons.show', $marathon->uuid);
    }

    public function show(Marathon $marathon)
    {
        return view('marathons.show', compact(['marathon']));
    }
}
