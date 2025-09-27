<x-app-layout>
		<x-slot name="header">
				<h2 class="inline font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Categories') }}
						<span class="text-lg">({{ $categories->where('parent_id', null)->count() }} main categories)</span>
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-2">
						<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
								<span class="text-yellow-400 italic">* Can't delete a category if it has subcategories</span>
								
								<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 mt-2 gap-4">
										<x-link-button href="{{ route('categories.create') }}">
												+ New Category
										</x-link-button>
								</div>

								<!-- Categories Table -->
								<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 overflow-hidden">
										<div class="overflow-x-auto">
												<table class="min-w-full divide-y divide-gray-700">
														<thead class="bg-gray-700/50">
																<tr>
																		<th scope="col"
																				class="px-6 py-3 text-left text-md font-medium text-gray-300 uppercase tracking-wider"> Category
																				Name </th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-md font-medium text-gray-300 uppercase tracking-wider">Subcategories
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-md font-medium text-gray-300 uppercase tracking-wider">Subcategories
																				Products </th>
																		<th scope="col"
																				class="px-6 py-3 text-right text-md font-medium text-gray-300 uppercase tracking-wider"> Actions
																		</th>
																</tr>
														</thead>
														<tbody class="bg-gray-800/50 divide-y divide-gray-700">
																@foreach ($categories->where('parent_id', null) as $category)
																		@php
																				$canDelete = $category->children->count() === 0;
																		@endphp
																		<tr class="hover:bg-gray-700/30 transition-colors duration-150">
																				<td class="px-6 py-4 whitespace-nowrap align-top">
																						<a href="{{ route('categories.show', $category->slug) }}"
																								class=" text-xl text-white font-medium hover:underline">
																								{{ $category->name }} {{ $canDelete ? '' : '*' }}
																						</a>
																						<div class="text-sm text-gray-400 mt-1">
																								📁 Subcategories: {{ $category->children->count() }}
																						</div>
																						<div class="text-sm text-gray-400 mt-1">
																								<a href="{{ route('categories.show', $category->slug) }}"
																										class="text-blue-400 hover:text-blue-300 underline cursor-pointer"
																										title="View full description">
																										description
																								</a> : {{ Str::limit($category->description, 20) }}
																						</div>
																				</td>
																				<td class="px-6 py-4 align-top">
																						<div class="space-y-4">
																								@foreach ($category->children as $subcategory)
																										@php
																												$canDeleteSub = $subcategory->children->count() === 0;
																										@endphp
																										<div class="border-l-2 border-gray-600 pl-3">
																												<a href="{{ route('categories.show', $subcategory->slug) }}"
																														class="text-white  hover:underline font-medium text-xl">
																														{{ $subcategory->name }} {{ $canDeleteSub ? '' : '*' }}
																														<span class="text-xs text-gray-400 ml-1">
																																SC:({{ $subcategory->children->count() }})
																														</span>
																												</a>
																												@if ($subcategory->children->count() > 0)
																														<div class=" text-gray-400 ml-3 mt-2 space-y-2">
																																@foreach ($subcategory->children as $othersubcategory)
																																		<div class="border-l-2 border-gray-500 pl-2">
																																				<a href="{{ route('categories.show', $othersubcategory->slug) }}"
																																						class="text-yellow-500 text-lg hover:underline text-gray-300 block">
																																						{{ $othersubcategory->name }}
																																						<a href="{{ route('products.index', ['category_id' => $othersubcategory->id]) }}"
																																								class="text-yellow-700 text-md hover:underline text-gray-300 block">
																																								Products:({{ $othersubcategory->products->count() }})
																																						</a>
																																				</a>
																																		</div>
																																@endforeach
																														</div>
																												@endif
																										</div>
																								@endforeach
																						</div>
																				</td>
																				<td class="px-6 py-4 align-top">
																						<div class="space-y-4">
																								@foreach ($category->children as $subcategory)
																										@php
																												$Catproducts = [];
																												foreach ($subcategory->children as $child) {
																												    $Catproducts = array_merge($Catproducts, $child->products->all());
																												    $productIds = array_map(function ($product) {
																												        return $product->id;
																												    }, $Catproducts);
																												}
																										@endphp
																										<div class="h-8 flex items-center min-h-[80px]">
																												@if (!empty($productIds))
																														<a href="{{ route('products.index', ['products' => $productIds]) }}"
																																class="text-blue-400 hover:text-blue-300 transition-colors text-sm">
																																View All Products
																														</a>
																												@else
																														<span class="text-gray-500 text-sm cursor-not-allowed"
																																title="No products in this category">
																																View All Products
																														</span>
																												@endif
																										</div>
																								@endforeach
																						</div>
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap text-right align-top">
																						<div class="flex justify-end space-x-2">
																								<!-- edit icon -->
																								@include('products.partials.edit', [
																										'action_route' => route('categories.edit', $category->id),
																								])

																								<!-- special category delete icon -->
																								<form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
																										@csrf
																										@method('DELETE')
																										<button {{ $canDelete ? '' : 'disabled' }} type="submit"
																												class="text-red-400 hover:text-red-300 transition-colors {{ $canDelete ? '' : 'opacity-50 cursor-not-allowed' }}"
																												onclick="{{ $canDelete ? 'return confirm(\'Are you sure you want to delete this category?\')' : 'return false' }}"
																												title="{{ $canDelete ? 'Delete Category' : 'Category has subcategories' }}">
																												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																														stroke="currentColor">
																														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																																d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
																												</svg>
																										</button>
																								</form>
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


</x-app-layout>
