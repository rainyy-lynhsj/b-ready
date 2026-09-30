<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\Workshop;
use App\Models\WorkshopTeacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LearningController extends Controller
{
    /**
     * Display the sequential module viewer with material preview and accordion list.
     * Enforces Strict Sequencing Rule: If Module N-1 status != 'completed', mark Module N as Locked.
     */
    public function show(Request $request, Workshop $workshop, Module $module): View|RedirectResponse
    {
        $teacher = $request->user();

        // Must enroll first to take the course and access modules
        $isEnrolled = WorkshopTeacher::where('workshop_id', $workshop->id)
            ->where(function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
            })
            ->whereIn('status', ['registered', 'completed'])
            ->exists();

        if (! $isEnrolled) {
            return redirect()->route('teacher.workshops.show', $workshop)
                ->with('warning', "You must enroll in {$workshop->title} first before accessing course modules.");
        }

        Gate::authorize('view', [$module, $workshop]);

        $workshop->load(['trainer', 'course.modules.materials']);
        $allModules = $workshop->ordered_modules->sortBy('sequence')->values();

        // Progress records for all modules in this workshop
        $progressList = ModuleProgress::where('workshop_id', $workshop->id)
            ->where(function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id)->orWhere('user_id', $teacher->id);
            })
            ->get()
            ->keyBy('module_id');

        $completedModuleIds = $progressList->where('status', 'completed')->pluck('module_id')->toArray();

        // Mark current module in_progress if not already started
        $currentProgress = ModuleProgress::firstOrCreate(
            [
                'workshop_id' => $workshop->id,
                'module_id' => $module->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'user_id' => $teacher->id,
                'progress' => 25,
                'status' => 'in_progress',
                'started_at' => now(),
            ]
        );

        if ($currentProgress->status === 'not_started') {
            $currentProgress->update([
                'status' => 'in_progress',
                'progress' => max((int)$currentProgress->progress, 25),
                'started_at' => $currentProgress->started_at ?? now(),
            ]);
        }

        // Map sequential unlock state for each module strictly:
        // Module N cannot be accessed if Module N-1 status != 'completed'
        $moduleNav = $allModules->map(function ($mod, $index) use ($allModules, $completedModuleIds, $progressList, $module) {
            $isCurrent = $mod->id === $module->id;
            $prog = $progressList->get($mod->id);
            $isCompleted = in_array($mod->id, $completedModuleIds);
            $isInProgress = $prog && $prog->status === 'in_progress';
            $progressPercentage = $prog ? (int)$prog->progress : ($isCompleted ? 100 : 0);

            // Strict Sequencing Rule:
            // Module 0 (or sequence <= 1) is unlocked.
            // For index > 0, preceding module (N-1) must have status == 'completed'
            $isUnlocked = false;
            if ($index === 0 || $mod->sequence <= 1) {
                $isUnlocked = true;
            } else {
                $precedingModule = $allModules->get($index - 1);
                $isUnlocked = $precedingModule && in_array($precedingModule->id, $completedModuleIds);
            }

            return (object) [
                'id' => $mod->id,
                'title' => $mod->title,
                'sequence' => $mod->sequence,
                'duration' => $mod->estimated_duration ?? 30,
                'is_current' => $isCurrent,
                'is_unlocked' => $isUnlocked,
                'is_completed' => $isCompleted,
                'is_in_progress' => $isInProgress,
                'progress_percentage' => $progressPercentage,
            ];
        });

        // Determine Next and Previous modules in workshop sequence
        $currentIndex = $allModules->search(fn($m) => $m->id === $module->id);
        $previousModule = ($currentIndex !== false && $currentIndex > 0) ? $allModules->get($currentIndex - 1) : null;
        $nextModule = ($currentIndex !== false && $currentIndex < $allModules->count() - 1) ? $allModules->get($currentIndex + 1) : null;

        // Check if all modules are completed
        $totalRequired = $allModules->where('is_required', true)->count();
        $totalCompleted = count(array_intersect($allModules->where('is_required', true)->pluck('id')->toArray(), $completedModuleIds));
        $allCompleted = $totalRequired > 0 && $totalCompleted >= $totalRequired;

        return view('teacher.modules.show', compact(
            'workshop',
            'module',
            'moduleNav',
            'currentProgress',
            'previousModule',
            'nextModule',
            'allCompleted'
        ));
    }

    /**
     * Mark a module as 100% completed and advance to the next module.
     */
    public function complete(Request $request, Workshop $workshop, Module $module): RedirectResponse
    {
        $teacher = $request->user();
        Gate::authorize('complete', [$module, $workshop]);

        ModuleProgress::updateOrCreate(
            [
                'workshop_id' => $workshop->id,
                'module_id' => $module->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'user_id' => $teacher->id,
                'progress' => 100,
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        // Find next sequential module in workshop's ordered modules
        $allModules = $workshop->ordered_modules->sortBy('sequence')->values();
        $currentIndex = $allModules->search(fn($m) => $m->id === $module->id);
        $nextModule = ($currentIndex !== false && $currentIndex < $allModules->count() - 1)
            ? $allModules->get($currentIndex + 1)
            : null;

        if ($nextModule) {
            // Pre-initialize next module as unlocked
            ModuleProgress::firstOrCreate(
                [
                    'workshop_id' => $workshop->id,
                    'module_id' => $nextModule->id,
                    'teacher_id' => $teacher->id,
                ],
                [
                    'user_id' => $teacher->id,
                    'progress' => 0,
                    'status' => 'not_started',
                ]
            );

            return redirect()->route('teacher.learning.module', [$workshop, $nextModule])
                ->with('success', "Module {$module->sequence} completed (100%)! Proceeding to Module {$nextModule->sequence}.");
        }

        // All modules completed
        return redirect()->route('teacher.workshops.show', $workshop)
            ->with('success', "Congratulations! You have completed all learning modules (100%). The Final Assessment is now unlocked!");
    }

    /**
     * Track interaction with a material (PDF, presentation, video).
     * Rule: When a teacher interacts with materials, update module_progress.progress to 100% and status to completed.
     */
    public function interactMaterial(Request $request, Workshop $workshop, Module $module, Material $material)
    {
        $teacher = $request->user();
        Gate::authorize('view', [$module, $workshop]);

        if ($material->module_id !== $module->id) {
            abort(404, "Material not found in this module.");
        }

        // Update progress to 100% and status to completed
        $progress = ModuleProgress::updateOrCreate(
            [
                'workshop_id' => $workshop->id,
                'module_id' => $module->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'user_id' => $teacher->id,
                'progress' => 100,
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Material '{$material->title}' finished! Module progress updated to 100% and marked completed.",
                'progress' => 100,
                'status' => 'completed',
            ]);
        }

        return back()->with('success', "Material '{$material->title}' completed! Module {$module->sequence} progress updated to 100%.");
    }

    /**
     * Preview or download a training material file securely.
     * Interacting with the material marks progress to 100% and status to completed.
     */
    public function downloadMaterial(Request $request, Workshop $workshop, Module $module, Material $material)
    {
        $teacher = $request->user();
        Gate::authorize('view', [$module, $workshop]);

        if ($material->module_id !== $module->id) {
            abort(404, "Material not found in this module.");
        }

        // Update module progress to 100% and completed upon material interaction
        ModuleProgress::updateOrCreate(
            [
                'workshop_id' => $workshop->id,
                'module_id' => $module->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'user_id' => $teacher->id,
                'progress' => 100,
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );

        if ($material->external_url) {
            return redirect()->away($material->external_url);
        }

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            return Storage::disk('public')->download($material->file_path, $material->title);
        }

        // If file_path is a local path or demo fallback
        return back()->with('success', "Training resource '{$material->title}' accessed! Module progress updated to 100% (Completed).");
    }
}
