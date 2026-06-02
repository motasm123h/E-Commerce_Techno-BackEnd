<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

class StoreController extends Controller
{


    // public function getProducts(Request $request)
    // {
    //     $query = Product::with(['brand', 'section.category', 'attributeValues']);
    //     // ->where('is_active', true);

    //     if ($request->has('search') && !empty($request->search)) {
    //         $searchTerm = $request->search;
    //         $query->where(function ($q) use ($searchTerm) {
    //             $q->where('name', 'LIKE', "%{$searchTerm}%")
    //                 ->orWhere('details', 'LIKE', "%{$searchTerm}%")
    //                 ->orWhereHas('brand', function ($brandQuery) use ($searchTerm) {
    //                     $brandQuery->where('name', 'LIKE', "%{$searchTerm}%");
    //                 });
    //         });
    //     }

    //     if ($request->has('category_id')) {
    //         $query->whereHas('section', function ($q) use ($request) {
    //             $q->where('category_id', $request->category_id);
    //         });
    //     }

    //     if ($request->has('section_id')) {
    //         $query->where('section_id', $request->section_id);
    //     }

    //     if ($request->has('brand_id')) {
    //         $query->where('brand_id', $request->brand_id);
    //     }

    //     if ($request->has('min_price')) {
    //         $query->where('price', '>=', $request->min_price);
    //     }

    //     if ($request->has('max_price')) {
    //         $query->where('price', '<=', $request->max_price);
    //     }


    //     $valueIds = $request->input('attribute_values');

    //     if (!empty($valueIds) && is_array($valueIds)) {
    //         $query->whereHas('attributeValues', function ($q) use ($valueIds) {
    //             $q->whereIn('attribute_values.id', $valueIds);
    //         });
    //     }


    //     if ($request->has('sort_by')) {
    //         switch ($request->sort_by) {
    //             case 'price_asc':
    //                 $query->orderBy('price', 'asc');
    //                 break;
    //             case 'price_desc':
    //                 $query->orderBy('price', 'desc');
    //                 break;
    //             case 'newest':
    //                 $query->latest();
    //                 break;
    //             default:
    //                 $query->latest();
    //                 break;
    //         }
    //     } else {
    //         $query->latest();
    //     }

    //     $products = $query->paginate(12);

    //     return ProductResource::collection($products);
    // }



    // public function getProducts(Request $request)
    // {
    //     // تضمين الـ tags مع العلاقات المجلوبة مسبقاً لتفادي مشكلة الـ N+1 Query
    //     $query = Product::with(['brand', 'section.category', 'attributeValues', 'tags']);
    //     // ->where('is_active', true);

    //     if ($request->has('search') && !empty($request->search)) {
    //         $searchTerm = $request->search;
    //         $query->where(function ($q) use ($searchTerm) {
    //             $q->where('name', 'LIKE', "%{$searchTerm}%")
    //                 ->orWhere('details', 'LIKE', "%{$searchTerm}%")
    //                 ->orWhereHas('brand', function ($brandQuery) use ($searchTerm) {
    //                     $brandQuery->where('name', 'LIKE', "%{$searchTerm}%");
    //                 })
    //                 ->orWhereHas('tags', function ($tagQuery) use ($searchTerm) {
    //                     $tagQuery->where('name', 'LIKE', "%{$searchTerm}%");
    //                 });
    //         });
    //     }

    //     if ($request->has('tag_id') && !empty($request->tag_id)) {
    //         $query->whereHas('tags', function ($q) use ($request) {
    //             $q->where('tags.id', $request->tag_id);
    //         });
    //     }

    //     if ($request->has('category_id')) {
    //         $query->whereHas('section', function ($q) use ($request) {
    //             $q->where('category_id', $request->category_id);
    //         });
    //     }

    //     if ($request->has('section_id')) {
    //         $query->where('section_id', $request->section_id);
    //     }

    //     if ($request->has('brand_id')) {
    //         $query->where('brand_id', $request->brand_id);
    //     }

    //     if ($request->has('min_price')) {
    //         $query->where('price', '>=', $request->min_price);
    //     }

    //     if ($request->has('max_price')) {
    //         $query->where('price', '<=', $request->max_price);
    //     }

    //     $valueIds = $request->input('attribute_values');

    //     if (!empty($valueIds) && is_array($valueIds)) {
    //         $query->whereHas('attributeValues', function ($q) use ($valueIds) {
    //             $q->whereIn('attribute_values.id', $valueIds);
    //         });
    //     }

    //     if ($request->has('sort_by')) {
    //         switch ($request->sort_by) {
    //             case 'price_asc':
    //                 $query->orderBy('price', 'asc');
    //                 break;
    //             case 'price_desc':
    //                 $query->orderBy('price', 'desc');
    //                 break;
    //             case 'newest':
    //                 $query->latest();
    //                 break;
    //             default:
    //                 $query->latest();
    //                 break;
    //         }
    //     } else {
    //         $query->latest();
    //     }

    //     $products = $query->paginate(12);

    //     return ProductResource::collection($products);
    // }


    public function getProducts(Request $request)
{
    // ⚡ تحديث ذكي: جلب الـ attribute التابع للـ attributeValues لتوفير أسمائها الصريحة
    $query = Product::with(['brand', 'section.category', 'attributeValues.attribute', 'tags']);
    // ->where('is_active', true);

    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('details', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('brand', function ($brandQuery) use ($searchTerm) {
                    $brandQuery->where('name', 'LIKE', "%{$searchTerm}%");
                })
                ->orWhereHas('tags', function ($tagQuery) use ($searchTerm) {
                    $tagQuery->where('name', 'LIKE', "%{$searchTerm}%");
                });
        });
    }

    if ($request->has('tag_id') && !empty($request->tag_id)) {
        $query->whereHas('tags', function ($q) use ($request) {
            $q->where('tags.id', $request->tag_id);
        });
    }

    if ($request->has('category_id')) {
        $query->whereHas('section', function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });
    }

    if ($request->has('section_id')) {
        $query->where('section_id', $request->section_id);
    }

    if ($request->has('brand_id')) {
        $query->where('brand_id', $request->brand_id);
    }

    if ($request->has('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->has('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    $valueIds = $request->input('attribute_values');

    if (!empty($valueIds) && is_array($valueIds)) {
        $query->whereHas('attributeValues', function ($q) use ($valueIds) {
            $q->whereIn('attribute_values.id', $valueIds);
        });
    }

    if ($request->has('sort_by')) {
        switch ($request->sort_by) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            default:
                $query->latest();
                break;
        }
    } else {
        $query->latest();
    }

    $products = $query->paginate(12);

    return ProductResource::collection($products);
}


    public function getCategories()
    {
        $categories = Category::with('sections')->get();
        return CategoryResource::collection($categories);
    }
}
