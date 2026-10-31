<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Workshop
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-100 px-4 py-3 text-red-800">
                        <p class="font-semibold">Please correct these errors:</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($courses->isEmpty())
                    <p class="mb-4 text-gray-700">
                        You don't have any published courses available for a workshop yet.
                        Create and publish a course first.
                    </p>

                    <a href="{{ route('trainer.courses.create') }}"
                       class="inline-block rounded-md bg-gray-800 px-4 py-2 text-white hover:bg-gray-700">
                        Create Course
                    </a>
                @else
                    <form method="POST" action="{{ route('trainer.workshops.store') }}">
                        @csrf

                        <div class="mb-5">
                            <label for="course_id" class="block text-sm font-medium text-gray-700">
                                Training Course
                            </label>
                            <select id="course_id" name="course_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select a published course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                            <label for="title" class="block text-sm font-medium text-gray-700">
                                Workshop Title
                            </label>
                            <input type="text" id="title" name="title"
                                   value="{{ old('title') }}" maxlength="255" required
                                   placeholder="Example: Disaster Preparedness Training - November 2026"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-5">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                      placeholder="Describe the workshop session.">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-5">
                            <label for="registration_deadline" class="block text-sm font-medium text-gray-700">
                                Registration Deadline
                            </label>
                            <input type="datetime-local" id="registration_deadline"
                                   name="registration_deadline"
                                   value="{{ old('registration_deadline') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-5">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">
                                Start Date and Time
                            </label>
                            <input type="datetime-local" id="start_date" name="start_date"
                                   value="{{ old('start_date') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="mb-6">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">
                                End Date and Time
                            </label>
                            <input type="datetime-local" id="end_date" name="end_date"
                                   value="{{ old('end_date') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('trainer.workshops.index') }}"
                               class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">
                                Create Workshop
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
