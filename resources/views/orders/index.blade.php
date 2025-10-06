<x-app-layout :backgroundImage="asset('images/orders.jpg')">
		<x-slot name="header">
				<h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
						{{ __('All Orders') }} <span class="text-base">({{ $orders->total() }} orders)</span>
				</h2>
		</x-slot>

		<div class="min-h-screen bg-cover bg-center bg-no-repeat">
				<div class="py-4">
						<div class="max-w-8xl mx-auto sm:px-4 lg:px-6">
								<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
										<x-link-button href="{{ route('orders.create') }}"> Create New Order</x-link-button>

										<div class="w-full md:w-auto mt-8">
												@include('partials.searchForm', [
														'route' => route('orders.index'),
														'placeholder' => 'Search Orders...',
												])
										</div>
								</div>

								{{-- Orders Table --}}
								<div class="bg-gray-800/50 backdrop-blur-md rounded-lg shadow-lg border border-gray-700/50 overflow-hidden mt-3">
										<div class="overflow-x-auto">
												<table class="min-w-full divide-y divide-gray-700">
														<thead class="bg-gray-700/50">
																<tr>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Customer
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Order Code
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Items
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Total Amount
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Status
																		</th>
																		<th scope="col"
																				class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
																				Actions
																		</th>
																</tr>
														</thead>
														<tbody class="bg-gray-800/50 divide-y divide-gray-700">
																@forelse ($orders as $order)
																		<tr class="hover:bg-gray-700/30 transition-colors duration-150">
																				<td class="px-6 py-4 whitespace-nowrap">
																						<div class="text-sm text-white">
																								@if ($order->user)
																										{{ $order->user->name }}
																								@elseif($order->Customer && is_array($order->Customer) && isset($order->Customer['name']))
																										{{ $order->Customer['name'] }}
																								@else
																										<span class="text-gray-400">No customer data</span>
																								@endif
																						</div>
																						<div class="text-sm text-gray-300">
																								@if ($order->user)
																										{{ $order->user->email }}
																								@elseif($order->Customer && is_array($order->Customer) && isset($order->Customer['email']))
																										{{ $order->Customer['email'] }}
																								@else
																										<span class="text-gray-400">No email</span>
																								@endif
																						</div>
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap">
																						<a href="{{ route('orders.show', $order) }}"
																								class="text-blue-400 font-medium hover:text-blue-300 hover:underline">
																								{{ $order->order_code }}
																						</a>
																				</td>
																				<td class="px-6 py-4">
																						<div class="text-sm text-gray-300">
																								<span class="text-white">{{ $order->items_count ?? $order->items->count() }}</span> items
																						</div>
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap text-sm text-white">
																						${{ number_format($order->total_amount, 2) }}
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap">
																						<span
																								class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $order->status === 'delivered'
																																																    ? 'bg-green-100 text-green-800'
																																																    : ($order->status === 'pending'
																																																        ? 'bg-yellow-100 text-yellow-800'
																																																        : ($order->status === 'cancelled'
																																																            ? 'bg-red-100 text-red-800'
																																																            : ($order->status === 'processing'
																																																                ? 'bg-blue-100 text-blue-800'
																																																                : ($order->status === 'shipped'
																																																                    ? 'bg-purple-100 text-purple-800'
																																																                    : 'bg-gray-100 text-gray-800')))) }}">
																								{{ ucfirst($order->status) }}
																						</span>
																				</td>
																				<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
																						<div class="flex justify-end space-x-2">
																								<a href="{{ route('orders.show', $order) }}"
																										class="text-green-400 hover:text-green-300 transition-colors" title="View Order">
																										<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																												stroke="currentColor">
																												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																														d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
																												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																														d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
																										</svg>
																								</a>
																								<a href="{{ route('orders.addProducts', $order) }}"
																										class="text-blue-400 hover:text-blue-300 transition-colors" title="Add Products">
																										<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																												stroke="currentColor">
																												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																														d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
																										</svg>
																								</a>
																								<form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline">
																										@csrf
																										@method('DELETE')
																										<button type="submit" class="text-red-400 hover:text-red-300 transition-colors"
																												onclick="return confirm('Are you sure you want to delete this order?')"
																												title="Delete Order">
																												<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
																														stroke="currentColor">
																														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																																d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
																												</svg>
																										</button>
																								</form>
																						</div>
																				</td>
																		</tr>
																@empty
																		<tr>
																				<td colspan="6" class="px-6 py-8 text-center text-gray-300">
																						<div class="flex flex-col items-center justify-center">
																								<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-500 mb-2" fill="none"
																										viewBox="0 0 24 24" stroke="currentColor">
																										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
																												d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
																								</svg>
																								<p class="text-lg font-medium">No orders found</p>
																								<p class="text-sm mt-1">Create your first order to get started</p>
																						</div>
																				</td>
																		</tr>
																@endforelse
														</tbody>
												</table>
										</div>

										{{-- Pagination --}}
										@if ($orders->hasPages())
												<div class="bg-gray-700/50 px-6 py-4 border-t border-gray-600">
														<div class="flex items-center justify-between">
																<div class="text-sm text-gray-300">
																		Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }}
																		results
																</div>
																<div class="flex space-x-2">
																		{{ $orders->links() }}
																</div>
														</div>
												</div>
										@endif
								</div>
						</div>
				</div>
		</div>
</x-app-layout>
