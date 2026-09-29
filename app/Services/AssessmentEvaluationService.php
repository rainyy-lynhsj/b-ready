<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\AssessmentAttempt;
use App\Models\Certification;
use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopTeacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AssessmentEvaluationService
{
    /**
     * Evaluate and store a teacher's assessment attempt.
     * Automatic Scoring with Zero Manual Input Rule.
     *
     * @param User $teacher
     * @param Assessment $assessment
     * @param array<int, int> $submittedAnswers [question_id => choice_id]
     * @return array{attempt: AssessmentAttempt, certification: ?Certification, passed: bool, score: float, total_points: float, percentage: float, correct_answers: int, total_questions: int, remaining_attempts: int}
     * @throws InvalidArgumentException
     */
    public function evaluateAttempt(User $teacher, Assessment $assessment, array $submittedAnswers): array
    {
        return DB::transaction(function () use ($teacher, $assessment, $submittedAnswers) {
            // Count existing attempts for this teacher and assessment
            $attemptCount = AssessmentAttempt::where('assessment_id', $assessment->id)
                ->where(function ($q) use ($teacher) {
                    $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
                })
                ->count();

            if ($attemptCount >= $assessment->max_attempts) {
                throw new InvalidArgumentException("Maximum attempts ({$assessment->max_attempts}) reached for this assessment.");
            }

            // Load assessment questions and choices
            $questions = $assessment->questions()->with('choices')->get();
            $totalQuestions = $questions->count();

            $totalPoints = 0;
            $earnedPoints = 0;
            $correctAnswersCount = 0;
            $answersData = [];

            foreach ($questions as $question) {
                $totalPoints += (float)$question->points;
                $submittedChoiceId = $submittedAnswers[$question->id] ?? null;

                $correctChoice = $question->choices->firstWhere('is_correct', true);
                $isCorrect = false;
                $pointsEarned = 0;

                if ($submittedChoiceId && $correctChoice && (int)$submittedChoiceId === (int)$correctChoice->id) {
                    $isCorrect = true;
                    $pointsEarned = (float)$question->points;
                    $earnedPoints += $pointsEarned;
                    $correctAnswersCount++;
                }

                $answersData[] = [
                    'question_id' => $question->id,
                    'choice_id' => $submittedChoiceId ? (int)$submittedChoiceId : null,
                    'is_correct' => $isCorrect,
                    'points_earned' => $pointsEarned,
                ];
            }

            // Zero Manual Input Rule: Compute percentage score ((Correct / Total) * 100) or ((Earned / TotalPoints) * 100)
            if ($totalPoints > 0) {
                $percentage = round(($earnedPoints / $totalPoints) * 100, 2);
            } elseif ($totalQuestions > 0) {
                $percentage = round(($correctAnswersCount / $totalQuestions) * 100, 2);
            } else {
                $percentage = 0.00;
            }

            $passed = $percentage >= $assessment->passing_score;
            $result = $passed ? 'passed' : 'failed';
            $newAttemptNumber = $attemptCount + 1;
            $remainingAttempts = max(0, $assessment->max_attempts - $newAttemptNumber);

            // Create Attempt Record
            $attempt = AssessmentAttempt::create([
                'assessment_id' => $assessment->id,
                'teacher_id' => $teacher->id,
                'attempt_number' => $newAttemptNumber,
                'score' => $earnedPoints,
                'percentage' => $percentage,
                'result' => $result,
                'started_at' => now()->subMinutes(rand(10, 25)),
                'submitted_at' => now(),
            ]);

            // Save individual candidate answer entries
            foreach ($answersData as $answer) {
                AssessmentAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $answer['question_id'],
                    'choice_id' => $answer['choice_id'],
                    'is_correct' => $answer['is_correct'],
                    'points_earned' => $answer['points_earned'],
                ]);
            }

            $certification = null;

            if ($passed) {
                $workshop = $assessment->workshop;

                // Check if certification already exists for this teacher & workshop
                $certification = Certification::where(function ($q) use ($teacher) {
                        $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
                    })
                    ->where('workshop_id', $workshop->id)
                    ->first();

                if (! $certification) {
                    $certNumber = 'BRD-' . date('Y') . '-' . strtoupper(Str::random(8));

                    $certification = Certification::create([
                        'teacher_id' => $teacher->id,
                        'workshop_id' => $workshop->id,
                        'assessment_attempt_id' => $attempt->id,
                        'certificate_number' => $certNumber,
                        'badge_name' => 'DRR Certified Educator',
                        'certificate_file' => null,
                        'certified_at' => now(),
                    ]);
                }

                // Update Workshop Teacher enrollment status to 'completed'
                WorkshopTeacher::where('workshop_id', $workshop->id)
                    ->where(function ($q) use ($teacher) {
                        $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
                    })
                    ->update(['status' => 'completed']);
            }

            return [
                'attempt' => $attempt,
                'certification' => $certification,
                'passed' => $passed,
                'score' => (float)$earnedPoints,
                'total_points' => (float)$totalPoints,
                'percentage' => $percentage,
                'correct_answers' => $correctAnswersCount,
                'total_questions' => $totalQuestions,
                'remaining_attempts' => $remainingAttempts,
            ];
        });
    }
}
