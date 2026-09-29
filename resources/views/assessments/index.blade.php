<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B-READY - Assessments Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex h-screen">

    <!-- Sidebar -->
    <div class="w-64 bg-slate-900 text-white flex flex-col">
        <div class="p-5 text-xl font-bold tracking-wider border-b border-slate-800">B-READY System</div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('reports.trainer') }}" class="block px-4 py-2.5 rounded hover:bg-slate-800 text-slate-300">Trainer Reports</a>
            <a href="{{ route('assessments.index') }}" class="block px-4 py-2.5 rounded bg-blue-600 text-white font-semibold">Assessments</a>
            <a href="#" class="block px-4 py-2.5 rounded hover:bg-slate-800 text-slate-300">Certificates</a>
            <a href="#" class="block px-4 py-2.5 rounded hover:bg-slate-800 text-slate-300">Monitoring</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center border-b border-gray-200">
            <h1 class="text-xl font-bold text-gray-800">Assessments Management</h1>
            <span class="text-sm font-semibold text-gray-600">Administrator</span>
        </header>

        <div class="p-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-bold text-gray-700">Listahan ng mga Pagsusulit</h2>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase border-b border-gray-200">
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Pamagat (Title)</th>
                            <th class="px-6 py-3">Passing Score</th>
                            <th class="px-6 py-3">Aksyon</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                        @forelse($assessments ?? [] as $assessment)
                            <tr>
                                <td class="px-6 py-4">{{ $assessment->id }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $assessment->title }}</td>
                                <td class="px-6 py-4">{{ $assessment->passing_score }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('assessment.take', $assessment->id) }}" class="text-blue-600 hover:underline font-medium">Sagutan (Take)</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500">Walang nakitang assessment sa ngayon.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
