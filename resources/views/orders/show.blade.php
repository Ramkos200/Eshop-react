<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Order Details') }} | <span class="text-2xl font-['Roboto']"> {{ $order->order_code }}</span>
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat py-4">
				<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

						<!-- Success/Error Messages -->
						@if (session('success'))
								<div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-300">
										{{ session('success') }}
								</div>
						@endif

						@if (session('error'))
								<div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-lg text-red-300">
										{{ session('error') }}
								</div>
						@endif

						<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

								<!-- Left Column - Order Information -->
								<div class="lg:col-span-2 space-y-6">

										<!-- Order Summary Card -->
										<div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-6">
												<h3 class="text-lg font-semibold text-white mb-4">Order Summary</h3>
												<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
														<div>
																<p class="text-sm text-gray-300">Order Code</p>
																<p class="text-white font-medium">{{ $order->order_code }}</p>
														</div>
														<div>
																<p class="text-sm text-gray-300">Status</p>
																<span
																		class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $order->status === 'delivered'
																																				    ? 'bg-green-100 text-green-800'
																																				    : ($order->status === 'pending'
																																				        ? 'bg-yellow-100 text-yellow-800'
																																				        : ($order->status === 'cancelled'
																																				            ? 'bg-red-100 text-red-800'
																																				            : ($order->status === 'processing'
																																				                ? 'bg-blue-100 text-blue-800'
																																				                : ($order->status === 'shipped'
																																				                    ? 'bg-purple-100 text-purple-800'
																																				                    : 'bg-gray-100 text-gray-800')))) }}">
																		{{ ucfirst($order->status) }}
																</span>
														</div>
														<div>
																<p class="text-sm text-gray-300">Total Amount</p>
																<p class="text-white font-medium text-lg">${{ number_format($order->total_amount, 2) }}</p>
														</div>
														<div>
																<p class="text-sm text-gray-300">Created Date</p>
																<p class="text-white">{{ $order->created_at->format('M j, Y g:i A') }}</p>
														</div>
												</div>

												@if ($order->notes)
														<div class="mt-4">
																<p class="text-sm text-gray-300">Notes</p>
																<p class="text-white">{{ $order->notes }}</p>
														</div>
												@endif
										</div>

										<!-- Order Items Card -->
										<div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-6">
												<div class="flex justify-between items-center mb-4">
														<h3 class="text-lg font-semibold text-white">Order Items ({{ $order->items->count() }})</h3>
														<a href="{{ route('orders.addProducts', $order) }}"
																class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm transition">
																Add Products
														</a>
												</div>

												@if ($order->items->count() > 0)
														<div class="overflow-x-auto">
																<table class="min-w-full divide-y divide-gray-700">
																		<thead>
																				<tr>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Product</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">SKU</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Price</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Qty</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">Subtotal</th>
																				</tr>
																		</thead>
																		<tbody class="divide-y divide-gray-700">
																				@foreach ($order->items as $item)
																						<tr>
																								<td class="px-4 py-3">
																										<div class="text-white font-medium">
																												{{ $item->sku->product->name ?? 'Product Not Found' }}
																										</div>
																										@if ($item->attributes && is_array($item->attributes))
																												<div class="text-sm text-gray-300">
																														@foreach ($item->attributes as $key => $value)
																																@if (!empty($value))
																																		<span class="capitalize">{{ $key }}: {{ $value }}</span>
																																		@if (!$loop->last)
																																				•
																																		@endif
																																@endif
																														@endforeach
																												</div>
																										@endif
																								</td>
																								<td class="px-4 py-3 text-gray-300 text-sm">
																										{{ $item->sku_code }}
																								</td>
																								<td class="px-4 py-3 text-white">
																										${{ number_format($item->price, 2) }}
																								</td>
																								<td class="px-4 py-3 text-white">
																										{{ $item->quantity }}
																								</td>
																								<td class="px-4 py-3 text-white font-medium">
																										${{ number_format($item->price * $item->quantity, 2) }}
																								</td>
																						</tr>
																				@endforeach
																		</tbody>
																		<tfoot class="bg-gray-700/50">
																				<tr>
																						<td colspan="4" class="px-4 py-3 text-right text-gray-300 font-medium">Total:</td>
																						<td class="px-4 py-3 text-white font-bold text-lg">
																								${{ number_format($order->total_amount, 2) }}
																						</td>
																				</tr>
																		</tfoot>
																</table>
														</div>
												@else
														<div class="text-center py-8 text-gray-300">
																<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-500 mb-2" fill="none"
																		viewBox="0 0 24 24" stroke="currentColor">
																		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																				d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
																</svg>
																<p class="text-lg">No items in this order</p>
																<p class="text-sm mt-1">Add products to get started</p>
																<a href="{{ route('orders.addProducts', $order) }}"
																		class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition">
																		Add Products
																</a>
														</div>
												@endif
										</div>
								</div>

								<!-- Right Column - Customer & Actions -->
								<div class="space-y-6">

										<!-- Customer Information Card -->
										<div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-6">
												<div class="flex justify-between items-center mb-4">
														<h3 class="text-lg font-semibold text-white">Customer Information</h3>
														<button onclick="toggleEditCustomer()" class="text-blue-400 hover:text-blue-300 text-sm">
																Edit
														</button>
												</div>

												<!-- Display Customer Information -->
												<div id="customerDisplay">
														@if ($order->user)
																<div class="space-y-3">
																		<div>
																				<p class="text-sm text-gray-300">Name</p>
																				<p class="text-white">{{ $order->user->name }}</p>
																		</div>
																		<div>
																				<p class="text-sm text-gray-300">Email</p>
																				<p class="text-white">{{ $order->user->email }}</p>
																		</div>
																		@if ($order->user->phone)
																				<div>
																						<p class="text-sm text-gray-300">Phone</p>
																						<p class="text-white">{{ $order->user->phone }}</p>
																				</div>
																		@endif
																</div>
														@elseif($order->Customer && is_array($order->Customer))
																<div class="space-y-3">
																		<div>
																				<p class="text-sm text-gray-300">Name</p>
																				<p class="text-white">{{ $order->Customer['name'] ?? 'N/A' }}</p>
																		</div>
																		<div>
																				<p class="text-sm text-gray-300">Email</p>
																				<p class="text-white">{{ $order->Customer['email'] ?? 'N/A' }}</p>
																		</div>
																		@if (isset($order->Customer['phone']))
																				<div>
																						<p class="text-sm text-gray-300">Phone</p>
																						<p class="text-white">{{ $order->Customer['phone'] }}</p>
																				</div>
																		@endif
																</div>
														@else
																<p class="text-gray-300">No customer information available</p>
														@endif
												</div>

												<!-- Edit Customer Form (Hidden by default) -->
												<div id="editCustomerForm" class="hidden mt-4 p-4 bg-gray-700/50 rounded-lg">
														<form action="{{ route('orders.updateUser', $order) }}" method="POST">
																@csrf
																@method('patch')
																<div class="space-y-3">
																		<div>
																				<label class="text-sm text-gray-300 block mb-1">Name *</label>
																				<input type="text" name="name"
																						value="{{ $order->user->name ?? ($order->Customer['name'] ?? '') }}"
																						class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm" required>
																		</div>
																		<div>
																				<label class="text-sm text-gray-300 block mb-1">Email *</label>
																				<input type="email" name="email"
																						value="{{ $order->user->email ?? ($order->Customer['email'] ?? '') }}"
																						class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm" required>
																		</div>
																		<div>
																				<label class="text-sm text-gray-300 block mb-1">Phone</label>
																				<input type="text" name="phone"
																						value="{{ $order->user->phone ?? ($order->Customer['phone'] ?? '') }}"
																						class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm">
																		</div>
																		<div class="flex space-x-2">
																				<button type="submit"
																						class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm">
																						Save Changes
																				</button>
																				<button type="button" onclick="toggleEditCustomer()"
																						class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm">
																						Cancel
																				</button>
																		</div>
																</div>
														</form>
												</div>
										</div>

										<!-- Shipping Address Card -->
										<div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-6">
												<div class="flex justify-between items-center mb-4">
														<h3 class="text-lg font-semibold text-white">Shipping Address</h3>
														<button onclick="toggleEditAddress()" class="text-blue-400 hover:text-blue-300 text-sm">
																Edit
														</button>
												</div>

												@if ($order->shipping_address && is_array($order->shipping_address))
														<div class="space-y-2 text-white">
																<p class="font-medium">Street Address: {{ $order->shipping_address['street_address'] }}</p>
																<p>City: {{ $order->shipping_address['city'] }} |
																		State: {{ $order->shipping_address['state'] }}</p>
																<p> Zip code: {{ $order->shipping_address['zip_code'] }}</p>
																<p>Country: {{ $order->shipping_address['country'] }}</p>
														</div>
												@else
														<p class="text-gray-300">No shipping address provided</p>
												@endif

												<!-- Edit Address Form -->
												<div id="editAddressForm" class="hidden mt-4 p-4 bg-gray-700/50 rounded-lg">
														<form action="{{ route('orders.updateAddress', $order) }}" method="POST">
																@csrf
																@method('patch')
																<div class="space-y-3">
																		<div>
																				<label class="text-sm text-gray-300 block mb-1">Street Address *</label>
																				<input type="text" name="street_address"
																						value="{{ $order->shipping_address['street_address'] ?? '' }}"
																						class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm" required>
																		</div>
																		<div class="grid grid-cols-2 gap-2">
																				<div>
																						<label class="text-sm text-gray-300 block mb-1">City *</label>
																						<input type="text" name="city" value="{{ $order->shipping_address['city'] ?? '' }}"
																								class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm" required>
																				</div>
																				<div>
																						<label class="text-sm text-gray-300 block mb-1">State *</label>
																						<input type="text" name="state" value="{{ $order->shipping_address['state'] ?? '' }}"
																								class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm" required>
																				</div>
																		</div>
																		<div class="grid grid-cols-2 gap-2">
																				<div>
																						<label class="text-sm text-gray-300 block mb-1">ZIP Code *</label>
																						<input type="text" name="zip_code" value="{{ $order->shipping_address['zip_code'] ?? '' }}"
																								class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm" required>
																				</div>
																				<div>
																						<label class="text-sm text-gray-300 block mb-1">Country *</label>
																						<input type="text" name="country" value="{{ $order->shipping_address['country'] ?? '' }}"
																								class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm" required>
																				</div>
																		</div>
																		<div class="flex space-x-2">
																				<button type="submit"
																						class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm">
																						Save Address
																				</button>
																				<button type="button" onclick="toggleEditAddress()"
																						class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm">
																						Cancel
																				</button>
																		</div>
																</div>
														</form>
												</div>
										</div>
										<!-- Quick Actions Card -->
										<div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-lg p-6">
												<h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
												<div class="space-y-3">
														<!-- Update Status Form -->
														<form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="flex gap-2">
																@csrf
																@method('patch')
																<select name="status"
																		class="flex-1 px-3 py-2 bg-white/5 border border-white/10 rounded text-white text-sm">
																		<option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
																		<option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing
																		</option>
																		<option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
																		<option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
																		<option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
																</select>

																<button type="submit"
																		class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium">
																		Update Status
																</button>
														</form>

														<!-- Navigation Buttons -->
														<div class="grid grid-cols-2 gap-2">
																<a href="{{ route('orders.addProducts', $order) }}"
																		class="bg-green-500 hover:bg-green-600 text-white text-center py-2 rounded text-sm transition">
																		Add Products
																</a>
																<a href="{{ route('orders.index') }}"
																		class="bg-gray-500 hover:bg-gray-600 text-white text-center py-2 rounded text-sm transition">
																		Back to List
																</a>
														</div>
												</div>
										</div>
								</div>
						</div>
				</div>
		</div>

		<script>
				function toggleEditAddress() {
						const form = document.getElementById('editAddressForm');
						form.classList.toggle('hidden');
				}

				function toggleEditCustomer() {
						const display = document.getElementById('customerDisplay');
						const form = document.getElementById('editCustomerForm');
						display.classList.toggle('hidden');
						form.classList.toggle('hidden');
				}
		</script>
</x-app-layout>
