<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::with([
            'section' => function ($query) {
                $query->select('id', 'name');
            }
        ])->get();
    }

    public function show(Brand $brand)
    {
        return $brand;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'section_id' => 'required|exists:sections,id',
            'icon' => 'nullable|string',
        ]);

        $section = Section::findOrFail($request->section_id);

        $slug = Str::slug(
            $request->name['en'] . '-' . $section->name
        );

        $brand = Brand::create([
            'name' => $request->name,
            'slug' => $slug,
            'section_id' => $request->section_id,
            'icon' => $request->icon,
        ]);

        return response()->json([
            'success' => true,
            'data' => $brand,
            'section' => $section->name
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'nullable|array',
            'name.en' => 'required_with:name|string|max:255',
            'name.ar' => 'required_with:name|string|max:255',
            'section_id' => 'nullable|exists:sections,id',
            'icon' => 'nullable|string',
        ]);

        if ($request->has('name')) {
            $brand->name = $request->name;
        }

        if ($request->has('section_id')) {
            $brand->section_id = $request->section_id;
        }

        if ($request->has('icon')) {
            $brand->icon = $request->icon;
        }

        $section = Section::findOrFail($brand->section_id);

        $brand->slug = Str::slug(
            $brand->name . '-' . $section->name
        );

        $brand->save();

        return response()->json([
            'success' => true,
            'data' => $brand,
        ]);
    }

    public function destroy(Brand $brand)
    {
        if ($brand->icon) {
            Storage::disk('public')->delete($brand->icon);
        }

        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully'
        ]);
    }
}