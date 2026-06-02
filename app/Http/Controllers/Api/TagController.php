<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        // جلب الوسوم مع ترجماتها الكاملة للوحة التحكم
        $tags = Tag::all()->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->getTranslations('name'),
                'slug' => $tag->slug
            ];
        });
        return response()->json($tags, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name['en']),
        ]);

        return response()->json(['success' => true, 'data' => $tag], 201);
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
        ]);

        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name['en']),
        ]);

        return response()->json(['success' => true, 'data' => $tag], 200);
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete(); // سيحذف الربط تلقائياً من جدول product_tag بفضل cascade

        return response()->json(['success' => true, 'message' => 'Tag deleted successfully'], 200);
    }
}
