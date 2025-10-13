<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __($category->name) }}
				</h2>
		</x-slot>
		<a href="{{ route('products.index', ['category_id' => $category->id]) }}">Back </a>
		{{-- session Messages --}}
		@if (session('success'))
				<div class="bg-green-500/40 border border-green-600 rounded-lg p-3">
						<p class="text-white text-sm">{{ session('success') }}</p>
				</div>
		@endif
		@if (session('error'))
				<div class="bg-red-500/40 border border-red-600 rounded-lg p-3">
						<p class="text-white text-sm">{{ session('error') }}</p>
				</div>
		@endif

		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-4">
						<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
								{{-- Back Button --}}
								<div class="mb-6">
										<a href="{{ route('categories.index') }}"
												class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
														stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
												</svg>
												Back to Categories
										</a>
								</div>
								{{-- Edit Category Button --}}
								<a href="{{ route('categories.edit', $category->id) }}"
										class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-md border border-white/30 rounded-full font-['Inter'] text-sm text-white uppercase tracking-widest hover:bg-white/30 hover:border-white/50 focus:bg-white/30 active:bg-white/40 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-gray-900 transition ease-in-out duration-150 ml-2 mt-3 mb-3"
										title="Edit Category">
										Edit Category
								</a>
								{{-- Image Upload Section --}}
								<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 p-6 mb-6">
										<x-image-gallery :images="$category->images" title="Category Images" :showSummary="true" :showEmptyState="true" />
								</div>

								{{-- Category Details Card --}}
								<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 p-6 mb-6">
										<div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-300">
												<div>
														<h3 class="text-white font-semibold mb-3 text-lg">Category Information</h3>
														<p class="mb-2"><span class="text-gray-400">Name:</span> {{ $category->name }}</p>
														<p class="mb-2"><span class="text-gray-400">Slug:</span> {{ $category->slug }}</p>
														<p class="mb-2">
																<span class="text-gray-400">Parent Category:</span>
																@if ($category->parent)
																		<a href="{{ route('categories.show', $category->parent->slug) }}"
																				class="text-blue-400 hover:text-blue-300">
																				{{ $category->parent->name }}
																		</a>
																@else
																		<span class="text-gray-500">Main Category</span>
																@endif
														</p>
												</div>
												<div>
														<h3 class="text-white font-semibold mb-3 text-lg">Statistics</h3>
														@if ($category->children->count() > 0)
																<p class="mb-2"><span class="text-gray-400">Subcategories:</span> {{ $category->children->count() }}
																</p>
														@endif

														<p class="mb-2"><span class="text-gray-400">Description:</span></p>
														<p class="text-gray-300 bg-gray-700/30 p-3 rounded-md">
																{{ $category->description ?? 'No description provided' }}
														</p>
												</div>
										</div>
								</div>
								{{-- Action Buttons --}}
								<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
										<div class="flex space-x-3">
												@if ($category->children->count() === 0)
														@if ($category->parent_id !== null && $category->parent->parent_id !== null)
																<x-link-button href="{{ route('products.create', ['category_id' => $category->id]) }}">
																		+ New Product
																</x-link-button>
														@else
																<x-link-button href="{{ route('categories.create', ['category_id' => $category->id]) }}">
																		+ New Category
																</x-link-button>
														@endif
												@else
														<x-link-button href="{{ route('categories.create', ['category_id' => $category->id]) }}">
																++ New Category
														</x-link-button>
												@endif
										</div>

										@if ($category->children->count() === 0 && $products->count() === 0)
												<form action="{{ route('categories.destroy', $category->id) }}" method="POST">
														@csrf
														@method('DELETE')
														<button type="submit"
																class="inline-flex items-center px-4 py-2 bg-red-600/20 backdrop-blur-md border border-red-500/30 rounded-lg font-['Inter'] text-sm text-white uppercase tracking-widest hover:bg-red-700/30 hover:border-red-500/50 focus:bg-red-700/30 active:bg-red-800/40 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2 focus:ring-offset-gray-900 transition ease-in-out duration-150"
																title="Delete Category" onclick="return confirm('Are you sure you want to delete this category?')">
																Delete Category
														</button>
												</form>
										@else
												@if ($category->parent_id === null || ($category->parent_id !== null && $category->parent->parent_id === null))
														<p class="text-yellow-400 text-sm font-medium">
																⚠️ Can't delete this category because it has {{ $category->children->count() }} subcategories
														</p>
												@else
														<p class="text-yellow-400 text-sm font-medium">
																🔔 Can't delete this category because it has {{ $category->products->count() }} products.
														</p>
												@endif
										@endif
								</div>

								{{-- Subcategories Section --}}
								@if ($category->parent_id === null || ($category->parent_id !== null && $category->parent->parent_id === null))
										{{-- Only show for main categories and direct children (not grandchildren) --}}
										<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 overflow-hidden">
												<div class="px-6 py-4 bg-gray-700/50 border-b border-gray-700">
														<h3 class="text-lg font-semibold text-white">
																@if ($category->children->count() > 0)
																		Subcategories ({{ $category->children->count() }})
																@else
																		No Subcategories
																@endif
														</h3>
												</div>

												<div class="p-6">
														@if ($category->children->count() === 0)
																<div class="text-center py-8">
																		<p class="text-gray-400 text-lg italic">No subcategories found</p>
																		<p class="text-gray-500 text-sm mt-2">This category doesn't have any subcategories yet.</p>
																</div>
														@else
																<div class="grid grid-cols-1 gap-6">
																		@foreach ($category->children as $subcategory)
																				<div
																						class="bg-gray-700/30 backdrop-blur-sm border border-gray-600/50 rounded-lg p-6 hover:bg-gray-700/50 transition-colors duration-200">
																						{{-- Main subcategory header --}}
																						<div class="flex justify-between items-start mb-4">
																								<div>
																										<a href="{{ route('categories.show', $subcategory->slug) }}"
																												class="text-xl font-semibold text-white hover:text-blue-300 transition-colors block">
																												{{ $subcategory->name }}
																										</a>
																										<div class="text-sm text-gray-400 mt-1">
																												<span class="mr-4">📁 {{ $subcategory->children->count() }} subcategories</span>
																												<span>🛍️ {{ $subcategory->products->count() }} direct products</span>
																										</div>
																										@if ($subcategory->description)
																												<div class="text-sm text-gray-300 mt-2">
																														{{ Str::limit($subcategory->description, 100) }}
																												</div>
																										@endif
																								</div>
																								<div class="flex space-x-2">
																										<a href="{{ route('categories.edit', $subcategory->id) }}"
																												class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit Subcategory">
																												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																														stroke="currentColor">
																														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																																d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
																												</svg>
																										</a>
																								</div>
																						</div>

																						{{-- Child subcategories --}}
																						@if ($subcategory->children->isNotEmpty())
																								<div class="mt-4">
																										<h5 class="text-sm font-medium text-gray-400 mb-3">Subcategories:</h5>
																										<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
																												@foreach ($subcategory->children as $child)
																														<a href="{{ route('categories.show', $child->slug) }}"
																																class="bg-gray-600/30 hover:bg-gray-600/50 p-3 rounded-md transition-colors duration-150">
																																<div class="text-sm text-white font-medium">{{ $child->name }}</div>
																																<div class="text-xs text-gray-400 mt-1">
																																		Products: {{ $child->products->count() }}
																																		{{-- Subcategories: {{ $child->children->count() }} --}}
																																</div>
																														</a>
																												@endforeach
																										</div>
																								</div>
																						@endif

																						{{-- Direct products in this subcategory --}}
																						@if ($subcategory->products->isNotEmpty())
																								<div class="mt-4">
																										<h5 class="text-sm font-medium text-gray-400 mb-3">Direct Products:</h5>
																										<div class="space-y-2">
																												@foreach ($subcategory->products as $product)
																														<a href="{{ route('products.show', $product->slug) }}"
																																class="flex items-center justify-between bg-gray-600/20 hover:bg-gray-600/40 p-2 rounded-md transition-colors duration-150">
																																<span class="text-sm text-white">{{ $product->name }}</span>
																																{{-- <span class="text-xs text-green-400">${{ number_format($product->price, 2) }}</span> --}}
																														</a>
																												@endforeach
																										</div>
																								</div>
																						@endif
																				</div>

																				{{-- Separator between subcategories --}}
																				@if (!$loop->last)
																						<hr class="border-gray-600/30 my-2">
																				@endif
																		@endforeach
																</div>
														@endif
												</div>
										</div>
								@endif

								{{-- Direct Products Section (if this category has direct products) --}}
								@if ($category->products->count() > 0)
										<div
												class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 overflow-hidden mt-6">
												<div class="px-6 py-4 bg-gray-700/50 border-b border-gray-700">
														<a href="{{ route('products.index', ['category_id' => $category->id]) }}"
																class="inline-flex items-center px-4 py-2 bg-green-600/20 backdrop-blur-md border border-green-500/30 rounded-lg font-['Inter'] text-sm text-white uppercase tracking-widest hover:bg-green-700/30 hover:border-green-500/50 focus:bg-green-700/30 active:bg-green-800/40 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:ring-offset-2 focus:ring-offset-gray-900 transition ease-in-out duration-150">
																Category's Direct Products ({{ $category->products->count() }})
														</a>
												</div>
												<div class="p-6">
														<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
																@foreach ($category->products as $product)
																		<a href="{{ route('products.show', $product->slug) }}"
																				class="bg-gray-700/30 hover:bg-gray-700/50 p-4 rounded-md transition-colors duration-150">
																				<div class="text-white font-medium">{{ $product->name }}</div>
																				{{-- <div class="text-sm text-gray-400 mt-1">${{ number_format($product->price, 2) }}</div> --}}
																		</a>
																@endforeach
														</div>
												</div>
										</div>
								@endif
						</div>
				</div>
		</div>
</x-app-layout>
