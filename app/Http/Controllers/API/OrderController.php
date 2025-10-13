<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sku;
use App\Services\CartServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('order_code', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('email', 'like', '%' . $request->search . '%')
                            ->orWhere('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'nullable|string|max:20',
                'shipping_street' => 'required|string|max:255',
                'shipping_city' => 'required|string|max:255',
                'shipping_state' => 'required|string|max:255',
                'shipping_zip' => 'required|string|max:20',
                'shipping_country' => 'required|string|max:255',
                'payment' => 'required|string|in:credit_card,paypal,bank_transfer,cash_on_delivery',
                'notes' => 'nullable|string',
            ]);

            $user = $request->user();
            $guestToken = $request->header('X-Cart-Token');
            $cartService = app(CartService::class);

            if ($user && $guestToken) {
                $cart = $cartService->convertGuestToUserCart($guestToken, $user);
            } else {
                $cart = $cartService->getCart($user, $guestToken);
            }

            if (!$cart) {
                return response()->json(['error' => 'Cart not found'], 422);
            }

            $cartData = $cartService->getCartTotal($cart);

            if (empty($cartData['items'])) {
                return response()->json(['error' => 'Cart is empty'], 422);
            }

            DB::beginTransaction();
            $order = Order::create([
                'order_code' => 'ORD-' . date('Ymd-His') . rand(100, 999),
                'user_id' => $user ? $user->id : null,
                'status' => 'pending',
                'total_amount' => $cartData['total'],
                'notes' => $validated['notes'],
                'payment' => $validated['payment'],
                'Customer' => [
                    'name' => $validated['customer_name'],
                    'email' => $validated['customer_email'],
                    'phone' => $validated['customer_phone'] ?? null,
                ],
                'shipping_address' => [
                    'street_address' => $validated['shipping_street'],
                    'city' => $validated['shipping_city'],
                    'state' => $validated['shipping_state'],
                    'zip_code' => $validated['shipping_zip'],
                    'country' => $validated['shipping_country'],
                ],
            ]);

            foreach ($cartData['items'] as $item) {
                $sku = Sku::find($item['sku_id']);
                if (!$sku) {
                    throw new \Exception("SKU not found: {$item['sku_id']}");
                }
                if ($sku->inventory < $item['quantity']) {
                    throw new \Exception("Not enough inventory for {$sku->code}");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'sku_id' => $item['sku_id'],
                    'product_id' => $sku->product_id,
                    'sku_code' => $item['sku_code'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'attributes' => $item['attributes'],
                ]);

                $sku->decrement('inventory', $item['quantity']);
            }

            $cart->update(['status' => 'converted']);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order,
                'order_code' => $order->order_code
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Order creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Order $order): JsonResponse
    {
        $order->load(['items.sku.product']);

        return response()->json($order);
    }



    public function userOrders(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $orders = Order::with(['items.sku.product.mainImage'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $orders,
                'count' => $orders->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}