<?php

namespace App\Policies;

use App\Models\Certification;
use App\Models\ClassroomImplementation;
use App\Models\User;
use App\Models\Workshop;

class ClassroomImplementationPolicy
{
    /**
     * Determine whether the teacher can create an implementation report for this workshop.
     */
    public function create(User $user, Workshop $workshop): bool
    {
        if ($user->role !== 'teacher') {
            return false;
        }

        // Must be certified in this workshop to conduct classroom implementations
        return Certification::where('teacher_id', $user->id)
            ->where('workshop_id', $workshop->id)
            ->exists();
    }

    /**
     * Determine whether the teacher can view this implementation.
     */
    public function view(User $user, ClassroomImplementation $implementation): bool
    {
        if ($user->role !== 'teacher') {
            return false;
        }

        return $implementation->teacher_id === $user->id;
    }
}
