<x-app-layout>
		<x-slot name="header">
				<h2 class="inline font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __($showTrash ? 'Deleted Products' : 'Products') }}
						<span class="text-lg">({{ $products->count() }} products)</span>
				</h2>
				<p class="text-2xl text-blue-400 underline mt-2 text-center">DON'T FORGET to assign a category to the restored
						products </p>
		</x-slot>
		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-2">
						<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
								<div class="inline-flex items-center">
										<x-link-button href="{{ route('products.create') }}" class="{{ $showTrash ? 'hidden' : '' }}">
												+ New Product
										</x-link-button>
										@if ($category && $category->children->isEmpty())
												<form action="{{ route('categories.destroy', $category->id) }}" method="POST"
														class="{{ $products->count() ? 'hidden' : '' }}">
														@csrf
														@method('DELETE')
														<button type="submit"
																class="inline-flex items-center ml-2 px-6 py-3 bg-white/20 backdrop-blur-md border border-white/30 rounded-full font-[&quot;Inter&quot;] text-sm text-white uppercase tracking-widest hover:bg-white/30 hover:border-white/50 focus:bg-white/30 active:bg-white/40 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 transition ease-in-out duration-150"
																title="Delete Category" onclick="return confirm('delete subcategory?')">
																Delete Subcategory
														</button>
												</form>
										@endif
								</div>
								<div class="p-2 text-white">
										<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 ">
												@foreach ($products as $product)
														<div class="bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-6 flex flex-col h-100">
																<!-- Edit and Delete Icons -->
																<div class="flex justify-end mr-2">
																		<!-- Edit Icon -->
																		@if (!$showTrash)
																				<a href="{{ route('products.edit', $product) }}"
																						class="text-blue-400 hover:text-blue-300 transition-colors mr-2" title="Edit Product">
																						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																								stroke="currentColor">
																								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																										d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
																						</svg>
																				</a>
																		@else
																				{{-- restoring trashed items --}}
																				<form action="{{ route('products.restore', $product->id) }}" method="POST" class="inline">
																						@csrf
																						@method('PUT')
																						<button type="submit" class="text-blue-400 hover:text-blue-300 transition-colors px-2"
																								onclick="return confirm('Want to Restore this product?')" title="Restore Product">
																								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																										stroke="currentColor">
																										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																												d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
																								</svg>
																						</button>
																				</form>
																		@endif
																		<!-- Delete Icon with Form -->
																		<form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
																				@csrf
																				@method('DELETE')
																				<button type="submit" class="text-red-400 hover:text-red-300 transition-colors"
																						onclick="return confirm('Are you sure you want to delete this product?')" title="Delete Product">
																						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																								stroke="currentColor">
																								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																										d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
																						</svg>
																				</button>
																		</form>
																</div>
																<a href="{{ route('products.show', $product->slug) }}"
																		class="text-2xl font-semibold hover:underline block mb-3">
																		{{ $product->name }}
																</a>
																<p class="text-sm mb-5">desription: {{ Str::limit($product->description, 100) }}</p>
																<p class="text-sm mb-2">price: {{ $product->price }}</p>
																<p class="text-sm mb-2">status: {{ $product->status }}</p>
																<div class="mt-4 bg-gray-700 rounded-lg h-30 flex items-center justify-center">
																		<img src="{{ asset('/product-images/' . $product->slug . '.jpg') }}" alt="{{ $product->slug }}"
																				class="w-full h-full object-cover rounded-lg">
																</div>
														</div>
												@endforeach
										</div>
								</div>
						</div>
				</div>
		</div>
</x-app-layout>
