<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    if (auth()->user()->role === 'trainer') {
        return app(TrainerController::class)->dashboard();
    }

    return view('teacher.dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Trainer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:trainer'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Trainer Test
    |--------------------------------------------------------------------------
    */

    Route::get('/trainer/test', function () {
        return 'Trainer access confirmed!';
    });


    /*
    |--------------------------------------------------------------------------
    | Trainer Courses
    |--------------------------------------------------------------------------
    */

    // Display all courses
    Route::get('/trainer/courses', [CourseController::class, 'index'])
        ->name('trainer.courses.index');

    // Show Create Course form
    Route::get('/trainer/courses/create', [CourseController::class, 'create'])
        ->name('trainer.courses.create');

    // Save new course
    Route::post('/trainer/courses', [CourseController::class, 'store'])
        ->name('trainer.courses.store');

    // View specific course
    Route::get('/trainer/courses/{id}', [CourseController::class, 'show'])
        ->name('trainer.courses.show');

    // Show Edit Course form
    Route::get('/trainer/courses/{id}/edit', [CourseController::class, 'edit'])
        ->name('trainer.courses.edit');

    // Update course
    Route::put('/trainer/courses/{id}', [CourseController::class, 'update'])
        ->name('trainer.courses.update');

    // Delete course
    Route::delete('/trainer/courses/{id}', [CourseController::class, 'destroy'])
        ->name('trainer.courses.destroy');

});


/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'role:teacher'])->group(function () {

    Route::get('/teacher/test', function () {
        return 'Teacher access confirmed!';
    });

});


/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';