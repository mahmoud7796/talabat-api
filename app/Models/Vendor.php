<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Type;
use App\Models\User;
class Vendor extends Model
{
    use HasFactory;
    protected $fillable=
    [
        'name_en',
        'name_ar',
        'address_en',
        'address_ar',
        'email',
        'phone',
        'user_id',
        'is_blocked',
        'type_id',

    ];
    public function user()
    {
        return $this->hasOne(User::class,'user_id');
    }
    public function type()
    {
        return $this->belongsTo(Type::class,'type_id');
    }

}