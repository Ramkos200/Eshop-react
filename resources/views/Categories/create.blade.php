<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Create New Category') }}
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-4">
						<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
								<h1 class="text-yellow-500 text-md italic mb-2"> Add the image after creating the category (click edit category)
								</h1>
								<div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
										<div class="p-8 text-white">
												<form action="{{ route('categories.store') }}" method="POST">
														@csrf

														<div class="mb-4">
																<label for="name" class="block text-sm font-medium mb-2">Category Name</label>
																<input type="text" name="name" id="name" required
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>

														<div class="mb-4">
																<label for="slug" class="block text-sm font-medium mb-2">Slug</label>
																<input type="text" name="slug" id="slug" disabled
																		placeholder="slug will be created automatically"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
														</div>

														<div class="mb-4">
																<label for="description" class="block text-sm font-medium mb-2">Description</label>
																<textarea name="description" id="description" rows="3"
																  class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400"></textarea>
														</div>

														<div class="mb-4">
																<label for="parent_id" class="block text-sm font-medium mb-2">
																		Parent Category
																		@if (isset($parentCategory))
																				<span class="text-green-400 text-sm">(Pre-selected: {{ $parentCategory->name }})</span>
																		@endif
																</label>
																<select name="parent_id" id="parent_id"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white">
																		<option value="">-- No Parent --</option>
																		@foreach ($categories->where('parent_id', null) as $cat)
																				<option value="{{ $cat->id }}"
																						{{ isset($category) && $category->id == $cat->id ? 'selected' : '' }}>
																						{{ $cat->name }} *
																				</option>
																				{{-- Subcategories --}}
																				@foreach ($cat->children as $subcategory)
																						<option value="{{ $subcategory->id }}"
																								{{ isset($category) && $category->id == $subcategory->id ? 'selected' : '' }}>
																								&nbsp;&nbsp;&nbsp;{{ $subcategory->name }}
																						</option>
																				@endforeach
																		@endforeach
																</select>
														</div>


														<div class="flex space-x-3 mb-5 mt-5">
																<button type="submit" class="px-4 py-2 rounded-md transition border">
																		Create Category
																</button>
																<a href="{{ route('categories.index') }}" class="px-4 py-2 rounded-md transition">
																		Cancel
																</a>
														</div>
												</form>
												{{-- Image Upload Field
												<x-image-upload-simplified :model="Category::class" :modelId="0" type="main" label="Category Image"
														:required="true" description="Upload the main image for this category" /> --}}
										</div>
								</div>
						</div>
				</div>
		</div>
</x-app-layout>
