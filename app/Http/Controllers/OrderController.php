<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //for search
        $query = Order::with('user');
        if ($request->has('search') && !empty($request->search)) {
            $query->where('order_code', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', function ($query) use ($request) {
                    $query->where('email', 'like', '%' . $request->search . '%');
                })->orWhereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                })->get();
            // ->orWhere('user->name', 'like', '%' . $request->search . '%')
            // ->orWhere('user->email', 'like', '%' . $request->search . '%')->get();
        }
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
        ]);
        $user_id = User::where('email', $request['email'])->value('id');
        if (!$user_id) $Customer = ['name' => $request['username'], 'email' => $request['email']];
        $order_code = 'ORD-' . date('Ymd-His') . rand(100, 999);


        $order = Order::create([
            'user_id' => $user_id,
            'order_code' => $order_code,
            'status' => $validated['status'],
            'total_amount' => 0,
            'notes' => $validated['notes'],
            'Customer' => $Customer ? json_encode($Customer) : null,
            'shipping_address' => json_encode([
                'city' => $validated['city'],
                'state' => $validated['state'],
                'Street Address' => $validated['street'],
                'Zip Code' => $validated['zip_code'],
                'Country' => $validated['country'],
            ]),
            'billing_address' => json_encode([
                'city' => $validated['city'],
                'state' => $validated['state'],
                'street_address' => $validated['street'],
                'zip_code' => $validated['zip_code'],
                'Country' => $validated['country'],
            ]),


        ]);
        return view('orders.show', compact('order'))->with('success', 'Order updated successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
        $order->load('items');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /*
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {

        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'order_code' => 'required|string|max:255|unique:orders,order_code,' . $order->id,
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'shipping_address' => 'required|array',
            'billing_address' => 'required|array',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
    //separate update address
    public function updateAddress(Request $request, Order $order)
    {
        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
        ]);

        $shippingAddress = [
            'Street Address' => $validated['street'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'Zip Code' => $validated['zip_code'],
            'Country' => $validated['country'],
        ];

        $order->update(['shipping_address' => $shippingAddress,]);
        return redirect()->back()->with('success', 'Shipping address updated successfully!');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);


        $order->update($validated);
        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function updateUser(Request $request, Order $order)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'string'
        ]);

        if (!$order->user) {
            $customer = json_decode($order->Customer, true);
            $customer['name'] = $request->name;
            $customer['email'] = $request->email;
            $order->Customer = json_encode($customer);
            $order->save();
        } else
            $order->user()->update($validated);

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function addProducts(Request $request, Order $order = null)
    {
        $query = Product::with(['category', 'skus' => function ($query) use ($request) {
            // Eager load only the matching SKUs when searching
            if ($request->has('search') && !empty($request->search)) {
                $query->where('code', 'like', '%' . $request->search . '%');
            }
            // $query->with('images');
        }]);

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';

            $query->whereHas('skus', function ($skuQuery) use ($searchTerm) {
                $skuQuery->where('code', 'like', $searchTerm);
            });
        }

        $products = $query->paginate(10);
        $selectedProducts = Session::get('selectedProducts', []);

        return view('orders.addproducts', compact('products', 'selectedProducts', 'order'));
    }

    public function addProduct($orderId, $skuId)
    {
        $order = Order::findOrFail($orderId);
        $sku = Sku::findOrFail($skuId);

        $selectedProducts = Session::get('selectedProducts', []);

        // Use the SKU's ID as the key
        if (!isset($selectedProducts[$sku->id])) {
            $selectedProducts[$sku->id] = [
                'sku' => $sku,
                'product' => $sku->product,
                'quantity' => 0
            ];
        }

        $selectedProducts[$sku->id]['quantity']++;
        Session::put('selectedProducts', $selectedProducts);

        return redirect()->route('orders.addProducts', $order);
    }

    public function decreaseQuantity($orderId, $skuId)
    {
        $order = Order::findOrFail($orderId);
        $sku = Sku::findOrFail($skuId);

        $selectedProducts = Session::get('selectedProducts', []);

        if (isset($selectedProducts[$sku->id]) && $selectedProducts[$sku->id]['quantity'] > 0) {
            $selectedProducts[$sku->id]['quantity']--;

            if ($selectedProducts[$sku->id]['quantity'] === 0) {
                unset($selectedProducts[$sku->id]);
            }

            Session::put('selectedProducts', $selectedProducts);
        }

        return redirect()->route('orders.addProducts', $order);
    }

    public function removeProduct(Order $order, Sku $sku)
    {
        $selectedProducts = Session::get('selectedProducts', []);

        if (isset($selectedProducts[$sku->id])) {
            unset($selectedProducts[$sku->id]);
            Session::put('selectedProducts', $selectedProducts);
        }

        return redirect()->route('orders.addProducts', $order);
    }

    public function clearSelection(Order $order)
    {
        Session::forget('selectedProducts');
        return redirect()->route('orders.addProducts', $order);
    }



    public function finalizeOrder(Request $request, Order $order)
    {
        $selectedProducts = Session::get('selectedProducts', []);

        if (empty($selectedProducts)) {
            return redirect()->route('orders.addProducts', $order)
                ->with('error', 'No SKUs selected for order.');
        }

        $totalAmount = 0;

        // Add SKUs to the order
        foreach ($selectedProducts as $skuId => $item) {
            $sku = Sku::find($skuId);

            if ($sku) {
                $quantity = $item['quantity'] ?? 1;
                $unitPrice = $sku->price;
                $subtotal = $unitPrice * $quantity;
                $totalAmount += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $sku->product_id, // Store the parent product ID
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'subtotal' => $subtotal,
                    'sku_code' => $sku->code,
                    'sku_id' => $sku->id,
                    'attributes' => json_encode($sku->attributes ?? ['color' => '', 'size' => '', 'materials' => '']),
                ]);
            }
        }

        // Update the order total amount
        $order->update(['total_amount' => $totalAmount]);

        // Clear the session selection
        Session::forget('selectedProducts');

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order finalized successfully! Order #: ' . $order->order_code);
    }
}