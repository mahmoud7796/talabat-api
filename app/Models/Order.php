<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['product_attributes','user_id','shipping_address_ar','shipping_address_en','shipping_google_address','shipping_status','shipping_date','payment_method','order_status','subtotal','shipping_cost','taxes','final_total','is_notified'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
