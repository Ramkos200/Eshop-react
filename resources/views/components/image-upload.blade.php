<div class="image-upload space-y-4">
		{{-- Header --}}
		<div>
				<label class="block text-sm font-medium text-white mb-2">
						{{ $label ?? 'Images' }}
						@if (isset($required) && $required)
								<span class="text-red-500">*</span>
						@endif
				</label>

				@if (isset($description))
						<p class="text-sm text-gray-300">{{ $description }}</p>
				@endif
		</div>
		{{-- test --}}

		{{-- Upload Form --}}
		<form action="{{ route('img.store') }}" method="POST" enctype="multipart/form-data"
				class="border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-gray-400 transition-colors" id="uploadForm">
				@csrf

				{{-- Hidden fields for image relationship --}}
				<input type="hidden" name="imageable_type" value="{{ $model }}">
				<input type="hidden" name="imageable_id" value="{{ $modelId }}">
				<input type="hidden" name="type" value="{{ $type ?? 'gallery' }}">

				<div class="text-center">
						{{-- Image Preview Container --}}
						<div id="imagePreviewContainer" class="hidden mb-4">
								<div class="relative inline-block">
										<img id="imagePreview" src="" alt="Preview"
												class="max-w-full max-h-48 rounded-lg shadow-md mx-auto border border-gray-400">
										<button type="button" onclick="removePreview()"
												class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 border border-white">
												×
										</button>
								</div>
								<p id="fileName" class="text-sm text-gray-300 mt-2"></p>
						</div>

						{{-- Default Upload Icon (shown when no preview) --}}
						<div id="uploadIcon">
								<svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
										<path
												d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
												stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
						</div>

						<div class="mt-4 flex justify-center text-sm text-gray-300">
								<label
										class="relative cursor-pointer bg-gray-600 rounded-md font-medium text-white hover:bg-gray-700 focus-within:outline-none p-2 m-2">
										<span>Choose Image</span>
										<input type="file" name="image" id="imageInput" class="sr-only" accept="image/*"
												onchange="handleImageSelect(event)" {{ isset($required) && $required ? 'required' : '' }}>
								</label>
						</div>

						<p class="text-xs text-gray-400 mt-2">
								PNG, JPG, GIF, WEBP up to 5MB
						</p>

						{{-- Alt Text Input --}}
						<div class="mt-4 max-w-xs mx-auto">
								<label for="alt_text" class="block text-xs font-medium text-white text-left mb-1">
										Alt Text (optional)
								</label>
								<input type="text" name="alt_text" id="alt_text"
										class="w-full px-3 py-2 border border-gray-600 bg-gray-700 text-white rounded-md text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
										placeholder="Describe this image..." maxlength="255">
						</div>

						{{-- Submit Button --}}
						<button type="submit"
								class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
								<svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
												d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
								</svg>
								Upload Image
						</button>
				</div>
		</form>

		{{-- Error Messages --}}
		@if ($errors->has('image'))
				<div class="bg-red-500/40 border border-red-600 rounded-lg p-3">
						<p class="text-white text-sm">{{ $errors->first('image') }}</p>
				</div>
		@endif

		{{-- Success Messages --}}
		@if (session('success'))
				<div class="bg-green-500/40 border border-green-600 rounded-lg p-3">
						<p class="text-white text-sm">{{ session('success') }}</p>
				</div>
		@endif

		{{-- Existing Images Gallery (your existing code remains the same) --}}
		@if (isset($existingImages) && $existingImages->count() > 0)
				<div class="mt-6">
						<h4 class="text-sm font-medium text-white mb-3">Existing Images ({{ $existingImages->count() }})</h4>

						<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
								@foreach ($existingImages as $image)
										@php
												// Method 1: Using the path from database
												$urlFromPath = asset('storage/' . $image->path);
												$fileExistsFromPath = file_exists(public_path('storage/' . $image->path));

												// Method 2: Direct filename approach
												$urlFromFilename = asset('storage/images/' . $image->filename);
												$fileExistsFromFilename = file_exists(public_path('storage/images/' . $image->filename));

												// Use the method that works
												if ($fileExistsFromPath) {
												    $imageUrl = $urlFromPath;
												    $fileExists = true;
												    $methodUsed = 'path';
												} elseif ($fileExistsFromFilename) {
												    $imageUrl = $urlFromFilename;
												    $fileExists = true;
												    $methodUsed = 'filename';
												} else {
												    $imageUrl = $urlFromPath; // fallback
												    $fileExists = false;
												    $methodUsed = 'none';
												}
										@endphp

										<div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden shadow-sm">
												{{-- Image --}}
												<div class="aspect-w-1 aspect-h-1 bg-gray-600">
														@if ($fileExists)
																<img src="{{ $imageUrl }}" alt="{{ $image->alt_text }}" class="w-full h-24 object-cover"
																		onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
																<div class="hidden w-full h-24 bg-yellow-900 flex items-center justify-center">
																		<span class="text-yellow-300 text-xs">Failed to load</span>
																</div>
														@else
																<div class="w-full h-24 bg-red-900 flex items-center justify-center">
																		<span class="text-red-300 text-xs text-center">
																				Image not found<br>
																				<span class="text-xs">Tried both methods</span>
																		</span>
																</div>
														@endif
												</div>

												{{-- Image Info --}}
												<div class="p-2">
														<p class="text-xs text-gray-300 truncate" title="{{ $image->original_name }}">
																{{ Str::limit($image->original_name, 20) }}
														</p>
														<p class="text-xs text-gray-400">{{ number_format($image->file_size / 1024, 1) }} KB</p>

														{{-- Image Type Badge --}}
														<div class="mt-1">
																@switch($image->type)
																		@case('main')
																				<span
																						class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500 text-yellow-900">
																						Main
																				</span>
																		@break

																		@case('variant')
																				<span
																						class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-500 text-blue-900">
																						Variant
																				</span>
																		@break

																		@case('receipt')
																				<span
																						class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-500 text-green-900">
																						Receipt
																				</span>
																		@break

																		@default
																				<span
																						class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-500 text-gray-900">
																						Gallery
																				</span>
																@endswitch
														</div>
												</div>

												{{-- Actions --}}
												<div class="border-t border-gray-600 px-2 py-2 bg-gray-600">
														<div class="flex justify-between items-center space-x-1">
																{{-- Set as Main Form --}}
																@if ($image->type !== 'main')
																		<form action="{{ route('img.set-main', $image) }}" method="POST" class="flex-1">
																				@csrf
																				@method('POST')
																				<button type="submit"
																						class="w-full inline-flex items-center justify-center px-2 py-1 border border-transparent text-xs font-medium rounded text-yellow-900 bg-yellow-400 hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-yellow-500"
																						title="Set as Main">
																						<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
																								<path
																										d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
																						</svg>
																						Main
																				</button>
																		</form>
																@else
																		<div class="flex-1">
																				<span
																						class="w-full inline-flex items-center justify-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-yellow-500">
																						<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
																								<path
																										d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
																						</svg>
																						Main
																				</span>
																		</div>
																@endif

																{{-- Delete Form --}}
																<form action="{{ route('img.destroy', $image) }}" method="POST"
																		onsubmit="return confirm('Are you sure you want to delete this image?');">
																		@csrf
																		@method('DELETE')
																		<button type="submit"
																				class="inline-flex items-center justify-center px-2 py-1 border border-transparent text-xs font-medium rounded text-red-900 bg-red-400 hover:bg-red-300 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500"
																				title="Delete Image">
																				<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
																						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																								d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
																				</svg>
																		</button>
																</form>
														</div>

														{{-- Edit Form for Alt Text --}}
														<form action="{{ route('img.update', $image) }}" method="POST" class="mt-1">
																@csrf
																@method('PUT')
																<div class="flex space-x-1">
																		<input type="text" name="alt_text" value="{{ $image->alt_text }}" placeholder="Alt..."
																				class="flex-1 px-1 py-0.5 text-[10px] border border-gray-500 bg-gray-600 text-white rounded focus:outline-none focus:ring-1 focus:ring-indigo-500"
																				maxlength="255">
																		<button type="submit"
																				class="px-1 py-0.5 text-[10px] bg-gray-500 text-white rounded hover:bg-gray-400 focus:outline-none">
																				✓
																		</button>
																</div>
														</form>
												</div>
										</div>
								@endforeach
						</div>

						{{-- Summary --}}
						<div class="mt-3 text-sm text-gray-300">
								Total: {{ $existingImages->count() }} image(s)
								@if ($existingImages->where('type', 'main')->count() > 0)
										• <span class="text-yellow-400">{{ $existingImages->where('type', 'main')->count() }} main image(s)</span>
								@endif
						</div>
				</div>
		@else
				{{-- No Images State --}}
				<div class="text-center py-8 bg-gray-800 rounded-lg border border-gray-600">
						<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
						</svg>
						<h3 class="mt-2 text-sm font-medium text-white">No images</h3>
						<p class="mt-1 text-sm text-gray-300">Get started by uploading your first image.</p>
				</div>
		@endif
