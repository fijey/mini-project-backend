<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\OrderDetailProductResource;
use App\Models\Product;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $product = @Product::where('id',$this->product_id)->first();
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'sub_total' => 'Rp ' . number_format($this->sub_total, 2, ',', '.'),
            'quantity' => $this->quantity,
            'user_id' => $this->user_id,
            'product' => OrderDetailProductResource::make($product),
            // Add more fields as needed
        ];
    }
}
