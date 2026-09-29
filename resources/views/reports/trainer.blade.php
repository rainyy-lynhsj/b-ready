<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B-READY - Trainer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-slate-800 text-white flex flex-col">
            <div class="p-5 text-2xl font-bold tracking-wider border-b border-slate-700">
                B-READY System
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('reports.trainer') }}" class="block py-2.5 px-4 rounded transition duration-200 bg-blue-600 text-white font-semibold">Trainer Reports</a>
                <a href="{{ route('assessments.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-slate-700 text-gray-300">Assessments</a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-slate-700 text-gray-300">Certificates</a>
                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-slate-700 text-gray-300">Monitoring</a>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">{{ $report_title ?? 'DRR Trainer Report' }}</h1>
                <div class="text-sm text-gray-600 font-medium">Administrator</div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                
                <!-- Summary Card -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase">Total Completed Trainings</p>
                            <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $total_completed_trainings ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-600 text-xl">
                            📜
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 font-bold text-gray-700">
                        Certificates Issued List
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                                <tr>
                                    <th class="px-6 py-3 text-left">ID</th>
                                    <th class="px-6 py-3 text-left">User ID</th>
                                    <th class="px-6 py-3 text-left">Assessment ID</th>
                                    <th class="px-6 py-3 text-left">Certificate Code</th>
                                    <th class="px-6 py-3 text-left">Issued At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-gray-700">
                                @forelse($certificates_list as $cert)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">{{ $cert->id }}</td>
                                    <td class="px-6 py-4">{{ $cert->user_id }}</td>
                                    <td class="px-6 py-4">{{ $cert->assessment_id }}</td>
                                    <td class="px-6 py-4 font-bold text-green-600">{{ $cert->certificate_code }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $cert->issued_at }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-400">Wala pang naitalang sertipiko.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>