<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ config('app.name', ' ') }}</title>
		{{-- Fonts --}}
		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=cormorant-garamond:300,400,500,600|inter:300,400,500&display=swap"
				rel="stylesheet" />
		<link rel="icon"
				href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>💡</text></svg>">
		{{-- Font Awesome --}}
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
		{{-- Scripts --}}
		@vite(['resources/css/app.css', 'resources/js/app.js'])
		<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="font-['Inter'] antialiased bg-gray-900 min-h-screen">
		<div class="min-h-screen flex flex-col">
				<x-alert />
				{{-- Top Navigation Bar --}}
				@include('partials.nav')
				<div class="flex flex-1">
						{{-- Sidebar --}}
						@include('partials.sidebar')
						{{-- Main Content --}}
						<main class="flex-1 p-4 bg-black">
								{{-- Page Heading --}}
								@isset($header)
										<div class="mb-1">
												<div class="bg-black rounded-lg shadow-sm p-6">
														{{ $header }}
												</div>
										</div>
								@endisset
								{{-- Page Content --}}
								<div class="bg-black rounded-lg shadow-sm p-1">
										{{ $slot }}
								</div>
						</main>
				</div>
				{{-- Footer --}}
				{{-- @include('partials.footer') --}}
		</div>
		{{-- dropdown --}}
		@include('partials.dropdown')
</body>

</html>
