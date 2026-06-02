<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug', 'icon', 'section_id'];

    public $translatable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
