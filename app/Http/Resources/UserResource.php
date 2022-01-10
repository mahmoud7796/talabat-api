<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "address_en"=> $this->address_en,
            "address_ar"=> $this->address_ar,
            "email"=> $this->email,
            "phone"=> $this->phone,
            "google_address"=> $this->google_address,

        ];
    }
}
