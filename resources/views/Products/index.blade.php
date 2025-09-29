<x-app-layout>
		<x-slot name="header">
				<h2 class="inline font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __($showTrash ? 'Deleted Products' : 'Products') }}{{ $category ? ' for ' . $category->name : '' }}
						<span class="text-lg">({{ $products->total() }} products)</span>
				</h2>
				<p class="text-2xl text-blue-400 underline mt-2 text-center {{ !$showTrash ? 'hidden' : '' }}">DON'T FORGET to assign
						a category to the restored products</p>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat min-w-full">
				<div class="py-2">
						<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
								<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">

										<x-link-button href="{{ route('products.create', ['category_id' => $category ? $category->id : '']) }}"
												class="{{ $showTrash ? 'hidden' : '' }}">
												+ New Product
										</x-link-button>

										<!-- Search Form -->
										<form method="GET" action="{{ route('products.index') }}" class="w-full md:w-auto">
												@if (request('trash'))
														<input type="hidden" name="trash" value="1">
												@endif
												@if (request('category_id'))
														<input type="hidden" name="category_id" value="{{ request('category_id') }}">
												@endif
												<div class="relative flex items-center">
														<input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
																class="bg-gray-700/50 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
														<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
																<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
																		<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																				d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
																</svg>
														</div>
												</div>
										</form>
								</div>


								<!-- Products Table -->
								@include('products.partials.products', ['showPlusButton' => false])
						</div>
				</div>
		</div>


</x-app-layout>
