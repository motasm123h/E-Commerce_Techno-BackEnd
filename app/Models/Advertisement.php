<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{

    protected $fillable = [
        'title',
        'image_path',
        'link_url',
        'type',
        'is_active',
        'sort_order',

    ];
}
