<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'trainer'){
        return view('trainer.dashboard');
    }

    return redirect()->route('teacher.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:trainer'])->group(function () {
    Route::get('/trainer/test', function () {
        return 'Trainer access confirmed!';
    });
});

Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    // Personal Reports Dashboard (Aggregating Progress, Assessments, Certifications, Implementations)
    Route::get('/reports', [\App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('reports.index');

    // Workshop Discovery & Enrollment
    Route::get('/workshops', [\App\Http\Controllers\Teacher\WorkshopController::class, 'index'])->name('workshops.index');
    Route::get('/my-workshops', [\App\Http\Controllers\Teacher\WorkshopController::class, 'myWorkshops'])->name('workshops.my');
    Route::get('/workshops/{workshop}', [\App\Http\Controllers\Teacher\WorkshopController::class, 'show'])->name('workshops.show');
    Route::post('/workshops/{workshop}/join', [\App\Http\Controllers\Teacher\WorkshopController::class, 'join'])->name('workshops.join');

    // Sequential Learning & Modules
    Route::get('/workshops/{workshop}/modules/{module}', [\App\Http\Controllers\Teacher\LearningController::class, 'show'])->name('learning.module');
    Route::post('/workshops/{workshop}/modules/{module}/complete', [\App\Http\Controllers\Teacher\LearningController::class, 'complete'])->name('learning.module.complete');
    Route::get('/workshops/{workshop}/modules/{module}/materials/{material}', [\App\Http\Controllers\Teacher\LearningController::class, 'downloadMaterial'])->name('learning.material.download');
    Route::post('/workshops/{workshop}/modules/{module}/materials/{material}/interact', [\App\Http\Controllers\Teacher\LearningController::class, 'interactMaterial'])->name('learning.material.interact');

    // Assessments & Certification
    Route::get('/workshops/{workshop}/assessment', [\App\Http\Controllers\Teacher\AssessmentController::class, 'show'])->name('assessments.show');
    Route::post('/workshops/{workshop}/assessment', [\App\Http\Controllers\Teacher\AssessmentController::class, 'submit'])->name('assessments.submit');
    Route::get('/workshops/{workshop}/assessment/attempts/{attempt}', [\App\Http\Controllers\Teacher\AssessmentController::class, 'result'])->name('assessments.result');
    Route::get('/workshops/{workshop}/certificate', [\App\Http\Controllers\Teacher\AssessmentController::class, 'certificate'])->name('assessments.certificate');

    // Classroom Packages (Guarded: must be certified to download/preview)
    Route::get('/packages', [\App\Http\Controllers\Teacher\ClassroomPackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/{package}', [\App\Http\Controllers\Teacher\ClassroomPackageController::class, 'show'])->name('packages.show');
    Route::get('/packages/{package}/materials/{material}', [\App\Http\Controllers\Teacher\ClassroomPackageController::class, 'downloadMaterial'])->middleware('certified.teacher')->name('packages.material.download');
    Route::get('/packages/{package}/materials/{material}/preview', [\App\Http\Controllers\Teacher\ClassroomPackageController::class, 'previewMaterial'])->middleware('certified.teacher')->name('packages.material.preview');

    // Classroom Implementation & Student Results (Guarded: must be certified to conduct implementations)
    Route::get('/implementations', [\App\Http\Controllers\Teacher\ImplementationController::class, 'index'])->name('implementations.index');
    Route::get('/implementations/create', [\App\Http\Controllers\Teacher\ImplementationController::class, 'create'])->name('implementations.create');
    Route::post('/implementations', [\App\Http\Controllers\Teacher\ImplementationController::class, 'store'])->middleware('certified.teacher')->name('implementations.store');
    Route::get('/implementations/{implementation}', [\App\Http\Controllers\Teacher\ImplementationController::class, 'show'])->name('implementations.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
