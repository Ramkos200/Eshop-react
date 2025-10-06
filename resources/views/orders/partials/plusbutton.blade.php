<form action="{{ route('orders.addProduct', [$order->id, $sku->id]) }}" method="POST" class="inline">
		@csrf
		<button type="submit"
				class="
		{{ $status === 'disabled' ? 'text-gray-400 ' : 'text-green-400 ' }}
		{{ $status === 'disabled' ? 'hover:text-gray-300 ' : 'hover:text-green-300 ' }}
		transition-colors"
				title={{ $status === 'disabled' ? 'out of stock' : 'Add SKU to order' }}
				{{ $status === 'disabled' ? 'disabled' : '' }}>
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
				</svg>
		</button>
</form>
