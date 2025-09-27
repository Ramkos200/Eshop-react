<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 overflow-hidden">
		<div class="overflow-x-auto min-w-full">
				<table class="min-w-full divide-y divide-gray-700">
						<thead class="bg-gray-700/50">
								<tr>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
												Image
										</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
												<a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
														class="flex items-center group hover:text-white">
														Variant SKU
														<span class="ml-1 flex flex-col">
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'name' && request('direction') === 'asc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 14L0 9h10L5 14z" />
																</svg>
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'name' && request('direction') === 'desc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 2L10 7H0L5 2z" />
																</svg>
														</span>
												</a>
										</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
												Product
										</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
												<a href="{{ request()->fullUrlWithQuery(['sort' => 'category', 'direction' => request('sort') === 'category' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
														class="flex items-center group hover:text-white">
														Category
														<span class="ml-1 flex flex-col">
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'category' && request('direction') === 'asc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 14L0 9h10L5 14z" />
																</svg>
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'category' && request('direction') === 'desc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 2L10 7H0L5 2z" />
																</svg>
														</span>
												</a>
										</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
												<a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('sort') === 'price' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
														class="flex items-center group hover:text-white">
														Price
														<span class="ml-1 flex flex-col">
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'price' && request('direction') === 'asc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 14L0 9h10L5 14z" />
																</svg>
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'price' && request('direction') === 'desc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 2L10 7H0L5 2z" />
																</svg>
														</span>
												</a>
										</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
												<a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
														class="flex items-center group hover:text-white">
														Status
														<span class="ml-1 flex flex-col">
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'status' && request('direction') === 'asc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 14L0 9h10L5 14z" />
																</svg>
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'status' && request('direction') === 'desc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 2L10 7H0L5 2z" />
																</svg>
														</span>
												</a>
										</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
												<a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
														class="flex items-center group hover:text-white">
														Created
														<span class="ml-1 flex flex-col">
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'created_at' && request('direction') === 'asc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 14L0 9h10L5 14z" />
																</svg>
																<svg
																		class="w-3 h-3 fill-current {{ request('sort') === 'created_at' && request('direction') === 'desc' ? 'text-amber-500' : 'text-gray-500' }} group-hover:text-gray-300"
																		viewBox="0 0 10 16" fill="currentColor">
																		<path d="M5 2L10 7H0L5 2z" />
																</svg>
														</span>
												</a>
										</th>
										<th scope="col" class="px-8 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
												Actions
										</th>
								</tr>
						</thead>
						<tbody class="bg-gray-800/50 divide-y divide-gray-700">
								@forelse ($products as $product)
										@foreach ($product->skus as $variant)
												<tr class="hover:bg-gray-700/30 transition-colors duration-150">
														<td class="px-6 py-4 whitespace-nowrap">
																<div class="flex space-x-2">
																		@if ($variant->images && $variant->images->count() > 0)
																				@foreach ($variant->images->take(3) as $image)
																						<div class="h-12 w-12 rounded overflow-hidden border border-gray-600">
																								<img src="{{ asset('storage/' . $image->path) }}" alt="{{ $variant->name }}"
																										class="h-full w-full object-cover">
																						</div>
																				@endforeach
																				@if ($variant->images->count() > 3)
																						<div class="h-12 w-12 rounded bg-gray-700 flex items-center justify-center text-xs text-gray-400">
																								+{{ $variant->images->count() - 3 }}
																						</div>
																				@endif
																		@else
																				<div class="h-12 w-12 rounded bg-gray-700 flex items-center justify-center text-xs text-gray-400">
																						No Image
																				</div>
																		@endif
																</div>
														</td>
														<td class="px-6 py-4 whitespace-nowrap">
																<div class="text-white font-medium">
																		{{ $variant->code }}
																</div>
														</td>
														<td class="px-6 py-4 whitespace-nowrap">
																<a href="{{ route('products.show', $product->slug) }}" class="text-white font-medium hover:underline">
																		{{ $product->name }}
																</a>
														</td>
														<td class="px-6 py-4 whitespace-nowrap">
																<div class="text-sm text-gray-300">
																		{{ $product->category->name ?? 'No Category' }}
																</div>
														</td>
														<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
																${{ number_format($variant->price ?? $product->price, 2) }}
														</td>
														<td class="px-6 py-4 whitespace-nowrap">
																<span
																		class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ ($variant->status ?? $product->status) === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
																		{{ $variant->status ?? $product->status }}
																</span>
														</td>
														<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
																{{ $variant->created_at->format('M d, Y') }}
														</td>
														<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
																<div class="flex justify-end space-x-2">
																		@if (isset($order) && $order)
																				{{-- Minus Button --}}
																				@if (isset($selectedProducts[$variant->id]) && $selectedProducts[$variant->id]['quantity'] > 0)
																						@include('orders.partials.minusbutton', ['sku' => $variant, 'order' => $order])
																				@else
																						<span class="w-5"></span>
																				@endif

																				{{-- Plus Button --}}
																				@include('orders.partials.plusbutton', ['sku' => $variant, 'order' => $order])
																		@else
																				{{-- edit button --}}
																				@include('products.partials.edit', [
																						'action_route' => route('skus.edit', $variant),
																				])
																				{{-- delete button --}}
																				@include('products.partials.delete', [
																						'action_route' => route('skus.destroy', $variant),
																				])
																		@endif
																</div>
														</td>
												</tr>
										@endforeach
								@empty
										<tr>
												<td colspan="8" class="px-6 py-4 text-center text-sm text-gray-400">
														No variants found.
												</td>
										</tr>
								@endforelse
						</tbody>
				</table>
		</div>

		<!-- Pagination -->
		@if ($products->hasPages())
				<div class="px-6 py-4 bg-gray-700/50 border-t border-gray-700">
						<div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
								<div class="text-sm text-gray-400">
										Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
										results
								</div>
								<div class="flex space-x-2">
										{{ $products->links() }}
								</div>
						</div>
				</div>
		@endif
</div>
