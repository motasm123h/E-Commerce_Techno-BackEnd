<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Get all categories (and include their sections)
    public function index()
    {
        return Category::with('sections')->get();
    }

    // Get a single category
    public function show(Category $category)
    {
        return $category->load('sections');
    }

    public function getStoreNavigation()
    {
        $categories = Category::with('sections')->get();
        return response()->json($categories, 200);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            // Ensure the name is unique in the categories table
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return response()->json($category, 201);
    }

    // Update an existing category
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            // Ignore the current category's ID when checking for uniqueness
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        if (isset($validated['name'])) {
            $category->name = $validated['name'];
            $category->slug = Str::slug($validated['name']);
            $category->save();
        }

        return response()->json($category);
    }

    // Delete a category
    public function destroy(Category $category)
    {
        // Because of 'onDelete("cascade")' in your migration, 
        // deleting a category will automatically delete its sections!
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
