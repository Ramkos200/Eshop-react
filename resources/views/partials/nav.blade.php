<nav class="bg-gray-800 shadow-md z-50">
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between h-16">
						<!-- Logo -->
						<div class="flex items-center">
								<a href="{{ url('/') }}" class="flex-shrink-0 flex items-center">
										<i class="fas fa-lightbulb text-amber-500 text-2xl mr-2"></i>
										<span class="font-['Cormorant_Garamond'] text-xl font-semibold text-white"> {{ env('APP_NAME') }}</span>
								</a>
						</div>

						<!-- Desktop Navigation -->
						<div class="hidden md:flex items-center space-x-8">
								<a href="{{ route('categories.index') }}"
										class="text-gray-300 hover:text-amber-400 transition duration-150">Categories</a>
								<a href="{{ route('products.index') }}" class="text-gray-300 hover:text-amber-400 transition duration-150">All
										Products</a>

								<a href="{{ route('orders.index') }}"
										class="text-gray-300 hover:text-amber-400 transition duration-150">Orders</a>
								<a href="{{ route('products.index', 'trash') }}"
										class="text-gray-300 hover:text-amber-400 transition duration-150">Deleted items</a>

								<!-- Search Bar -->
								<div class="relative">
										<input type="text" placeholder="Search products..."
												class="w-64 px-4 py-2 rounded-full border border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-gray-700 text-white">
										<button class="absolute right-3 top-2 text-gray-500 hover:text-amber-400">
												<i class="fas fa-search"></i>
										</button>
								</div>

								<!-- Cart Icon -->
								<a href="#" class="relative text-gray-300 hover:text-amber-400 transition duration-150">
										<i class="fas fa-shopping-cart text-xl"></i>
										<span
												class="absolute -top-2 -right-2 bg-accent text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">3</span>
								</a>

								<!-- User Profile -->
								<div class="relative dropdown">
										<button class="flex items-center space-x-2 focus:outline-none">
												<div class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center">
														{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
												</div>
												<span class="text-gray-300">{{ Auth::user()->name }}</span>
												<i class="fas fa-chevron-down text-xs text-gray-400"></i>
										</button>
										<div
												class="dropdown-menu absolute right-0 mt-2 w-48 bg-gray-700 rounded-md shadow-lg py-1 z-50 hidden opacity-0 transition-opacity duration-300">
												<a href="{{ route('profile.edit') }}"
														class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Profile</a>
												<a href="{{ route('orders.index') }}"
														class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Orders</a>
												<a href="#" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">Wishlist</a>
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
