<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('categories.index')->with('categories', Category::orderBy('created_at', 'desc')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // dd($category);

        $categories = Category::all();
        $category = null;
        if (request()->has('category_id')) $category = Category::find(request('category_id'));
        return view('categories.create', compact('categories', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        $slug = Str::slug($validated['name']);
        if (Product::where('slug', $slug)->exists()) {
            $newslug = $slug . '-' . rand(1, 99);
            $slug = $newslug;
        }
        Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id']
        ]);
        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $categories = Category::all();
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)->get();
        $backgroundImage = file_exists(public_path('images/' . $category->slug . '.jpg'))
            ? asset('images/' . $category->slug . '.jpg')
            : asset('images/welcome-dashboard-picture.jpg');
        session(['previous_url' => url()->previous()]);
        return view('categories.show', compact('category', 'products', 'categories', 'backgroundImage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
        session(['previous_url' => url()->previous()]);
        $categories = Category::all();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);
        $category->update($validated);
        return redirect(session('previous_url', route('categories.index')))
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}