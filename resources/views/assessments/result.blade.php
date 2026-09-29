<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B-READY - Assessment Result</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-6 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Resulta ng Pagsusulit</h1>
        <p class="text-gray-600 text-sm mb-6">{{ $assessment->title ?? 'Assessment' }}</p>

        <div class="my-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            <p class="text-sm font-semibold uppercase tracking-wider">Katayuan (Status)</p>
            <p class="text-3xl font-extrabold mt-1">{{ $status ?? 'Passed' }}</p>
            <p class="text-sm mt-2">Iyong Iskor: <span class="font-bold">{{ $score ?? 0 }}</span></p>
        </div>

        @if(isset($certificate) && $certificate)
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg text-left">
                <p class="text-xs font-bold text-amber-800 uppercase">🎉 Binabati kita!</p>
                <p class="text-xs text-gray-600 mt-1">Certificate Code:</p>
                <p class="font-mono font-bold text-green-700 text-sm mt-1">{{ $certificate->certificate_code }}</p>
            </div>
        @endif

        <a href="{{ route('assessment.take', 1) }}" class="inline-block w-full bg-slate-800 text-white font-semibold py-2 px-4 rounded hover:bg-slate-700 transition duration-200">
            Bumalik sa Pagsusulit
        </a>
    </div>

</body>
</html>
