<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Sku;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_street' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Get cart from session
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 422);
        }

        // Calculate total and validate inventory
        $totalAmount = 0;
        $orderItems = [];

        foreach ($cart as $skuId => $quantity) {
            $sku = Sku::with('product')->find($skuId);

            if (!$sku) {
                return response()->json(['error' => 'Invalid product in cart'], 422);
            }

            if ($sku->inventory < $quantity) {
                return response()->json([
                    'error' => "Not enough inventory for {$sku->product->name}. Only {$sku->inventory} available."
                ], 422);
            }

            $subtotal = $sku->price * $quantity;
            $totalAmount += $subtotal;

            $orderItems[] = [
                'sku_id' => $sku->id,
                'product_id' => $sku->product_id,
                'sku_code' => $sku->code,
                'quantity' => $quantity,
                'price' => $sku->price,
                'subtotal' => $subtotal,
                'attributes' => $sku->attributes,
            ];
        }

        // Create order
        $order = Order::create([
            'order_code' => 'ORD-' . date('Ymd-His') . rand(100, 999),
            'user_id' => null, // Guest order
            'status' => 'pending',
            'total_amount' => $totalAmount,
            'notes' => $validated['notes'],
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

        // Create order items
        foreach ($orderItems as $item) {
            OrderItem::create(array_merge($item, ['order_id' => $order->id]));

            // Update inventory
            $sku = Sku::find($item['sku_id']);
            $sku->decrement('inventory', $item['quantity']);
        }

        // Clear cart
        Session::forget('cart');

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
            'order_code' => $order->order_code
        ]);
    }

    public function show(Order $order): JsonResponse
    {
        $order->load(['items.sku.product']);

        return response()->json($order);
    }

    public function userOrders(Request $request): JsonResponse
    {
        // For future authenticated users
        $orders = Order::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }
}