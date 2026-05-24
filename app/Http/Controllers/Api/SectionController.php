<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    // Get all sections (and include their parent category)
    public function index()
    {
        return Section::with('category')->get();
    }

    // Get a single section (include category and products)
    public function show(Section $section)
    {
        return $section->load(['category', 'products']);
    }

    // Create a new section
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'display_on_home' => 'nullable|boolean',
            'home_order' => 'nullable|integer'
        ]);

        $section = Section::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            // قراءة القيم الجديدة وتخزينها
            'display_on_home' => $request->display_on_home ?? false,
            'home_order' => $request->home_order ?? 0,
        ]);

        return response()->json(['success' => true, 'data' => $section]);
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'display_on_home' => 'nullable|boolean',
            'home_order' => 'nullable|integer'
        ]);

        $section->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            // تحديث القيم
            'display_on_home' => $request->display_on_home ?? false,
            'home_order' => $request->home_order ?? 0,
        ]);

        return response()->json(['success' => true, 'data' => $section]);
    }

    // Delete a section
    public function destroy(Section $section)
    {
        // Because of cascade delete, deleting a section deletes all products inside it.
        $section->delete();

        return response()->json(['message' => 'Section deleted successfully']);
    }


    public function getHomeSections()
    {
        // تعديل الاستعلام: يجلب فقط الأقسام المفعلة للهوم ويرتبها تصاعدياً
        $sections = Section::where('display_on_home', true)
            ->orderBy('home_order', 'asc')
            ->with(['products' => function ($query) {
                $query->where('is_active', true)
                    ->where('stock', '>', 0)
                    ->with(['brand', 'section.category'])
                    ->latest()
                    ->take(15);
            }])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }



    public function syncAttributes(Request $request, Section $section)
    {
        $request->validate([
            'attribute_ids' => 'required|array',
            'attribute_ids.*' => 'exists:attributes,id'
        ]);

        $section->attributes()->sync($request->attribute_ids);

        return response()->json(['message' => 'Attributes linked to section successfully.']);
    }

    public function getAttributes(Section $section)
    {
        $attributes = $section->attributes()->get();

        return response()->json(['data' => $attributes]);
    }
}
