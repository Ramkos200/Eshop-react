<x-app-layout>

		<div class="mb-6">
				<h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard') }}</h1>
				<p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Welcome to the dashboard') }}</p>
		</div>

		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
				<!-- Orders Card -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
								<div>
										<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Orders') }}</p>
										<p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalOrders }}</p>
										<p class="text-xs text-gray-500 flex items-center mt-1">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
														stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
												</svg>
												{{ __('All time orders') }}
										</p>
								</div>
								<div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500 dark:text-purple-300" fill="none"
												viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
														d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
										</svg>
								</div>
						</div>
				</div>

				<!-- Orders Amount Card -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
								<div>
										<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Orders Amount') }}</p>
										<p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
												${{ number_format($totalOrdersAmount, 2) }}</p>
										<p class="text-xs text-gray-500 flex items-center mt-1">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
														stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
												</svg>
												{{ __('Total revenue') }}
										</p>
								</div>
								<div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 dark:text-green-300" fill="none"
												viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
														d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
										</svg>
								</div>
						</div>
				</div>

				<!-- Categories Card -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
								<div>
										<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Categories') }}</p>
										<p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalCategories }}</p>
										<p class="text-xs text-gray-500 flex items-center mt-1">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
														stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
												</svg>
												{{ __('Product categories') }}
										</p>
								</div>
								<div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 dark:text-blue-300" fill="none"
												viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
														d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
										</svg>
								</div>
						</div>
				</div>

				<!-- Products Card -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
								<div>
										<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Products') }}</p>
										<p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalProducts }}</p>
										<p class="text-xs text-gray-500 flex items-center mt-1">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
														stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
												</svg>
												{{ __('Available products') }}
										</p>
								</div>
								<div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
										<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 dark:text-orange-300" fill="none"
												viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
														d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
										</svg>
								</div>
						</div>
				</div>
		</div>

</x-app-layout>
