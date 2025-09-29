<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $showTrash = $request->has('trash');
        $query = $showTrash ? Product::onlyTrashed() : Product::query();
        $perPage = 10;

        //  eager load 
        $query->with('category', 'skus');

        // Handle products filter
        if ($request->has('products')) {
            $query->whereIn('id', (array)$request->products);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
            $category = Category::find($request->category_id);
        } elseif ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        } else {
            $category = null;
        }

        // search 
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // sort 
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $products = $query->with('skus')->orderBy($sort, $direction)->paginate($perPage);

        return view('products.index', compact('products', 'showTrash', 'category'));
    }

    public function create(Request $request)
    {
        //
        $categories = Category::all();
        $category = Category::find($request->category_id);

        return view('products.create', compact('category', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            // 'price' => 'required|numeric|min:1',
            'status' => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);
        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $newslug = $slug . '-' . rand(1, 99);
            $slug = 'SKU-' . $newslug;
        }
        Product::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'price' => 0,
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        //
        $product = Product::where('slug', $slug)->firstOrFail();
        $attributes = $product->skus;
        return view('products.show', compact('product', 'attributes'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        // $product = Product::find($product_id);
        $categories = Category::all();
        $grandchildcategory = Category::whereHas('parent.parent')->get();

        session(['previous_url' => url()->previous()]);
        return view('products.edit', compact('product', 'categories', 'grandchildcategory'));
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
            // 'price' => 'required|numeric|min:1',
            'status' => 'required|string',
            'category_id' => 'required|exists:categories,id'
        ]);
        $product->update($validated);
        // return redirect()->route('products.show', $product->slug);
        return redirect(session('previous_url', route('products.show', $product->slug)))
            ->with('success', 'Product updated successfully!');
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