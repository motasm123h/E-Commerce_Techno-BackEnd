<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug'];

    public $translatable = ['name'];


    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
