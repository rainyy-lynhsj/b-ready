
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Workshop Modules
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Select the training modules for your workshop.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Workshop Information --}}
            <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <div class="rounded-lg bg-blue-100 p-2">
                        <span class="text-blue-700 font-bold">01</span>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        Workshop Information
                    </h3>
                </div>

                <h4 class="text-xl font-bold text-gray-900">
                    {{ $workshop->title }}
                </h4>

                <p class="mt-2 text-sm text-gray-600">
                    {{ $workshop->description }}
                </p>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-medium uppercase text-gray-500">
                            Training Course
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $workshop->course?->title ?? 'Course unavailable' }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-medium uppercase text-gray-500">
                            Workshop Status
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ ucfirst($workshop->status) }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-medium uppercase text-gray-500">
                            Start Date
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $workshop->start_date->format('M d, Y h:i A') }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-gray-50 p-4">
                        <p class="text-xs font-medium uppercase text-gray-500">
                            End Date
                        </p>
                        <p class="mt-1 font-semibold text-gray-800">
                            {{ $workshop->end_date->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 border border-red-200 px-5 py-4 text-red-800">
                    <p class="font-semibold mb-2">
                        Unable to save your assignments.
                    </p>

                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($modules->isEmpty())

                {{-- No Modules Available --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-8 text-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        No Modules Available
                    </h3>

                    <p class="mt-2 text-sm text-gray-600">
                        This course does not have any modules yet.
                        Please create modules under the course first.
                    </p>

                    <a href="{{ route('trainer.courses.show', $workshop->course_id) }}"
                       class="mt-5 inline-block rounded-md bg-gray-800 px-5 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                        Go to Training Course
                    </a>
                </div>

            @else

                <form method="POST"
                      action="{{ route('trainer.workshops.modules.update', $workshop->id) }}"
                      id="moduleAssignmentForm">

                    @csrf
                    @method('PUT')

                    {{-- Available Modules --}}
                    <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-6">

                        <div class="flex items-center gap-2 mb-2">
                            <div class="rounded-lg bg-blue-100 p-2">
                                <span class="text-blue-700 font-bold">02</span>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900">
                                Available Course Modules
                            </h3>
                        </div>

                        <p class="mb-5 text-sm text-gray-600">
                            Choose the modules that teachers must complete
                            as part of this workshop.
                        </p>

                        <div class="space-y-3">

                            @foreach ($modules as $module)

                                <label class="module-card flex cursor-pointer items-start gap-4 rounded-lg border border-gray-200 p-4 transition hover:border-blue-400 hover:bg-blue-50">

                                    <input
                                        type="checkbox"
                                        name="modules[]"
                                        value="{{ $module->id }}"
                                        class="module-checkbox mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        {{ in_array($module->id, old('modules', $selectedModuleIds)) ? 'checked' : '' }}
                                    >

                                    <div class="flex-1">

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-semibold text-gray-900">
                                                {{ $module->sequence }}. {{ $module->title }}
                                            </span>

                                            @if ($module->is_required)
                                                <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-medium text-amber-800">
                                                    Required
                                                </span>
                                            @endif
                                        </div>

                                        @if ($module->description)
                                            <p class="mt-2 text-sm text-gray-600">
                                                {{ $module->description }}
                                            </p>
                                        @endif

                                        <p class="mt-3 text-xs text-gray-500">
                                            Duration:
                                            {{ $module->estimated_duration }} minutes
                                        </p>

                                    </div>

                                    <span class="module-state text-xs font-semibold text-gray-400">
                                        Not selected
                                    </span>

                                </label>

                            @endforeach

                        </div>
                    </div>

                    {{-- Assignment Summary --}}
                    <div class="mb-6 bg-white shadow-sm sm:rounded-lg p-6">

                        <div class="flex items-center gap-2 mb-4">
                            <div class="rounded-lg bg-green-100 p-2">
                                <span class="text-green-700 font-bold">03</span>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900">
                                Assignment Summary
                            </h3>
                        </div>

                        <div class="rounded-lg bg-blue-50 border border-blue-100 p-5">
                            <p class="text-sm text-gray-600">
                                Selected Modules
                            </p>

                            <p class="mt-1 text-3xl font-bold text-blue-700">
                                <span id="selectedCount">0</span>
                                <span class="text-base font-medium text-gray-600">
                                    / {{ $modules->count() }} modules
                                </span>
                            </p>

                            <p id="selectionMessage" class="mt-2 text-sm text-gray-600">
                                Select the modules you want to include in this workshop.
                            </p>
                        </div>

                        <div class="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">

                            <a href="{{ route('trainer.workshops.index') }}"
                               class="text-center rounded-md border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Back to My Workshops
                            </a>

                            <button type="submit"
                                    class="rounded-md bg-blue-700 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Save Assigned Modules
                            </button>

                        </div>

                    </div>

                </form>

            @endif

        </div>
    </div>

    {{-- Update selected module count --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const checkboxes = document.querySelectorAll('.module-checkbox');
            const selectedCount = document.getElementById('selectedCount');
            const selectionMessage = document.getElementById('selectionMessage');

            function updateSummary() {

                let count = 0;

                checkboxes.forEach(function (checkbox) {

                    const card = checkbox.closest('.module-card');
                    const state = card.querySelector('.module-state');

                    if (checkbox.checked) {
                        count++;

                        card.classList.add('border-blue-500', 'bg-blue-50');
                        card.classList.remove('border-gray-200');

                        state.textContent = 'Selected';
                        state.classList.add('text-blue-700');
                        state.classList.remove('text-gray-400');

                    } else {

                        card.classList.remove('border-blue-500', 'bg-blue-50');
                        card.classList.add('border-gray-200');

                        state.textContent = 'Not selected';
                        state.classList.remove('text-blue-700');
                        state.classList.add('text-gray-400');

                    }

                });

                selectedCount.textContent = count;

                if (count === 0) {
                    selectionMessage.textContent =
                        'No modules selected. You can select modules above.';
                } else if (count === 1) {
                    selectionMessage.textContent =
                        '1 module is selected for this workshop.';
                } else {
                    selectionMessage.textContent =
                        count + ' modules are selected for this workshop.';
                }
            }

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', updateSummary);
            });

            updateSummary();

        });
    </script>

</x-app-layout>