</div>

{{-- jave script code --}}
<script>
		function handleImageSelect(event) {
				const file = event.target.files[0];
				const previewContainer = document.getElementById('imagePreviewContainer');
				const previewImage = document.getElementById('imagePreview');
				const fileName = document.getElementById('fileName');
				const uploadIcon = document.getElementById('uploadIcon');

				if (file) {
						// Check if file is an image
						if (!file.type.match('image.*')) {
								alert('Please select an image file (PNG, JPG, GIF, WEBP).');
								event.target.value = ''; // Clear the input
								return;
						}

						// Check file size (5MB limit)
						if (file.size > 5 * 1024 * 1024) {
								alert('File size must be less than 5MB.');
								event.target.value = ''; // Clear the input
								return;
						}

						// Create a FileReader to read the file
						const reader = new FileReader();

						reader.onload = function(e) {
								// Show the preview
								previewImage.src = e.target.result;
								previewContainer.classList.remove('hidden');
								uploadIcon.classList.add('hidden');

								// Show file name
								fileName.textContent = file.name;
						};

						// Read the file as Data URL
						reader.readAsDataURL(file);
				}
		}

		function removePreview() {
				const previewContainer = document.getElementById('imagePreviewContainer');
				const previewImage = document.getElementById('imagePreview');
				const fileName = document.getElementById('fileName');
				const uploadIcon = document.getElementById('uploadIcon');
				const fileInput = document.getElementById('imageInput');

				// Clear the file input
				fileInput.value = '';

				// Hide preview and show upload icon
				previewContainer.classList.add('hidden');
				uploadIcon.classList.remove('hidden');

				// Clear file name and preview src
				fileName.textContent = '';
				previewImage.src = '';
		}
</script>
