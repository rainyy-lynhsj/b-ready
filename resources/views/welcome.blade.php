<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>B-READY</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50">

    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">

            <h1 class="text-5xl font-bold">
                B-READY
            </h1>

            <p class="mt-4 text-lg text-gray-600">
                Disaster Preparedness Learning and Training Platform
            </p>

            <div class="mt-8 flex justify-center gap-4">

                <a href="{{ route('login') }}"
                   class="px-6 py-3 rounded-lg bg-blue-700 text-white">
                    Log in
                </a>

                <a href="{{ route('register') }}"
                   class="px-6 py-3 rounded-lg border border-blue-700 text-blue-700">
                    Register
                </a>

            </div>

        </div>
    </div>

</body>
</html>