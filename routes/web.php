<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileServerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'trainer'){
        return view('trainer.dashboard');
    }

    return view('teacher.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:trainer'])->group(function () {
    Route::get('/trainer/test', function () {
        return 'Trainer access confirmed!';
    });
});

Route::middleware(['auth', 'verified', 'role:teacher'])->group(function () {
    Route::get('/teacher/test', function () {
        return 'Teacher access confirmed!';
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/file-server', [FileServerController::class, 'index'])->name('file-server.index');
Route::post('/file-server', [FileServerController::class, 'store'])->name('file-server.store');

require __DIR__.'/auth.php';
