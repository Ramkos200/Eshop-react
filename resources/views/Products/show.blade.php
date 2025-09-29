<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						Variantes For {{ __($product->name) }}
				</h2>
		</x-slot>
		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-2">
						<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">

								<x-link-button href="{{ route('sku.create', $product) }}"> Add Product Variant
								</x-link-button>
								<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 overflow-hidden mt-4">
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
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
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
																								<img src="{{ asset('/product-images/' . $product->slug . '.jpg') }}" alt="{{ $product->slug }}"
																										class="h-full w-full object-cover">
																						</div>
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap">
																						<div class="text-sm text-white">{{ $sku->code }}</div>
																				</td>
																				<td class="px-6 py-4 text-sm text-white whitespace-nowrap">
																						${{ number_format($sku->price, 2) }}
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap text-sm text-white	">
																						{{ $sku->inventory }}
																				</td>
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
