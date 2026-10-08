<x-dynamic-component component="layouts.teacher">
    <x-slot name="title">Final Assessment &bull; {{ $workshop->title }}</x-slot>
    <x-slot name="header">{{ $workshop->title }} &bull; Final Assessment</x-slot>

    <div class="space-y-6">
        <!-- Back Navigation -->
        <div>
            <a href="{{ route('teacher.workshops.show', $workshop) }}" 
               class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Workshop Overview
            </a>
        </div>

        @if ($certification)
            <!-- Already Certified State -->
            <div class="bg-white rounded-2xl border border-emerald-200 p-8 shadow-xs text-center max-w-2xl mx-auto space-y-4">
                <div class="mx-auto h-16 w-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900">You Are Already Certified!</h3>
                <p class="text-sm text-slate-600 max-w-md mx-auto">
                    You have successfully passed the final assessment for <strong>{{ $workshop->title }}</strong>. Your official certificate of completion is ready.
                </p>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs inline-block text-slate-700">
                    Certificate Number: <span class="font-bold text-slate-900">{{ $certification->certificate_number }}</span>
                    <span class="text-slate-400 mx-2">&bull;</span>
                    Date: <span class="font-semibold">{{ $certification->certified_at->format('M d, Y') }}</span>
                </div>
                <div class="pt-4 flex justify-center gap-4">
                    <a href="{{ route('teacher.assessments.certificate', $workshop) }}" target="_blank"
                       class="inline-flex items-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 text-xs font-bold shadow-xs transition-colors">
                        View Official Certificate
                    </a>
                    @if ($workshop->classroomPackage)
                        <a href="{{ route('teacher.packages.show', $workshop->classroomPackage) }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 text-xs font-bold shadow-xs transition-colors">
                            Access Teaching Package
                        </a>
                    @endif
                </div>
            </div>

        @elseif (! $allModulesCompleted)
            <!-- Gated Locked State -->
            <div class="bg-white rounded-2xl border border-amber-200/90 p-8 shadow-xs max-w-2xl mx-auto space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-amber-100 text-amber-800">
                    <div class="p-2 rounded-xl bg-amber-100 text-amber-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Assessment Gated &bull; Prerequisite Incomplete</h3>
                        <p class="text-xs text-amber-700">Complete 100% of the required learning modules to unlock this assessment.</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs space-y-2">
                    <div class="flex justify-between font-medium">
                        <span class="text-slate-600">Modules Completed:</span>
                        <span class="font-bold text-slate-800">{{ $completedCount }} of {{ $totalRequiredCount }} required modules</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $totalRequiredCount > 0 ? round(($completedCount / $totalRequiredCount) * 100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="pt-2 text-center">
                    <a href="{{ route('teacher.workshops.show', $workshop) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 text-xs font-bold shadow-xs transition-colors">
                        Return to Syllabus & Complete Modules
                    </a>
                </div>
            </div>

        @elseif ($attemptsLeft <= 0)
            <!-- Max Attempts Reached -->
            <div class="bg-white rounded-2xl border border-rose-200 p-8 shadow-xs max-w-2xl mx-auto space-y-4 text-center">
                <div class="mx-auto h-14 w-14 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Maximum Attempts Exceeded</h3>
                <p class="text-xs text-slate-600 max-w-md mx-auto">
                    You have utilized all {{ $assessment->max_attempts }} allowed attempts for this assessment. Please contact your trainer ({{ $workshop->trainer->email ?? 'DRR Trainer' }}) to request re-registration or remedial consultation.
                </p>

                <!-- Previous Attempts Table -->
                <div class="mt-4 border border-slate-200 rounded-xl overflow-hidden text-xs">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-2.5">Attempt</th>
                                <th class="p-2.5">Score</th>
                                <th class="p-2.5">Percentage</th>
                                <th class="p-2.5">Result</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pastAttempts as $att)
                                <tr>
                                    <td class="p-2.5 font-medium text-slate-800">#{{ $att->attempt_number }}</td>
                                    <td class="p-2.5 text-slate-600">{{ $att->score }}</td>
                                    <td class="p-2.5 text-slate-600">{{ $att->percentage }}%</td>
                                    <td class="p-2.5">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200">
                                            Failed
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <!-- Exam Questionnaire Interface -->
            <div class="max-w-3xl mx-auto space-y-6" x-data="{ submitModal: false }">
                <!-- Exam Instructions Banner -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">
                                Official Examination
                            </span>
                            <h2 class="text-xl font-extrabold text-slate-900 mt-2">{{ $assessment->title }}</h2>
                        </div>

                        <!-- Attempt & Score Badges -->
                        <div class="flex items-center gap-2 text-xs">
                            <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 font-semibold">
                                Attempt <strong class="text-slate-900">{{ $attemptCount + 1 }}</strong> of {{ $assessment->max_attempts }}
                            </span>
                            <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 font-semibold border border-emerald-200">
                                Passing: <strong class="text-emerald-900">{{ $assessment->passing_score }}%</strong>
                            </span>
                        </div>
                    </div>

                    <p class="mt-4 text-xs text-slate-600 leading-relaxed">
                        {{ $assessment->description ?? 'Answer all questions carefully. You must achieve at least the passing threshold to earn your official certification and unlock your teaching packages.' }}
                    </p>

                    <div class="mt-4 flex items-center gap-2 text-xs text-slate-500 font-medium">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Standard Recommended Time: <strong>{{ $assessment->time_limit }} minutes</strong> &bull; {{ $questions->count() }} Questions
                    </div>
                </div>

                <!-- Questions Form -->
                <form id="assessment-form" method="POST" action="{{ route('teacher.assessments.submit', $workshop) }}" class="space-y-6">
                    @csrf

                    @foreach ($questions as $index => $q)
                        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs space-y-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <span class="h-7 w-7 rounded-lg bg-indigo-50 text-indigo-700 font-extrabold flex items-center justify-center text-xs shrink-0 border border-indigo-100">
                                        {{ $index + 1 }}
                                    </span>
                                    <h3 class="text-sm font-bold text-slate-900 pt-0.5 leading-snug">
                                        {{ $q->question_text }}
                                    </h3>
                                </div>
                                <span class="text-[11px] font-semibold text-slate-400 shrink-0">
                                    {{ $q->points }} {{ Str::plural('point', $q->points) }}
                                </span>
                            </div>

                            <!-- Choices -->
                            <div class="space-y-2.5 pt-2 pl-10">
                                @foreach ($q->choices as $c)
                                    <label class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200/80 hover:bg-slate-50/80 cursor-pointer transition-colors has-checked:border-indigo-600 has-checked:bg-indigo-50/40">
                                        <input type="radio" 
                                               name="answers[{{ $q->id }}]" 
                                               value="{{ $c->id }}"
                                               required
                                               class="mt-0.5 text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                        <span class="text-xs font-medium text-slate-800 leading-relaxed">{{ $c->choice_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Bottom Action: Open Confirmation Modal -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between">
                        <div class="text-xs text-slate-500">
                            Make sure you have selected an answer for all {{ $questions->count() }} questions.
                        </div>

                        <button type="button" 
                                @click="submitModal = true"
                                class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs px-6 py-3 shadow-xs transition-colors">
                            Submit Assessment
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Clean Confirmation Modal -->
                    <div x-show="submitModal" 
                         class="fixed inset-0 z-50 overflow-y-auto" 
                         style="display: none;" 
                         role="dialog" 
                         aria-modal="true">
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="submitModal = false"></div>

                        <div class="flex min-h-full items-center justify-center p-4">
                            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl border border-slate-100 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 rounded-full bg-indigo-50 text-indigo-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-900">Confirm Assessment Submission</h4>
                                </div>

                                <p class="text-xs text-slate-600 leading-relaxed">
                                    Are you ready to submit your exam? This will record Attempt #{{ $attemptCount + 1 }} and immediately evaluate your answers against the {{ $assessment->passing_score }}% passing threshold.
                                </p>

                                <div class="flex items-center justify-end gap-3 pt-3">
                                    <button type="button" 
                                            @click="submitModal = false"
                                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                                        Review Answers
                                    </button>
                                    <button type="button" 
                                            onclick="document.getElementById('assessment-form').submit();"
                                            class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-xs font-bold text-white shadow-xs transition-colors">
                                        Yes, Submit Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-dynamic-component>
