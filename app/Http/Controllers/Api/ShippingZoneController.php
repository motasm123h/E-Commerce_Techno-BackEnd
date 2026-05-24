<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    // PUBLIC & ADMIN: Get all active shipping zones (to show in a dropdown at checkout)
    public function index()
    {
        return response()->json(ShippingZone::where('is_active', true)->get());
    }

    // ADMIN: Create a new shipping zone
    public function store(Request $request)
    {
        $request->validate([
            'city_name' => 'required|string|unique:shipping_zones,city_name',
            'fee' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        $zone = ShippingZone::create([
            'city_name' => $request->city_name,
            'fee' => $request->fee,
            'is_active' => $request->is_active ?? true
        ]);

        return response()->json($zone, 201);
    }

    // ADMIN: Update a shipping zone (e.g., raise the price or disable it)
    public function update(Request $request, ShippingZone $shippingZone)
    {
        $request->validate([
            'city_name' => 'sometimes|string|unique:shipping_zones,city_name,' . $shippingZone->id,
            'fee' => 'sometimes|numeric|min:0',
            'is_active' => 'sometimes|boolean'
        ]);

        $shippingZone->update($request->only(['city_name', 'fee', 'is_active']));

        return response()->json($shippingZone);
    }

    // ADMIN: Delete a shipping zone
    public function destroy(ShippingZone $shippingZone)
    {
        $shippingZone->delete();
        return response()->json(['message' => 'Shipping zone deleted']);
    }
}