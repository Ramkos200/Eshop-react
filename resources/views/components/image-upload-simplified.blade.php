{{-- Compact Image Upload Field --}}
<div class="image-upload-simplified space-y-3">
		{{-- Header --}}
		<div>
				<label class="block text-sm font-medium text-white mb-2">
						{{ $label ?? 'Image' }}
						@if (isset($required) && $required)
								<span class="text-red-500">*</span>
						@endif
				</label>

				@if (isset($description))
						<p class="text-sm text-gray-300">{{ $description }}</p>
				@endif
		</div>

		{{-- Upload Form --}}
		<form action="{{ route('img.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
				@csrf

				{{-- Hidden fields for image relationship --}}
				<input type="hidden" name="imageable_type" value="{{ $model }}">
				<input type="hidden" name="imageable_id" value="{{ $modelId }}">
				<input type="hidden" name="type" value="{{ $type ?? 'gallery' }}">

				{{-- Upload Area --}}
				<div class="border-2 border-dashed border-gray-300 rounded-lg p-4 hover:border-gray-400 transition-colors">
						{{-- Image Preview --}}
						<div id="imagePreviewContainer" class="hidden mb-3 text-center">
								<div class="relative inline-block">
										<img id="imagePreview" src="" alt="Preview"
												class="max-w-full max-h-32 rounded-lg shadow-md mx-auto border border-gray-400">
										<button type="button" onclick="removePreview()"
												class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 border border-white">
												×
										</button>
								</div>
								<p id="fileName" class="text-sm text-gray-300 mt-1"></p>
						</div>

						{{-- Upload Controls --}}
						<div class="text-center">
								{{-- Default Upload State --}}
								<div id="uploadIcon" class="mb-2">
										<svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
												<path
														d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
														stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
										</svg>
								</div>

								{{-- File Input --}}
								<div class="flex flex-col items-center space-y-2">
										<label
												class="cursor-pointer bg-gray-600 rounded-md font-medium text-white hover:bg-gray-700 focus-within:outline-none px-3 py-2 text-sm">
												<span>Choose File</span>
												<input type="file" name="image" id="imageInput" class="sr-only" accept="image/*"
														onchange="handleImageSelect(event)" {{ isset($required) && $required ? 'required' : '' }}>
										</label>
										<p class="text-xs text-gray-400">PNG, JPG, GIF, WEBP up to 5MB</p>
								</div>

								{{-- Alt Text Input --}}
								<div class="mt-3 max-w-xs mx-auto">
										<label for="alt_text" class="block text-xs font-medium text-white text-left mb-1">
												Alt Text (optional)
										</label>
										<input type="text" name="alt_text" id="alt_text"
												class="w-full px-3 py-2 border border-gray-600 bg-gray-700 text-white rounded-md text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
												placeholder="Describe this image..." maxlength="255">
								</div>
						</div>
				</div>

				{{-- Upload Button --}}
				<button type="submit"
						class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						<svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
						</svg>
						Upload Image
				</button>
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
</div>

<script>
		function handleImageSelect(event) {
				const file = event.target.files[0];
				const previewContainer = document.getElementById('imagePreviewContainer');
				const previewImage = document.getElementById('imagePreview');
				const fileName = document.getElementById('fileName');
				const uploadIcon = document.getElementById('uploadIcon');

				if (file) {
						if (!file.type.match('image.*')) {
								alert('Please select an image file (PNG, JPG, GIF, WEBP).');
								event.target.value = '';
								return;
						}

						if (file.size > 5 * 1024 * 1024) {
								alert('File size must be less than 5MB.');
								event.target.value = '';
								return;
						}

						const reader = new FileReader();
						reader.onload = function(e) {
								previewImage.src = e.target.result;
								previewContainer.classList.remove('hidden');
								uploadIcon.classList.add('hidden');
								fileName.textContent = file.name;
						};
						reader.readAsDataURL(file);
				}
		}

		function removePreview() {
				const previewContainer = document.getElementById('imagePreviewContainer');
				const previewImage = document.getElementById('imagePreview');
				const fileName = document.getElementById('fileName');
				const uploadIcon = document.getElementById('uploadIcon');
				const fileInput = document.getElementById('imageInput');

				fileInput.value = '';
				previewContainer.classList.add('hidden');
				uploadIcon.classList.remove('hidden');
				fileName.textContent = '';
				previewImage.src = '';
		}
</script>
