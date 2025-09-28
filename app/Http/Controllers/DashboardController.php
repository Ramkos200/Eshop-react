<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalOrdersAmount = Order::sum('total_amount');
        $totalCategories = Category::count();
        $totalProducts = Product::count();

        return view('dashboard', compact(
            'totalOrders',
            'totalOrdersAmount',
            'totalCategories',
            'totalProducts'
        ));
    }
}