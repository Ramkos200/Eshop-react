<x-app-layout :backgroundImage="asset('images/orders.jpg')">
    <x-slot name="header">
        <h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
            Order#: {{ __($order->order_code) }} | Customer: {{ __($order->user->name) }}
        </h2>
    </x-slot>
    <div class="min-h-screen bg-cover bg-center bg-no-repeat">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-6 flex flex-col h-100">
                    <div class="grid lg:grid-cols-3 grid-cols-1 p-2">
                        @foreach ($order->items as $item)
                            <div class="max-w-xl bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-6 flex flex-col h-100 mb-2 mr-2">
                                <!-- orders -->
                                <div class="text-sm text-gray-300 mb-3">
                                    <ul class="mr-4">
                                        <div class="flex justify-end mr-2">
                                            <!-- Edit Icon -->
                                            <a href="{{ route('orderItem.edit', $item) }}"
                                                class="text-blue-400 hover:text-blue-300 transition-colors mr-2"
                                                title="Edit Product">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <!-- Delete Icon with Form -->
                                            <form action="{{ route('orderItem.destroy', $item) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-300 transition-colors"
                                                    onclick="return confirm('Are you sure you want to Cancel this order?')"
                                                    title="Cancel Order">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                        <a class="text-lg font-bold hover:text-blue-300 hover:underline"
                                            href="">Product:
                                            {{ $item->sku->product->name }} </a>
                                        <li>* sku: {{ $item->sku_code }} </li>
                                        <li>* price: {{ $item->price }} </li>
                                        <li>* quantity: {{ $item->quantity }} </li>
                                        <li> * attributes:
                                            Color: {{ $item->attributes['color'] }},
                                            Size: {{ $item->attributes['size'] }},
                                            Material:{{ $item->attributes['material'] }}
                                        </li>
                                        <li>* created at: {{ $item->created_at }} </li>
                                        <li>* updated at: {{ $item->updated_at }} </li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
