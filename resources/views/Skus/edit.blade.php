<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Edit Variant') }}
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
												<form action="{{ route('skus.update', $sku) }}" method="POST" enctype="multipart/form-data">
														@csrf
														@method('put')
														<div class="mb-4">
																<label for="code" class="block text-sm font-medium mb-2">Variant SKU </label>
																<input type="text" name="code" id="code" placeholder={{ $sku->code }} disabled
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="price" class="block text-sm font-medium mb-2">Variant Price </label>
																<input type="number" name="price" id="price" step="1" required
																		value="{{ old('price', $sku->price) }}"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="inventory" class="block text-sm font-medium mb-2">Variant Inventory </label>
																<input type="number" name="inventory" id="inventory" step="1" required
																		value="{{ old('inventory', $sku->inventory) }}"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="color" class="block text-sm font-medium mb-2">Variant color </label>
																<input type="text" name="color" id="color" required
																		value="{{ old('attributes.color', $sku->attributes['color']) }}"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="size" class="block text-sm font-medium mb-2">Variant Size </label>
																<input type="text" name="size" id="size" required
																		value="{{ old('attributes.size', $sku->attributes['size']) }}"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="material" class="block text-sm font-medium mb-2">Variant Material </label>
																<input type="text" name="material" id="material" required
																		value="{{ old('attributes.material', $sku->attributes['material']) }}"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>
														<div class="mb-4">
																<label for="images" class="block text-sm font-medium mb-2">Variant Images </label>
																<input type="file" name="images[]" id="images" multiple accept="image/*"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
																<p class="text-sm text-gray-400 mt-1">You can select multiple images. </p>
														</div>
														<div class="flex space-x-3 mb-5">
																<button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
																		Create Variant
																</button>
																<a href="{{ route('products.show', $sku->product->slug) }}"
																		class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
																		Cancel
																</a>
														</div>
												</form>
										</div>
								</div>
						</div>
				</div>
				<!-- Upload Field -->
				{{-- <x-image-upload-simplified :model="Sku::class" :modelId="$sku->id" type="main" label="Upload New Image"
						description="Add a new image to this Variant" /> --}}

				<!-- Existing Images Gallery -->
				<x-image-gallery :images="$sku->images" title="Variant Images" :showSummary="true" :showEmptyState="true"
						emptyMessage="No images for this product yet." />
</x-app-layout>
