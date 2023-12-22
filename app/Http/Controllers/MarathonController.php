<?php

namespace App\Http\Controllers;

use App\Models\Marathon;
use App\Models\Option;
use App\Models\Task;
use App\Models\Test;
use App\Models\Token;
use Illuminate\Http\Request;

class MarathonController extends Controller
{
//    public function index(Request $request)
//    {
//        $marathons = Marathon::query()
//            ->withCount(['tasks', 'success_tasks', 'error_tasks'])
//            ->where('token_uuid', $request->cookie('guest'))
//            ->get();
//
////        dd($marathons->toArray());
//
//        return view('marathons.index', compact(['marathons']));
//    }

    public function create(Request $request)
    {
        $token = Token::query()
            ->with(['course'])
            ->firstWhere('uuid', $request->cookie('guest'));

        $tasks_count = Task::query()
            ->where('course_id', $token->course_id)
//            ->whereNull('task_id')
            ->count();

        return view('marathons.create', compact(['tasks_count', 'token']));
    }

//    public function store(Request $request)
//    {
//        $marathon = Marathon::query()->create([
//            'token_uuid' => $request->cookie('guest'),
//        ]);
//
//        $tasks = Task::query()
//            ->whereNull('task_id')
//            ->get();
//
//        if ($request->boolean('shuffle')) {
//            $tasks = $tasks->shuffle();
//        }
//
//        foreach ($tasks as $task) {
//            $newTask = $task->replicate()->fill([
//                'marathon_id' => $marathon->id,
//                'task_id' => $task->id,
//            ]);
//
//            $newTask->save();
//
//            $options = Option::query()
//                ->where('task_id', $task->id)
//                ->get();
//
//            if ($request->boolean('shuffle')) {
//                $options = $options->shuffle();
//            }
//
//            foreach ($options as $option) {
//                $option->replicate()->fill([
//                    'is_chosen' => null,
//                    'task_id' => $newTask->id,
//                ])->save();
//            }
//        }
//
//        return to_route('marathons.show', $marathon->uuid);
//    }

//    public function show(Marathon $marathon)
//    {
//        return view('marathons.show', compact(['marathon']));
//    }

    public function show(Test $test)
    {
        return view('marathons.show', compact(['test']));
    }
}
