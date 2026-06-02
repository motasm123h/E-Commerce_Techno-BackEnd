<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ShippingZone;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    /**
     * ADMIN: Get all orders (Latest first)
     */
    public function index()
    {
        $orders = Order::with('items.product')->paginate(8);
        return response()->json($orders);
    }

    /**
     * ADMIN: Get a single order's details
     */
    public function show(Order $order)
    {
        $order->load('items.product');
        return response()->json($order);
    }

    /**
     * PUBLIC: Store a new order from the React Cart
     */
    public function store(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'delivery_location' => 'required|string',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'payment_method' => 'required|in:cash,haram_transfer',
            
            'city_location' => 'required|string|max:255',
            'addressOne_location' => 'required|string|max:255', 
            'order_note' => 'required|string|max:255',
            
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.selectedColor' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $shippingZone = ShippingZone::find($request->shipping_zone_id);
            if (!$shippingZone->is_active) {
                return response()->json(['message' => 'We do not deliver to this area currently.'], 400);
            }

            $subtotal = 0;
            $orderItemsData = [];

            foreach ($request->cart as $item) {
                $product = Product::find($item['id']);
                
                if (!$product || !$product->is_active || $product->stock < $item['quantity']) {
                    throw new \Exception("Product {$product->name} is out of stock or unavailable.");
                }

                $realPrice = $product->price;
                $subtotal += ($realPrice * $item['quantity']);

                $orderItemsData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $realPrice,
                    'color' => $item['selectedColor'] ?? null
                ];
            }

            if ($subtotal < 20.00) {
                return response()->json(['message' => 'Minimum order amount is $20.00'], 400);
            }

            $finalTotal = $subtotal + $shippingZone->fee;

            $order = Order::create([
                'reference_number' => 'ORD-' . strtoupper(Str::random(6)),
                'tracking_code'    => strtoupper(Str::random(8)),
                'customer_name'    => $request->customer_name,
                'customer_phone'   => $request->customer_phone,
                'delivery_location'=> $request->delivery_location,
                'shipping_city'    => $shippingZone->city_name,
                'shipping_fee'     => $shippingZone->fee,

                'city_location' => $request->city_location,
                'addressOne_location' => $request->addressOne_location, 
                'order_note' => $request->order_note,
                
                'total_amount'     => $finalTotal,
                'payment_method'   => $request->payment_method,
                'status'           => 'pending'
            ]);

            // 7. Save Items and Deduct Stock
            foreach ($orderItemsData as $data) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $data['product']->id,
                    'quantity' => $data['quantity'],
                    'price' => $data['price'],
                    'color' => $data['color']
                ]);

                $data['product']->decrement('stock', $data['quantity']);
                if ($data['product']->stock === 0) {
                    $data['product']->update(['is_active' => false]);
                }
            }
            $admins = User::get();
            foreach($admins as $admin){
                Notification::send($admin, new NewOrderNotification($order));
            } 
            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'reference_number' => $order->reference_number,
                'tracking_code' => $order->tracking_code, // إرجاع كود التتبع للمستخدم ليحفظه
                'final_total' => $finalTotal
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Order failed.',
                'error' => $e->getMessage()
            ], 400);
        }
    }

   
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled'
        ]);

        $order->status = $request->status;
        $order->save();

        if ($request->status === 'cancelled') {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    $product->update(['is_active' => true]); 
                }
            }
        }

        return response()->json([
            'message' => 'Order status updated',
            'order' => $order
        ]);
    }

    /**
     * ADMIN: Delete an order entirely
     */
    public function destroy(Order $order)
    {
        // Note: Because of 'cascade' delete on your migrations, deleting the Order 
        // will automatically delete the linked OrderItems.
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }


   

    public function trackOrder($trackingCode)
    {
        // Fetch the order and optionally eager-load the items if you want to display them
        $order = Order::where('tracking_code', $trackingCode)->first();

        if (!$order) {
            return response()->json(['message' => 'Invalid tracking code. Order not found.'], 404);
        }

        return response()->json([
            'tracking_code' => $order->tracking_code,
            'customer_name' => $order->customer_name,
            'status' => $order->status,
            'total_amount' => $order->total_amount,
            'payment_method' => $order->payment_method,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
        ], 200);
    }



    public function cancelPublicOrder($trackingCode)
    {
        $order = Order::where('tracking_code', $trackingCode)->firstOrFail();

        // التأكد من أن الطلب معلق فقط
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending orders can be cancelled.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // إعادة الكميات إلى المخزون
            $items = OrderItem::where('order_id', $order->id)->get();
            
            foreach ($items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    
                    // إعادة تفعيل المنتج في حال كان قد نفد من المخزن وأصبح غير فعال
                    if (!$product->is_active && $product->stock > 0) {
                        $product->update(['is_active' => true]);
                    }
                }
            }

            // تحديث حالة الطلب
            $order->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'message' => 'Order cancelled successfully and stock restored.',
                'status' => 'cancelled'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to cancel order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
