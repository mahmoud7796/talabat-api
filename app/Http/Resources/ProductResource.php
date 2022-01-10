<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            "name_en"=> $this->name_en,
            "name_ar"=> $this->name_ar,
            "price"=>$this->price,
            "price_after_discount"=>$this->price_after_discount
        ];
    }
}
