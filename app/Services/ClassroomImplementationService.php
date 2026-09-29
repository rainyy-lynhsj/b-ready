<?php

namespace App\Services;

use App\Models\ClassroomImplementation;
use App\Models\ClassroomPackage;
use App\Models\StudentResult;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class ClassroomImplementationService
{
    /**
     * Store classroom implementation and calculate aggregated statistics from student results.
     * Zero Manual Input Rule: Automated summary calculations via database aggregation.
     *
     * @param User $teacher
     * @param array{
     *     workshop_id: int,
     *     classroom_package_id: int,
     *     implementation_date: string,
     *     teacher_reflection?: ?string,
     *     remarks?: ?string,
     *     supporting_record?: ?UploadedFile,
     *     students: array<int, array{student_identifier: string, score: numeric, total_questions?: numeric, max_score?: numeric}>
     * } $data
     * @return ClassroomImplementation
     */
    public function recordImplementation(User $teacher, array $data): ClassroomImplementation
    {
        return DB::transaction(function () use ($teacher, $data) {
            $workshop = Workshop::findOrFail($data['workshop_id']);
            $package = ClassroomPackage::findOrFail($data['classroom_package_id']);

            // Calculate student metrics dynamically
            $studentsData = $data['students'] ?? [];
            if (empty($studentsData)) {
                throw new InvalidArgumentException("At least one student result must be provided.");
            }

            $studentsParticipated = count($studentsData);
            $studentsCompleted = 0;
            $studentsPassed = 0;
            $studentsFailed = 0;

            $processedResults = [];

            foreach ($studentsData as $student) {
                $identifier = trim($student['student_identifier']);
                $score = (float)$student['score'];
                $totalQuestions = isset($student['total_questions']) && (float)$student['total_questions'] > 0
                    ? (float)$student['total_questions']
                    : (isset($student['max_score']) && (float)$student['max_score'] > 0 ? (float)$student['max_score'] : 100.0);

                $percentage = round(($score / $totalQuestions) * 100, 2);
                $isPassed = $percentage >= 70.0; // standard 70% passing threshold
                $status = $isPassed ? 'Passed' : 'Failed';
                $result = strtolower($status);

                $studentsCompleted++;
                if ($isPassed) {
                    $studentsPassed++;
                } else {
                    $studentsFailed++;
                }

                $processedResults[] = [
                    'student_identifier' => $identifier,
                    'score' => $score,
                    'total_questions' => (int)$totalQuestions,
                    'percentage' => $percentage,
                    'status' => $status,
                    'result' => $result,
                ];
            }

            // Handle file upload if present
            $supportingFilePath = null;
            if (isset($data['supporting_record']) && $data['supporting_record'] instanceof UploadedFile) {
                $supportingFilePath = $data['supporting_record']->store('implementations', 'public');
            }

            // Create implementation record with aggregated values computed from student rows
            $implementation = ClassroomImplementation::create([
                'teacher_id' => $teacher->id,
                'workshop_id' => $workshop->id,
                'classroom_package_id' => $package->id,
                'implementation_date' => $data['implementation_date'],
                'status' => 'completed',
                'students_participated' => $studentsParticipated,
                'students_completed_assessment' => $studentsCompleted,
                'students_passed' => $studentsPassed,
                'students_failed' => $studentsFailed,
                'teacher_reflection' => $data['teacher_reflection'] ?? null,
                'remarks' => $data['remarks'] ?? null,
                'supporting_record' => $supportingFilePath,
            ]);

            // Save individual student results
            foreach ($processedResults as $studentResult) {
                StudentResult::create([
                    'classroom_implementation_id' => $implementation->id,
                    'student_identifier' => $studentResult['student_identifier'],
                    'score' => $studentResult['score'],
                    'total_questions' => $studentResult['total_questions'],
                    'percentage' => $studentResult['percentage'],
                    'status' => $studentResult['status'],
                    'result' => $studentResult['result'],
                ]);
            }

            return $implementation->load(['studentResults', 'workshop', 'classroomPackage']);
        });
    }

    /**
     * Compute aggregated performance statistics dynamically via database aggregation.
     * Zero Manual Input Rule: All metrics derived from database rows.
     *
     * @param ClassroomImplementation $implementation
     * @return array{
     *     total_students: int,
     *     total_completed: int,
     *     passed_count: int,
     *     failed_count: int,
     *     passing_rate: float,
     *     average_score: float,
     *     average_percentage: float,
     *     highest_score: float,
     *     lowest_score: float
     * }
     */
    public function getAnalyticsSummary(ClassroomImplementation $implementation): array
    {
        $total = $implementation->studentResults()->count();

        if ($total === 0) {
            return [
                'total_students' => 0,
                'total_completed' => 0,
                'passed_count' => 0,
                'failed_count' => 0,
                'passing_rate' => 0.0,
                'average_score' => 0.0,
                'average_percentage' => 0.0,
                'highest_score' => 0.0,
                'lowest_score' => 0.0,
            ];
        }

        $passedCount = $implementation->studentResults()
            ->where(function ($q) {
                $q->where('status', 'Passed')->orWhere('result', 'passed');
            })
            ->count();

        $failedCount = $implementation->studentResults()
            ->where(function ($q) {
                $q->where('status', 'Failed')->orWhere('result', 'failed');
            })
            ->count();

        // Database aggregation: AVG(percentage), AVG(score)
        $avgScore = round((float)$implementation->studentResults()->avg('score'), 2);
        $avgPercentage = round((float)$implementation->studentResults()->avg('percentage'), 2);
        $highestScore = round((float)$implementation->studentResults()->max('score'), 2);
        $lowestScore = round((float)$implementation->studentResults()->min('score'), 2);
        $passingRate = round(($passedCount / $total) * 100, 2);

        return [
            'total_students' => $total,
            'total_completed' => $total,
            'passed_count' => $passedCount,
            'failed_count' => $failedCount,
            'passing_rate' => $passingRate,
            'average_score' => $avgScore,
            'average_percentage' => $avgPercentage,
            'highest_score' => $highestScore,
            'lowest_score' => $lowestScore,
        ];
    }
}
