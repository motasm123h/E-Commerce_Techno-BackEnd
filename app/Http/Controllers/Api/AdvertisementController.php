<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    /**
     * READ: Get all advertisements (Admin view)
     */
    public function index()
    {
        $advertisements = Advertisement::orderBy('sort_order', 'asc')->get();
        return response()->json(['success' => true, 'data' => $advertisements]);
    }

    /**
     * CREATE: Store a new advertisement
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB
            'link_url' => 'nullable|url',
            'type' => 'required|in:logo,banner',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image');

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('advertisements', 'public');
            $data['image_path'] = '/storage/' . $path;
        }

        $advertisement = Advertisement::create($data);

        return response()->json(['success' => true, 'data' => $advertisement]);
    }

    /**
     * UPDATE: Update an existing advertisement
     */
    public function update(Request $request, $id)
    {
        $advertisement = Advertisement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Nullable because they might not change the image
            'link_url' => 'nullable|url',
            'type' => 'required|in:logo,banner',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('image');

        // Handle Image Update
        if ($request->hasFile('image')) {
            // 1. Delete the old image from storage to save space
            if ($advertisement->image_path && Storage::disk('public')->exists($advertisement->image_path)) {
                Storage::disk('public')->delete($advertisement->image_path);
            }
            // 2. Upload the new image
            $path = $request->file('image')->store('advertisements', 'public');
            $data['image_path'] = $path;
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

        // Delete the image from the server before deleting the database record
        if ($advertisement->image_path && Storage::disk('public')->exists($advertisement->image_path)) {
            Storage::disk('public')->delete($advertisement->image_path);
        }

        $advertisement->delete();

        return response()->json(['success' => true, 'message' => 'Advertisement deleted successfully']);
    }
}
