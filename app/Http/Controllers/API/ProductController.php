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

    // Filter by category SLUG (this is what's missing)
    if ($request->has('category')) {
      $query->whereHas('category', function ($q) use ($request) {
        $q->where('slug', $request->category);
      });
    }

    // Keep your existing category_id filter for backward compatibility
    if ($request->has('category_id')) {
      $query->where('category_id', $request->category_id);
    }

    if ($request->has('search')) {
      $searchTerm = '%' . $request->search . '%';
      $query->where('name', 'like', $searchTerm);
    }

    // Sorting
    if ($request->has('sort') && $request->has('direction')) {
      $query->orderBy($request->sort, $request->direction);
    } else {
      $query->orderBy('created_at', 'desc');
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