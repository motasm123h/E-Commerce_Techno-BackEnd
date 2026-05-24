<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'section_id',
        'brand_id',
        'name',
        'slug',
        'price',
        'stock',
        'is_active',
        'images',
        'colors',
        'details',
        'component_type',
    ];
    protected $casts = [
        'images' => 'array',
        'colors' => 'array',
        'details' => 'array',
        'is_active' => 'boolean',
        'attributes' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute');
    }
}
