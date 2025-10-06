<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $categories = Category::with(['children.children'])
                ->whereNull('parent_id')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $categories,
                'count' => $categories->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Category $category): JsonResponse
    {
        try {
            $category->load(['products.skus', 'children.products.skus']);

            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
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
        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'parent_id' => $validated['parent_id']
        ]);
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }
}