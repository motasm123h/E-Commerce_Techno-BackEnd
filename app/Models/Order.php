<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        // 'user_id', 
        // 'reference_number',
        'tracking_code',
        'customer_name',
        'customer_phone',
        'delivery_location', // هذا الحقل الذي سبب الخطأ
        'shipping_city',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
