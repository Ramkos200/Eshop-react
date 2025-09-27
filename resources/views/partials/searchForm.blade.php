@php
		$route = $route ?? '';
		$placeholder = $placeholder ?? 'Search...';
@endphp

<form method="GET" action="{{ $route }}">
		<div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
				<div class="relative flex items-center w-full md:w-96 mb-4">
						<input type="text" name="search" placeholder="{{ $placeholder }}" value="{{ request('search') }}"
								class="bg-gray-700/50 border border-gray-600 rounded-lg pl-10 pr-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
						<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
								<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
												d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
								</svg>
						</div>
				</div>
				<button type="submit"
						class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition w-full md:w-auto mb-4">
						Search
				</button>
				@if (request('search'))
						<a href="{{ $route }}"
								class="text-gray-400 hover:text-white transition-colors w-full md:w-auto text-center">
								Clear Search
						</a>
				@endif
		</div>
</form>
