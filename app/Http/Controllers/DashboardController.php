<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Cache dashboard stats for 5 minutes to improve performance
        $stats = Cache::remember('dashboard_stats', 300, function () {
            return [
                'totalOrders' => Order::count(),
                'totalOrdersAmount' => Order::sum('total_amount'),
                'totalCategories' => Category::count(),
                'totalProducts' => Product::count(),

                // Additional useful metrics
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'completedOrders' => Order::where('status', 'completed')->count(),

                // Recent activities (last 7 days)
                'recentOrders' => Order::where('created_at', '>=', now()->subDays(7))->count(),
                'recentRevenue' => Order::where('created_at', '>=', now()->subDays(7))->sum('total_amount'),
            ];
        });

        return view('dashboard', $stats);
    }

    // Optional: Method to clear dashboard cache when needed
    public function clearCache()
    {
        Cache::forget('dashboard_stats');
        return redirect()->route('dashboard')->with('success', 'Dashboard cache cleared!');
    }
}