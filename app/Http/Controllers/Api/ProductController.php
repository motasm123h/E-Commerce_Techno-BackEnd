<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Attribute;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }


    public function getPcComponents(Request $request)
    {
        $products = Product::with(['brand', 'section.category', 'tags', 'attributeValues.attribute'])
            ->where('is_active', true)
            ->whereNotNull('component_type')
            ->get();

        $resourceCollection = ProductResource::collection($products)->resolve();
        $grouped = collect($resourceCollection)->groupBy('component_type');

        return response()->json([
            'success' => true,
            'data' => $grouped
        ], 200);
    }
    
    public function index()
    {
        $products = Product::with([
            'brand',
            'section.category',
            'tags',
            'attributeValues' => function ($query) {
                $query->select('attribute_values.id', 'value', 'attribute_id');
            }
        ])->get();

        return ProductResource::collection($products);
    }

    /**
     * نظام اقتراحات سلة المشتريات الذكي
     */
    public function getCartRecommendations(Request $request)
    {
        $cartProductIds = $request->input('product_ids', []);

        if (empty($cartProductIds)) {
            $recommendations = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->with(['brand', 'section.category', 'tags', 'attributeValues'])
                ->latest()
                ->take(4)
                ->get();

            return response()->json([
                'success' => true,
                'data' => ProductResource::collection($recommendations)
            ]);
        }

        $sectionIDs = Product::whereIn('id', $cartProductIds)->pluck('section_id')->unique();

        $recommendations = Product::where('is_active', true)
            ->whereIn('section_id', $sectionIDs)
            ->whereNotIn('id', $cartProductIds)
            ->with(['brand', 'section.category', 'tags', 'attributeValues'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($recommendations->count() < 4) {
            $needed = 4 - $recommendations->count();
            $extraProducts = Product::where('is_active', true)
                ->whereNotIn('id', array_merge($cartProductIds, $recommendations->pluck('id')->toArray()))
                ->with(['brand', 'section.category', 'tags', 'attributeValues'])
                ->inRandomOrder()
                ->take($needed)
                ->get();

            $recommendations = $recommendations->merge($extraProducts);
        }

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($recommendations)
        ]);
    }

    
    public function show($slug)
    {
        $product = Product::with(['attributeValues.attribute', 'brand', 'section.category', 'tags'])
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'The requested product was not found in our catalog entries.'
            ], 404);
        }

        return new ProductResource($product);
    }

    /**
     * تخزين منتج جديد بربط فلاتره وأوسمته
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'attribute_value_ids' => 'nullable|array',
            'attribute_value_ids.*' => 'exists:attribute_values,id',
            'tag_ids' => 'nullable|array',
            'description' => 'nullable|string',
            'tag_ids.*' => 'exists:tags,id',
            // Validation rules to accept arrays directly
            'colors' => 'nullable|array',
            'details' => 'nullable|array',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            $imagePaths = $this->imageService->uploadAndCompressImages(
                $request->file('images'),
                'products'
            );
        }

        $product = Product::create([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'price' => $validatedData['price'],
            'description' => $request->description,
            'stock' => $validatedData['stock'],
            'component_type' => $validatedData['component_type'] ?? null,
            'is_active' => (bool) $validatedData['is_active'],
            'images' => $imagePaths,
            // Assign the arrays directly, defaulting to empty arrays if null
            'colors' => $request->input('colors', []),
            'details' => $request->input('details', []),
            'category_id' => $request->category_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'section_id' => $request->section_id ?? null,
        ]);

        if ($request->has('attribute_value_ids')) {
            $product->attributeValues()->sync($request->attribute_value_ids);
        }

        if ($request->has('tag_ids')) {
            $product->tags()->sync($request->tag_ids);
        }

        $product->load(['category', 'brand', 'section.category', 'attributeValues', 'tags']);

        return (new ProductResource($product))
            ->additional(['message' => 'Product launched successfully'])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * تحديث منتج قائم ومزامنة وسومه وفلاتره
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|in:0,1',
            'tag_ids' => 'nullable|array',
            'description' => 'nullable|string',
            'tag_ids.*' => 'exists:tags,id',
            // Validation rules to accept arrays directly
            'colors' => 'nullable|array',
            'details' => 'nullable|array',
        ]);

        $oldImages = $product->images ?? [];

        $remainingImages = [];
        if ($request->has('existing_images')) {
            $remainingImages = array_filter($request->existing_images, function ($item) {
                return is_string($item) && !empty($item);
            });
        }
        $remainingImages = array_values($remainingImages);

        $deletedImages = array_diff($oldImages, $remainingImages);
        if (!empty($deletedImages)) {
            $this->imageService->deletePhysicalImages($deletedImages);
        }

        $imagePaths = $remainingImages;

        if ($request->hasFile('new_images')) {
            $newFiles = [];
            foreach ($request->file('new_images') as $file) {
                if ($file->isValid()) {
                    $newFiles[] = $file;
                }
            }

            if (!empty($newFiles)) {
                $newCompressedImages = $this->imageService->uploadAndCompressImages($newFiles, 'products');
                $imagePaths = array_merge($imagePaths, $newCompressedImages);
            }
        }

        $product->update([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'price' => $validatedData['price'],
            'stock' => $validatedData['stock'],
            'is_active' => (bool) $validatedData['is_active'],
            'description' => $request->description,
            'images' => $imagePaths,
            'component_type' => $request->component_type ?? null,
            'category_id' => $request->category_id ?? null,
            'brand_id' => $request->brand_id ?? null,
            'section_id' => $request->section_id ?? null,
            // Assign the arrays directly, defaulting to empty arrays if null
            'colors' => $request->input('colors', []),
            'details' => $request->input('details', []),
        ]);

        if ($request->has('attribute_value_ids')) {
            $product->attributeValues()->sync($request->attribute_value_ids);
        }

        if ($request->has('tag_ids')) {
            $product->tags()->sync($request->tag_ids);
        }

        $product->load(['category', 'brand', 'section.category', 'attributeValues', 'tags']);

        return (new ProductResource($product))
            ->additional(['message' => 'Product updated successfully'])
            ->response()
            ->setStatusCode(200);
    }

    /**
     * حذف منتج من السيرفر
     */
    public function destroy(Product $product)
    {
        if (!empty($product->images)) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * فلاتر البحث للسكشن
     */
    public function getSectionFilters(Request $request)
    {
        $request->validate(['section_id' => 'required|exists:sections,id']);
        $sectionId = $request->section_id;

        $filters = Attribute::whereHas('sections', function ($q) use ($sectionId) {
            $q->where('sections.id', $sectionId);
        })->with(['values' => function ($q) use ($sectionId) {
            $q->whereHas('products', function ($pq) use ($sectionId) {
                $pq->where('products.section_id', $sectionId)
                    ->where('products.is_active', true);
            });
        }])->get();

        return response()->json([
            'success' => true,
            'data' => $filters
        ]);
    }
}