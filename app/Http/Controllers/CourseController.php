<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Token;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function create()
    {
        $courses = Course::query()
            ->latest()
            ->get();

        return view('courses.create', compact(['courses']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
        ]);

        $token = Token::query()->firstWhere('uuid', $request->cookie('guest'));

        $token->update([
            'course_id' => $validated['course_id'],
        ]);

        return to_route('index');
    }
}
