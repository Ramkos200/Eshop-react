<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Lumière Lamps') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:300,400,500,600|inter:300,400,500&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-['Inter'] text-white antialiased bg-cover bg-center bg-no-repeat min-h-screen"
    style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('{{ asset('images/welcome-dashboard-picture.jpg') }}')">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-black/40 backdrop-blur-sm">
        <div class="mb-8">
            <a href="/"
                class="font-['Cormorant_Garamond'] text-4xl font-light text-white text-shadow-lg shadow-white/10">
                Lumière
            </a>
        </div>

        <div
            class="w-full sm:max-w-md bg-white/10 backdrop-blur-md border border-white/10 shadow-lg overflow-hidden sm:rounded-2xl">
            <div class="p-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
