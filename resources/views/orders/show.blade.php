<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white">
						{{ __('Order Details') }} | <span class="text-2xl font-['Roboto']"> {{ $order->order_code }}</span>
				</h2>
		</x-slot>

		<div class="min-h-screen bg-gray-900 py-4">
				{{-- Changed from bg-cover to solid color --}}
				<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

						{{-- Success/Error Messages --}}
						@if (session('success'))
								<div class="mb-6 p-4 bg-green-500/40 border border-green-500/50 rounded-lg text-green-300">
										{{ session('success') }}
								</div>
						@endif

						@if (session('error'))
								<div class="mb-6 p-4 bg-red-500/40 border border-red-500/50 rounded-lg text-red-300">
										{{ session('error') }}
								</div>
						@endif

						<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
								<div class="lg:col-span-2 space-y-6">
										{{-- Order Summary --}}
										<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
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
																		{{ $order->status }}
																</span>
														</div>
														<div>
																<p class="text-sm text-gray-300">Total Amount</p>
																<p class="text-white font-medium text-lg">${{ number_format($order->total_amount, 2) }}
																</p>
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

										{{-- Order Items Card --}}
										<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
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
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">
																								Image</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">
																								Product</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">
																								SKU</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">
																								Price</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">
																								Qty</th>
																						<th class="px-4 py-2 text-left text-xs font-medium text-gray-300 uppercase">
																								Subtotal</th>
																				</tr>
																		</thead>
																		<tbody class="divide-y divide-gray-700">
																				@foreach ($order->items as $item)
																						<tr>
																								<td>
																										<div class="h-12 w-12 rounded overflow-hidden">
																												@if ($item->sku->mainImage)
																														<img src="{{ Storage::url($item->sku->mainImage->path) }}"
																																alt="{{ $item->sku->mainImage->alt_text }}" class="h-full w-full object-cover">
																												@else
																														<div class="h-full w-full bg-gray-600 flex items-center justify-center">
																																<span class="text-white text-xs">No Image</span>
																														</div>
																												@endif
																										</div>
																								</td>
																								<td class="px-4 py-3">
																										<div class="text-white font-medium">
																												{{ $item->sku->product->name ?? 'Product Not Found' }}
																										</div>
																										<div class="text-sm text-gray-300">
																												@foreach ($item->attributes as $key => $value)
																														@if (!empty($value))
																																<span class="capitalize">{{ $key }}: {{ $value }}</span>
																														@endif
																												@endforeach
																										</div>
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
																						<td colspan="2">
																								<label class="py-3 text-white text-sm px-2">Payment Method</label>
																						</td>
																						<td>
																								<select id="paymentMethod"
																										class="px-3 py-2 w-full bg-gray-700 border border-gray-600 rounded text-white">
																										<option value="">Select a Payment</option>
																										<option value="cash">Cash on Delivery</option>
																										<option value="card">Credit Card</option>
																										<option value="bank">Bank Transfer</option>
																										<option value="paypal">PayPal</option>
																								</select>
																						</td>
																						<td colspan="2" class="px-4 py-3 text-right text-gray-300 font-medium">Total:
																						</td>
																						<td class="px-4 py-3 text-white font-bold text-lg">
																								${{ number_format($order->total_amount, 2) }}
																						</td>
																				</tr>

																				<tr id="receiptUpload" class="hidden">
																						<td colspan="2">
																								<label class="py-3 text-white text-sm px-2">Upload receipt</label>
																						</td>
																						<td colspan="4">
																								<form action="{{ route('orders.uploadReceipt', $order) }}" method="POST"
																										enctype="multipart/form-data" id="receiptForm">
																										@csrf
																										<input type="file" name="receipts[]" multiple accept="image/*,application/pdf"
																												class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white"
																												onchange="document.getElementById('receiptForm').submit()">
																								</form>
																								<p class="text-xs text-gray-400 mt-1">You can upload multiple receipts (images or PDF)</p>
																						</td>
																				</tr>

																		</tfoot>
																</table>
														</div>
														@if ($order->receipts->count() > 0)
																<tr>
																		<td colspan="6" class="px-6 py-4">
																				<h4 class="text-white font-medium mb-2">Uploaded Receipts:</h4>
																				<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
																						@foreach ($order->receipts as $receipt)
																								<div class="relative border border-gray-600 rounded p-2 bg-gray-700/50">
																										@if (str_contains($receipt->mime_type, 'image'))
																												<img src="{{ Storage::url($receipt->path) }}" alt="{{ $receipt->alt_text }}"
																														class="w-full h-24 object-cover rounded">
																										@else
																												<div class="w-full h-24 bg-red-500 flex items-center justify-center rounded">
																														<span class="text-white text-sm">PDF</span>
																												</div>
																										@endif

																										{{-- View Button --}}
																										<a href="{{ Storage::url($receipt->path) }}" target="_blank"
																												class="absolute bottom-1 left-1 bg-blue-600 text-white text-xs px-2 py-1 rounded hover:bg-blue-700 transition">
																												View
																										</a>

																										{{-- Delete Button --}}
																										<form action="{{ route('orders.deleteReceipt', [$order, $receipt]) }}" method="POST"
																												class="absolute bottom-1 right-1">
																												@csrf
																												@method('DELETE')
																												<button type="submit"
																														onclick="return confirm('Are you sure you want to delete this receipt?')"
																														class="bg-red-600 text-white text-xs px-2 py-1 rounded hover:bg-red-700 transition"
																														title="Delete Receipt">
																														✕
																												</button>
																										</form>

																										{{-- File info
																										<div class="text-xs text-gray-300 mt-2 truncate">
																												{{ $receipt->original_name }}
																										</div> --}}
																								</div>
																						@endforeach
																				</div>
																		</td>
																</tr>
														@endif
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

								{{-- Right Column - Customer & Actions --}}
								<div class="space-y-6">

										{{-- Customer Information Card --}}
										<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
												<div class="flex justify-between items-center mb-4">
														<h3 class="text-lg font-semibold text-white">Customer Information</h3>
														<button onclick="toggleEditCustomer()" class="text-blue-400 hover:text-blue-300 text-sm">
																Edit
														</button>
												</div>

												{{-- Display Customer Information --}}
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

												{{-- Edit Customer Form (Hidden by default) --}}
												<div id="editCustomerForm" class="hidden mt-4 p-4 bg-gray-700 rounded-lg">
														<form action="{{ route('orders.updateUser', $order) }}" method="POST">
																@csrf
																@method('patch')
																<div class="space-y-3">
																		<div>
																				<label class="text-sm text-gray-300 block mb-1">Name *</label>
																				<input type="text" name="name"
																						value="{{ $order->user->name ?? ($order->Customer['name'] ?? '') }}"
																						class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded text-white text-sm" required>
																		</div>
																		<div>
																				<label class="text-sm text-gray-300 block mb-1">Email *</label>
																				<input type="email" name="email"
																						value="{{ $order->user->email ?? ($order->Customer['email'] ?? '') }}"
																						class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded text-white text-sm" required>
																		</div>
																		<div>
																				<label class="text-sm text-gray-300 block mb-1">Phone</label>
																				<input type="text" name="phone"
																						value="{{ $order->user->phone ?? ($order->Customer['phone'] ?? '') }}"
																						class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded text-white text-sm">
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

										{{-- Shipping Address Card --}}
										<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
												<div class="flex justify-between items-center mb-4">
														<h3 class="text-lg font-semibold text-white">Shipping Address</h3>
														<button onclick="toggleEditAddress()" class="text-blue-400 hover:text-blue-300 text-sm">
																Edit
														</button>
												</div>

												@if ($order->shipping_address && is_array($order->shipping_address))
														<div class="space-y-2 text-white">
																<p><span class="text-gray-300">Street:</span> {{ $order->shipping_address['street_address'] }}</p>
																<p><span class="text-gray-300">City:</span> {{ $order->shipping_address['city'] }}</p>
																<p><span class="text-gray-300">State:</span> {{ $order->shipping_address['state'] }}</p>
																<p><span class="text-gray-300">ZIP:</span> {{ $order->shipping_address['zip_code'] }}</p>
																<p><span class="text-gray-300">Country:</span> {{ $order->shipping_address['country'] }}</p>
														</div>
												@else
														<p class="text-gray-300">No shipping address provided</p>
												@endif

												{{-- Edit Address Form --}}
												<div id="editAddressForm" class="hidden mt-4 p-4 bg-gray-700 rounded-lg">
														<form action="{{ route('orders.updateAddress', $order) }}" method="POST">
																@csrf
																@method('patch')
																<div class="space-y-3">
																		<div>
																				<label class="text-sm text-gray-300 block mb-1">Street Address *</label>
																				<input type="text" name="street_address"
																						value="{{ $order->shipping_address['street_address'] ?? '' }}"
																						class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded text-white text-sm" required>
																		</div>
																		<div class="grid grid-cols-2 gap-2">
																				<div>
																						<label class="text-sm text-gray-300 block mb-1">City *</label>
																						<input type="text" name="city" value="{{ $order->shipping_address['city'] ?? '' }}"
																								class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded text-white text-sm"
																								required>
																				</div>
																				<div>
																						<label class="text-sm text-gray-300 block mb-1">State *</label>
																						<input type="text" name="state" value="{{ $order->shipping_address['state'] ?? '' }}"
																								class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded text-white text-sm"
																								required>
																				</div>
																		</div>
																		<div class="grid grid-cols-2 gap-2">
																				<div>
																						<label class="text-sm text-gray-300 block mb-1">ZIP Code *</label>
																						<input type="text" name="zip_code" value="{{ $order->shipping_address['zip_code'] ?? '' }}"
																								class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded text-white text-sm"
																								required>
																				</div>
																				<div>
																						<label class="text-sm text-gray-300 block mb-1">Country *</label>
																						<input type="text" name="country" value="{{ $order->shipping_address['country'] ?? '' }}"
																								class="w-full px-3 py-2 bg-gray-600 border border-gray-500 rounded text-white text-sm"
																								required>
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

										{{-- Quick Actions Card --}}
										<div class="bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg">
												<h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
												<div class="space-y-3">
														{{-- Update Status Form --}}
														<form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="flex gap-2">
																@csrf
																@method('patch')
																<select name="status"
																		class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm">
																		<option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending
																		</option>
																		<option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing
																		</option>
																		<option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped
																		</option>
																		<option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
																		<option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
																</select>
																<button type="submit"
																		class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium">
																		Update Status
																</button>
														</form>

														{{-- Navigation Buttons --}}
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

				// Payment method file upload control
				document.addEventListener('DOMContentLoaded', function() {
						const paymentSelect = document.getElementById('paymentMethod');
						const receiptRow = document.getElementById('receiptUpload');

						paymentSelect.addEventListener('change', function() {
								const selectedValue = this.value;
								const showReceiptMethods = ['bank', 'paypal', 'card'];

								if (showReceiptMethods.includes(selectedValue)) {
										receiptRow.classList.remove('hidden');
								} else {
										receiptRow.classList.add('hidden');
								}
						});
				});
		</script>
</x-app-layout>
