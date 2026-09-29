<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B-Ready File Server</title>
    <!-- Tailwind CSS para sa mabilis at magandang styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">B-Ready File Server (PDFs, Images & 10-Min Videos)</h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Upload Form -->
        <form action="{{ route('file-server.store') }}" method="POST" enctype="multipart/form-data" class="mb-8 space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 font-semibold mb-1">Title ng Material:</label>
                <input type="text" name="title" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Pumili ng File (PDF, Image, Video max 10 mins):</label>
                <input type="file" name="file" required class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">
                I-upload sa File Server
            </button>
        </form>

        <hr class="my-6">

        <!-- Listahan ng mga Files -->
        <h2 class="text-xl font-bold mb-4 text-gray-800">Listahan sa File Server</h2>
        <div class="space-y-6">
            @forelse($files as $file)
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 shadow-sm">
                    <h3 class="font-bold text-lg text-blue-900 mb-2">{{ $file->title }}</h3>

                    {{-- Kung Larawan / Image --}}
                    @if(in_array($file->file_type, ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('storage/' . $file->file_path) }}" class="w-72 h-auto rounded shadow-sm mt-2">
                    
                    {{-- Kung Video (MP4, MOV, AVI) --}}
                    @elseif(in_array($file->file_type, ['mp4', 'mov', 'avi']))
                        <video width="480" controls class="rounded shadow-sm mt-2">
                            <source src="{{ asset('storage/' . $file->file_path) }}" type="video/mp4">
                            Hindi sinusuportahan ng iyong browser ang video tag.
                        </video>
                    
                    {{-- Kung PDF o iba pang files --}}
                    @else
                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="text-blue-600 underline font-semibold mt-2 inline-block">
                            📄 I-download / Basahin ang File
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">Wala pang laman ang file server.</p>
            @endforelse
        </div>
    </div>
</body>
</html>