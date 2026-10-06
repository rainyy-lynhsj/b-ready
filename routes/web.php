<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Teacher\AssessmentController;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Choice;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard redirection batay sa role
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'trainer'){
        return view('trainer.dashboard');
    }

    return redirect()->route('teacher.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// ==========================================
// TRAINER ASSESSMENT ROUTES (CSV Upload & Take Assessment)
// ==========================================

// 1. Ruta para sa pagpapakita ng Upload Form (GET)
Route::get('/trainer/assessments/create', function () {
    return view('trainer.create');
});

// 2. Ruta para i-save ang in-upload na CSV (POST)
Route::post('/trainer/assessments/store', function (Request $request) {
    if ($request->hasFile('assessment_file')) {
        $file = $request->file('assessment_file');
        
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        $sequenceNumber = 1;

        foreach ($data as $index => $row) {
            if ($index === 0 || empty($row[0])) continue; 
            
            $question = new Question();
            $question->assessment_id = 1;
            $question->question_text = trim($row[0]);
            $question->sequence = $sequenceNumber++;
            $question->save();

            for ($i = 1; $i <= 4; $i++) {
                if (isset($row[$i]) && trim($row[$i]) !== '') {
                    $isCorrect = (isset($row[5]) && trim($row[5]) == trim($row[$i])) ? 1 : 0;

                    $choice = new Choice();
                    $choice->question_id = $question->id;
                    $choice->choice_text = trim($row[$i]);
                    $choice->is_correct = $isCorrect;
                    $choice->sequence = $i;
                    $choice->save();
                }
            }
        }

       return redirect('/trainer/assessments');
    }
    
    dd($request->all());
});

// 3. Ruta para makita ang listahan ng mga na-upload na tanong (GET)
Route::get('/trainer/assessments', function () {
    $questions = Question::with('choices')->get();
    return view('trainer.index', compact('questions'));
});

// 4. Ruta para mag-take ng assessment ang teacher/trainer (GET)
Route::get('/trainer/take-assessment', function () {
    $questions = Question::with('choices')->get();
    return view('trainer.take-assessment', compact('questions'));
});

// 5. Ruta para i-grade at ipakita agad ang score (POST)
Route::post('/trainer/grade-assessment', function (Request $request) {
    $answers = $request->input('answers', []); 
    $score = 0;
    $totalQuestions = count($answers);
    $detailedResults = [];

    foreach ($answers as $questionId => $choiceId) {
        $choice = Choice::find($choiceId);
        $question = Question::find($questionId);

        $isCorrect = $choice && $choice->is_correct;
        if ($isCorrect) {
            $score++;
        }

        $detailedResults[] = [
            'question' => $question->question_text ?? '',
            'selected_choice' => $choice->choice_text ?? '',
            'is_correct' => $isCorrect
        ];
    }

    return view('trainer.score-result', compact('score', 'totalQuestions', 'detailedResults'));
});


// ==========================================
// TEACHER MODULE ROUTES
// ==========================================
Route::middleware(['auth', 'verified', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');

    // Personal Reports Dashboard
    Route::get('/reports', [\App\Http\Controllers\Teacher\ReportController::class, 'index'])->name('reports.index');

    // Workshop Discovery & Enrollment
    Route::get('/workshops', [\App\Http\Controllers\Teacher\WorkshopController::class, 'index'])->name('workshops.index');
    Route::get('/my-workshops', [\App\Http\Controllers\Teacher\WorkshopController::class, 'myWorkshops'])->name('workshops.my');
    Route::get('/workshops/{workshop}', [\App\Http\Controllers\Teacher\WorkshopController::class, 'show'])->name('workshops.show');
    Route::post('/workshops/{workshop}/join', [\App\Http\Controllers\Teacher\WorkshopController::class, 'join'])->name('workshops.join');
    Route::post('/workshops/{workshop}/enroll', [\App\Http\Controllers\Teacher\WorkshopController::class, 'join'])->name('workshops.enroll');

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

    // Classroom Packages
    Route::get('/packages', [\App\Http\Controllers\Teacher\ClassroomPackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/{package}', [\App\Http\Controllers\Teacher\ClassroomPackageController::class, 'show'])->name('packages.show');
    Route::get('/packages/{package}/materials/{material}', [\App\Http\Controllers\Teacher\ClassroomPackageController::class, 'downloadMaterial'])->middleware('certified.teacher')->name('packages.material.download');
    Route::get('/packages/{package}/materials/{material}/preview', [\App\Http\Controllers\Teacher\ClassroomPackageController::class, 'previewMaterial'])->middleware('certified.teacher')->name('packages.material.preview');

    // Classroom Implementation & Student Results
    Route::get('/implementations', [\App\Http\Controllers\Teacher\ImplementationController::class, 'index'])->name('implementations.index');
    Route::get('/implementations/create', [\App\Http\Controllers\Teacher\ImplementationController::class, 'create'])->name('implementations.create');
    Route::post('/implementations', [\App\Http\Controllers\Teacher\ImplementationController::class, 'store'])->middleware('certified.teacher')->name('implementations.store');
    Route::get('/implementations/{implementation}', [\App\Http\Controllers\Teacher\ImplementationController::class, 'show'])->name('implementations.show');
});


// ==========================================
// PROFILE & AUTH ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';