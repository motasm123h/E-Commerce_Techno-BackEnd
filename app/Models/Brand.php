<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'icon','section_id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }


    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
