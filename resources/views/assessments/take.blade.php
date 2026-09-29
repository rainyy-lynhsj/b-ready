<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B-READY - Take Assessment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans p-6">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">B-READY Final Assessment</h1>
        <p class="text-gray-600 text-sm mb-6">Sagutan ang pagsusulit na ito upang masubukan ang iyong natutunan at makuha ang iyong Certificate.</p>

        <!-- Form para sa Pagsusulit -->
        <form action="{{ route('assessment.submit', 1) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Ilagay ang iyong Score (Testing muna):</label>
                <input type="number" name="score" required min="0" max="100" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" placeholder="Halimbawa: 85">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition duration-200">
                I-submit ang Pagsusulit (Submit Assessment)
            </button>
        </form>
    </div>

</body>
</html>