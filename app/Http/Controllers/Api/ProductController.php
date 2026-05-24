<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function getPcComponents()
    {
        $products = Product::with(['brand'])
            ->where('is_active', true)
            ->whereNotNull('component_type')
            ->get();

        $grouped = $products->groupBy('component_type');

        return response()->json($grouped, 200);
    }
    public function index()
    {
        // Eager load relationships to prevent N+1 query problems
        $products = Product::with(['brand', 'section.category'])->get();
        return ProductResource::collection($products);
    }

    public function getCartRecommendations(Request $request)
    {
        $cartProductIds = $request->input('product_ids', []);

        if (empty($cartProductIds)) {
            $recommendations = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->with(['images'])
                ->latest()
                ->take(4)
                ->get();

            return response()->json(['success' => true, 'data' => $recommendations]);
        }

        $sectionIDs = Product::whereIn('id', $cartProductIds)->pluck('section_id')->unique();

        $recommendations = Product::where('is_active', true)
            ->whereIn('section_id', $sectionIDs)
            ->whereNotIn('id', $cartProductIds) // لا تعرض منتجاً موجوداً بالفعل في السلة
            // ->with(['images'])
            ->inRandomOrder()
            ->take(4) // نعرض 4 منتجات فقط ليتناسب مع التصميم
            ->get();

        if ($recommendations->count() < 4) {
            $needed = 4 - $recommendations->count();
            $extraProducts = Product::where('is_active', true)
                ->whereNotIn('id', array_merge($cartProductIds, $recommendations->pluck('id')->toArray()))
                ->inRandomOrder()
                ->take($needed)
                ->get();

            $recommendations = $recommendations->merge($extraProducts);
        }

        return response()->json(['success' => true, 'data' => $recommendations]);
    }


    public function show($id)
    {
        $product = Product::with(['brand', 'section.category'])
            ->where('is_active', true)
            ->findOrFail($id);

        return new ProductResource($product);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|in:0,1',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'section_id' => 'nullable|exists:sections,id',
            'images' => 'nullable|array',
            'component_type' => 'nullable',
            '`attributes`' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if ($imageFile->isValid()) {
                    $path = $imageFile->store('products', 'public');
                    $imagePaths[] = '/storage/' . $path;
                }
            }
        }

        // إنشاء المنتج وحفظ الألوان والتفاصيل كمصفوفات
        $product = Product::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'component_type' => $validatedData['component_type'],
            'is_active' => (bool) $validatedData['is_active'],
            'images' => $imagePaths,

            // تحويل نص الألوان القادم "Red, Black" إلى مصفوفة ["Red", "Black"]
            'colors' => $request->colors ? array_map('trim', explode(',', $request->colors)) : [],


            // تقسيم النص عند علامة // لبناء مصفوفة المواصفات بشكل نظيف
            'details' => $request->details ? array_filter(array_map('trim', explode('//', $request->details))) : [],
            'category_id' => $request->category_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'section_id' => $request->section_id ?? null,
        ]);

        $product->load(['category', 'brand', 'section']);

        return response()->json($product, 201);
    }



    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|in:0,1',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'section_id' => 'nullable|exists:sections,id',
            'component_type' => 'nullable|string',
            'images' => 'nullable|array',
        ]);

        // معالجة الصور: إذا رفع صوراً جديدة نقوم بدمجها أو استبدالها
        $imagePaths = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                if ($imageFile->isValid()) {
                    $path = $imageFile->store('products', 'public');
                    $imagePaths[] = '/storage/' . $path;
                }
            }
        }

        $product->update([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'is_active' => (bool) $validatedData['is_active'],
            'images' => $imagePaths,
            'component_type' => $request->component_type ?? null,
            'category_id' => $request->category_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'section_id' => $request->section_id ?? null,

            // استخدام المعالجة الذكية المحدثة دائماً للحفاظ على نفس النظام
            'colors' => $request->colors ? array_filter(array_map('trim', explode(',', $request->colors))) : $product->colors,
            'details' => $request->details ? array_filter(array_map('trim', explode('//', $request->details))) : $product->details,
        ]);

        return response()->json(['message' => 'Product updated successfully.', 'product' => $product], 200);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Ensure images are deleted from the disk when the product is removed
        if (!empty($product->images)) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
