<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ImageService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

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

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link_url' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $imagePath = $this->imageService->uploadAndCompressSingleImage($request->file('image'), 'banners', 1200, 450);

        $banner = Banner::create([
            'image_path' => $imagePath,
            'link_url' => $request->link_url,
            'is_active' => $request->is_active ?? true
        ]);

        return response()->json(['success' => true, 'data' => $banner]);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link_url' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $this->imageService->deletePhysicalImages([$banner->image_path]);

            $banner->image_path = $this->imageService->uploadAndCompressSingleImage($request->file('image'), 'banners', 1200, 450);
        }

        if ($request->has('link_url')) $banner->link_url = $request->link_url;
        if ($request->has('is_active')) $banner->is_active = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);

        $banner->save();

        return response()->json(['success' => true, 'data' => $banner]);
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        $this->imageService->deletePhysicalImages([$banner->image_path]);

        $banner->delete();

        return response()->json(['success' => true, 'message' => 'Banner asset and timeline sync dropped cleanly.']);
    }
}
