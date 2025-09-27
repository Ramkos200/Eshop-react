<form action="{{ route('orders.decreaseQuantity', [$order->id, $sku->id]) }}" method="POST" class="inline">
		@csrf
		<button type="submit" class="text-red-400 hover:text-red-300 transition-colors" title="Decrease quantity">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
				</svg>
		</button>
</form>
