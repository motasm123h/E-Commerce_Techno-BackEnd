<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'tracking_code',
        'customer_name',
        'customer_phone',
        'delivery_location',
        'shipping_city',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'status',
        'city_location',
        'addressOne_location',
        'order_note',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }
}
