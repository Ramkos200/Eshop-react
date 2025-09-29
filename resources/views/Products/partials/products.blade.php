@if ($showTrash)
		<span class="text-yellow-500 italic text-xl">Restore the product to edit it or see its variants</span>
@endif
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
														Name
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
												Description
										</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
												<a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}"
														class="flex items-center group hover:text-white">
														Category
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
												Price
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
										<tr class="hover:bg-gray-700/30 transition-colors duration-150">
												<td class="px-6 py-4 whitespace-nowrap">
														<div class="h-12 w-12 rounded overflow-hidden">
																<img src="{{ asset('/product-images/' . $product->slug . '.jpg') }}" alt="{{ $product->slug }}"
																		class="h-full w-full object-cover">
														</div>
												</td>
												<td class="px-6 py-4 whitespace-nowrap">
														@if (!$showTrash)
																<a href="{{ route('products.show', $product->slug) }}" class="text-white font-medium hover:underline">
																		{{ $product->name }}
																</a>
														@else
																<div class="text-white"> {{ $product->name }}</div>
														@endif
														<div class="text-sm text-gray-400 mt-1">
																variants: {{ $product->skus->count() }}
														</div>
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-white">
														{{ Str::limit($product->description, 20) }}
												</td>
												@if ($product->category)
														<td class="px-6 py-4 whitespace-nowrap text-white ">
																{{ $product->category->name }}
														</td>
												@else
														<td class="px-6 py-4 whitespace-nowrap text-red-500 ">
																NO CATEGORY
														</td>
												@endif
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
														${{ number_format($product->skus->min('price'), 2) }}--${{ number_format($product->skus->max('price'), 2) }}
												</td>
												<td class="px-6 py-4 whitespace-nowrap">
														<span
																class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $product->status === 'Published'
																																																    ? 'bg-green-100 text-green-800'
																																																    : ($product->status === 'Draft'
																																																        ? 'bg-yellow-100 text-yellow-800'
																																																        : ($product->status === 'Archived'
																																																            ? 'bg-red-100 text-red-800'
																																																            : 'bg-gray-100 text-gray-800')) }}">
																{{ $product->status }}
														</span>
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
														{{ $product->created_at->format('M d, Y') }}
												</td>
												@if ($showPlusButton)
														<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
																<div class="flex justify-end space-x-2">
																		{{-- Minus Button  --}}
																		@if (isset($selectedProducts[$product->id]) && $selectedProducts[$product->id]['quantity'] > 0)
																				@include('orders.partials.minusbutton')
																		@else
																				<span class="w-5"></span>
																				{{-- Spacer for alignment  --}}
																		@endif

																		{{-- Plus Button  --}}
																		@include('orders.partials.plusbutton')
																</div>
														</td>
												@endif
												@if (!$showPlusButton)
														<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
																<div class="flex justify-end space-x-2">
																		@if (!$showTrash)
																				{{-- edit button --}}
																				@include('products.partials.edit', [
																						'action_route' => route('products.edit', $product),
																				])
																		@else
																				{{-- restore button --}}
																				@include('products.partials.restore', [
																						'action_route' => route('products.restore', $product),
																				])
																		@endif
																		{{-- delete button --}}
																		@include('products.partials.delete', [
																				'action_route' => route('products.destroy', $product),
																		])
																</div>
														</td>
												@endif
										</tr>
								@empty
										<tr>
												<td colspan="7" class="px-6 py-4 text-center text-sm text-gray-400">
														No products found.
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
