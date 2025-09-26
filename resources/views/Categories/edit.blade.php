<x-app-layout>
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('Edit Category') }}
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-12">
						<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
								<div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
										<div class="p-8 text-white">
												<form action="{{ route('categories.update', $category) }}" method="POST">
														@csrf
														@method('put')
														<div class="mb-4">
																<label for="name" class="block text-sm font-medium mb-2">Category Name </label>
																<input type="text" name="name" id="name" required value="{{ old('name', $category->name) }}"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
																@error('name')
																		<p class="text-red-400 text-sm mt-1">{{ $message }}</p>
																@enderror
														</div>

														<div class="mb-4">
																<label for="slug" class="block text-sm font-medium mb-2">Slug</label>
																<input type="text" name="slug" id="slug" required value="{{ old('slug', $category->slug) }}"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
																@error('slug')
																		<p class="text-red-400 text-sm mt-1">{{ $message }}</p>
																@enderror
														</div>

														<div class="mb-4">
																<label for="description" class="block text-sm font-medium mb-2">Description</label>
																<textarea name="description" id="description" rows="3"
																  class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">{{ old('description', $category->description ?? '') }}</textarea>
														</div>

														<div class="mb-4">
																<label for="parent_id" class="block text-sm font-medium mb-2">
																		Parent Category (choose No Parent if it is a main category(*), or choose the parent category if it is
																		subcategory)
																</label>
																<select name="parent_id" id="parent_id"
																		class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white">
																		<option value="" {{ old('parent_id', $category->parent_id ?? '') == '' ? 'selected' : '' }}>
																				-- No Parent --
																		</option>
																		@foreach ($categories->where('parent_id', null) as $cat)
																				<option value="{{ $cat->id }}"
																						{{ old('parent_id', $category->parent_id ?? '') == $cat->id ? 'selected' : '' }}>
																						{{ $cat->name }} *
																				</option>
																				{{-- Subcategories --}}
																				@foreach ($cat->children as $subcategory)
																						<option value="{{ $subcategory->id }}"
																								{{ old('parent_id', $category->parent_id ?? '') == $subcategory->id ? 'selected' : '' }}>
																								&nbsp;&nbsp;&nbsp;{{ $subcategory->name }}
																						</option>
																				@endforeach
																		@endforeach
																</select>
																@error('parent_id')
																		<p class="text-red-400 text-sm mt-1">{{ $message }}</p>
																@enderror
														</div>

														<div class="flex space-x-3 mb-5">
																<button type="submit"
																		class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md transition border text-white">
																		Save
																</button>
																<a href="{{ url()->previous() }}"
																		class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md transition text-white">
																		Cancel
																</a>
														</div>
												</form>
										</div>
								</div>
						</div>
				</div>
		</div>
</x-app-layout>
