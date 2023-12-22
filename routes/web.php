<?php

use App\Http\Controllers\BaseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MarathonController;
use App\Http\Controllers\TestController;
use App\Http\Middleware\EnsureCourseIsChosen;
use App\Http\Middleware\EnsureHasGuestToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(EnsureHasGuestToken::class)->group(function () {
    Route::get('/choose-course', [CourseController::class, 'create'])->name('courses.choose');
    Route::post('/choose-course', [CourseController::class, 'store']);
});

Route::middleware(EnsureCourseIsChosen::class)->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');

//    Route::get('/marathons', [MarathonController::class, 'index'])->name('marathons.index');
    Route::get('/marathons/create', [MarathonController::class, 'create'])->name('marathons.create');
//    Route::post('/marathons', [MarathonController::class, 'store'])->name('marathons.store');
//    Route::get('/marathons/{marathon:uuid}', [MarathonController::class, 'show'])->name('marathons.show');
    Route::get('/marathons/{test:uuid}', [MarathonController::class, 'show'])->name('marathons.show');

//    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{test:uuid}', [ExamController::class, 'show'])->name('exams.show');

    Route::get('/api/tests/{test:uuid}/exercises', [ExerciseController::class, 'index']);
    Route::post('/api/tests/{test:uuid}/exercises/{exercise}', [ExerciseController::class, 'store']);
    Route::patch('/api/tests/{test:uuid}/exercises/{exercise}', [ExerciseController::class, 'update']);

    Route::get('/testings', [TestController::class, 'index'])->name('tests.index');
    Route::post('/testings', [TestController::class, 'store'])->name('tests.store');

    Route::get('/base', [BaseController::class, 'index'])->name('base.index');
});
