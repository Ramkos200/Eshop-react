<x-app-layout :backgroundImage="asset('images/orders.jpg')">
		<x-slot name="header">
				<h2 class="inline-flex font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						Order#:
				</h2>
				<span class="inline-flex text-white text-lg">{{ __($order->order_code) }}</span>
				<h2 class="inline-flex font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						| Customer:
				</h2>
				<span
						class="inline-flex text-white text-lg">{{ __($order->user->name ?? json_decode($order->Customer)->name) }}</span>
		</x-slot>
		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-4">
						<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
								<x-link-button href="{{ route('orders.index') }}"> <- Back </x-link-button>
												<x-link-button href="{{ route('orders.addProducts', $order) }}"> Add Product </x-link-button>

												<!-- Order Summary -->
												<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 p-6 mb-6 mt-2">
														<div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-300">
																<div>
																		<h3 class="text-white font-semibold mb-2">Order Information</h3>
																		<p><span class="text-gray-400">Order Code:</span> {{ $order->order_code }}</p>
																		<p>
																				<span class="text-gray-400">Status:</span>
																				<span
																						class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                           {{ $order->status === 'delivered'
																											    ? 'bg-green-100 text-green-800'
																											    : ($order->status === 'pending'
																											        ? 'bg-yellow-100 text-yellow-800'
																											        : ($order->status === 'cancelled'
																											            ? 'bg-red-100 text-red-800'
																											            : ($order->status === 'processing'
																											                ? 'bg-blue-100 text-blue-800'
																											                : ($order->status === 'shipped'
																											                    ? 'bg-indigo-100 text-indigo-800'
																											                    : 'bg-gray-100 text-gray-800')))) }}">
																						{{ $order->status }}
																				</span>
																		</p>
																		<p><span class="text-gray-400">Total Amount:</span> ${{ number_format($order->total_amount, 2) }}</p>
																		<!-- Status Update Form -->
																		<div class="mt-4">
																				<form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="flex items-center">
																						@csrf
																						@method('patch')
																						<label for="status" class="text-gray-400 mr-2">Update Status:</label>
																						<select name="status" id="status"
																								class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-1.5">
																								<option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
																								<option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
																								</option>
																								<option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
																								<option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
																								</option>
																								<option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
																								</option>
																						</select>
																						<button type="submit"
																								class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm transition-colors duration-200">
																								Update
																						</button>
																				</form>
																		</div>
																</div>
																<div>
																		<h3 class="text-white font-semibold mb-2">Customer Information</h3>
																		<p><span class="text-gray-400">Name:</span>
																				{{ $order->user->name ?? json_decode($order->Customer)->name }}</p>
																		<p><span class="text-gray-400">Email:</span>
																				{{ $order->user->email ?? json_decode($order->Customer)->email }}
																		</p>
																		<p><span class="text-gray-400">Phone:</span> {{ $order->user->phone ?? 'Not provided' }}</p>
																		<button onclick="toggleCustomerForm()"
																				class="text-blue-400 hover:text-blue-300 text-sm flex items-center">
																				<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
																						stroke="currentColor">
																						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																								d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
																				</svg>
																				Edit User Details
																		</button>
																		<!-- Customer edit form -->
																		<div id="customerForm" class="mt-3 p-3 bg-gray-700/70 rounded-md hidden">
																				<form action="{{ route('orders.updateUser', $order) }}" method="POST">
																						@csrf
																						@method('patch')
																						<div class="grid grid-cols-1 gap-2 text-sm">
																								<div>
																										<label for="name" class="text-gray-300">Customer Name</label>
																										<input type="text" name="name" id="name"
																												value="{{ $order->user->name ?? json_decode($order->Customer)->name }}"
																												class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																								</div>
																								<div class="grid grid-cols-2 gap-2">
																										<div>
																												<label for="email" class="text-gray-300">Email</label>
																												<input type="text" name="email" id="email"
																														value="{{ $order->user->email ?? json_decode($order->Customer)->email }}"
																														class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																										</div>
																										<div>
																												<label for="phone" class="text-gray-300">Phone</label>
																												<input type="text" name="phone" id="phone"
																														value="{{ $order->user->phone ?? 'Not Provided' }}"
																														class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																										</div>
																								</div>
																								<div class="flex justify-end space-x-2 mt-2">
																										<button type="button" onclick="toggleCustomerForm()"
																												class="px-3 py-1.5 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-500">
																												Cancel
																										</button>
																										<button type="submit"
																												class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-500">
																												Save
																										</button>
																								</div>
																						</div>
																				</form>
																		</div>
																</div>
																<div>
																		{{-- shipping address form --}}
																		<h3 class="text-white font-semibold mb-2">Shipping Address</h3>
																		@if ($order->shipping_address)
																				<div class="text-gray-300 text-sm mb-2">
																						<p>{{ json_decode($order->shipping_address)->{'Street Address'} }}</p>
																						<p>{{ json_decode($order->shipping_address)->city }},
																								{{ json_decode($order->shipping_address)->state }}
																								{{ json_decode($order->shipping_address)->{'Zip Code'} }}</p>
																						<p>{{ json_decode($order->shipping_address)->Country }}</p>
																				</div>
																				<button onclick="toggleAddressForm()"
																						class="text-blue-400 hover:text-blue-300 text-sm flex items-center">
																						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
																								stroke="currentColor">
																								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																										d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
																						</svg>
																						Edit Address
																				</button>
																		@else
																				<p class="text-gray-400 text-sm mb-2">No shipping address provided</p>
																				<button onclick="toggleAddressForm()"
																						class="text-blue-400 hover:text-blue-300 text-sm flex items-center">
																						<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
																								stroke="currentColor">
																								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																										d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
																						</svg>
																						Add Address
																				</button>
																		@endif

																		<!-- Address Edit  -->
																		<div id="addressForm" class="mt-3 p-3 bg-gray-700/70 rounded-md hidden">
																				<form action="{{ route('orders.updateAddress', $order) }}" method="POST">
																						@csrf
																						@method('patch')
																						<div class="grid grid-cols-1 gap-2 text-sm">
																								<div>
																										<label for="street" class="text-gray-300">Street Address</label>
																										<input type="text" name="street" id="street"
																												value="{{ json_decode($order->shipping_address)->{'Street Address'} ?? '' }}"
																												class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																								</div>
																								<div class="grid grid-cols-2 gap-2">
																										<div>
																												<label for="city" class="text-gray-300">City</label>
																												<input type="text" name="city" id="city"
																														value="{{ json_decode($order->shipping_address)->city ?? '' }}"
																														class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																										</div>
																										<div>
																												<label for="state" class="text-gray-300">State</label>
																												<input type="text" name="state" id="state"
																														value="{{ json_decode($order->shipping_address)->state ?? '' }}"
																														class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																										</div>
																								</div>
																								<div class="grid grid-cols-2 gap-2">
																										<div>
																												<label for="zip_code" class="text-gray-300">ZIP Code</label>
																												<input type="text" name="zip_code" id="zip_code"
																														value="{{ json_decode($order->shipping_address)->{'Zip Code'} ?? '' }}"
																														class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																										</div>
																										<div>
																												<label for="country" class="text-gray-300">Country</label>
																												<input type="text" name="country" id="country"
																														value="{{ json_decode($order->shipping_address)->Country ?? '' }}"
																														class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																										</div>
																								</div>
																								<div class="flex justify-end space-x-2 mt-2">
																										<button type="button" onclick="toggleAddressForm()"
																												class="px-3 py-1.5 bg-gray-600 text-white rounded-md text-sm hover:bg-gray-500">
																												Cancel
																										</button>
																										<button type="submit"
																												class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-500">
																												Save Address
																										</button>
																								</div>
																						</div>
																				</form>
																		</div>

																</div>
														</div>
												</div>

												<!-- Order Items Table -->
												<div
														class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 overflow-hidden">
														<div class="px-6 py-4 bg-gray-700/50 border-b border-gray-700">
																<h3 class="text-lg font-semibold text-white">Order Items</h3>
														</div>
														<div class="overflow-x-auto">
																<table class="min-w-full divide-y divide-gray-700">
																		<thead class="bg-gray-700/50">
																				<tr>
																						<th scope="col"
																								class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																								Product
																						</th>
																						<th scope="col"
																								class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																								SKU
																						</th>
																						<th scope="col"
																								class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																								Attributes
																						</th>
																						<th scope="col"
																								class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																								Price
																						</th>
																						<th scope="col"
																								class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																								Quantity
																						</th>
																						<th scope="col"
																								class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																								Subtotal
																						</th>
																						<th scope="col"
																								class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
																								Actions
																						</th>
																				</tr>
																		</thead>
																		<tbody class="bg-gray-800/50 divide-y divide-gray-700">
																				@foreach ($order->items as $item)
																						<tr class="hover:bg-gray-700/30 transition-colors duration-150">
																								<td class="px-6 py-4 whitespace-nowrap">
																										<div class="text-sm text-white">{{ $item->sku->product->name }}</div>
																								</td>
																								<td class="px-6 py-4 whitespace-nowrap">
																										<div class="text-sm text-gray-300">{{ $item->sku_code }}</div>
																								</td>
																								<td class="px-6 py-4">
																										<div class="text-sm text-gray-300">
																												@if (isset($item->attributes['color']))
																														<div>Color: {{ $item->attributes['color'] }}</div>
																												@endif
																												@if (isset($item->attributes['size']))
																														<div>Size: {{ $item->attributes['size'] }}</div>
																												@endif
																												@if (isset($item->attributes['material']))
																														<div>Material: {{ $item->attributes['material'] }}</div>
																												@endif
																										</div>
																								</td>
																								<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
																										${{ number_format($item->price, 2) }}
																								</td>
																								<td class="px-6 py-4 whitespace-nowrap text-sm text-white">
																										{{ $item->quantity }}
																								</td>
																								<td class="px-6 py-4 whitespace-nowrap text-sm text-white">
																										${{ number_format($item->price * $item->quantity, 2) }}
																								</td>
																								<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
																										<div class="flex justify-end space-x-2">
																												<a href="{{ route('orderItem.edit', $item) }}"
																														class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit Order Item">
																														<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
																																viewBox="0 0 24 24" stroke="currentColor">
																																<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																																		d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
																														</svg>
																												</a>
																												<form action="{{ route('orderItem.destroy', $item) }}" method="POST" class="inline">
																														@csrf
																														@method('DELETE')
																														<button type="submit" class="text-red-400 hover:text-red-300 transition-colors"
																																onclick="return confirm('Are you sure you want to remove this item from the order?')"
																																title="Remove Item">
																																<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
																																		viewBox="0 0 24 24" stroke="currentColor">
																																		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																																				d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
																																</svg>
																														</button>
																												</form>
																										</div>
																								</td>
																						</tr>
																				@endforeach
																				<!-- Order Total Row -->
																				<tr class="bg-gray-700/50">
																						<td colspan="5" class="px-6 py-4 text-right text-sm font-medium text-gray-300">
																								Order Total:
																						</td>
																						<td class="px-6 py-4 text-sm font-medium text-white">
																								${{ number_format($order->total_amount, 2) }}
																						</td>
																						<td class="px-6 py-4"></td>
																				</tr>
																		</tbody>
																</table>
														</div>
												</div>
						</div>
				</div>
		</div>


		<script>
				function toggleAddressForm() {
						const form = document.getElementById('addressForm');
						form.classList.toggle('hidden');
				}

				function toggleCustomerForm() {
						const form = document.getElementById('customerForm');
						form.classList.toggle('hidden');
				}
		</script>
</x-app-layout>
