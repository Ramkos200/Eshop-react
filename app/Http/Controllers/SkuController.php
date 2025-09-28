<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sku;
use Illuminate\Http\Request;

class SkuController extends Controller
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
    public function create(Product $product)
    {
        return view('skus.create', compact('product'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Product $product)
    {

        $validated = $request->validate([
            'price' => 'required|numeric|min:1',
            'inventory' => 'required|numeric|min:1',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            'color' => 'required|string',
            'size' => 'required|string',
            'material' => 'required|string',

        ]);
        $categoryCode = substr(strtoupper($product->category->name ?? 'GEN'), 0, 3);
        $productCode = substr(strtoupper($product->name ?? 'GEN'), 0, 3);
        $colorCode = substr(strtoupper($validated['color']), 0, 3);
        $materialCode = substr(strtoupper($validated['material']), 0, 3);
        $sizeCode = strtoupper($validated['size']);
        $code = $categoryCode . $productCode . $colorCode  . $sizeCode . $materialCode . rand(100, 999);
        Sku::create([
            'product_id' => $product->id,
            // 'code' => 'SKU-' . date('Ymd-His') . rand(100, 999),
            'code' => $code,
            'price' => $validated['price'],
            'inventory' => $validated['inventory'],
            'attributes' => [
                'color' => $validated['color'],
                'size' => $validated['size'],
                'material' => $validated['material'],
            ]

        ]);

        return redirect()->route('products.show', $product->slug)->with('success', 'Variant created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sku $sku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sku $sku)
    {
        return view('skus.edit', compact('sku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sku $sku)
    {
        $sku->load('product');
        $validated = $request->validate([
            'price' => 'required|numeric|min:1',
            'inventory' => 'required|numeric|min:1',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            'color' => 'required|string',
            'size' => 'required|string',
            'material' => 'required|string',

        ]);
        $sku->update([
            'price' => $validated['price'],
            'inventory' => $validated['inventory'],
            'attributes' => [
                'color' => $validated['color'],
                'size' => $validated['size'],
                'material' => $validated['material'],
            ]
        ]);

        return redirect()->route('products.show', $sku->product->slug)->with('success', 'Variant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sku $sku)
    {
        //
        $previousUrl = url()->previous();
        $sku->delete();
        return redirect()->to($previousUrl);
    }
}