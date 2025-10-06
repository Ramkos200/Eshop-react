<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem)
    {
        $orderItem->load(['order', 'sku.product']);
        return view('orderitems.edit', [
            'orderItem' => $orderItem,
            'order' => $orderItem->order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'attributes.color' => 'required|string',
            'attributes.size' => 'required|in:medium,large',
            'attributes.material' => 'required|in:wood,metal,plastic'
        ]);

        // dd($request);
        $orderItem->update([
            'quantity' => $validated['quantity'],
            'attributes' => $validated['attributes']
        ]);

        return redirect()->route('orders.show', $orderItem->order);
    }
}