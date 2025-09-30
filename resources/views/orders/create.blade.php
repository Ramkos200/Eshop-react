<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Create New Order') }}
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-4">
						<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
								<div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
										<div class="p-8 text-white">

												<!-- Display Validation Errors -->
												@if ($errors->any())
														<div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
																<div class="text-red-300 font-medium mb-2">Please fix the following errors:</div>
																<ul class="list-disc list-inside text-red-200 text-sm">
																		@foreach ($errors->all() as $error)
																				<li>{{ $error }}</li>
																		@endforeach
																</ul>
														</div>
												@endif

												<form action="{{ route('orders.store') }}" method="POST">
														@csrf

														<!-- Customer Information -->
														<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
																<div>
																		<label for="username" class="block text-sm font-medium mb-2">Customer Full Name *</label>
																		<input type="text" name="username" id="username" value="{{ old('username') }}" required
																				class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																		@error('username')
																				<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																		@enderror
																</div>
																<div>
																		<label for="email" class="block text-sm font-medium mb-2">Customer Email *</label>
																		<input type="email" name="email" id="email" value="{{ old('email') }}" required
																				class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																		@error('email')
																				<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																		@enderror
																</div>
														</div>

														<!-- Order Information -->
														<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
																<div>
																		<label for="status" class="block text-sm font-medium mb-2">Order Status *</label>
																		<select name="status" id="status" required
																				class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																				<option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
																				<option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
																				<option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
																				<option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
																				<option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
																		</select>
																		@error('status')
																				<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																		@enderror
																</div>
																<div>
																		<label for="notes" class="block text-sm font-medium mb-2">Notes</label>
																		<input type="text" name="notes" id="notes" value="{{ old('notes') }}"
																				class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																		@error('notes')
																				<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																		@enderror
																</div>
														</div>

														<!-- Shipping Address -->
														<div class="mb-6">
																<h3 class="text-lg font-medium text-white mb-4">Shipping Address</h3>
																<div class="grid grid-cols-1 gap-4">
																		<div>
																				<label for="street_address" class="block text-sm font-medium mb-2">Street Address *</label>
																				<input type="text" name="street_address" id="street_address" value="{{ old('street_address') }}"
																						required
																						class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																				@error('street_address')
																						<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																				@enderror
																		</div>
																		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
																				<div>
																						<label for="city" class="block text-sm font-medium mb-2">City *</label>
																						<input type="text" name="city" id="city" value="{{ old('city') }}" required
																								class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																						@error('city')
																								<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																						@enderror
																				</div>
																				<div>
																						<label for="state" class="block text-sm font-medium mb-2">State *</label>
																						<input type="text" name="state" id="state" value="{{ old('state') }}" required
																								class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																						@error('state')
																								<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																						@enderror
																				</div>
																		</div>
																		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
																				<div>
																						<label for="zip_code" class="block text-sm font-medium mb-2">ZIP Code *</label>
																						<input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', '00000') }}"
																								required
																								class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																						@error('zip_code')
																								<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																						@enderror
																				</div>
																				<div>
																						<label for="country" class="block text-sm font-medium mb-2">Country *</label>
																						<input type="text" name="country" id="country" value="{{ old('country', '') }}" required
																								class="w-full px-3 py-2 bg-white/5 border border-white/20 rounded-md text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
																						@error('country')
																								<p class="text-red-300 text-xs mt-1">{{ $message }}</p>
																						@enderror
																				</div>
																		</div>
																</div>
														</div>

														<!-- Form Actions -->
														<div class="flex space-x-3">
																<button type="submit"
																		class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md transition font-medium">
																		Create Order
																</button>
																<a href="{{ route('orders.index') }}"
																		class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition font-medium">
																		Cancel
																</a>
														</div>
												</form>
										</div>
								</div>
						</div>
				</div>
		</div>
</x-app-layout>
