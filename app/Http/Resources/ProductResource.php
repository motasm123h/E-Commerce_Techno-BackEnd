<?php

// namespace App\Http\Resources;

// use Illuminate\Http\Request;
// use Illuminate\Http\Resources\Json\JsonResource;

// class ProductResource extends JsonResource
// {
//     public function toArray(Request $request): array
//     {
//         return [
//             'id' => $this->id,
//             'name' => $this->name,
//             'slug' => $this->slug,
//             'price' => (float) $this->price,
//             'stock' => $this->stock,
//             'is_active' => $this->is_active,
//             'in_stock' => $this->stock > 0,
//             'images' => $this->images ?? [],
//             'colors' => $this->colors ?? [],
//             'details' => $this->details ?? [],
//             'description' => $this->description,
//             'brand' => [
//                 'name' => $this->brand?->name ?? 'Unknown Brand',
//                 'icon' => $this->brand?->icon ? asset('storage/' . $this->brand->icon) : null,
//             ],
//             'category' => $this->section?->category?->name ?? 'Uncategorized',
//             'section' => $this->section?->name ?? 'Standard Section',
//             'category_id' => $this->section?->category?->id ?? null,
//             'brand_id' => $this->brand?->id ?? null,
//             'section_id' => $this->section?->id ?? null,
//             'attributeValues' => $this->attributeValues->map(function ($atter) use ($request){
//                 return [
//                     'id' => $atter->id,
//                     'value' => $atter->value,
//                     'product_id' => $atter->pivot->product_id,
//                     'attribute_value_id' => $atter->pivot->attribute_value_id,
//                     'attribute_id' => $atter->attribute_id,
//                 ];
//             }),
            
            
//             'tags' => $this->tags->map(function ($tag) use ($request) {
//                 return [
//                     'id' => $tag->id,
//                     'name' => $tag->name,
//                     'slug' => $tag->slug
//                 ];
//             }),
//         ];
//     }
// }


namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $groupedAttributes = $this->attributeValues->groupBy('attribute_id')->map(function ($values) {
            $firstValue = $values->first();
            $attributeName = $firstValue->attribute?->name ?? 'Specification';

            return [
                'attribute_id' => $firstValue->attribute_id,
                'attribute_name' => $attributeName, 
                
                'selected_values' => $values->map(function ($val) {
                    return [
                        'value_id' => $val->id,
                        'value_name' => $val->value,
                        'pivot_product_id' => $val->pivot->product_id,
                        'pivot_value_id' => $val->pivot->attribute_value_id,
                    ];
                })->values()->all()
            ];
        })->values()->all();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'component_type' => $this->component_type,
            'slug' => $this->slug,
            'price' => (float) $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'in_stock' => $this->stock > 0,
            'images' => $this->images ?? [],
            'colors' => $this->colors ?? [],
            'details' => $this->details ?? [],
            'description' => $this->description,
            'brand' => [
                'name' => $this->brand?->name ?? 'Unknown Brand',
                'icon' => $this->brand?->icon ? asset('storage/' . $this->brand->icon) : null,
            ],
            'category' => $this->section?->category?->name ?? 'Uncategorized',
            'section' => $this->section?->name ?? 'Standard Section',
            'category_id' => $this->section?->category?->id ?? null,
            'brand_id' => $this->brand?->id ?? null,
            'section_id' => $this->section?->id ?? null,
            
            'attribute_groups' => $groupedAttributes,
            
            'tags' => $this->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug
                ];
            }),
        ];
    }
}