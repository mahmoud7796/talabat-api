<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "product_quantity"=> $this->product_quantity,
            "shipping_address_ar"=> $this->shipping_address_ar,
            "shipping_address_en"=> $this->shipping_address_en,
            "shipping_google_address"=> $this->shipping_google_address,
            "shipping_status"=> $this->shipping_status,
            "shipping_date"=> $this->shipping_date,
            "payment_method"=> $this->payment_method,
            "order_status	"=> $this->order_status	,
            "order_status"=> $this->order_status,
            "shipping_cost"=> $this->shipping_cost,
            "taxes"=> $this->taxes,
            "final_total"=> $this->final_total,
            "is_notified"=> $this->is_notified,
        ];
    }
}
