<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = ['city_name', 'fee', 'is_active'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_zone_id');
    }
}
