<div class="hidden md:block bg-gray-800 shadow-md pt-5 relative" id="sidebar"
		style="width: 256px; min-width: 200px; max-width: 400px; ">
		<!-- Resize handle -->
		<div
				class="absolute right-0 top-0 h-full w-2 cursor-col-resize bg-gray-700 hover:bg-yellow-800 transition-colors duration-200"
				id="sidebar-resize-handle"></div>

		<div class="px-4 mb-6">
				<h2 class="text-lg font-semibold text-white">Categories</h2>
		</div>
		<nav class="space-y-1 px-4 overflow-y-auto h-[calc(100vh-200px)]" id="sidebar-content">
				<a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 bg-blue-900/30 rounded-lg">
						<i class="fas fa-chart-line mr-3 text-amber-500"></i>
						Dashboard
				</a>
				<a href="{{ route('categories.create') }}"
						class="flex items-center px-4 py-2 text-gray-300 bg-blue-900/30 rounded-lg">
						<i class="fas fa-plus mr-3 text-amber-500"></i>
						New Category
				</a>
				<a href="{{ route('products.create') }}"
						class="flex items-center px-4 py-2 text-gray-300 bg-blue-900/30 rounded-lg">
						<i class="fas fa-plus mr-3 text-green-500"></i>
						New Product
				</a>
				<a href="{{ route('orders.create') }}" class="flex items-center px-4 py-2 text-gray-300 bg-blue-900/30 rounded-lg">
						<i class="fas fa-plus mr-3 text-green-500"></i>
						Orders
				</a>
				<a href="{{ route('categories.index') }}"
						class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 rounded-lg">
						<i class="fas fa-list mr-3 text-gray-400"></i>
						All Categories
				</a>
				<a href="{{ route('products.index') }}"
						class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 rounded-lg">
						<i class="fas fa-box mr-3 text-yellow-400"></i>
						Show all Products
				</a>
				<a href="{{ route('products.browse') }}"
						class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 rounded-lg">
						<i class="fas fa-box mr-3 text-yellow-400"></i>
						Show all Variants
				</a>


				<!-- Dynamic Categories Section -->
				<div class="mt-4">
						<h3 class="text-sm font-semibold text-gray-400 uppercase px-2 mb-2">Product Categories</h3>

						@php
								// Get main categories (parent_id is null) with their children and grandchildren
								$mainCategories = \App\Models\Category::with(['children.children'])
								    ->where('parent_id', null)
								    ->orderBy('created_at', 'desc')
								    ->get();
						@endphp

						@foreach ($mainCategories as $category)
								<div class="category-group mb-1">
										<!-- Parent Category -->
										<div
												class="flex items-center justify-between px-3 py-2 text-gray-300 hover:bg-gray-700 rounded-lg cursor-pointer"
												onclick="toggleSubcategories('cat{{ $category->id }}')">
												<div class="flex items-center">
														<i class="fas fa-store mr-3 text-yellow-400"></i>
														<span class="truncate">{{ $category->name }}</span>
												</div>
												@if ($category->children->count() > 0)
														<i class="fas fa-chevron-down text-xs transition-transform duration-300"
																id="icon-cat{{ $category->id }}"></i>
												@endif
										</div>

										<!-- Children Subcategories -->
										@if ($category->children->count() > 0)
												<div class="subcategory-transition overflow-hidden ml-4 pl-2 border-l border-gray-600"
														id="subcategories-cat{{ $category->id }}" style="display: none;">
														@foreach ($category->children as $subcategory)
																<div class="subcategory-item">
																		<div
																				class="flex items-center justify-between px-3 py-2 text-gray-400 hover:bg-gray-700 rounded-lg cursor-pointer"
																				onclick="toggleSubcategories('subcat{{ $subcategory->id }}')">
																				<div class="flex items-center">
																						<i class="fas fa-store-alt mr-3 text-yellow-600"></i>
																						<span class="truncate">{{ $subcategory->name }}</span>
																				</div>
																				@if ($subcategory->children->count() > 0)
																						<i class="fas fa-chevron-down text-xs transition-transform duration-300"
																								id="icon-subcat{{ $subcategory->id }}"></i>
																				@endif
																		</div>

																		<!-- Grandchildren Categories -->
																		@if ($subcategory->children->count() > 0)
																				<div class="subcategory-transition overflow-hidden ml-4 pl-2 border-l border-gray-600"
																						id="subcategories-subcat{{ $subcategory->id }}" style="display: none;">
																						@foreach ($subcategory->children as $child)
																								<a href="{{ route('products.index', ['category_id' => $child->id]) }}"
																										class="flex items-center px-3 py-2 text-gray-400 hover:bg-gray-700 rounded-lg">
																										<i class="fas fa-circle text-xs mr-3 text-yellow-700"></i>
																										<span class="truncate">{{ $child->name }}</span>
																										<span class="text-xs text-gray-500 ml-2">
																												({{ $child->products->count() }})
																										</span>
																								</a>
																						@endforeach
																				</div>
																		@endif
																</div>
														@endforeach
												</div>
										@endif
								</div>
						@endforeach

						@if ($mainCategories->count() === 0)
								<p class="text-sm text-gray-400 italic px-3 py-2">No categories yet</p>
						@endif
				</div>
		</nav>

		{{-- <div class="mt-8 px-4 absolute bottom-4 w-full">
				<h2 class="text-lg font-semibold text-white">Filters</h2>
				<div class="mt-4 space-y-3">
						<div>
								<h3 class="text-sm font-medium text-gray-400">Price Range</h3>
								<input type="range" class="w-full mt-2 bg-gray-700">
						</div>
						<div>
								<h3 class="text-sm font-medium text-gray-400">Brand</h3>
								<div class="mt-2 space-y-1">
										<label class="flex items-center">
												<input type="checkbox" class="rounded text-primary focus:ring-primary bg-gray-700">
												<span class="ml-2 text-sm text-gray-400">Philips</span>
										</label>
									
								</div>
						</div>
				</div>
		</div> --}}
