<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalOrders' => Order::count(),
            'totalOrdersAmount' => Order::sum('total_amount'),
            'totalCategories' => Category::count(),
            'totalProducts' => Product::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'completedOrders' => Order::where('status', 'completed')->count(),
            'recentOrders' => Order::where('created_at', '>=', now()->subDays(7))->count(),
            'recentRevenue' => Order::where('created_at', '>=', now()->subDays(7))->sum('total_amount'),
        ];

        return view('dashboard', $stats);
    }
}