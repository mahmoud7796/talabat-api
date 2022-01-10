<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;
use App\Models\Section;
class Category extends Model
{
    use HasFactory;

    protected $fillable=
    [
        'name_ar',
        'name_en',
        'vendor_id',
        'attributes',
        'section_id'
    ];

    public function vendor()
    {
        return $this->hasOne(vendor::class, 'vendor_id');
    }
    public function section()
    {
         return $this->belongsTo(Section::class,'section_id');
    }



}
