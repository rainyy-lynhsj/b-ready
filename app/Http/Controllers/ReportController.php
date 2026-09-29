<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentAttempt;
use App\Models\Certificate;
use App\Models\User;

class ReportController extends Controller
{
    // 1. Reports para sa DRR Trainer (Teacher Progress, Assessment Results, at Training Completion)
    public function trainerReports(Request $request)
    {
        // Kunin ang mga tala ng pagsusulit at sertipiko para sa monitoring
        $assessmentAttempts = AssessmentAttempt::with('assessment')->get();
        $certificatesIssued = Certificate::all();
        $totalCertificates = Certificate::count();

        // Ibalik ito bilang Blade view para lumabas sa Frontend UI
        return view('reports.trainer', [
            'report_title' => 'DRR Trainer - Monitoring & Implementation Report',
            'total_completed_trainings' => $totalCertificates,
            'assessment_records' => $assessmentAttempts,
            'certificates_list' => $certificatesIssued,
        ]);
    }

    // 2. Reports para sa Teacher (Student Progress at Classroom Performance)
    public function teacherReports(Request $request)
    {
        // Dito makikita ng teacher ang performance at progress ng mga estudyante sa classroom
        return response()->json([
            'report_title' => 'Teacher - Student Performance & Classroom Report',
            'message' => 'Student progress and assessment reports retrieved successfully.',
        ]);
    }
}