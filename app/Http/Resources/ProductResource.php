<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // return $request;
        // dd($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => (float) $this->price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'in_stock' => $this->stock > 0,
            'images' => $this->images ?? [],
            'colors' => $this->colors ?? [], // Add this line
            'details' => $this->details ?? [],
            'brand' => [
                'name' => $this->brand->name,
                'icon' => $this->brand->icon ? asset('storage/' . $this->brand->icon) : null,
                ],
            'category' => $this->section->category->name,
            'section' => $this->section->name,
            'category_id' => $this->section->category->id,
            'brand_id' => $this->brand->id,
            'section_id' => $this->section->id,
        ];
    }
}
