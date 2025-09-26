<x-app-layout>
		<x-slot name="header">
				<h2 class="inline font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						Add Products to Order
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat min-w-full">
				<div class="py-2">
						<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
								<!-- Back Button -->
								<div class="mb-6">
										<a href="{{ route('orders.index') }}"
												class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors">
												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
														stroke="currentColor">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
												</svg>
												Back to Orders
										</a>
								</div>
								{{-- searchForm --}}
								@include('partials.searchForm', [
										'route' => route('orders.addProducts', $order),
										'placeholder' => 'Seacrch Products...',
								])

								<!-- Selected items -->
								@if (!empty($selectedProducts))
										<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 p-6 mb-6">
												<h3 class="text-lg font-medium text-white mb-4">Current Selection</h3>
												<div class="space-y-3">
														@php $subtotal = 0; @endphp
														@foreach ($selectedProducts as $productId => $item)
																@php $subtotal += $item['product']->price * $item['quantity']; @endphp
																<div class="flex justify-between items-center py-2 border-b border-gray-700/30">
																		<div class="flex items-center space-x-3">
																				<div class="h-10 w-10 rounded overflow-hidden">
																						<img src="{{ asset('/product-images/' . $item['product']->slug . '.jpg') }}"
																								alt="{{ $item['product']->name }}" class="h-full w-full object-cover">
																				</div>
																				<span class="text-white">{{ $item['product']->name }}</span>
																		</div>
																		<div class="flex items-center space-x-4">
																				<span class="text-gray-300">${{ number_format($item['product']->price, 2) }} ×
																						{{ $item['quantity'] }}</span>
																				<span
																						class="text-white font-medium">${{ number_format($item['product']->price * $item['quantity'], 2) }}</span>
																				<form action="{{ route('orders.removeProduct', ['order' => $order, 'product' => $productId]) }}"
																						method="POST" class="inline">
																						@csrf
																						<button type="submit" class="text-red-400 hover:text-red-300 transition-colors"
																								title="Remove from order">
																								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																										stroke="currentColor">
																										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																												d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
																								</svg>
																						</button>
																				</form>
																		</div>
																</div>
														@endforeach
												</div>
												<div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-700/30">
														<span class="text-lg font-medium text-white">Subtotal: ${{ number_format($subtotal, 2) }}</span>
														<div class="flex space-x-2">
																<form action="{{ route('orders.clearSelection', $order) }}" method="POST">
																		@csrf
																		<button type="submit"
																				class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
																				Clear All
																		</button>
																</form>
																<form action="{{ route('orders.finalize', $order) }}" method="POST">
																		@csrf
																		<button type="submit"
																				class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
																				Add All to Order
																		</button>
																</form>
														</div>
												</div>
										</div>
								@endif

								<!-- Products Table with plus and minus buttons-->
								@include('products.partials.products', ['showPlusButton' => true])
						</div>
				</div>
		</div>
</x-app-layout>
