<?php

namespace App\Http\Controllers;

use App\Models\Marathon;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $marathons = Marathon::query()
            ->withCount(['tasks', 'success_tasks', 'error_tasks'])
            ->where('token_uuid', $request->cookie('guest'))
            ->get();

        $tests = Test::query()
            ->withCount(['exercises', 'success_exercises', 'error_exercises'])
            ->where('token_uuid', $request->cookie('guest'))
            ->get();

        return view('tests.index', compact(['marathons', 'tests']));
    }
}
