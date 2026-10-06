<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Course Details') }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    View your course information.
                </p>
            </div>

            <div class="flex gap-2">

                <a
                    href="{{ route('trainer.courses.edit', $course->id) }}"
                    class="px-4 py-2 bg-gray-800 text-white rounded-md
                           text-sm font-semibold hover:bg-gray-700"
                >
                    Edit Course
                </a>

                <a
                    href="{{ route('trainer.courses.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md
                           text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Back to Courses
                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-10">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))

                <div class="mb-6 bg-green-100 border border-green-200
                            text-green-800 px-4 py-3 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            {{-- Course Information --}}
            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="mb-8">

                        <div class="flex items-center justify-between">

                            <div>

                                <h1 class="text-2xl font-bold text-gray-900">
                                    {{ $course->title }}
                                </h1>

                                <p class="text-sm text-gray-500 mt-1">
                                    Created by {{ $course->trainer->name }}
                                </p>

                            </div>


                            {{-- Status --}}
                            <div>

                                @if ($course->status === 'published')

                                    <span class="px-3 py-1 text-sm font-semibold
                                                 rounded-full bg-green-100
                                                 text-green-800">
                                        Published
                                    </span>

                                @elseif ($course->status === 'unpublished')

                                    <span class="px-3 py-1 text-sm font-semibold
                                                 rounded-full bg-gray-100
                                                 text-gray-800">
                                        Unpublished
                                    </span>

                                @else

                                    <span class="px-3 py-1 text-sm font-semibold
                                                 rounded-full bg-yellow-100
                                                 text-yellow-800">
                                        Draft
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="mb-8">

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            Description
                        </h3>

                        <p class="text-gray-600 leading-relaxed">
                            {{ $course->description }}
                        </p>

                    </div>


                    {{-- Course Information Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                        <div class="bg-gray-50 rounded-lg p-5">

                            <p class="text-sm text-gray-500">
                                Estimated Duration
                            </p>

                            <p class="text-xl font-semibold text-gray-800 mt-1">
                                {{ $course->estimated_duration }} minutes
                            </p>

                        </div>


                        <div class="bg-gray-50 rounded-lg p-5">

                            <p class="text-sm text-gray-500">
                                Modules
                            </p>

                            <p class="text-xl font-semibold text-gray-800 mt-1">
                                {{ $course->modules->count() }}
                            </p>

                        </div>


                        <div class="bg-gray-50 rounded-lg p-5">

                            <p class="text-sm text-gray-500">
                                Status
                            </p>

                            <p class="text-xl font-semibold text-gray-800 mt-1">
                                {{ ucfirst($course->status) }}
                            </p>

                        </div>

                    </div>


                    {{-- Learning Objectives --}}
                    <div class="mb-8">

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            Learning Objectives
                        </h3>

                        <p class="text-gray-600 whitespace-pre-line leading-relaxed">
                            {{ $course->learning_objectives }}
                        </p>

                    </div>


                    {{-- Target Participants --}}
                    <div class="mb-8">

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            Target Participants
                        </h3>

                        <p class="text-gray-600 whitespace-pre-line leading-relaxed">
                            {{ $course->target_participants }}
                        </p>

                    </div>


                    {{-- Modules --}}
                    <div>

                        <div class="flex items-center justify-between mb-4">

                            <div>

                                <h3 class="text-lg font-semibold text-gray-800">
                                    Course Modules
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Modules will be added here.
                                </p>

                            </div>

                        </div>


                        @if ($course->modules->count() > 0)

                            <div class="space-y-3">

                                @foreach ($course->modules as $module)

                                    <div class="border rounded-lg p-4">

                                        <div class="flex items-center gap-3">

                                            <span class="font-semibold text-gray-800">
                                                {{ $module->sequence }}.
                                            </span>

                                            <span class="font-medium text-gray-800">
                                                {{ $module->title }}
                                            </span>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="bg-gray-50 rounded-lg p-6 text-center">

                                <p class="text-gray-500">
                                    No modules have been added to this course yet.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>