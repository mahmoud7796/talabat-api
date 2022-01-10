<?php

namespace App\Models;

use GuzzleHttp\Handler\Proxy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Vendor;
class Section extends Model
{
    use HasFactory;
    protected $table = "sections";
    protected $fillable = [
        'name_en',
        'name_ar',
        'vendor_id',
        'section_id',
        'user_id',

    ];

    public function user()
    {
       return $this->belongsTo(User::class, 'user_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class. 'vendor_id');
    }

}
