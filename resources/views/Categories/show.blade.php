<x-app-layout :backgroundImage="$backgroundImage">
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __($category->name) }}
				</h2>

		</x-slot>
		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-12">
						<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
								<div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
										<div class="p-8 text-white">
												@if ($category->children->count() === 0)
														@if ($category->parent_id !== null && $category->parent->parent_id !== null)
																<p class="text-lg text-gray-400 italic ml-4 mb-5">Can't create more
																		subcategories</p>
														@endif
														<div class="flex justify-left mb-5">
																@if ($category->parent_id !== null && $category->parent->parent_id !== null)
																		<x-link-button href="{{ route('products.create') }}">
																				+ New Product
																		</x-link-button>
																@else
																		<x-link-button href="{{ route('categories.create') }}"> + New Category</x-link-button>
																@endif
																<form action="{{ route('categories.destroy', $category->id) }}" method="POST"
																		class="{{ $products->count() ? 'hidden' : '' }}">
																		@csrf
																		@method('DELETE')
																		<button type="submit"
																				class="inline-flex items-center ml-2 px-6 py-3 bg-white/20 backdrop-blur-md border border-white/30 rounded-full font-[&quot;Inter&quot;] text-sm text-white uppercase tracking-widest hover:bg-white/30 hover:border-white/50 focus:bg-white/30 active:bg-white/40 focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 transition ease-in-out duration-150"
																				title="Delete Category" onclick="return confirm('delete subcategory?')">
																				Delete Category
																		</button>
																</form>
														</div>
														<p class="text-lg text-gray-400 italic ml-4 mt-10">No subcategories</p>
												@else
														<p class="text-lg text-gray-400 italic ml-4 mb-5">Can't delete this category because it has subcategories
														</p>
														<x-link-button href="{{ route('categories.create') }}"> + New Category</x-link-button>
														<div class="grid grid-cols-1  gap-6 mt-8 ">
																<div class="bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-6">

																		@foreach ($category->children as $subcategory)
																				<div class="mb-6 p-4 bg-white/10 rounded-lg">
																						<!-- Main subcategory  -->
																						<a href="{{ route('categories.show', $subcategory->slug) }}"
																								class="block text-lg font-semibold text-white hover:text-blue-300 transition-colors mb-2">
																								{{ $subcategory->name }}
																						</a>

																						<!-- subcategories and products -->
																						<div class="text-sm text-gray-300 mb-3">
																								<span class="mr-4">
																										📁 {{ $subcategory->children->count() }} subcategories
																								</span>
																								<span>
																										@foreach ($subcategory->products as $product)
																												<a href="{{ route('products.show', $product->slug) }}"
																														class="text-xl font-semibold hover:underline block mb-3">
																														🛍️ {{ $product->name }}
																												</a>
																										@endforeach
																								</span>
																						</div>

																						<!-- Child subcategories -->
																						@if ($subcategory->children->isNotEmpty())
																								<div class="ml-4 mt-2">
																										<h5 class="text-sm font-medium text-gray-400 mb-2">Subcategories:
																										</h5>
																										<div class="grid grid-cols-1 md:grid-cols-3 gap-2">
																												@foreach ($subcategory->children as $child)
																														<a href="{{ route('products.index', ['category_id' => $child->id]) }}"
																																class="text-sm text-blue-300 hover:text-blue-200 transition-colors flex items-center">
																																<span class="mr-1">•</span>
																																{{ $child->name }}
																																<span class="text-xs text-gray-400 ml-2">
																																		P: ({{ $child->products->count() }})
																																		SC: ({{ $subcategory->children->count() }})
																																</span>
																														</a>
																												@endforeach
																										</div>
																								</div>
																						@else
																								<p class="text-sm text-gray-400 italic ml-5">No subcategories</p>
																						@endif
																				</div>

																				<!-- Separator between subcategories -->
																				@if (!$loop->last)
																						<hr class="border-white/10 my-4">
																				@endif
																		@endforeach
																</div>
														</div>
												@endif
										</div>
								</div>
						</div>
				</div>
		</div>
</x-app-layout>
