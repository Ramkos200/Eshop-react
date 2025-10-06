<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						Variantes For {{ __($product->name) }}
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
				<div class="py-2">
						<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
								<div class="flex justify-between items-center">
										<x-link-button href="{{ route('sku.create', $product) }}">
												Add Product Variant
										</x-link-button>

										@if ($product->skus->count() === 0)
												<form action="{{ route('products.destroy', $product) }}" method="POST">
														@csrf
														@method('DELETE')
														<button type="submit"
																class="inline-flex items-center px-4 py-2 bg-red-600/20 backdrop-blur-md border border-red-500/30 rounded-lg font-['Inter'] text-sm text-white uppercase tracking-widest hover:bg-red-700/30 hover:border-red-500/50 focus:bg-red-700/30 active:bg-red-800/40 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:ring-offset-2 focus:ring-offset-gray-900 transition ease-in-out duration-150"
																title="Delete Product" onclick="return confirm('Are you sure you want to delete this Product???')">
																Delete Product
														</button>
												</form>
										@else
												<p class="text-yellow-400 text-sm font-medium">
														🔔 Can't delete this product because it has {{ $product->skus->count() }} variants.
												</p>
										@endif
								</div>
								<div
										class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 overflow-hidden mt-4">
										<div class="px-6 py-4 bg-gray-700/50 border-b border-gray-700">
												<h3 class="text-lg font-semibold text-white">Product Variants</h3>
												<table class="min-w-full divide-y divide-gray-700">
														<thead class="bg-gray-700/50">
																<tr>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				image
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				SKU Code
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-green-300 uppercase tracking-wider">
																				Price
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Inventory
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Attributes
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Actions
																		</th>
																</tr>
														</thead>
														<tbody class="bg-gray-800/50 divide-y divide-gray-700">
																@foreach ($product->skus as $sku)
																		<tr class="hover:bg-gray-700/30 transition-colors duration-150">
																				<td class="px-6 py-4 whitespace-nowrap">
																						<div class="h-12 w-12 rounded overflow-hidden">
																								@if ($sku->mainImage)
																										<img src="{{ Storage::url($sku->mainImage->path) }}" alt="{{ $sku->mainImage->alt_text }}"
																												class="h-full w-full object-cover">
																								@else
																										<div class="h-full w-full bg-gray-600 flex items-center justify-center">
																												<span class="text-white text-xs">No Image</span>
																										</div>
																								@endif
																						</div>
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap">
																						<div class="text-sm text-white">{{ $sku->code }}</div>
																				</td>
																				<td class="px-6 py-4 text-sm text-green-500 whitespace-nowrap">
																						${{ number_format($sku->price, 2) }}
																				</td>
																				@if ($sku->inventory == 0)
																						<td class="px-6 py-4 whitespace-nowrap text-sm text-red-400 font-semibold">
																								Out of Stock 🚨
																						</td>
																				@elseif ($sku->inventory <= 5)
																						<td class="px-6 py-4 whitespace-nowrap text-sm text-orange-400">
																								{{ number_format($sku->inventory) }} - Low Stock ⚠️
																						</td>
																				@else
																						<td class="px-6 py-4 whitespace-nowrap text-sm text-green-400">
																								{{ number_format($sku->inventory) }} - In Stock ✅
																						</td>
																				@endif
																				<td class="px-6 py-4">
																						<div class="text-sm text-gray-300">
																								@if (isset($sku->attributes['color']))
																										<div>Color: {{ $sku->attributes['color'] }}</div>
																								@endif
																								@if (isset($sku->attributes['size']))
																										<div>Size: {{ $sku->attributes['size'] }}</div>
																								@endif
																								@if (isset($sku->attributes['material']))
																										<div>Material: {{ $sku->attributes['material'] }}</div>
																								@endif
																						</div>
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
																						<div class="flex justify-end space-x-2">
																								@include('products.partials.edit', [
																										'action_route' => route('skus.edit', $sku),
																								])

																								@include('products.partials.delete', [
																										'action_route' => route('skus.destroy', $sku),
																								])

																						</div>
																				</td>
																		</tr>
																@endforeach
														</tbody>
												</table>

										</div>
								</div>
						</div>
				</div>
		</div>
		</div>
</x-app-layout>
