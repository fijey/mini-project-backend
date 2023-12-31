<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderDetailResource;

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

        return [
            'id' => $this->id,
            'invoice' => $this->invoice,
            'sub_total' => 'Rp ' . number_format($this->sub_total, 2, ',', '.'),
            'created_at' => $this->created_at,
            'order_details' => OrderDetailResource::collection($this->whenLoaded('orderDetails')),
            // Add more fields as needed
        ];

    }
}
