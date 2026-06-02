<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\ImageService;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * READ: Get all advertisements (Admin view)
     */
    public function index()
    {
        $advertisements = Advertisement::orderBy('sort_order', 'asc')->get();
        return response()->json(['success' => true, 'data' => $advertisements]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link_url' => 'nullable|url',
            'type' => 'required|in:logo,banner',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $width = $request->type === 'banner' ? 600 : 200;
            $height = $request->type === 'banner' ? 300 : 200;

            $data['image_path'] = $this->imageService->uploadAndCompressSingleImage(
                $request->file('image'),
                'advertisements',
                $width,
                $height
            );
        }

        $advertisement = Advertisement::create($data);

        return response()->json(['success' => true, 'data' => $advertisement]);
    }

    public function update(Request $request, $id)
    {
        $advertisement = Advertisement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'link_url' => 'nullable|url',
            'type' => 'required|in:logo,banner',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $this->imageService->deletePhysicalImages([$advertisement->image_path]);

            $width = $request->type === 'banner' ? 600 : 200;
            $height = $request->type === 'banner' ? 300 : 200;

            $data['image_path'] = $this->imageService->uploadAndCompressSingleImage(
                $request->file('image'),
                'advertisements',
                $width,
                $height
            );
        }

        $advertisement->update($data);

        return response()->json(['success' => true, 'data' => $advertisement]);
    }

    /**
     * DELETE: Remove an advertisement
     */
    public function destroy($id)
    {
        $advertisement = Advertisement::findOrFail($id);

        $this->imageService->deletePhysicalImages([$advertisement->image_path]);

        $advertisement->delete();

        return response()->json(['success' => true, 'message' => 'Advertisement record and asset files dropped cleanly.']);
    }
}
