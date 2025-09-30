<x-app-layout>
		<div class="mb-6">
				<div class="flex justify-between items-center">
						<div>
								<h1 class="text-2xl font-bold text-white dark:text-white">{{ __('Dashboard') }}</h1>
								<p class="text-white dark:text-white mt-1">{{ __('Welcome to the dashboard') }}</p>
						</div>

						<!-- Cache clear button (optional) -->
						@if (auth()->user()->role === 'admin')
								<form action="{{ route('dashboard.clear-cache') }}" method="POST" class="inline">
										@csrf
										<button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
												🔄 Clear Cache
										</button>
								</form>
						@endif
				</div>
		</div>

		<!-- Stats Grid -->
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
				<!-- Orders Card -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
								<div>
										<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Orders') }}</p>
										<p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalOrders }}</p>
										<div class="flex space-x-2 mt-2">
												<span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
														Pending: {{ $pendingOrders ?? 0 }}
												</span>
												<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
														Completed: {{ $completedOrders ?? 0 }}
												</span>
										</div>
								</div>
								<div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
										<!-- SVG remains same -->
								</div>
						</div>
				</div>

				<!-- Orders Amount Card -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
								<div>
										<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Revenue') }}</p>
										<p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">
												${{ number_format($totalOrdersAmount, 2) }}
										</p>
										<p class="text-xs text-green-500 flex items-center mt-1">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
														stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
												</svg>
												Recent (7d): ${{ number_format($recentRevenue ?? 0, 2) }}
										</p>
								</div>
								<div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
										<!-- SVG remains same -->
								</div>
						</div>
				</div>

				<!-- Categories Card -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
								<div>
										<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Categories') }}</p>
										<p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalCategories }}</p>
										<p class="text-xs text-gray-500 mt-1">
												Organized product catalog
										</p>
								</div>
								<div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
										<!-- SVG remains same -->
								</div>
						</div>
				</div>

				<!-- Products Card -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<div class="flex items-center justify-between">
								<div>
										<p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Products') }}</p>
										<p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $totalProducts }}</p>
										<p class="text-xs text-gray-500 mt-1">
												Active inventory
										</p>
								</div>
								<div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
										<!-- SVG remains same -->
								</div>
						</div>
				</div>
		</div>

		<!-- Recent Activity Section -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
				<!-- Recent Orders Summary -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Recent Activity</h3>
						<div class="space-y-3">
								<div class="flex justify-between items-center">
										<span class="text-sm text-gray-600 dark:text-gray-400">Orders (Last 7 days)</span>
										<span class="font-semibold text-blue-600">{{ $recentOrders ?? 0 }}</span>
								</div>
								<div class="flex justify-between items-center">
										<span class="text-sm text-gray-600 dark:text-gray-400">Revenue (Last 7 days)</span>
										<span class="font-semibold text-green-600">${{ number_format($recentRevenue ?? 0, 2) }}</span>
								</div>
						</div>
				</div>

				<!-- Quick Actions -->
				<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
						<h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Quick Actions</h3>
						<div class="grid grid-cols-2 gap-3">
								<a href="{{ route('products.create') }}"
										class="bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg text-sm transition duration-200">
										Add Product
								</a>
								<a href="{{ route('orders.create') }}"
										class="bg-green-500 hover:bg-green-600 text-white text-center py-2 px-4 rounded-lg text-sm transition duration-200">
										Create Order
								</a>
								<a href="{{ route('categories.index') }}"
										class="bg-purple-500 hover:bg-purple-600 text-white text-center py-2 px-4 rounded-lg text-sm transition duration-200">
										Manage Categories
								</a>
								<a href="{{ route('orders.index') }}"
										class="bg-orange-500 hover:bg-orange-600 text-white text-center py-2 px-4 rounded-lg text-sm transition duration-200">
										View Orders
								</a>
						</div>
				</div>
		</div>
</x-app-layout>
