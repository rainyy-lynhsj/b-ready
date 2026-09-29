<?php

namespace App\Policies;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\Certification;
use App\Models\ModuleProgress;
use App\Models\User;
use App\Models\WorkshopTeacher;

class AssessmentPolicy
{
    /**
     * Determine whether the teacher can view assessment info.
     */
    public function view(User $user, Assessment $assessment): bool
    {
        if ($user->role !== 'teacher') {
            return false;
        }

        return WorkshopTeacher::where('workshop_id', $assessment->workshop_id)
            ->where(function ($q) use ($user) {
                $q->where('teacher_id', $user->id)->orWhere('user_id', $user->id);
            })
            ->whereIn('status', ['registered', 'completed'])
            ->exists();
    }

    /**
     * Determine whether the teacher can attempt the assessment.
     * Unlocking Rule: Evaluate if all required modules for the workshop have module_progress.status == 'completed' (100% completion).
     */
    public function attempt(User $user, Assessment $assessment): bool
    {
        if (! $this->view($user, $assessment)) {
            return false;
        }

        if (! $assessment->is_published) {
            return false;
        }

        // Check if teacher has already certified/passed
        $alreadyCertified = Certification::where(function ($q) use ($user) {
                $q->where('teacher_id', $user->id)->orWhere('user_id', $user->id);
            })
            ->where('workshop_id', $assessment->workshop_id)
            ->exists();

        if ($alreadyCertified) {
            return false;
        }

        // Check max attempts
        $attemptCount = AssessmentAttempt::where('assessment_id', $assessment->id)
            ->where(function ($q) use ($user) {
                $q->where('teacher_id', $user->id)->orWhere('user_id', $user->id);
            })
            ->count();

        if ($attemptCount >= $assessment->max_attempts) {
            return false;
        }

        // Unlocking Rule: Evaluate if all required modules for the workshop have status == 'completed' (100% completion)
        $workshop = $assessment->workshop;
        if (! $workshop) {
            return false;
        }

        $modules = $workshop->ordered_modules;
        $requiredModules = $modules->where('is_required', true);

        if ($requiredModules->isEmpty()) {
            return true;
        }

        $completedCount = ModuleProgress::where('workshop_id', $workshop->id)
            ->where(function ($q) use ($user) {
                $q->where('teacher_id', $user->id)->orWhere('user_id', $user->id);
            })
            ->whereIn('module_id', $requiredModules->pluck('id'))
            ->where('status', 'completed')
            ->count();

        return $completedCount >= $requiredModules->count();
    }
}
