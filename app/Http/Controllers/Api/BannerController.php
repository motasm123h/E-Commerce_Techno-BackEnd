<?php

namespace App\Http\Controllers\Api;

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function getPublicBanners()
    {
        $banners = Banner::where('is_active', true)->latest()->get();
        return response()->json(['success' => true, 'data' => $banners]);
    }

    public function index()
    {
        $banners = Banner::latest()->get();
        return response()->json(['success' => true, 'data' => $banners]);
    }

    // إنشاء بنر جديد
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image',
            'link_url' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $path = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'image_path' => '/storage/' . $path,
            'link_url' => $request->link_url,
            'is_active' => $request->is_active ?? true
        ]);

        return response()->json(['success' => true, 'data' => $banner]);
    }

    // تحديث البنر
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image',
            'link_url' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // مسح الصورة القديمة
            $oldPath = str_replace('/storage/', '', $banner->image_path);
            Storage::disk('public')->delete($oldPath);
            
            $path = $request->file('image')->store('banners', 'public');
            $banner->image_path = '/storage/' . $path;
        }

        if ($request->has('link_url')) $banner->link_url = $request->link_url;
        if ($request->has('is_active')) $banner->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        $banner->save();

        return response()->json(['success' => true, 'data' => $banner]);
    }

    // حذف البنر
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $oldPath = str_replace('/storage/', '', $banner->image_path);
        Storage::disk('public')->delete($oldPath);
        $banner->delete();

        return response()->json(['success' => true, 'message' => 'Banner deleted']);
    }
}