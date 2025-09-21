<x-app-layout :backgroundImage="asset('images/orders.jpg')">
    <x-slot name="header">
        <h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
            {{ __('All Orders') }} <span class="text-base">({{$orders->count()}} orders)</span>
        </h2>
    </x-slot>
    <div class="min-h-screen bg-cover bg-center bg-no-repeat">
        <div class="py-8">
            <div class=" grid gri-cols-1 lg:grid-cols-3 max-w-7xl mx-auto sm:px-4 lg:px-6">
                @foreach ($orders as $order)
                    <div class=" bg-white/5 backdrop-blur-sm border border-white/25 rounded-lg p-4 mb-2 ml-2">
                       
                            <!-- Main Order  -->
                            <a href="{{ route('orders.show', $order) }}"
                                class="block text-lg font-semibold text-white hover:text-blue-300 transition-colors mb-2">
                                👤 : {{ $order->user->name }} |
                                📧: {{ $order->user->email }}
                            </a>
                            <!-- number of items in each order -->
                            <ul class="text-sm text-gray-300 mb-3">
                                <li> order_code: {{ $order->order_code }}</li>
                                <li> 🛍️ #items: {{ $order->items->count() }} </li>
                                <li>💸 total: ${{ $order->total_amount }}</li>
                                <li>✓ status: {{ $order->status }}</li>
                            </ul>
                            <!-- Child subcategories -->
                            {{-- @if ($order)
                                                <div class="ml-4 mt-2">
                                                    <h5 class="text-sm font-medium text-gray-400 mb-2">Orders:</h5>
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                                        @foreach ($order->user as $child)
                                                            @php
                                                                dd($child);
                                                            @endphp
                                                            <a href="{{ route('orders.index', 'order_id') }}"
                                                                class="text-sm text-blue-300 hover:text-blue-200 transition-colors flex items-center">
                                                                <span class="mr-1">•</span>
                                                                {{ $child->name }}
                                                                <span class="text-xs text-gray-400 ml-2">
                                                                    📅: ({{ $child->products->count() }})
                                                                    ⏰: ({{ $order->user->count() }})
                                                                </span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-400 italic ml-4">No subcategories</p>
                                            @endif --}}
                            <!-- Separator between subcategories -->
                       
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
