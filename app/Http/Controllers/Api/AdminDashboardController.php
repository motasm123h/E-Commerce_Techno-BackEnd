<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate Total Revenue (Only from completed orders)
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        // 2. Revenue this month
        $thisMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_amount');

        // 3. Count Pending Orders
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        // 4. Low Stock Products (Less than 5 items left)
        $lowStockProducts = Product::where('stock', '<', 5)
            ->where('is_active', true)
            ->select('id', 'name', 'stock')
            ->get();

        return response()->json([
            'total_revenue' => $totalRevenue,
            'this_month_revenue' => $thisMonthRevenue,
            'pending_orders_count' => $pendingOrdersCount,
            'low_stock_products' => $lowStockProducts,
        ]);
    }
}