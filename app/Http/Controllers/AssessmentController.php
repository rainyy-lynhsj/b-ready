<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Certificate;
use Illuminate\Support\Str;

class AssessmentController extends Controller
{
    // Ipakita ang listahan ng mga assessments sa dashboard
    public function index()
    {
        // Awtomatikong lumikha ng sample assessment kung wala pang laman ang database
        if (Assessment::count() === 0) {
            Assessment::create([
                'title' => 'B-READY Final Assessment',
                'passing_score' => 75,
                'number_of_attempts' => 3,
            ]);
        }

        $assessments = Assessment::all();
        return view('assessments.index', compact('assessments'));
    }

    // Mag-save ng bagong assessment (Gawa ng Trainer)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'passing_score' => 'required|integer',
            'number_of_attempts' => 'required|integer',
        ]);

        $assessment = Assessment::create([
            'title' => $request->title,
            'passing_score' => $request->passing_score,
            'time_limit' => $request->time_limit,
            'number_of_attempts' => $request->number_of_attempts,
        ]);

        return response()->json(['message' => 'Assessment created successfully!', 'data' => $assessment]);
    }

    // Pag-submit at pag-check ng score ng Test
    public function submitAssessment(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('assessment.take', $id);
        }

        $assessment = Assessment::firstOrCreate(
            ['id' => $id],
            [
                'title' => 'B-READY Final Assessment',
                'passing_score' => 75,
                'number_of_attempts' => 3
            ]
        );

        $user = auth()->user();
        $score = $request->score;
        $passingScore = $assessment->passing_score;

        $status = $score >= $passingScore ? 'Passed' : 'Failed';

        $attempt = AssessmentAttempt::create([
            'assessment_id' => $assessment->id,
            'user_id' => $user->id ?? 1,
            'score' => $score,
            'status' => $status,
        ]);

        $certificate = null;
        if ($status === 'Passed') {
            $certificate = Certificate::where('user_id', $user->id ?? 1)
                                       ->where('assessment_id', $assessment->id)
                                       ->first();

            if (!$certificate) {
                $certificate = Certificate::create([
                    'user_id' => $user->id ?? 1,
                    'assessment_id' => $assessment->id,
                    'certificate_code' => 'BREADY-CERT-' . strtoupper(Str::random(8)),
                    'issued_at' => now(),
                ]);
            }
        }

        return view('assessments.result', compact('assessment', 'score', 'status', 'certificate'));
    }
}