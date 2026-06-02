<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::with('sections')->get();
    }

    public function show(Category $category)
    {
        return $category->load('sections');
    }

    public function getStoreNavigation()
    {
        $categories = Category::with('sections',)->get();
        return response()->json($categories, 200);
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return response()->json(['success' => true, 'data' => $category], 201);
    }

  
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'nullable|array',
            'name.en' => 'required_with:name|string|max:255',
            'name.ar' => 'required_with:name|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
        ]);

        if ($request->has('name')) {
            $category->name = $request->name;
        }

        if ($request->has('slug')) {
            $category->slug = $request->slug;
        }

        $category->save();

        return response()->json(['success' => true, 'data' => $category], 200);
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
