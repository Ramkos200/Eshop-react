<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Img;
use App\Models\Sku;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $guestToken = $request->header('X-Cart-Token');

            $cartService = app(CartService::class);
            $cart = $cartService->getCart($user, $guestToken);

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create or retrieve cart'
                ], 500);
            }

            $cartData = $cartService->getCartTotal($cart);

            return response()->json([
                'success' => true,
                'data' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch cart'
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

            $user = $request->user();
            $guestToken = $request->header('X-Cart-Token');
            $cart = $this->cartService->getCart($user, $guestToken);
            $cartData = $this->cartService->addItem($cart, $request->sku_id, $request->quantity);
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'data' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function updateQuantity(Request $request, $skuId): JsonResponse
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:0|max:10'
            ]);

            $user = $request->user();
            $guestToken = $request->header('X-Cart-Token');

            $cart = $this->cartService->getCart($user, $guestToken);
            $cartData = $this->cartService->updateQuantity($cart, $skuId, $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'data' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    public function removeItem(Request $request, $skuId): JsonResponse
    {
        try {
            $user = $request->user();
            $guestToken = $request->header('X-Cart-Token');

            $cart = $this->cartService->getCart($user, $guestToken);
            $cartData = $this->cartService->removeItem($cart, $skuId);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'data' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function clear(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $guestToken = $request->header('X-Cart-Token');

            $cartService = app(CartService::class);
            $cart = $cartService->getCart($user, $guestToken);

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ], 404);
            }

            $cartData = $cartService->clearCart($cart);

            return response()->json([
                'success' => true,
                'data' => $cartData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart: ' . $e->getMessage()
            ], 500);
        }
    }
}