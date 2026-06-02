<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{

    public function index()
    {
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $thisMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        $pendingOrdersCount = Order::where('status', 'pending')->count();

        $lowStockProducts = Product::where('stock', '<', 5)
            ->where('is_active', true)
            ->select('id', 'name', 'stock', 'slug')
            ->get();

        return response()->json([
            'success' => true,
            'total_revenue' => (float) $totalRevenue,
            'this_month_revenue' => (float) $thisMonthRevenue,
            'pending_orders_count' => $pendingOrdersCount,
            'low_stock_products' => $lowStockProducts,
        ]);
    }


    public function getOrdersByStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled'
        ]);

        $orders = Order::where('status', $request->status)
            ->with(['shippingZone'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'status_requested' => $request->status,
            'count' => $orders->count(),
            'data' => $orders
        ]);
    }


    public function getSectionComponentStats(Request $request)
    {
        $request->validate([
            'section_name' => 'required|string|max:255'
        ]);

        $searchName = trim($request->section_name);

        $section = Section::where('name->en', 'LIKE', "%{$searchName}%")
            ->orWhere('name->ar', 'LIKE', "%{$searchName}%")
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Target operations section node not found.'
            ], 404);
        }

        $stats = Product::where('section_id', $section->id)
            ->select(
                'component_type',
                DB::raw('count(*) as total_products_count'),
                DB::raw('sum(stock) as total_physical_stock')
            )
            ->groupBy('component_type')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->component_type ? strtoupper($item->component_type) : 'STANDARD_ASSET',
                    'products_count' => $item->total_products_count,
                    'total_stock' => (int) ($item->total_physical_stock ?? 0)
                ];
            });

        return response()->json([
            'success' => true,
            'section' => [
                'id' => $section->id,
                'name' => $section->name
            ],
            'inventory_matrix' => $stats
        ]);
    }
}
