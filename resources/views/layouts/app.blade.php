<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ config('app.name', ' ') }}</title>
		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=cormorant-garamond:300,400,500,600|inter:300,400,500&display=swap"
				rel="stylesheet" />
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
		<!-- Scripts -->
		@vite(['resources/css/app.css', 'resources/js/app.js'])
		<script src="https://cdn.tailwindcss.com"></script>
		<script>
				tailwind.config = {
						darkMode: 'class',
						theme: {
								extend: {
										colors: {
												// Modern e-commerce color palette
												primary: '#3B82F6', // Blue
												secondary: '#6366F1', // Indigo
												accent: '#F59E0B', // Amber
												neutral: '#1F2937', // Dark gray
												light: '#F9FAFB', // Light gray

												// Additional colors you might need
												success: '#10B981', // Green
												warning: '#F59E0B', // Amber
												error: '#EF4444', // Red
												info: '#3B82F6', // Blue
										}
								}
						}
				}
		</script>
</head>

<body class="font-['Inter'] antialiased bg-gray-900 min-h-screen">
		<div class="min-h-screen flex flex-col">
				<x-alert />
				<!-- Top Navigation Bar -->
				@include('partials.nav')
				<div class="flex flex-1">
						<!-- Sidebar -->
						@include('partials.sidebar')
						<!-- Main Content -->
						<main class="flex-1 p-6 bg-gray-900">
								<!-- Page Heading -->
								@isset($header)
										<div class="mb-6">
												<div class="bg-gray-800 rounded-lg shadow-sm p-6">
														{{ $header }}
												</div>
										</div>
								@endisset
								<!-- Page Content -->
								<div class="bg-gray-800 rounded-lg shadow-sm p-6">
										{{ $slot }}
								</div>
						</main>
				</div>
				<!-- Footer -->
				{{-- @include('partials.footer') --}}
		</div>
		{{-- dropdown --}}
		@include('partials.dropdown')
</body>

</html>
