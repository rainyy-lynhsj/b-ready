<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\MaterialController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Welcome Page
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

Route::middleware([
    'auth',
    'verified',
    'role:trainer'
])->group(function () {


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
    | Course Routes
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/trainer/courses',
        [CourseController::class, 'index']
    )->name('trainer.courses.index');


    Route::get(
        '/trainer/courses/create',
        [CourseController::class, 'create']
    )->name('trainer.courses.create');


    Route::post(
        '/trainer/courses',
        [CourseController::class, 'store']
    )->name('trainer.courses.store');


    Route::get(
        '/trainer/courses/{id}',
        [CourseController::class, 'show']
    )->name('trainer.courses.show');


    Route::get(
        '/trainer/courses/{id}/edit',
        [CourseController::class, 'edit']
    )->name('trainer.courses.edit');


    Route::put(
        '/trainer/courses/{id}',
        [CourseController::class, 'update']
    )->name('trainer.courses.update');


    Route::delete(
        '/trainer/courses/{id}',
        [CourseController::class, 'destroy']
    )->name('trainer.courses.destroy');


    /*
    |--------------------------------------------------------------------------
    | Module Routes
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/trainer/courses/{courseId}/modules/create',
        [ModuleController::class, 'create']
    )->name('trainer.modules.create');


    Route::post(
        '/trainer/courses/{courseId}/modules',
        [ModuleController::class, 'store']
    )->name('trainer.modules.store');


    Route::get(
        '/trainer/courses/{courseId}/modules/{moduleId}/edit',
        [ModuleController::class, 'edit']
    )->name('trainer.modules.edit');


    Route::put(
        '/trainer/courses/{courseId}/modules/{moduleId}',
        [ModuleController::class, 'update']
    )->name('trainer.modules.update');


    Route::delete(
        '/trainer/courses/{courseId}/modules/{moduleId}',
        [ModuleController::class, 'destroy']
    )->name('trainer.modules.destroy');


    /*
    |--------------------------------------------------------------------------
    | Training Material Routes
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/trainer/courses/{courseId}/modules/{moduleId}/materials/create',
        [MaterialController::class, 'create']
    )->name('trainer.materials.create');


    Route::post(
        '/trainer/courses/{courseId}/modules/{moduleId}/materials',
        [MaterialController::class, 'store']
    )->name('trainer.materials.store');


    Route::get(
        '/trainer/courses/{courseId}/modules/{moduleId}/materials/{materialId}/edit',
        [MaterialController::class, 'edit']
    )->name('trainer.materials.edit');


    Route::put(
        '/trainer/courses/{courseId}/modules/{moduleId}/materials/{materialId}',
        [MaterialController::class, 'update']
    )->name('trainer.materials.update');


    Route::delete(
        '/trainer/courses/{courseId}/modules/{moduleId}/materials/{materialId}',
        [MaterialController::class, 'destroy']
    )->name('trainer.materials.destroy');

});


/*
|--------------------------------------------------------------------------
| Teacher Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
    'role:teacher'
])->group(function () {

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

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';