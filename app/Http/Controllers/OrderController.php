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
      $query->where(function ($q) use ($request) {
        $q->where('order_code', 'like', '%' . $request->search . '%')
          ->orWhereHas('user', function ($userQuery) use ($request) {
            $userQuery->where('email', 'like', '%' . $request->search . '%')
              ->orWhere('name', 'like', '%' . $request->search . '%');
          });
      });
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
    $validated = $request->validate([
      'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
      'notes' => 'nullable|string',
      'street_address' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'state' => 'required|string|max:255',
      'zip_code' => 'required|string|max:20',
      'country' => 'required|string|max:255',
      'email' => 'required|email',
      'username' => 'required|string|max:255',
    ]);

    $user_id = User::where('email', $validated['email'])->value('id');
    $customer = null;

    if (!$user_id) {
      $customer = [
        'name' => $validated['username'],
        'email' => $validated['email']
      ];
    }

    $order_code = 'ORD-' . date('Ymd-His') . rand(100, 999);

    $order = Order::create([
      'user_id' => $user_id,
      'order_code' => $order_code,
      'status' => $validated['status'],
      'total_amount' => 0, // Will be updated when products are added
      'notes' => $validated['notes'],
      'Customer' => $customer ??  null,
      'shipping_address' => [
        'city' => $validated['city'],
        'state' => $validated['state'],
        'street_address' => $validated['street_address'],
        'zip_code' => $validated['zip_code'],
        'country' => $validated['country'],
      ],
      'billing_address' => [
        'city' => $validated['city'],
        'state' => $validated['state'],
        'street_address' => $validated['street_address'],
        'zip_code' => $validated['zip_code'],
        'country' => $validated['country'],
      ],
    ]);

    return redirect()->route('orders.show', $order)->with('success', 'Order created successfully!');
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

    $order->items()->delete();


    $order->delete();

    return redirect()->route('orders.index')
      ->with('success', 'Order #' . $order->order_code . ' deleted successfully!');
  }

  public function updateAddress(Request $request, Order $order)
  {
    $validated = $request->validate([
      'street_address' => 'required|string|max:255',
      'city' => 'required|string|max:255',
      'state' => 'required|string|max:255',
      'zip_code' => 'required|string|max:20',
      'country' => 'required|string|max:255',
    ]);

    $shippingAddress = [
      'street_address' => $validated['street_address'],
      'city' => $validated['city'],
      'state' => $validated['state'],
      'zip_code' => $validated['zip_code'],
      'country' => $validated['country'],
    ];

    $order->update(['shipping_address' => $shippingAddress]);

    return redirect()->back()->with('success', 'Shipping address updated successfully!');
  }

  public function updateStatus(Request $request, Order $order)
  {
    $validated = $request->validate([
      'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
    ]);

    $order->update($validated);
    $order->refresh();
    return redirect()->back()->with('success', 'Status updated successfully!');
  }

  public function updateUser(Request $request, Order $order)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|max:255',
      'phone' => 'nullable|string|max:20|regex:/^[0-9\-\+\s\(\)]{10,20}$/'
    ]);

    if ($order->user) {
      // Update  user 
      $order->user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? $order->user->phone
      ]);

      $message = 'User account updated successfully!';
    } else {
      //  guest customer 

      $customer = $order->Customer ?? [];

      // Update customer 
      $customer['name'] = $validated['name'];
      $customer['email'] = $validated['email'];


      if (isset($validated['phone']) && !empty($validated['phone'])) {
        $customer['phone'] = $validated['phone'];
      } elseif (isset($customer['phone'])) {
        unset($customer['phone']);
      }

      $order->update([
        'Customer' => $customer
      ]);

      $message = 'Guest customer information updated successfully!';
    }

    return redirect()->back()->with('success', $message);
  }

  public function addProducts(Request $request, Order $order = null)
  {
    $query = Product::with(['category', 'skus' => function ($query) use ($request) {
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
      ->with('success', 'Order finalized successfully!');
  }
}