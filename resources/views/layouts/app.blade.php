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

    <style>
        .text-shadow-lg {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

@props(['backgroundImage'])

@php

    $backgroundImage = $backgroundImage ?? asset('images/welcome-dashboard-picture.jpg');
@endphp

<body class="font-['Inter'] antialiased bg-cover bg-center bg-no-repeat min-h-screen"
    style="background-image: linear-gradient(rgba(0, 0, 0, 0.2)), url('{{ $backgroundImage }}')">

    <div class="min-h-screen bg-black/40 backdrop-blur-sm">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            {{-- class="bg-white/10 backdrop-blur-md border-b border-white/10 shadow-lg" --}}
            <header>
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="text-white">
            {{ $slot }}
        </main>
        {{-- <!-- Page Footer -->
        @isset($footer)
            <header class="bg-white/10 backdrop-blur-md border-b border-white/10 shadow-lg">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $footer }}
                </div>
            </header>
        @endisset --}}
    </div>
</body>

</html>
