<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::all();
    }
    public function show(Brand $brand)
    {
        return $brand;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ];

        if ($request->hasFile('icon')) {
            // Saves to storage/app/public/brands
            $data['icon'] = $request->file('icon')->store('brands', 'public');
        }

        return Brand::create($data);
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if (isset($validated['name'])) {
            $brand->name = $validated['name'];
            $brand->slug = Str::slug($validated['name']);
        }

        if ($request->hasFile('icon')) {
            // 1. Delete the old icon from storage to prevent junk files
            if ($brand->icon) {
                Storage::disk('public')->delete($brand->icon);
            }
            // 2. Save the new icon
            $brand->icon = $request->file('icon')->store('brands', 'public');
        }

        $brand->save();
        return $brand;
    }

    public function destroy(Brand $brand)
    {
        // Delete the icon file before deleting the database record
        if ($brand->icon) {
            Storage::disk('public')->delete($brand->icon);
        }
        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully']);
    }
}
