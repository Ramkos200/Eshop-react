<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Edit Order') }}
				</h2>
		</x-slot>
@if ($errors->any())
    <div class="mb-6 bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
        <h4 class="font-bold">Validation Errors:</h4>
        <ul class="list-disc list-inside mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-4">
						<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
								<div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
										<div class="p-8 text-white">
												<form action="{{ route('orders.update') }}" method="POST">
														@csrf
														<div class="mb-4">
																<label for="code" class="block text-sm font-medium mb-2">Order Code</label>
																<input type="text" name="code" id="code" placeholder="will be generated automatically"
																		disabled
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="username" class="block text-sm font-medium mb-2">Customer Full Name* </label>
																<input type="text" name="username" id="username" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="email" class="block text-sm font-medium mb-2">Customer Email* </label>
																<input type="email" name="email" id="email" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="status" class="block text-sm font-medium mb-2">Order Status </label>
																<select name="status" id="status" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white">
																		<option value="pending">Pending</option>
																		<option value="processing">Processing</option>
																		<option value="shipped">Shipped</option>
																		<option value="delivered">Delivered</option>
																		<option value="cancelled">Cancelled</option>


																</select>
														</div>
														<div class="mb-4">
																<label for="notes" class="block text-sm font-medium mb-2">Notes</label>
																<input type="text" name="notes" id="notes"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="grid grid-cols-1 gap-2 text-sm">
																<div>
																		<label for="street_address" class="text-gray-300">Street Address *</label>
																		<input type="text" name="street_address" id="street_address" required
																				class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																</div>
																<div class="grid grid-cols-2 gap-2">
																		<div>
																				<label for="city" class="text-gray-300">City *</label>
																				<input type="text" name="city" id="city" required
																						class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																		</div>
																		<div>
																				<label for="state" class="text-gray-300">State *</label>
																				<input type="text" name="state" id="state" required
																						class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																		</div>
																</div>
																<div class="grid grid-cols-2 gap-2">
																		<div>
																				<label for="zip_code" class="text-gray-300">ZIP Code *</label>
																				<input type="integer" name="zip_code" id="zip_code" value="00000"
																						class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																		</div>
																		<div>
																				<label for="country" class="text-gray-300">Country *</label>
																				<input type="text" name="country" id="country" required
																						class="w-full bg-gray-600 border border-gray-500 text-white rounded-md px-2 py-1">
																		</div>
																</div>
																<div class="flex space-x-3 mb-5">
																		<button type="submit"
																				class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
																				Create Order
																		</button>
																		<a href="{{ route('orders.index') }}"
																				class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
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
