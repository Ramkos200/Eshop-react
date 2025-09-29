<nav class="bg-black shadow-md z-50">
		<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between h-16">
						<!-- Logo -->
						<div class="flex items-center">
								<a href="{{ url('dashboard') }}" class="flex-shrink-0 flex items-center">
										<i class="fas fa-lightbulb text-amber-500 text-3xl mr-2"></i>
										<span class="font-['Cormorant_Garamond'] text-4xl font-semibold text-white"> {{ env('APP_NAME') }}</span>
								</a>
						</div>

						<!-- Desktop Navigation -->
						<div class="hidden md:flex items-center space-x-20">
								<a href="{{ route('categories.index') }}"
										class="text-gray-300 hover:text-amber-400 transition duration-150">Categories</a>
								<a href="{{ route('products.index') }}" class="text-gray-300 hover:text-amber-400 transition duration-150">All
										Products</a>
								<a href="{{ route('orders.index') }}"
										class="text-gray-300 hover:text-amber-400 transition duration-150">Orders</a>
								<a href="{{ route('products.index', 'trash') }}"
										class="text-gray-300 hover:text-amber-400 transition duration-150">Deleted items</a>
						</div>
						<div class="flex inline-flex mt-4 space-x-2">
								<!-- Search Bar -->
								<form method="GET" action="{{ route('products.index') }}" class="w-full md:w-auto">
										<div class="relative flex items-center ">
												<input type="text" name="search" placeholder="Search products..."
														class="bg-gray-700/50 border border-gray-600 rounded-full pl-10 pr-4 py-2 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64 ">
												<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none ">
														<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
																<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																		d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
														</svg>
												</div>
										</div>
								</form>

								{{-- <!-- Cart Icon -->
								<a href="#" class="relative text-gray-300 hover:text-amber-400 transition duration-150">
										<i class="fas fa-shopping-cart text-xl"></i>
										<span
												class="absolute -top-2 -right-2 bg-accent text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
								</a> --}}

								<!-- User Profile -->
								<div class="flex inline-flex dropdown">
										<button class="flex items-center space-x-2 focus:outline-none">
												<div class="h-8 w-8 rounded-full bg-white text-black flex items-center justify-center">
														{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
												</div>
												<span class="text-gray-300">{{ Auth::user()->name }}</span>
												<i class="fas fa-chevron-down text-xs text-gray-400"></i>
										</button>
										<div
												class="dropdown-menu absolute right-0 mt-12 mr-4 w-48 bg-gray-900 rounded-md shadow-lg py-1 z-50 hidden opacity-0 transition-opacity duration-300">
												<a href="{{ route('profile.edit') }}"
														class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Profile</a>
												<a href="{{ route('orders.index') }}"
														class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Orders</a>
												{{-- <a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Wishlist</a> --}}
												<div class="border-t border-gray-600"></div>
												<form method="POST" action="{{ route('logout') }}">
														@csrf
														<button type="submit"
																class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Sign out</button>
												</form>
										</div>
								</div>
						</div>

						<!-- Mobile menu button -->
						<div class="md:hidden flex items-center">
								<button class="text-gray-300 hover:text-amber-400 focus:outline-none">
										<i class="fas fa-bars text-xl"></i>
								</button>
						</div>
				</div>
		</div>
</nav>
