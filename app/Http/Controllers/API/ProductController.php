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

    $query = Product::with(['category', 'skus']);


    // Filters
    if ($request->has('category_id')) {
      $query->where('category_id', $request->category_id);
    }

    if ($request->has('search')) {
      $searchTerm = '%' . $request->search . '%';
      $query->where('name', 'like', $searchTerm);
    }

    $perPage = $request->get('per_page', 12);
    $products = $query->orderBy('created_at', 'desc')->paginate($perPage);

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
      $product->load(['category', 'skus']);

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
    $query = Product::with(['category', 'skus']);


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