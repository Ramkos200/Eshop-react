<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Categories') }}
						<span class="text-lg ">({{ $categories->where('parent_id', null)->count() }}
								main categories)</span>
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-12">
						<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
								<div class="mb-3 flex justify-between">
										<span class="text-sm  font-bold"> * Can't delete a category if it has subcategories</span>
										<span class="text-sm  font-bold text-green-400 justify-end"> Note: (P) Product | (SC) Subcategory
										</span>
								</div>
								<div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
										<div class="p-8 text-white">
												<x-link-button href="{{ route('categories.create') }}"> + New Category</x-link-button>
												<x-link-button href="{{ route('products.index') }}"> show all Products</x-link-button>
												<div class="overflow-x-auto overflow-y-auto gap-6 mt-8 ">
														<table class="w-full border-collapse align">
																<thead class="text-center">
																		<tr>
																				<th class="py-3 px-4">Category Name</th>
																				<th class="py-3 px-4">Subcategories</th>
																				<th class="py-3 px-4">ٍSubcategories Products </th>
																				<th class="py-3 px-4">Action</th>
																		</tr>
																</thead>
																<tbody class="text-center">
																		@foreach ($categories->where('parent_id', null) as $category)
																				@php
																						$canDelete = $category->children->count() === 0;
																				@endphp
																				<tr class="border-b border-gray-700 hover:bg-gray-750 transition-colors">
																						<td class="py-4 px-4 align-top text-center">
																								<a href="{{ route('categories.show', $category->slug) }} "
																										class="text-2xl font-semibold hover:underline block mb-3">
																										{{ $category->name }} {{ $canDelete ? '' : '*' }}
																								</a>
																								<div class="text-sm text-gray-400 mb-3">
																										📁 Subcategories :
																										{{ $category->children->count() }}
																								</div>
																						</td>
																						<td class="py-4 px-4 align-top text-center">
																								<ul>
																										@foreach ($category->children as $subcategory)
																												<li>
																														<a href="{{ route('categories.show', $subcategory->slug) }}"
																																class="mb-5 hover:underline text-xl">
																																{{ $subcategory->name }}
																																<span class="text-xs">
																																		SC:({{ $subcategory->children->count() }})
																																</span>
																														</a>
																														<div class="text-md text-gray-400 mb-3">
																																@foreach ($subcategory->children as $othersubcategory)
																																		<ul>
																																				<a href="{{ route('products.index', ['category_id' => $othersubcategory->id]) }}"
																																						class="mb-5 hover:underline ">
																																						<li> {{ $othersubcategory->name }}
																																								<span class="text-xs">
																																										P:({{ $othersubcategory->products->count() }})
																																								</span>
																																						</li>
																																				</a>
																																		</ul>
																																@endforeach
																														</div>
																												</li>
																										@endforeach
																								</ul>
																						</td>
																						<td class="py-4 px-4 align-top text-center">
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
																										<a href="{{ route('products.index', ['products' => $productIds]) }}"
																												class="text-sm font-semibold hover:underline text-blue-400 block mb-10 pb-1">
																												View All
																										</a>
																								@endforeach
																						</td>
																						<td class="py-4 px-4 align-top text-center">
																								{{-- edit and delete icons  --}}
																								<div class="flex justify-center mr-2">
																										<!-- Edit Icon -->
																										<a href="{{ route('categories.edit', $category->id) }}"
																												class="text-blue-400 hover:text-blue-300 transition-colors mr-2" title="Edit Category">
																												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																														stroke="currentColor">
																														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																																d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
																												</svg>
																										</a>

																										<!-- Delete Icon with Form -->
																										<form action="{{ route('categories.destroy', $category->id) }}" method="POST"
																												class="inline">
																												@csrf
																												@method('DELETE')
																												<button {{ $canDelete ? '' : 'disabled' }} type="submit"
																														class="text-red-400 hover:text-red-300 transition-colors"
																														onclick="{{ $canDelete ? 'return confirm(\'Are you surewant to delete this category?\')' : 'return confirm(\'Category Not Empty?\')' }}"
																														title="Delete Category">
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
		</div>
</x-app-layout>
