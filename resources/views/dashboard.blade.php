<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Trainer Dashboard') }}
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Manage your training courses and learning materials.
                </p>

            </div>


            {{-- Courses Button --}}
            <div>

                <a
                    href="{{ route('trainer.courses.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800
                           border border-transparent rounded-md font-semibold
                           text-xs text-white uppercase tracking-widest
                           hover:bg-gray-700"
                >
                    Courses
                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- Welcome Message --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <h3 class="text-2xl font-bold text-gray-800">
                        Welcome, {{ auth()->user()->name }}!
                    </h3>

                    <p class="mt-2 text-gray-600">
                        Manage your courses, modules, and training materials
                        from your Trainer dashboard.
                    </p>

                </div>

            </div>


            {{-- Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">


                {{-- Total Courses --}}
                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Total Courses
                        </p>

                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $courseCount }}
                        </p>

                    </div>

                </div>


                {{-- Total Modules --}}
                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Total Modules
                        </p>

                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            {{ $moduleCount }}
                        </p>

                    </div>

                </div>


                {{-- Published Courses --}}
                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Published Courses
                        </p>

                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ $publishedCount }}
                        </p>

                    </div>

                </div>


                {{-- Draft Courses --}}
                <div class="bg-white shadow-sm sm:rounded-lg">

                    <div class="p-6">

                        <p class="text-sm font-medium text-gray-500">
                            Draft Courses
                        </p>

                        <p class="text-3xl font-bold text-yellow-600 mt-2">
                            {{ $draftCount }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- My Courses --}}
            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">


                    {{-- My Courses Header --}}
                    <div class="flex items-center justify-between mb-6">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-800">
                                My Courses
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Courses created by you.
                            </p>

                        </div>


                        {{-- Manage Courses Button --}}
                        <a
                            href="{{ route('trainer.courses.index') }}"
                            class="inline-flex items-center px-4 py-2
                                   bg-indigo-600 border border-transparent
                                   rounded-md font-semibold text-xs text-white
                                   uppercase tracking-widest hover:bg-indigo-700"
                        >
                            Manage Courses
                        </a>

                    </div>


                    @if ($courses->count() > 0)

                        <div class="overflow-x-auto">

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-50">

                                    <tr>

                                        <th class="px-6 py-3 text-left text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Course
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Modules
                                        </th>

                                        <th class="px-6 py-3 text-left text-xs
                                                   font-medium text-gray-500
                                                   uppercase tracking-wider">
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach ($courses as $course)

                                        <tr>

                                            <td class="px-6 py-4">

                                                <div class="font-medium text-gray-900">
                                                    {{ $course->title }}
                                                </div>

                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($course->description, 80) }}
                                                </div>

                                            </td>


                                            <td class="px-6 py-4 text-sm text-gray-700">

                                                {{ $course->modules->count() }}

                                                {{ $course->modules->count() == 1 ? 'module' : 'modules' }}

                                            </td>


                                            <td class="px-6 py-4">

                                                @if ($course->status === 'published')

                                                    <span class="px-2 py-1 text-xs
                                                                 font-semibold rounded-full
                                                                 bg-green-100 text-green-800">
                                                        Published
                                                    </span>

                                                @elseif ($course->status === 'unpublished')

                                                    <span class="px-2 py-1 text-xs
                                                                 font-semibold rounded-full
                                                                 bg-gray-100 text-gray-800">
                                                        Unpublished
                                                    </span>

                                                @else

                                                    <span class="px-2 py-1 text-xs
                                                                 font-semibold rounded-full
                                                                 bg-yellow-100 text-yellow-800">
                                                        Draft
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                    @else

                        <div class="text-center py-10">

                            <p class="text-gray-500">
                                You don't have any courses yet.
                            </p>

                            <p class="text-sm text-gray-400 mt-2">
                                Create your first training course to get started.
                            </p>


                            {{-- Create First Course --}}
                            <div class="mt-5">

                                <a
                                    href="{{ route('trainer.courses.create') }}"
                                    class="inline-flex items-center px-4 py-2
                                           bg-gray-800 border border-transparent
                                           rounded-md font-semibold text-xs
                                           text-white uppercase tracking-widest
                                           hover:bg-gray-700"
                                >
                                    Create Your First Course
                                </a>

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>