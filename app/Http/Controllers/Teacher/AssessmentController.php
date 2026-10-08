<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreAssessmentAttemptRequest;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Certification;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\Workshop;
use App\Models\WorkshopTeacher;
use App\Services\AssessmentEvaluationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    public function __construct(
        protected AssessmentEvaluationService $evaluationService
    ) {}

    /**
     * Display the assessment questionnaire or gating screen.
     * Unlocking Rule: Evaluate if all required modules have module_progress.status == 'completed' (100% completion).
     */
    public function show(Request $request, Workshop $workshop): View|RedirectResponse
    {
        $teacher = $request->user();
        $assessment = $workshop->assessment;

        if (! $assessment) {
            abort(404, "No assessment is assigned to this workshop.");
        }

        // Must enroll in workshop first before taking assessment
        $isEnrolled = WorkshopTeacher::where('workshop_id', $workshop->id)
            ->where(function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
            })
            ->whereIn('status', ['registered', 'completed'])
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('teacher.workshops.show', $workshop)
                ->with('warning', "You must register for {$workshop->title} and complete all course modules before attempting the final assessment.");
        }

        Gate::authorize('view', $assessment);

        // Check if already certified
        $certification = Certification::where(function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
            })
            ->where('workshop_id', $workshop->id)
            ->first();

        // Check attempt count and past attempts
        $pastAttempts = AssessmentAttempt::where('assessment_id', $assessment->id)
            ->where(function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
            })
            ->latest('attempt_number')
            ->get();

        $attemptCount = $pastAttempts->count();
        $attemptsLeft = max(0, $assessment->max_attempts - $attemptCount);

        // Check module completion status against ordered modules
        $modules = $workshop->ordered_modules;
        $requiredModules = $modules->where('is_required', true);
        $totalRequiredCount = $requiredModules->count();

        $completedCount = 0;
        if ($totalRequiredCount > 0) {
            $completedCount = ModuleProgress::where('workshop_id', $workshop->id)
                ->where(function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
                })
                ->whereIn('module_id', $requiredModules->pluck('id'))
                ->where('status', 'completed')
                ->count();
        }

        // Unlocking rule: all required modules must be 100% completed
        $allModulesCompleted = ($totalRequiredCount === 0) || ($completedCount >= $totalRequiredCount);
        $canAttempt = Gate::allows('attempt', $assessment) && $allModulesCompleted && $attemptsLeft > 0 && ! $certification;

        // Load questions with randomized choices if ready to take
        $questions = collect();
        if ($canAttempt) {
            $questions = $assessment->questions()
                ->with(['choices' => fn($q) => $q->inRandomOrder()])
                ->orderBy('sequence')
                ->get();
        }

        return view('teacher.assessments.show', compact(
            'workshop',
            'assessment',
            'canAttempt',
            'allModulesCompleted',
            'completedCount',
            'totalRequiredCount',
            'attemptCount',
            'attemptsLeft',
            'pastAttempts',
            'certification',
            'questions'
        ));
    }

    /**
     * Process exam submission and evaluate grading with Zero Manual Input Rule.
     */
    public function submit(StoreAssessmentAttemptRequest $request, Workshop $workshop): RedirectResponse
    {
        $teacher = $request->user();
        $assessment = $workshop->assessment;

        if (! $assessment) {
            abort(404, "No assessment found.");
        }

        Gate::authorize('attempt', $assessment);

        $answers = $request->validated('answers');

        $result = $this->evaluationService->evaluateAttempt($teacher, $assessment, $answers);

        $attempt = $result['attempt'];

        if ($result['passed']) {
            return redirect()->route('teacher.assessments.result', [$workshop, $attempt])
                ->with('success', "Outstanding! You scored {$result['percentage']}% ({$result['correct_answers']}/{$result['total_questions']} correct) and PASSED the final assessment! Your certification has been issued and the Workshop Repository is now UNLOCKED.");
        }

        $remaining = $result['remaining_attempts'];
        $message = $remaining > 0
            ? "You scored {$result['percentage']}%. Passing mark is {$assessment->passing_score}%. You have {$remaining} attempt(s) remaining."
            : "You scored {$result['percentage']}%. Passing mark is {$assessment->passing_score}%. Maximum attempts reached. The assessment is now locked.";

        return redirect()->route('teacher.assessments.result', [$workshop, $attempt])
            ->with('warning', $message);
    }

    /**
     * Display instant assessment results with score feedback.
     */
    public function result(Request $request, Workshop $workshop, AssessmentAttempt $attempt): View
    {
        $teacher = $request->user();

        $attemptTeacherId = $attempt->teacher_id ?? $attempt->user_id;
        if ((int)$attemptTeacherId !== (int)$teacher->id || (int)$attempt->assessment->workshop_id !== (int)$workshop->id) {
            abort(403, "Unauthorized attempt record.");
        }

        $attempt->load(['answers.question.choices', 'assessment']);
        $certification = Certification::where(function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
            })
            ->where('workshop_id', $workshop->id)
            ->first();

        $attemptsLeft = max(0, $attempt->assessment->max_attempts - $attempt->attempt_number);

        return view('teacher.assessments.result', compact(
            'workshop',
            'attempt',
            'certification',
            'attemptsLeft'
        ));
    }

    /**
     * Render the printable, high-resolution digital certificate view.
     */
    public function certificate(Request $request, Workshop $workshop): View
    {
        $teacher = $request->user();

        $certification = Certification::where(function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
            })
            ->where('workshop_id', $workshop->id)
            ->with(['workshop.trainer', 'assessmentAttempt'])
            ->firstOrFail();

        return view('teacher.assessments.certificate', compact('workshop', 'certification', 'teacher'));
    }
}
