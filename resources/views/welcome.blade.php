<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lumière Lamps - Calming Illumination</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|cormorant-garamond:300,400,500,600|inter:300,400,500"
        rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="min-h-screen bg-cover bg-center bg-no-repeat"
    style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('{{ asset('dimmed-lamp-on-table.jpg') }}')">

    <!-- Hero Section -->
    <div class="min-h-screen flex items-center justify-center p-6 lg:p-8">
        <div class="text-center text-white max-w-4xl">

            <!-- Main Content -->
            <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-8 lg:p-12 mx-auto max-w-2xl">
                <h1
                    class="font-['Cormorant_Garamond'] text-3xl lg:text-5xl font-light mb-6 text-white text-shadow-lg shadow-white/10">
                    Lumière
                </h1>
                <p class="font-['Inter'] text-lg lg:text-xl mb-8 text-gray-200 opacity-90 tracking-wide">
                    Lighting for Peaceful Spaces
                </p>
                <p
                    class="font-['Inter'] text-sm lg:text-base mb-10 text-gray-300 opacity-80 leading-relaxed max-w-md mx-auto">
                    Discover our collection of softly dimming lamps designed to create tranquil environments and promote
                    relaxation.
                </p>

                <!-- Call to Action Buttons -->
                <div class="flex flex-col gap-3 justify-center items-center">
                    <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                        <a href="{{ route('login') }}"
                            class="bg-white/10 hover:bg-white/20 text-white px-8 py-3 rounded-full font-light text-sm tracking-wide transition-all duration-300 ease-in-out hover:-translate-y-0.5">
                            Login & Explore Calm Collection
                        </a>
                        <a href="{{ route('register') }}"
                            class="border border-gray-400 hover:border-white text-gray-200 hover:text-white px-8 py-2.5 rounded-full font-light text-sm tracking-wide transition-all duration-300 ease-in-out hover:-translate-y-0.5">
                            Register & Begin Your Journey
                        </a>
                    </div>

                    <!-- Continue as Guest Button -->
                    <a href="{{ url('/categories') }}"
                        class="border border-white/30 hover:border-white/50 bg-transparent hover:bg-white/5 text-gray-300 hover:text-white px-8 py-2.5 rounded-full font-light text-sm tracking-wide mt-4 transition-all duration-300 ease-in-out hover:-translate-y-0.5">
                        Continue as Guest
                    </a>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-20 opacity-90">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-4 text-center">
                    <h3 class="font-light text-sm mb-1">Soft Glow</h3>
                    <p class="text-xs opacity-80">Gentle illumination</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-4 text-center">
                    <h3 class="font-light text-sm mb-1">Calm Atmosphere</h3>
                    <p class="text-xs opacity-80">Peaceful ambiance</p>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-4 text-center">
                    <h3 class="font-light text-sm mb-1">Quality Craft</h3>
                    <p class="text-xs opacity-80">Artisan made</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Background Fallback -->
    <style>
        @media (max-width: 768px) {
            body {
                background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url({{ asset('dimmed-lamp-on-table.jpg') }});
            }
        }

        /* Fallback for browsers that don't support backdrop-filter */
        @supports not (backdrop-filter: blur(12px)) {
            .bg-white\/10 {
                background: rgba(255, 255, 255, 0.15) !important;
            }
        }
    </style>
</body>

</html>