</div>

<script>
		function toggleSubcategories(id) {
				const subcategories = document.getElementById('subcategories-' + id);
				const icon = document.getElementById('icon-' + id);

				if (subcategories.style.display === 'none') {
						subcategories.style.display = 'block';
						icon.classList.add('rotate-90');
				} else {
						subcategories.style.display = 'none';
						icon.classList.remove('rotate-90');
				}
		}

		// Sidebar resize functionality
		document.addEventListener('DOMContentLoaded', function() {
				const sidebar = document.getElementById('sidebar');
				const resizeHandle = document.getElementById('sidebar-resize-handle');
				const sidebarContent = document.getElementById('sidebar-content');

				let isResizing = false;
				let startX, startWidth;

				// Initialize sidebar width from localStorage if available
				const savedWidth = localStorage.getItem('sidebarWidth');
				if (savedWidth) {
						sidebar.style.width = savedWidth + 'px';
				}

				resizeHandle.addEventListener('mousedown', function(e) {
						isResizing = true;
						startX = e.clientX;
						startWidth = parseInt(document.defaultView.getComputedStyle(sidebar).width, 10);

						document.addEventListener('mousemove', handleResize);
						document.addEventListener('mouseup', stopResize);

						e.preventDefault();
				});

				function handleResize(e) {
						if (!isResizing) return;

						const newWidth = startWidth + (e.clientX - startX);

						// Apply constraints
						if (newWidth >= 200 && newWidth <= 400) {
								sidebar.style.width = newWidth + 'px';

								// Adjust content overflow if needed
								if (newWidth < 250) {
										sidebarContent.classList.add('text-sm');
								} else {
										sidebarContent.classList.remove('text-sm');
								}
						}
				}

				function stopResize() {
						isResizing = false;

						// Save the width to localStorage
						localStorage.setItem('sidebarWidth', parseInt(sidebar.style.width, 10));

						document.removeEventListener('mousemove', handleResize);
						document.removeEventListener('mouseup', stopResize);
				}

				// Touch support for mobile devices
				resizeHandle.addEventListener('touchstart', function(e) {
						isResizing = true;
						startX = e.touches[0].clientX;
						startWidth = parseInt(document.defaultView.getComputedStyle(sidebar).width, 10);

						document.addEventListener('touchmove', handleTouchResize);
						document.addEventListener('touchend', stopTouchResize);

						e.preventDefault();
				});

				function handleTouchResize(e) {
						if (!isResizing) return;

						const newWidth = startWidth + (e.touches[0].clientX - startX);

						// Apply constraints
						if (newWidth >= 200 && newWidth <= 400) {
								sidebar.style.width = newWidth + 'px';

								// Adjust content overflow if needed
								if (newWidth < 250) {
										sidebarContent.classList.add('text-sm');
								} else {
										sidebarContent.classList.remove('text-sm');
								}
						}
				}

				function stopTouchResize() {
						isResizing = false;

						// Save the width to localStorage
						localStorage.setItem('sidebarWidth', parseInt(sidebar.style.width, 10));

						document.removeEventListener('touchmove', handleTouchResize);
						document.removeEventListener('touchend', stopTouchResize);
				}
		});
</script>

<style>
		.subcategory-transition {
				transition: all 0.3s ease;
		}

		.rotate-90 {
				transform: rotate(90deg);
		}

		#sidebar {
				transition: width 0.2s ease;
				user-select: none;
		}

		#sidebar-content {
				scrollbar-width: thin;
				scrollbar-color: #4B5563 #1F2937;
		}

		#sidebar-content::-webkit-scrollbar {
				width: 4px;
		}

		#sidebar-content::-webkit-scrollbar-track {
				background: #1F2937;
		}

		#sidebar-content::-webkit-scrollbar-thumb {
				background-color: #4B5563;
				border-radius: 2px;
		}
</style>
