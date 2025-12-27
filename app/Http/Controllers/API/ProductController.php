<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'skus', 'mainImage'])
            ->where('status', 'Published');

        // Filter by multiple categories
        if ($request->has('categories')) {
            $categoryIds = explode(',', $request->categories);
            $query->whereIn('category_id', $categoryIds);
        }

        // Filter by category SLUG
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Price filtering - using minimum SKU price
        if ($request->has('min_price') || $request->has('max_price')) {
            $query->whereHas('skus', function ($q) use ($request) {
                if ($request->has('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }
                if ($request->has('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }


        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where('name', 'like', $searchTerm);
        }

        // Enhanced sorting logic
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSortFields = ['created_at', 'name', 'price', 'updated_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        if (!in_array($sortDirection, $allowedDirections)) {
            $sortDirection = 'desc';
        }

        // Apply case-insensitive sorting for name field
        if ($sortField === 'name') {
            $query->orderByRaw("LOWER(name) $sortDirection");
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'total_pages' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ]
        ]);
    }


    public function show(Product $product): JsonResponse
    {
        try {
            $product->load(['category', 'skus', 'images', 'skus.mainImage', 'skus.galleryImages']);

            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(string $query): JsonResponse
    {
        try {
            $products = Product::with(['category', 'skus'])
                ->where('name', 'like', "%{$query}%")
                ->where('status', 'active')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products,
                'count' => $products->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function browse(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'skus', 'skus.mainImage', 'skus.galleryImages']);
        $query->whereHas('skus');
        $products = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'total_pages' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total()
            ]
        ]);
    }
}
