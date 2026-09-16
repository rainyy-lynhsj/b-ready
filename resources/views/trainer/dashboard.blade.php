<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trainer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h1 class="text-2xl font-bold mb-2">
                        Welcome, {{ auth()->user()->name }}!
                    </h1>

                    <p class="text-gray-600">
                        You are logged in as a <strong>DRR Trainer / Expert</strong>.
                    </p>

                    <p class="mt-4">
                        From this dashboard, you can manage disaster preparedness
                        training courses, learning modules, workshops, and classroom
                        implementation resources.
                    </p>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>