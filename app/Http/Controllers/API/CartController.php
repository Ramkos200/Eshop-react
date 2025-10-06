<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $cart = Session::get('cart', []);
            $cartItems = [];
            $total = 0;
            $count = 0;

            foreach ($cart as $skuId => $quantity) {
                $sku = Sku::with('product')->find($skuId);

                if ($sku) {
                    $subtotal = $sku->price * $quantity;
                    $total += $subtotal;
                    $count += $quantity;

                    $cartItems[] = [
                        'sku_id' => $sku->id,
                        'sku_code' => $sku->code,
                        'product_name' => $sku->product->name,
                        'product_slug' => $sku->product->slug,
                        'price' => $sku->price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'inventory' => $sku->inventory,
                        'attributes' => $sku->attributes
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => $cartItems,
                    'total' => $total,
                    'count' => $count
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addItem(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'sku_id' => 'required|exists:skus,id',
                'quantity' => 'required|integer|min:1|max:10'
            ]);

            $cart = Session::get('cart', []);
            $skuId = $request->sku_id;
            $quantity = $request->quantity;

            // Check inventory
            $sku = Sku::find($skuId);
            $currentQuantity = $cart[$skuId] ?? 0;

            if ($sku->inventory < ($currentQuantity + $quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough inventory. Only ' . $sku->inventory . ' items available.'
                ], 422);
            }

            $cart[$skuId] = $currentQuantity + $quantity;
            Session::put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'cart_count' => array_sum($cart)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ... (updateQuantity, removeItem, clear methods similar structure)
}