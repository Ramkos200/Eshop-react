<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItem $orderItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderItem $orderItem)
    {
        // Eager load relationships to avoid N+1 queries

        $orderItem->load(['order', 'sku.product']);
        $order = $orderItem->order;

        return view('orderitems.edit', [
            'orderItem' => $orderItem,
            'order' => $orderItem->order
        ]);
        // return view('orderitems.edit', compact('orderItem', 'order'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItem $orderItem)
    {
        //
    }
}
