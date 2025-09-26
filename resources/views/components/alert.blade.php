@if (session()->has('success'))
		<div class="bg-green-600/20 border border-green-500/30 text-green-400 px-6 py-4 rounded-lg mb-6 backdrop-blur-md"
				role="alert">
				<div class="flex items-center justify-between">
						<div class="flex items-center">
								<svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd"
												d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
												clip-rule="evenodd" />
								</svg>
								<span class="font-medium">{{ session('success') }}</span>
						</div>
						<button onclick="this.parentElement.parentElement.remove()"
								class="text-green-400 hover:text-green-300 transition-colors">
								<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd"
												d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
												clip-rule="evenodd" />
								</svg>
						</button>
				</div>
		</div>
@endif

@if (session()->has('error'))
		<div class="bg-red-600/20 border border-red-500/30 text-red-400 px-6 py-4 rounded-lg mb-6 backdrop-blur-md"
				role="alert">
				<div class="flex items-center justify-between">
						<div class="flex items-center">
								<svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd"
												d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
												clip-rule="evenodd" />
								</svg>
								<span class="font-medium">{{ session('error') }}</span>
						</div>
						<button onclick="this.parentElement.parentElement.remove()"
								class="text-red-400 hover:text-red-300 transition-colors">
								<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
										<path fill-rule="evenodd"
												d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
												clip-rule="evenodd" />
								</svg>
						</button>
				</div>
		</div>
@endif
