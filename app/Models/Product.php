<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Section;
class Product extends Model
{
    use HasFactory;
    protected $fillable=
    [
        'name_en',
        'name_ar',
        'price',
        'fill_attribute_en',
        'fill_attribute_ar',
        'category_id',
        'store_id',
        'user_id',
        'stock_quantity',
        'section_id',
        'discount',
        'price_after_discount',
        'is_available',
    ];
    public function category()
    {
        return $this->hasOne(Category::class,'category_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }
    public function user()
    {
        return $this->hasOne(User::class,'user_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class,'section_id');
    }


}
