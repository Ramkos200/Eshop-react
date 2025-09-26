<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Create New Variant') }}{{ $product ? ' for ' . $product->name : '' }}
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-12">
						<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
								<div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
										<div class="p-8 text-white">
												<form action="{{ route('skus.store', $product) }}" method="POST">
														@csrf

														<div class="mb-4">
																<label for="code" class="block text-sm font-medium mb-2">Variant SKU </label>
																<input type="text" name="code" id="code" placeholder="will be generated automatically"
																		disabled
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="price" class="block text-sm font-medium mb-2">Variant Price </label>
																<input type="number" name="price" id="price" step="1" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="inventory" class="block text-sm font-medium mb-2">Variant Inventory </label>
																<input type="number" name="inventory" id="inventory" step="1" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="color" class="block text-sm font-medium mb-2">Variant color </label>
																<input type="text" name="color" id="color" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="size" class="block text-sm font-medium mb-2">Variant Size </label>
																<input type="text" name="size" id="size" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="material" class="block text-sm font-medium mb-2">Variant Material </label>
																<input type="text" name="material" id="material" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="image" class="block text-sm font-medium mb-2">Variant image </label>
																<input type="file" name="images" id="images[]" multiple
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
																<p class="text-sm text-gray-400 mt-1">You can select multiple images</p>
														</div>
														<div class="flex space-x-3 mb-5">
																<button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
																		Create Variant
																</button>
																<a href="{{ route('products.show', $product->slug) }}"
																		class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
																		Cancel
																</a>
														</div>
												</form>
										</div>
								</div>
						</div>
				</div>
				{{-- <div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-12">
						<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
								<div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
										<div class="p-8 text-white">
												<form action="{{ route('products.store') }}" method="POST">
														@csrf

														<div class="mb-4">
																<label for="name" class="block text-sm font-medium mb-2">Product Name *</label>
																<input type="text" name="name" id="name" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>

														<div class="mb-4">
																<label for="slug" class="block text-sm font-medium mb-2">Slug</label>
																<input type="text" name="slug" id="slug" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">

														</div>

														<div class="mb-4">
																<label for="price" class="block text-sm font-medium mb-2">Price *</label>
																<input type="number" step="0.1" name="price" id="price" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>

														<div class="mb-4">
																<label for="status" class="block text-sm font-medium mb-2">Status </label>
																<select name="status" id="status" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white">
																		<option value="Published">Published</option>
																		<option value="Draft">Draft</option>
																		<option value="Archived">Archived</option>
																</select>
														</div>

														<div class="mb-4">
																<label for="description" class="block text-sm font-medium mb-2">Description</label>
																<textarea name="description" id="description" rows="3"
																  class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400"></textarea>
														</div>

														<div class="mb-4">
																<label for="category_id" class="block text-sm font-medium mb-2">Category *</label>
																<select name="category_id" id="category_id" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white">
																		<option value="">Select Category</option>
																		@foreach ($categories->whereNotNull('parent_id') as $category)
																				@if ($category->children->count() === 0)
																						<option value="{{ $category->id }}">
																								{{ $category->name }}
																						</option>
																				@endif
																		@endforeach
																</select>
														</div>

														<div class="flex space-x-3 mb-5">
																<button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
																		Create Product
																</button>
																<a href="{{ route('products.index') }}"
																		class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
																		Cancel
																</a>
														</div>
												</form>
										</div>
								</div>
						</div>
				</div>
		</div> --}}
</x-app-layout>
