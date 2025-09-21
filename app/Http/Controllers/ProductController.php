<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $showTrash = $request->has('trash');
        $query = $showTrash ? Product::onlyTrashed() : Product::query();
        $category = Category::find($request->category_id);

        if ($request->has('products')) {
            $products = Product::whereIn('id', $request->products)->orderBy('created_at', 'desc')->get();

            return view('products.index', compact('products', 'showTrash', 'category'));
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
            $products = $query->orderBy('created_at', 'desc')->get();
            return view('products.index', compact('products', 'showTrash', 'category'));
        }

        $products = $query->orderBy('created_at', 'desc')->get();
        return view('products.index', compact('products', 'showTrash', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'status' => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        //
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('products.show', compact('product'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        // $product = Product::find($product_id);
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'status' => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);
        $product->update($validated);
        return redirect()->route('products.show', $product->slug);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $previousUrl = url()->previous();
        $product = Product::withTrashed()->findOrFail($id);
        if ($product->trashed()) {
            $product->forceDelete();
        } else {
            $product->category_id = null;
            $product->save();
            $product->delete();
        }
        return redirect()->to($previousUrl);
    }

    public function restore($id)
    {
        $previousUrl = url()->previous();
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->to($previousUrl);
    }
}