<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Workshops
            </h2>

            <a href="{{ route('trainer.workshops.create') }}"
               class="rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                Create Workshop
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-5 rounded-md bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if ($workshops->isEmpty())
                        <p class="text-gray-600">
                            You haven't created any workshops yet.
                        </p>
                    @else
                        <div class="space-y-5">
                            @foreach ($workshops as $workshop)
                                <div class="rounded-lg border border-gray-200 p-5">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $workshop->title }}
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-600">
                                        Course:
                                        {{ $workshop->course?->title ?? 'Course unavailable' }}
                                    </p>

                                    @if ($workshop->description)
                                        <p class="mt-3 text-gray-700">
                                            {{ $workshop->description }}
                                        </p>
                                    @endif

                                    <div class="mt-4 grid gap-2 text-sm text-gray-600 sm:grid-cols-3">
                                        <p>
                                            <strong>Registration deadline:</strong><br>
                                            {{ $workshop->registration_deadline->format('M d, Y h:i A') }}
                                        </p>

                                        <p>
                                            <strong>Starts:</strong><br>
                                            {{ $workshop->start_date->format('M d, Y h:i A') }}
                                        </p>

                                        <p>
                                            <strong>Ends:</strong><br>
                                            {{ $workshop->end_date->format('M d, Y h:i A') }}
                                        </p>
                                    </div>

                                    <p class="mt-3 text-sm text-gray-600">
                                        Status: {{ ucfirst($workshop->status) }}

                                    <div class="mt-4">
                                            <a href="{{ route('trainer.workshops.modules.edit', $workshop->id) }}"
                                                class="inline-block rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-600">
                                                    Manage Modules
                                            </a>
                                    </div>
                                    </p>
                                    <div class="mt-4 rounded-lg bg-gray-50 border border-gray-200 p-4">

                                            <div class="flex items-center justify-between">
                                                <h4 class="font-semibold text-gray-800">
                                                    Assigned Modules
                                                </h4>

                                                <span class="text-sm text-gray-600">
                                                    {{ $workshop->modules->count() }} module(s)
                                                </span>
                                            </div>

                                            @if ($workshop->modules->isEmpty())
                                                <p class="mt-2 text-sm text-gray-500">
                                                    No modules assigned yet.
                                                </p>
                                            @else
                                                <ol class="mt-3 list-decimal list-inside space-y-2">
                                                    @foreach ($workshop->modules->sortBy('pivot.sequence') as $module)
                                                        <li class="text-sm text-gray-700">
                                                            {{ $module->title }}
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif

                                        </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
