{{-- Image Gallery Component --}}
@if ($images->count() > 0)
		<div class="image-gallery mt-6">
				<h4 class="text-sm font-medium text-white mb-3">{{ $title ?? 'Existing Images' }} ({{ $images->count() }})</h4>

				<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
						@foreach ($images as $image)
								@php
										$imageUrl = asset('storage/' . $image->path);
										$fileExists = file_exists(public_path('storage/' . $image->path));
								@endphp

								<div class="bg-gray-700 rounded-lg border border-gray-600 overflow-hidden shadow-sm">
										{{-- Image --}}
										<div class="aspect-w-1 aspect-h-1 bg-gray-600">
												@if ($fileExists)
														<img src="{{ $imageUrl }}" alt="{{ $image->alt_text }}" class="w-full h-24 object-cover"
																onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
														<div class="hidden w-full h-24 bg-yellow-900 flex items-center justify-center">
																<span class="text-yellow-300 text-xs">Failed to load</span>
														</div>
												@else
														<div class="w-full h-24 bg-red-900 flex items-center justify-center">
																<span class="text-red-300 text-xs">Image missing</span>
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
																				class="w-full inline-flex items-center justify-center px-2 py-1 border border-transparent text-xs font-medium rounded text-yellow-900 bg-yellow-400 hover:bg-yellow-300 focus:outline-none"
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
																		class="inline-flex items-center justify-center px-2 py-1 border border-transparent text-xs font-medium rounded text-red-900 bg-red-400 hover:bg-red-300 focus:outline-none"
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
				@if (isset($showSummary) && $showSummary)
						<div class="mt-3 text-sm text-gray-300">
								Total: {{ $images->count() }} image(s)
								@if ($images->where('type', 'main')->count() > 0)
										• <span class="text-yellow-400">{{ $images->where('type', 'main')->count() }} main image(s)</span>
								@endif
						</div>
				@endif
		</div>
@elseif (isset($showEmptyState) && $showEmptyState)
		{{-- Empty State --}}
		<div class="text-center py-8 bg-gray-800 rounded-lg border border-gray-600">
				<svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
				</svg>
				<h3 class="mt-2 text-sm font-medium text-white">No images</h3>
				<p class="mt-1 text-sm text-gray-300">{{ $emptyMessage ?? 'No images uploaded yet.' }}</p>
		</div>
@endif
