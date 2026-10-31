<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\Workshop;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    /**
     * Display the trainer's workshops.
     */
    public function index()
    {
        $workshops = Workshop::where('trainer_id', auth()->id())
            ->with(['course', 'modules'])
            ->latest()
            ->get();

        return view('trainer.workshops.index', compact('workshops'));
    }

    /**
     * Show the Create Workshop form.
     */
    public function create()
    {
        $courses = Course::where('trainer_id', auth()->id())
            ->where('status', 'published')
            ->orderBy('title')
            ->get();

        return view('trainer.workshops.create', compact('courses'));
    }

    /**
     * Store a newly created workshop.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'start_date' => [
                'required',
                'date',
                'after:now',
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
            ],
            'registration_deadline' => [
                'required',
                'date',
                'before:start_date',
            ],
        ]);

        // Only allow the trainer to use their own published courses.
        Course::where('trainer_id', auth()->id())
            ->where('status', 'published')
            ->findOrFail($validated['course_id']);

        $validated['trainer_id'] = auth()->id();

        Workshop::create($validated);

        return redirect()
            ->route('trainer.workshops.index')
            ->with('success', 'Workshop created successfully.');
    }

    
    /**
     * Show modules available for a workshop.
     */
    public function editModules($workshop)
    {
        $workshop = Workshop::where('trainer_id', auth()->id())
            ->findOrFail($workshop);

        $modules = Module::where('course_id', $workshop->course_id)
            ->orderBy('sequence')
            ->get();

        $selectedModuleIds = $workshop->modules()
            ->pluck('modules.id')
            ->toArray();

        return view(
            'trainer.workshops.modules',
            compact('workshop', 'modules', 'selectedModuleIds')
        );
    }

    /**
     * Save the modules assigned to a workshop.
     */
    public function updateModules(Request $request, $workshop)
    {
        $workshop = Workshop::where('trainer_id', auth()->id())
            ->findOrFail($workshop);

        $validated = $request->validate([
            'modules' => ['nullable', 'array'],
            'modules.*' => [
                'integer',
                'distinct',
                'exists:modules,id',
            ],
        ]);

        $moduleIds = $validated['modules'] ?? [];

        // Only accept modules from this workshop's course.
        $validModuleIds = Module::where('course_id', $workshop->course_id)
            ->whereIn('id', $moduleIds)
            ->pluck('id')
            ->toArray();

        if (count($validModuleIds) !== count($moduleIds)) {
            abort(403, 'One or more modules do not belong to this workshop course.');
        }

        // Keep the sequence defined in the course.
        $syncData = [];

        foreach ($moduleIds as $moduleId) {
            $module = Module::findOrFail($moduleId);
            $syncData[$moduleId] = [
                'sequence' => $module->sequence,
            ];
        }

        $workshop->modules()->sync($syncData);

        return redirect()
            ->route('trainer.workshops.index')
            ->with('success', 'Workshop modules updated successfully.');
    }

}
