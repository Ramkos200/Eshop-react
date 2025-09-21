<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
            {{ __($product->name) }}

        </h2>
    </x-slot>
    <div class="min-h-screen bg-cover bg-center bg-no-repeat">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-white">
                        <!-- show the product -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-4">
                                <h1 class="text-lg font-bold"> {{ $product->name }} </h1>
                                <p class="text-sm text-gray-300 mb-2">
                                    price: ${{ number_format($product->price, 2) }}</p>
                                <p class="text-sm mb-3">Description: $product->description</p>
                                <div class="bg-gray-700 rounded-lg h-40 flex items-center justify-center">
                                    <img src="{{ asset('/product-images/' . $product->slug . '.jpg') }}"
                                        alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
