<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-cover bg-center bg-no-repeat">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-white">
                        <h3 class="font-['Cormorant_Garamond'] text-2xl font-light mb-4">Welcome,
                            {{ Auth::user()->name }}!</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                            <div class="bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-6">
                                <h4 class="font-['Inter'] font-semibold text-white mb-2 underline">Quick Actions </h4>
                                <ul class="font-['Inter'] text-gray-200 space-y-2">
                                    <li><a href="{{ url('/categories') }}"
                                            class="hover:underline transition-colors">Browse Categories</a></li>
                                    <li><a href="{{ route('profile.edit') }}"
                                            class="hover:underline transition-colors">Update Profile</a></li>
                                    {{-- <li><a href="{{ url('/cart') }}" class="hover:text-white transition-colors">View
                                            Cart</a></li> --}}
                                </ul>
                            </div>

                            <div class="bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-6">
                                <h4 class="font-['Inter'] font-semibold text-white mb-2 underline">Account Info</h4>
                                <p class="font-['Inter'] text-gray-200">Email: {{ Auth::user()->email }}</p>
                                <p class="font-['Inter'] text-gray-200">Member since:
                                    {{ Auth::user()->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
