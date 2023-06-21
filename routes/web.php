<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\MarathonController;
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
    Route::get('/', [IndexController::class, 'index']);

    Route::post('/marathons', [MarathonController::class, 'store'])->name('marathons.store');
    Route::get('/marathons/{marathon:uuid}', [MarathonController::class, 'show'])->name('marathons.show');
});
