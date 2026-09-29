<x-dynamic-component component="layouts.teacher">
    <x-slot name="title">Teacher Dashboard</x-slot>
    <x-slot name="header">Teacher Dashboard</x-slot>

    <div class="space-y-8">
        <!-- Hero Welcome Banner -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white shadow-md">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-200 border border-indigo-400/30">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span> DRR Preparedness Active
                </span>
                <h2 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Welcome back, {{ auth()->user()->name }}!
                </h2>
                <p class="mt-2 text-sm sm:text-base text-indigo-100/90 leading-relaxed">
                    Access your enrolled Disaster Risk Reduction workshops, complete sequential modules, earn your certification, and download teaching packages for your classroom.
                </p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('teacher.workshops.my') }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs sm:text-sm font-bold text-indigo-900 shadow-sm hover:bg-indigo-50 transition-colors">
                        Continue Learning
                        <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('teacher.reports.index') }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-700/80 hover:bg-indigo-700 px-4 py-2.5 text-xs sm:text-sm font-bold text-white border border-indigo-400/40 shadow-sm transition-colors">
                        <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Personal Reports Dashboard
                    </a>
                    <a href="{{ route('teacher.workshops.index') }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-900/60 hover:bg-indigo-900 px-4 py-2.5 text-xs sm:text-sm font-semibold text-indigo-200 border border-indigo-700/40 transition-colors">
                        Browse Workshops
                    </a>
                </div>
            </div>

            <!-- Background decorative element -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- 5 Metric Cards (Including Dynamic Class Average) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Metric 1: Enrolled Workshops -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Enrolled Workshops</span>
                    <div class="h-9 w-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalEnrolled }}</span>
                    <span class="text-xs text-slate-500 font-medium">({{ $totalInProgress }} active)</span>
                </div>
            </div>

            <!-- Metric 2: Completed Modules -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Modules Done</span>
                    <div class="h-9 w-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $completedModulesCount }}</span>
                    <span class="text-xs text-emerald-700 font-medium bg-emerald-50 px-1.5 py-0.5 rounded-full border border-emerald-100">Sequential</span>
                </div>
            </div>

            <!-- Metric 3: Certifications Issued -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Certificates</span>
                    <div class="h-9 w-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $totalCertifications }}</span>
                    <span class="text-xs text-amber-700 font-medium bg-amber-50 px-1.5 py-0.5 rounded-full border border-amber-100">Accredited</span>
                </div>
            </div>

            <!-- Metric 4: Students Reached -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Students Reached</span>
                    <div class="h-9 w-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($totalStudentsReached) }}</span>
                    <span class="text-xs text-slate-500 font-medium">in {{ $totalImplementations }} classes</span>
                </div>
            </div>

            <!-- Metric 5: Dynamic Class Average -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Class Average</span>
                    <div class="h-9 w-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ number_format($overallClassAverage, 1) }}%</span>
                    <span class="text-xs text-purple-700 font-medium bg-purple-50 px-1.5 py-0.5 rounded-full border border-purple-100">Auto-Aggregated</span>
                </div>
            </div>
        </div>

        <!-- Section: Active Workshops Progress -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Your Active Workshops</h3>
                    <p class="text-xs text-slate-500">Pick up where you left off in your disaster preparedness journey.</p>
                </div>
                <a href="{{ route('teacher.workshops.my') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                    View all my workshops &rarr;
                </a>
            </div>

            @if ($activeWorkshops->isEmpty())
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center">
                    <div class="mx-auto h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h4 class="mt-3 text-sm font-bold text-slate-900">No active workshops yet</h4>
                    <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                        Explore available published disaster preparedness workshops to start training.
                    </p>
                    <a href="{{ route('teacher.workshops.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700 transition-colors">
                        Browse Workshops
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($activeWorkshops as $item)
                        <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between hover:border-indigo-200 transition-all">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="inline-block text-[11px] font-semibold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-md px-2 py-0.5">
                                            {{ $item->workshop->course->title ?? 'DRR Training Course' }}
                                        </span>
                                        <h4 class="mt-2 text-base font-bold text-slate-900 line-clamp-1">
                                            {{ $item->workshop->title }}
                                        </h4>
                                    </div>
                                    @if ($item->has_certificate)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Certified
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 border border-amber-200 shrink-0">
                                            In Progress
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-2 text-xs text-slate-500 line-clamp-2">
                                    {{ $item->workshop->description ?? 'Learn comprehensive disaster risk reduction principles and student classroom activities.' }}
                                </p>

                                <!-- Visual Progress Bar -->
                                <div class="mt-4">
                                    <div class="flex justify-between text-xs font-medium text-slate-600 mb-1.5">
                                        <span>Course Progress</span>
                                        <span class="font-bold text-slate-800">{{ $item->progress_percentage }}% ({{ $item->completed_modules }}/{{ $item->total_modules }} modules)</span>
                                    </div>
                                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-600 rounded-full transition-all duration-500" style="width: {{ $item->progress_percentage }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action button -->
                            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div class="text-xs text-slate-500">
                                    Trainer: <span class="font-semibold text-slate-700">{{ $item->workshop->trainer->name ?? 'DRR Expert' }}</span>
                                </div>

                                @if ($item->next_module)
                                    <a href="{{ route('teacher.learning.module', [$item->workshop, $item->next_module]) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-3.5 py-2 rounded-lg transition-colors shadow-xs">
                                        Continue Module {{ $item->next_module->sequence }}
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('teacher.workshops.show', $item->workshop) }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3.5 py-2 rounded-lg transition-colors border border-indigo-200">
                                        View Workshop
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Two Columns: Recent Certifications & Recent Implementations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left: Certifications -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </span>
                            <h3 class="text-base font-bold text-slate-900">Your Certifications</h3>
                        </div>
                        <span class="text-xs font-semibold text-slate-500">{{ $certifications->count() }} Issued</span>
                    </div>

                    @if ($certifications->isEmpty())
                        <div class="py-6 text-center text-xs text-slate-400">
                            No certifications yet. Finish all required modules and pass the final exam to earn your certificate!
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($certifications->take(3) as $cert)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 border border-slate-100 hover:bg-slate-100/70 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-black text-xs border border-amber-200">
                                            DRR
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-900">{{ $cert->workshop->title }}</div>
                                            <div class="text-[11px] text-slate-500">ID: {{ $cert->certificate_number }} &bull; {{ $cert->certified_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('teacher.assessments.certificate', $cert->workshop) }}" target="_blank"
                                       class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 px-2.5 py-1 rounded bg-white border border-slate-200 shadow-2xs">
                                        View Certificate
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Classroom Implementations -->
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="p-1.5 rounded-lg bg-sky-50 text-sky-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </span>
                            <h3 class="text-base font-bold text-slate-900">Recent Implementations</h3>
                        </div>
                        <a href="{{ route('teacher.implementations.create') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                            + Log New
                        </a>
                    </div>

                    @if ($implementations->isEmpty())
                        <div class="py-6 text-center text-xs text-slate-400">
                            No classroom implementations logged yet. Once you're certified, log teaching sessions with your students!
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($implementations->take(3) as $imp)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 border border-slate-100">
                                    <div>
                                        <div class="text-xs font-bold text-slate-900">{{ $imp->workshop->title }}</div>
                                        <div class="text-[11px] text-slate-500">
                                            {{ $imp->implementation_date->format('M d, Y') }} &bull; {{ $imp->students_participated }} Students &bull; 
                                            <span class="text-emerald-600 font-semibold">{{ $imp->students_passed }} Passed</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('teacher.implementations.show', $imp) }}" 
                                       class="text-xs font-semibold text-slate-700 hover:text-indigo-600 px-2.5 py-1 rounded bg-white border border-slate-200 shadow-2xs">
                                        Summary
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section: Recent Assessment Attempts & Scoring Logs -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 sm:p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2">
                    <span class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Recent Assessment Attempts &amp; Auto-Scoring Logs</h3>
                        <p class="text-xs text-slate-500">Automated evaluation records comparing submitted answers with passing thresholds.</p>
                    </div>
                </div>
                <a href="{{ route('teacher.reports.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                    View Full Reports &rarr;
                </a>
            </div>

            @if ($recentAttempts->isEmpty())
                <div class="py-6 text-center text-xs text-slate-400">
                    No assessment attempts recorded yet. Finish your sequential modules to unlock the final examination.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                            <tr>
                                <th class="p-3">Workshop Exam</th>
                                <th class="p-3 text-center">Attempt #</th>
                                <th class="p-3 text-center">Score Ratio</th>
                                <th class="p-3 text-center">Percentage</th>
                                <th class="p-3 text-center">Passing Mark</th>
                                <th class="p-3 text-center">Evaluation</th>
                                <th class="p-3 text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentAttempts as $attempt)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="p-3 font-bold text-slate-900">
                                        {{ $attempt->assessment->workshop->title ?? 'DRR Assessment' }}
                                    </td>
                                    <td class="p-3 text-center text-slate-600 font-semibold">
                                        Attempt {{ $attempt->attempt_number }}
                                    </td>
                                    <td class="p-3 text-center text-slate-600 font-mono">
                                        {{ $attempt->score }} / {{ $attempt->total_questions }}
                                    </td>
                                    <td class="p-3 text-center font-bold text-slate-900 font-mono">
                                        {{ number_format($attempt->percentage, 1) }}%
                                    </td>
                                    <td class="p-3 text-center text-slate-500">
                                        {{ $attempt->passing_score }}%
                                    </td>
                                    <td class="p-3 text-center">
                                        @if ($attempt->status === 'passed')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-200">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                PASSED
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-0.5 text-[11px] font-bold text-rose-700 border border-rose-200">
                                                FAILED
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-right text-slate-400">
                                        {{ $attempt->submitted_at ? $attempt->submitted_at->format('M d, Y h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Available Workshops Discovery Banner -->
        @if ($availableWorkshops->isNotEmpty())
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Available Workshops to Join</h3>
                        <p class="text-xs text-slate-500">Expand your disaster preparedness accreditation.</p>
                    </div>
                    <a href="{{ route('teacher.workshops.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Browse all &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach ($availableWorkshops as $av)
                        <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-xs flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider bg-indigo-50 px-2 py-0.5 rounded">
                                    {{ $av->course->title ?? 'DRR Training' }}
                                </span>
                                <h4 class="mt-2 text-sm font-bold text-slate-900 line-clamp-1">{{ $av->title }}</h4>
                                <p class="mt-1 text-xs text-slate-500 line-clamp-2">{{ $av->description }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-500">
                                    Starts {{ $av->start_date ? $av->start_date->format('M d') : 'Flexible' }}
                                </span>
                                <form method="POST" action="{{ route('teacher.workshops.join', $av) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-2xs transition-colors">
                                        Join Workshop
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-dynamic-component>